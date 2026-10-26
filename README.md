# Smart Healthcare Assistance System
> **Intelligent care. Connected healthcare.**
> 
> *A comprehensive, enterprise-grade AI-Powered Healthcare & Hospital Management Microservices Platform developed as a BCA Final Year Major Project.*

---

## 🌟 Architecture Overview

The system is architected into clean, decoupled tiers matching modern distributed healthcare enterprise standards:

```
[ React.js + Tailwind CSS Frontend ] (Port 3000)
                 │  (REST / JSON)
                 ▼
     [ Node.js + Express API Gateway ] (Port 5000)
           │                   │
  (Auth & Business)       (AI / Triage)
           │                   │
           ▼                   ▼
[ Spring Boot Backend ] (Port 8081)    [ FastAPI AI Service ] (Port 8000)
(Spring Security + JWT + BCrypt)        (Tripartite AI Safety Architecture)
           │                             - Deterministic Rule Engine
           ▼                             - TF-IDF + ML (Random Forest / XGBoost)
 [ PostgreSQL Database ]                 - Guardrailed LLM Conversational Assistant
 (Port 5432)
```

---

## 🏗️ Approved Technology Stack

| Tier / Service | Technology | Key Responsibilities |
|---|---|---|
| **Frontend** | React.js, Tailwind CSS, Vanilla CSS (`medinexa-theme.css`), FontAwesome 6 | Preserved visual identity, landing page, symptom triage widget, and 4 role dashboards |
| **API Gateway** | Node.js, Express.js, Axios, Morgan | Centralized routing, request validation, composite AI triage orchestration |
| **Hospital Backend** | Java 21/24, Spring Boot 3.4.x, Spring Security, JWT, BCrypt, Spring Data JPA | Appointments state machine, concurrency safeguards, EMR records, role-based access |
| **AI / ML Service** | Python 3.11, FastAPI, Scikit-learn, XGBoost, TF-IDF | Natural language symptom extraction, emergency red-flag triggers, ML specialty routing |
| **Database** | PostgreSQL 16+ (with H2 embedded fallback) | Relational persistence: users, patients, doctors, departments, appointments, EMR |

---

## 🛡️ Tripartite AI Safety Architecture

1. **Deterministic Rule Engine (`rules.py`)**:
   - Executes **first and independently** of any probabilistic model.
   - Evaluates high-risk red-flag criteria: acute myocardial infarction (crushing radiating chest pain), cerebrovascular stroke (FAST), airway compromise, systemic anaphylaxis, or acute hemorrhage.
   - Bypasses ML output and forces immediate ER / Emergency routing (`is_emergency = true`).
2. **ML Condition & Specialty Predictor (`train_model.py` / `main.py`)**:
   - Evaluates TF-IDF n-gram text features against trained multi-class classifiers.
   - Evaluated models: **Random Forest** vs **XGBoost**.
   - Model selection is based on comparative validation results (Random Forest achieved macro F1-score 0.5170 on the multi-class clinical corpus).
3. **LLM Conversational Engine (`/chat-assistant`)**:
   - Facilitates conversational symptom intake while enforcing strict healthcare prompts preventing definitive diagnosis.

---

## 🔑 Demonstration Role Credentials

All demonstration accounts are pre-configured with the default password: **`123456`**.

| Role | Username | Default Password | Primary Dashboard Capabilities |
|---|---|---|---|
| **Administrator** | `admin` | `123456` | Hospital-wide telemetry, department configurations, doctor credentials, bed census |
| **Doctor** | `dr.sarah` | `123456` | Daily consultation queue, pre-triage AI notes, clinical EMR record creation |
| **Receptionist** | `receptionist` | `123456` | Front-desk patient arrival check-in (`CHECKED_IN`), lobby queue management |
| **Patient** | `patient.john` | `123456` | Self-service booking, upcoming appointments, longitudinal EMR medical history |

---

## 🚀 Running the Services

### Option A: One-Click Local Launcher (PowerShell)
```powershell
.\start-services.ps1
```
This automatically starts:
- **FastAPI AI Service** on `http://localhost:8000`
- **Spring Boot Backend** on `http://localhost:8081`
- **Express API Gateway** on `http://localhost:5000`
- **React Frontend** on `http://localhost:3000`

### Option B: Docker Compose
```bash
docker-compose up --build
```

### Option C: Running Services Individually

1. **AI Service (Python FastAPI)**:
   ```bash
   cd ai-service
   pip install -r requirements.txt
   python train_model.py
   uvicorn main:app --host 0.0.0.0 --port 8000
   ```

2. **Hospital Backend (Java Spring Boot)**:
   ```bash
   cd hospital-backend
   mvn spring-boot:run
   ```

3. **API Gateway (Node.js Express)**:
   ```bash
   cd api-gateway
   npm install
   npm start
   ```

4. **Frontend (React + Vite)**:
   ```bash
   cd frontend
   npm install
   npm run dev -- --port 3000
   ```

---

## 🧪 Automated Test Verification

All services contain standalone unit and integration test suites:

- **AI Service Tests**:
  ```bash
  cd ai-service
  pytest test_ai_service.py
  # Result: 8 passed (Emergency triggers, ML predictions, validation)
  ```

- **Spring Boot Integration Tests**:
  ```bash
  cd hospital-backend
  mvn clean test
  # Result: 6 passed (JPA context, auth, BCrypt, role isolation, public endpoints)
  ```

- **API Gateway Tests**:
  ```bash
  cd api-gateway
  npm test
  # Result: 4 passed (Health checks, request validation, proxy routes)
  ```

- **React Frontend Build**:
  ```bash
  cd frontend
  npm run build
  # Result: 0 errors, production bundle compiled cleanly in under 1 second
  ```
