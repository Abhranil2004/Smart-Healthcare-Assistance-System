-- ============================================================================
-- Smart Healthcare Assistance System - PostgreSQL Database Schema
-- Architecture: Relational PostgreSQL Schema for Hospital Management & AI Triage
-- ============================================================================

-- Drop existing tables if re-creating
DROP TABLE IF EXISTS symptom_assessments CASCADE;
DROP TABLE IF EXISTS medical_records CASCADE;
DROP TABLE IF EXISTS appointments CASCADE;
DROP TABLE IF EXISTS doctors CASCADE;
DROP TABLE IF EXISTS patients CASCADE;
DROP TABLE IF EXISTS departments CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- 1. USERS & AUTHENTICATION TABLE
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    role VARCHAR(30) NOT NULL CHECK (role IN ('ROLE_PATIENT', 'ROLE_DOCTOR', 'ROLE_RECEPTIONIST', 'ROLE_ADMIN')),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- 2. CLINICAL DEPARTMENTS
CREATE TABLE departments (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    code VARCHAR(30) NOT NULL UNIQUE,
    description TEXT,
    floor_location VARCHAR(50),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_departments_name ON departments(name);

-- 3. DOCTORS TABLE
CREATE TABLE doctors (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    department_id BIGINT NOT NULL REFERENCES departments(id) ON DELETE RESTRICT,
    license_number VARCHAR(80) NOT NULL UNIQUE,
    specialization VARCHAR(150) NOT NULL,
    qualification VARCHAR(200) NOT NULL,
    consultation_fee NUMERIC(10, 2) NOT NULL DEFAULT 50.00,
    room_number VARCHAR(50),
    available_days VARCHAR(100) DEFAULT 'Mon,Tue,Wed,Thu,Fri',
    slot_duration_minutes INT NOT NULL DEFAULT 30,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_doctors_department ON doctors(department_id);

-- 4. PATIENTS TABLE
CREATE TABLE patients (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT UNIQUE REFERENCES users(id) ON DELETE SET NULL,
    patient_identifier VARCHAR(40) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    gender VARCHAR(20) CHECK (gender IN ('Male', 'Female', 'Other', 'Undisclosed')),
    blood_group VARCHAR(10),
    emergency_contact VARCHAR(100),
    emergency_phone VARCHAR(30),
    address TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_patients_identifier ON patients(patient_identifier);

-- 5. APPOINTMENTS (State Machine: SCHEDULED -> CONFIRMED -> CHECKED_IN -> IN_CONSULTATION -> COMPLETED / CANCELLED)
CREATE TABLE appointments (
    id BIGSERIAL PRIMARY KEY,
    appointment_number VARCHAR(50) NOT NULL UNIQUE,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    department_id BIGINT NOT NULL REFERENCES departments(id) ON DELETE RESTRICT,
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'SCHEDULED' 
        CHECK (status IN ('SCHEDULED', 'CONFIRMED', 'CHECKED_IN', 'IN_CONSULTATION', 'COMPLETED', 'CANCELLED')),
    urgency_level VARCHAR(30) DEFAULT 'Routine' 
        CHECK (urgency_level IN ('Routine', 'Moderate', 'Urgent', 'Emergency')),
    triage_notes TEXT,
    cancellation_reason TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_doctor_slot UNIQUE (doctor_id, appointment_date, start_time)
);

CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_patient ON appointments(patient_id);
CREATE INDEX idx_appointments_doctor ON appointments(doctor_id);
CREATE INDEX idx_appointments_status ON appointments(status);

-- 6. MEDICAL RECORDS (EMR)
CREATE TABLE medical_records (
    id BIGSERIAL PRIMARY KEY,
    record_number VARCHAR(50) NOT NULL UNIQUE,
    appointment_id BIGINT UNIQUE REFERENCES appointments(id) ON DELETE SET NULL,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE RESTRICT,
    chief_complaint TEXT NOT NULL,
    clinical_examination TEXT,
    diagnosis TEXT NOT NULL,
    prescription_notes TEXT,
    lab_tests_ordered TEXT,
    vitals_bp VARCHAR(20),
    vitals_heart_rate INT,
    vitals_temp_celsius NUMERIC(4, 1),
    follow_up_date DATE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_records_patient ON medical_records(patient_id);
CREATE INDEX idx_records_doctor ON medical_records(doctor_id);

-- 7. SYMPTOM ASSESSMENTS (AI & Triage Log with Privacy Safeguards)
CREATE TABLE symptom_assessments (
    id BIGSERIAL PRIMARY KEY,
    patient_id BIGINT REFERENCES patients(id) ON DELETE SET NULL,
    session_uuid VARCHAR(80) NOT NULL,
    raw_symptoms TEXT NOT NULL,
    extracted_symptoms JSONB,
    duration_stated VARCHAR(100),
    is_emergency BOOLEAN NOT NULL DEFAULT FALSE,
    emergency_trigger_reason TEXT,
    urgency_level VARCHAR(30) NOT NULL,
    ml_predicted_condition VARCHAR(150),
    ml_confidence_score NUMERIC(5, 4),
    recommended_specialty VARCHAR(120) NOT NULL,
    rule_engine_triggered BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_assessments_patient ON symptom_assessments(patient_id);
CREATE INDEX idx_assessments_created ON symptom_assessments(created_at);
