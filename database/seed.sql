-- ============================================================================
-- Smart Healthcare Assistance System - PostgreSQL Synthetic Seed Data
-- Passwords for all demo accounts: 123456
-- BCrypt Hash: $2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y
-- ============================================================================

-- 1. CLINICAL DEPARTMENTS
INSERT INTO departments (id, name, code, description, floor_location) VALUES
(1, 'Cardiology', 'CARD', 'Comprehensive cardiovascular medicine, cardiac telemetry, and heart care.', 'Floor 2 - East Wing'),
(2, 'Neurology', 'NEUR', 'Diagnosis and management of neurological disorders, stroke care, and neurology.', 'Floor 3 - North Wing'),
(3, 'Orthopedics', 'ORTH', 'Bone, joint, spine, and musculoskeletal trauma and reconstruction.', 'Floor 1 - West Wing'),
(4, 'General Medicine', 'GMED', 'Adult internal medicine, primary diagnostic evaluation, and acute illness care.', 'Floor 1 - Main Concourse'),
(5, 'Pediatrics', 'PEDI', 'Infant, child, and adolescent healthcare, developmental tracking, and vaccination.', 'Floor 2 - West Wing'),
(6, 'Dermatology', 'DERM', 'Clinical evaluation and treatment of dermatologic conditions and skin disorders.', 'Floor 3 - South Wing'),
(7, 'Gastroenterology', 'GAST', 'Digestive health, liver care, endoscopy, and abdominal pathology management.', 'Floor 2 - North Wing'),
(8, 'Pulmonology', 'PULM', 'Respiratory health, asthma, COPD, and acute pulmonary consultation.', 'Floor 3 - East Wing');

-- 2. USERS (Admin, Doctors, Receptionist, Patients)
INSERT INTO users (id, username, email, password_hash, first_name, last_name, phone, role) VALUES
-- Administrator
(1, 'admin', 'admin@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Operations', 'Administrator', '+1-555-0100', 'ROLE_ADMIN'),

-- Doctors
(2, 'dr.sarah', 'sarah.jenkins@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Sarah', 'Jenkins', '+1-555-0101', 'ROLE_DOCTOR'),
(3, 'dr.marcus', 'marcus.vance@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Marcus', 'Vance', '+1-555-0102', 'ROLE_DOCTOR'),
(4, 'dr.elena', 'elena.rostova@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Elena', 'Rostova', '+1-555-0103', 'ROLE_DOCTOR'),
(5, 'dr.david', 'david.kim@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'David', 'Kim', '+1-555-0104', 'ROLE_DOCTOR'),
(6, 'dr.priya', 'priya.patel@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Priya', 'Patel', '+1-555-0105', 'ROLE_DOCTOR'),

-- Receptionist
(7, 'receptionist', 'frontdesk@healthcare.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Emily', 'Clark', '+1-555-0120', 'ROLE_RECEPTIONIST'),

-- Patients
(8, 'patient.john', 'john.doe@patient.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'John', 'Doe', '+1-555-0130', 'ROLE_PATIENT'),
(9, 'patient.alice', 'alice.smith@patient.system', '$2b$12$njRJlHxokVyp2kMUj88WUO4W5F0YLC92DBSeR2L0AMSPdgzlIgF0y', 'Alice', 'Smith', '+1-555-0131', 'ROLE_PATIENT');

-- 3. DOCTORS CLINICAL PROFILES
INSERT INTO doctors (id, user_id, department_id, license_number, specialization, qualification, consultation_fee, room_number, available_days) VALUES
(1, 2, 1, 'MED-LIC-CARD-081', 'Interventional Cardiology', 'MD, FACC, Board Certified Cardiologist', 75.00, 'Suite 204B', 'Mon,Tue,Wed,Thu,Fri'),
(2, 3, 2, 'MED-LIC-NEUR-094', 'Clinical Neurophysiology & Stroke', 'MD, PhD, Board Certified Neurologist', 85.00, 'Suite 310A', 'Mon,Wed,Fri'),
(3, 4, 3, 'MED-LIC-ORTH-112', 'Joint Reconstruction & Sports Med', 'MS (Ortho), FRCS', 70.00, 'Suite 108', 'Tue,Thu,Sat'),
(4, 5, 4, 'MED-LIC-GMED-055', 'Internal Medicine & Primary Care', 'MBBS, MD (General Medicine)', 50.00, 'Suite 102A', 'Mon,Tue,Wed,Thu,Fri,Sat'),
(5, 6, 7, 'MED-LIC-GAST-073', 'Gastroenterology & Hepatology', 'MD, DM (Gastroenterology)', 80.00, 'Suite 215', 'Mon,Tue,Wed,Fri');

-- 4. PATIENT MEDICAL IDENTIFIERS
INSERT INTO patients (id, user_id, patient_identifier, first_name, last_name, date_of_birth, gender, blood_group, emergency_contact, emergency_phone, address) VALUES
(1, 8, 'PAT-2026-0001', 'John', 'Doe', '1988-04-12', 'Male', 'O+', 'Mary Doe (Spouse)', '+1-555-0199', '742 Evergreen Terrace, Springfield'),
(2, 9, 'PAT-2026-0002', 'Alice', 'Smith', '1995-09-23', 'Female', 'A+', 'Robert Smith (Father)', '+1-555-0198', '1088 Rosewood Way, Metropolis');

-- 5. DEMONSTRATION APPOINTMENTS
INSERT INTO appointments (id, appointment_number, patient_id, doctor_id, department_id, appointment_date, start_time, end_time, status, urgency_level, triage_notes) VALUES
(1, 'APT-2026-8801', 1, 1, 1, CURRENT_DATE, '10:00:00', '10:30:00', 'CONFIRMED', 'Moderate', 'Pre-triage: Mild chest tightness on exertion, duration 4 days. Non-radiating.'),
(2, 'APT-2026-8802', 2, 5, 7, CURRENT_DATE, '11:00:00', '11:30:00', 'SCHEDULED', 'Routine', 'Pre-triage: Abdominal cramping after heavy meals for 2 weeks. No red flags.');

-- 6. MEDICAL RECORD
INSERT INTO medical_records (id, record_number, appointment_id, patient_id, doctor_id, chief_complaint, clinical_examination, diagnosis, prescription_notes, lab_tests_ordered, vitals_bp, vitals_heart_rate, vitals_temp_celsius) VALUES
(1, 'EMR-2026-001', 1, 1, 1, 
'Substernal chest tightness worsening during stair climbing.', 
'Regular heart rhythm, normal S1/S2, clear lung sounds bilaterally. No peripheral edema.', 
'Exertional Angina Suspect - Stable Profile', 
'Aspirin 81mg PO Daily; Atorvastatin 20mg PO at bedtime.', 
'12-Lead ECG, Lipid Profile, Troponin-I, Stress Echocardiogram.', 
'128/82 mmHg', 72, 36.8);

-- Reset sequence IDs
SELECT setval('departments_id_seq', (SELECT MAX(id) FROM departments));
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
SELECT setval('doctors_id_seq', (SELECT MAX(id) FROM doctors));
SELECT setval('patients_id_seq', (SELECT MAX(id) FROM patients));
SELECT setval('appointments_id_seq', (SELECT MAX(id) FROM appointments));
SELECT setval('medical_records_id_seq', (SELECT MAX(id) FROM medical_records));
