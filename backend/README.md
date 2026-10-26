# Smart Healthcare Assistance System - Backend Services

This directory contains all backend microservices, AI inference systems, gateway orchestration, and database schemas.

## Directory Structure

```
backend/
├── hospital-backend/   # Java 24 + Spring Boot 3.4.2 Hospital Management Service
├── ai-service/         # Python 3.11 + FastAPI Machine Learning & Triage Service
├── api-gateway/        # Node.js + Express.js API Gateway & Composite Orchestrator
└── database/           # PostgreSQL Schema (schema.sql) and Seed Data (seed.sql)
```

---

## 1. Hospital Backend (`backend/hospital-backend/`)
- **Technology**: Java 24, Spring Boot 3.4.2, Spring Security, JWT, Spring Data JPA, PostgreSQL.
- **Port**: `8081`
- **Key Modules**:
  - `config/`: SecurityConfig (stateless JWT, BCrypt, role-based authorizeHttpRequests), JwtUtil, DataInitializer.
  - `model/`: User, Role, Department, Doctor, Patient, Appointment, MedicalRecord.
  - `repository/`: Spring Data JPA Repositories with custom query methods.
  - `controller/`: AuthController, DepartmentController, DoctorController, PatientController, AppointmentController, MedicalRecordController, AnalyticsController.
- **Run**:
  ```bash
  cd backend/hospital-backend
  mvn spring-boot:run
  ```

---

## 2. AI Service (`backend/ai-service/`)
- **Technology**: Python 3.11, FastAPI, Uvicorn, Scikit-Learn, XGBoost, Joblib.
- **Port**: `8000`
- **Safety Architecture**:
  - `rules.py`: Deterministic Emergency Rule Engine (Cardiac arrest, Acute Coronary Syndrome, Stroke FAST, Respiratory Distress, Anaphylaxis) executing with absolute priority.
  - `train_model.py`: TF-IDF + Random Forest / XGBoost clinical condition classifier.
  - `main.py`: FastAPI endpoints for `/health`, `/analyze-symptoms`, and `/chat-assistant`.
- **Run**:
  ```bash
  cd backend/ai-service
  uvicorn main:app --host 0.0.0.0 --port 8000 --reload
  ```

---

## 3. API Gateway (`backend/api-gateway/`)
- **Technology**: Node.js, Express.js, Axios, CORS, Dotenv.
- **Port**: `5000`
- **Key Features**:
  - Single entrypoint for the frontend (`http://localhost:5000`).
  - Orchestrates composite requests (`/api/ai/triage-and-recommend`) combining FastAPI clinical triage and Spring Boot doctor recommendations.
  - Reverse proxies to Spring Boot (`/api/auth`, `/api/departments`, `/api/doctors`, `/api/patients`, `/api/appointments`, `/api/records`).
- **Run**:
  ```bash
  cd backend/api-gateway
  npm install
  npm start
  ```

---

## 4. Database (`backend/database/`)
- `schema.sql`: Complete PostgreSQL DDL with 7 core tables, constraints, foreign keys, and indexes.
- `seed.sql`: Initial clinical departments, doctors, test patients, appointments, and records.
