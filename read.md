# Smart Healthcare Assistance System
## BCA Final Year Major Project — Project Documentation & Architectural Report

> **Tagline:** *Intelligent care. Connected healthcare.*  
> **Author:** BCA Final Year Student  
> **Technology Stack:** Laravel 12.x | PHP 8.2+ | Blade & Vanilla CSS | MySQL / SQLite | Laravel Passport  

---

## 📑 Table of Contents
1. [Project Overview & Abstract](#1-project-overview--abstract)
2. [Key Objectives & Architecture](#2-key-objectives--architecture)
3. [Technology Stack & System Specifications](#3-technology-stack--system-specifications)
4. [Public Healthcare Portal & Interactive AI Assistant](#4-public-healthcare-portal--interactive-ai-assistant)
5. [Role-Based Portals & Dashboards](#5-role-based-portals--dashboards)
   - [Patient Self-Service Portal](#51-patient-self-service-portal)
   - [Doctor Clinical Dashboard](#52-doctor-clinical-dashboard)
   - [Receptionist & Front-Desk Dashboard](#53-receptionist--front-desk-dashboard)
   - [Hospital Administrator Dashboard](#54-hospital-administrator-dashboard)
6. [Hospital Clinical & Operations Modules](#6-hospital-clinical--operations-modules)
   - [Patient Medical Records (EMR)](#61-patient-medical-records-emr)
   - [Conflict-Free Appointment Scheduling](#62-conflict-free-appointment-scheduling)
   - [Inpatient Ward & Bed Census](#63-inpatient-ward--bed-census)
   - [Digital Prescription Builder](#64-digital-prescription-builder)
   - [Diagnostic Laboratory & Partial Reporting](#65-diagnostic-laboratory--partial-reporting)
   - [Pharmacy Dispensing & Atomic Stock Control](#66-pharmacy-dispensing--atomic-stock-control)
   - [Installment Invoicing & Hospital Accounting](#67-installment-invoicing--hospital-accounting)
   - [Operational Reports & Real-Time Analytics](#68-operational-reports--real-time-analytics)
7. [Database Architecture & Concurrency Control](#7-database-architecture--concurrency-control)
8. [Role-Based Access Control (RBAC)](#8-role-based-access-control-rbac)
9. [Installation, Configuration & Seed Data](#9-installation-configuration--seed-data)
10. [Automated Testing & Quality Verification](#10-automated-testing--quality-verification)

---

## 1. Project Overview & Abstract

**Smart Healthcare Assistance System** is an enterprise-grade hospital management and AI-assisted healthcare SaaS platform designed and developed from scratch as a BCA Final Year Major Project. The platform bridges the gap between patient-facing digital healthcare services—such as natural language symptom triage and doctor discovery—and comprehensive hospital operational management across outpatient consultations, inpatient admissions, laboratory testing, pharmacy inventory, and financial accounting.

Healthcare facilities often suffer from fragmented software systems where appointment scheduling, electronic medical records (EMR), bed allocation, and pharmacy stock management operate in disconnected silos. Smart Healthcare Assistance System solves this by integrating all clinical, operational, and patient workflows into a unified platform powered by modern web technologies, transactional concurrency safeguards, and an intelligent triage engine.

---

## 2. Key Objectives & Architecture

* **Unified Healthcare Platform:** Unify patients, clinicians, receptionists, pharmacists, and administrators on a single platform with tailored, role-specific views.
* **Intelligent AI Triage:** Provide patients with a natural-language symptom analysis tool that assesses risk urgency, triggers emergency red-flag warnings, recommends appropriate medical specialties, and queries available doctors.
* **Clinical Safety & Strict Disclaimers:** Present all AI results with clear probabilistic indicators and prominent emergency banners, ensuring technology acts as an assistive tool rather than an unverified replacement for medical diagnosis.
* **Data Integrity & Concurrency Safeguards:** Prevent race conditions in high-concurrency healthcare environments (e.g., duplicate bed assignments, double-booked appointment slots, and negative pharmacy stock) through database transactions and pessimistic row locks (`lockForUpdate`).
* **Granular Role-Based Access Control (RBAC):** Implement a catalog of 90 fine-grained permissions to strictly isolate clinical, financial, and administrative operations.

---

## 3. Technology Stack & System Specifications

| Component | Specification |
|---|---|
| **Backend Framework** | Laravel 12.x running on PHP 8.2+ |
| **Database Engine** | MySQL 8.0+ / MariaDB 10.4+ (SQLite for automated feature tests) |
| **Authentication & Tokens** | Laravel Session Guard + Laravel Passport (OAuth2 REST API Guard) |
| **Frontend UI/UX** | Blade Templating Engine, Vanilla CSS Design System (`medinexa-theme.css`), FontAwesome 6 |
| **Document & PDF Generation**| `barryvdh/laravel-dompdf` for clinical summaries and receipts |
| **Barcode Engine** | `picqer/php-barcode-generator` for specimen and patient wristband tracking |
| **Data Export** | `maatwebsite/excel` for accounting and operational reports |
| **Audit Logging** | `spatie/laravel-activitylog` & `jeremykenedy/laravel-logger` |
| **Testing Framework** | PHPUnit 11.5 / Laravel Testing Suite |

---

## 4. Public Healthcare Portal & Interactive AI Assistant

### 4.1 Public Healthcare Homepage (`/`)
* **Clean Clinical Visual Identity:** Designed using deep navy headings (`#0f172a`), medical teal accents (`#0d9488`), clinical blue action buttons (`#0284c7`), and soft neutral surfaces (`#f8fafc`).
* **Navigation Bar:** MediNexa logo, links to Healthcare Assistant, Services, How It Works, Specialties, About, and direct action buttons ("Sign In" and "Get Started").
* **Hero Section:** Features headline *"Smarter Healthcare Starts Here."* and an interactive symptom checker widget.
* **Database-Driven Specialties & Services:** Dynamically fetches active clinical specialties and billable services from the database.
* **5-Step Patient Journey:** Clearly illustrates the patient journey from symptom intake to consultation and post-care follow-up.

### 4.2 AI Healthcare Assistant & Symptom Triage (`/assistant`)
Implemented in `App\Services\HealthcareAssistantService`:
* **Natural-Language Symptom Intake:** Patients describe symptoms in conversational language (e.g., *"sharp chest pain radiating to left arm with shortness of breath"* or *"fever, persistent cough and sore throat"*).
* **Emergency Red-Flag Trigger:** Scans for acute life-threatening indicators (acute cardiac arrest signs, stroke symptoms, acute respiratory distress, severe trauma). When detected, an emergency alert banner is immediately triggered directing the patient to emergency medical services.
* **Medical Specialty Recommendation:** Non-emergency symptoms are evaluated against clinical specialty rules (Cardiology, Neurology, Pulmonology, Orthopedics, Pediatrics, Dermatology, Gastroenterology, ENT, General Medicine) to recommend the right department.
* **Matching Doctor Discovery:** Queries the database for active physicians belonging to the recommended specialty.
* **Medical Safety Notice:** Prominently displays: *"AI assessment is preliminary and probabilistic for informational triage only. It does not replace certified clinical diagnosis."*

---

## 5. Role-Based Portals & Dashboards

### 5.1 Patient Self-Service Portal (`/dashboard`)
* **Personalized Greeting:** Contextual welcome banner with patient's registered name and ID.
* **Upcoming Appointments:** Timeline card of confirmed and pending consultations with doctor name, date, and slot time.
* **Active Digital Prescriptions:** View prescribed medications, instructions, dosage, and prescribing doctor.
* **Laboratory Diagnostic Reports:** Download completed test results and monitor pending tests.
* **Invoices & Billing History:** Review itemized charges, settled amounts, and remaining debtor balances.
* **Self-Service AI Symptom Checker:** Integrated symptom triage widget within the portal.

### 5.2 Doctor Clinical Dashboard (`/dashboard`)
* **Consultation Queue:** Real-time list of today's scheduled and arrived patients.
* **Pre-Consultation Clinical Notes:** Review patient-submitted AI symptom summaries and triage urgency before the patient enters the consultation room.
* **Patient Medical Background:** Instant access to allergies, chronic conditions, and previous surgical history.
* **Rapid Clinical Actions:** Quick shortcuts to write prescriptions, order laboratory tests, or initiate inpatient ward admission.

### 5.3 Receptionist & Front-Desk Dashboard (`/dashboard`)
* **Daily Patient Queue & Check-In:** Monitor scheduled arrivals and check patients into the waiting room.
* **Walk-In Registration:** Simplified rapid patient registration requiring only a patient's name to generate an immediate hospital record.
* **Doctor Availability Roster:** Real-time visibility into physician schedules to book back-to-back, non-conflicting appointments.
* **Live Ward Bed Census:** Real-time display of available, occupied, and cleaning ward beds.

### 5.4 Hospital Administrator Dashboard (`/dashboard`)
* **Operational Overview:** Total patient registrations, completed consultations, active inpatient admissions, and bed occupancy rates.
* **Financial Ledger:** Real-time calculation of hospital income, pharmacy sales, operational expenses, and outstanding debtor balances.
* **Pharmacy Stock Monitoring:** Visual alerts for low-stock medications.
* **Superuser Role Preview Switcher:** Allows administrators to inspect each role-specific dashboard (Patient, Doctor, Receptionist, Admin) seamlessly.

---

## 6. Hospital Clinical & Operations Modules

### 6.1 Patient Medical Records (EMR)
* Comprehensive electronic health records storing demographic details, emergency contacts, blood group, allergies, past operations, and chronic conditions.
* Document and scan attachment support for historical medical reports.

### 6.2 Conflict-Free Appointment Scheduling
* Day-view schedule grouped by physician and status (Scheduled, Confirmed, Checked-in, Completed, Cancelled).
* **Concurrency Locking:** Employs database row locking to guarantee that two simultaneous booking requests for the same physician slot result in one success and one refusal rather than a double-booking.

### 6.3 Inpatient Ward & Bed Census
* Visual ward management supporting multiple wards and bed categories (ICU, General Male/Female, VIP, Private).
* Automatic bed state management: A bed is strictly marked occupied while an admission record has no discharge timestamp, preventing duplicate bed allocations.

### 6.4 Digital Prescription Builder
* Normalized child table architecture (`prescription_medicine`) storing drug name, pack size, dosage, frequency, and instructions as discrete relational rows rather than concatenated strings.
* Real-time search across the hospital formulary with support for ad-hoc custom medications.

### 6.5 Diagnostic Laboratory & Partial Reporting
* Relational laboratory orders (`lab_test`) allowing individual tests within a single lab requisition to be reported progressively while microbiological cultures remain pending.
* Overdue test tracking identifying tests waiting more than 72 hours without reported findings.

### 6.6 Pharmacy Dispensing & Atomic Stock Control
* Point-of-sale pharmacy dispensing directly linked to the medicine inventory catalog.
* **Atomic Deduction:** Stock deduction occurs in the same database transaction as the sale. If requested quantity exceeds current inventory, the transaction rolls back, preventing negative stock.

### 6.7 Installment Invoicing & Hospital Accounting
* Itemized invoicing linking clinical consultations, bed day charges, lab procedures, and pharmacy sales.
* Support for split payments and installment tracking, deriving invoice settlement state dynamically from payment receipts.

### 6.8 Operational Reports & Real-Time Analytics
* **Aged Debtors Report:** Dynamic calculation of outstanding patient balances.
* **Bed Occupancy & Average Length of Stay (ALOS):** Real-time occupancy calculations over configurable date ranges.
* **Prescribed vs. Dispensed Drug Usage:** Reconciliation report monitoring pharmacy distribution against physician prescriptions.

---

## 7. Database Architecture & Concurrency Control

### 7.1 Relational Normalization
The database schema consists of 50 normalized operational tables. Discrete child tables replace legacy repeating columns:
* `prescription_medicine`: Individual drug lines with dosage and instructions.
* `lab_test`: Individual laboratory test lines with status and clinical findings.
* `pharmacy_sale_item`: Itemized medication sales linked to stock batches.
* `invoice_item` & `invoice_payment`: Itemized charges and transaction payment receipts.

### 7.2 Pessimistic Row Locking
In critical operations where concurrent user requests could corrupt hospital state, transactions apply `lockForUpdate()`:
* **Appointment Booking:** Locks the doctor's schedule records for the selected timeslot.
* **Bed Allocation:** Locks the targeted bed record during patient admission.
* **Pharmacy Stock:** Locks inventory rows during medication dispensing.
* **Invoice Payments:** Locks invoice balances during payment processing.

---

## 8. Role-Based Access Control (RBAC)

MediNexa enforces 90 catalogued permissions across four primary roles:
1. **Administrator:** Unrestricted operational, administrative, financial, and user management rights.
2. **Doctor:** Access to patient clinical records, consultation queues, prescription generation, and lab orders.
3. **Nurse / Receptionist:** Front-desk booking, patient check-in, walk-in registration, and bed allocation.
4. **Accountant / Pharmacist:** Billing settlement, invoice generation, expense tracking, and pharmacy dispensing.

Permissions are strictly enforced at the HTTP layer via `App\Http\Middleware\CheckPermission`. Unauthorized requests are intercepted and redirected with clear permission notices.

---

## 9. Installation, Configuration & Seed Data

### 9.1 Environment Setup
```powershell
# 1. Install dependencies
composer install

# 2. Configure environment
cp .env.example .env

# 3. Generate application and API keys
php artisan key:generate
php artisan passport:keys
```

### 9.2 Database Setup & Permissions Sync
```powershell
# 4. Run database migrations and seed core roles
php artisan migrate --seed

# 5. Seed realistic hospital demo data
php artisan db:seed --class=DemoDataSeeder

# 6. Synchronize RBAC permissions
php artisan hospital:sync-permissions
```

### 9.3 Launching the Application
```powershell
php artisan serve
```
* **Public Homepage & AI Assistant:** `http://localhost:8000/`
* **Dedicated Sign-In Screen:** `http://localhost:8000/login`
* **Standalone AI Assistant:** `http://localhost:8000/assistant`

---

## 10. Automated Testing & Quality Verification

MediNexa is validated by a comprehensive automated test suite covering all operational modules, concurrency boundaries, RBAC protections, and AI triage flows:

```powershell
php artisan test
```

### Test Suite Summary:
* **Total Tests Executed:** 168 tests
* **Total Assertions:** 504 assertions
* **Passing Rate:** 100% (0 errors, 0 failures, 0 regressions)

| Test Suite | Focus Area | Result |
|---|---|---|
| `MediNexaRebrandTest` | Public homepage, login branding, AI triage NLP engine, emergency triggers, and role dashboards | **PASS** |
| `AppointmentTest` | Conflict prevention, back-to-back slots, and booking state machine | **PASS** |
| `AdmissionTest` | Bed occupancy, admission workflows, and discharge release | **PASS** |
| `PrescriptionTest` | Multi-drug relational lines, catalogue validation, and drug search | **PASS** |
| `PharmacyTest` | Atomic dispensing, negative stock prevention, and return reversals | **PASS** |
| `InvoiceTest` | Split invoicing, part-payments, and debtor balance calculation | **PASS** |
| `LabRequestTest` | Specimen tracking and partial test result reporting | **PASS** |
| `ConcurrentWriteTest` | Database transaction boundaries and race condition prevention | **PASS** |
| `PermissionEnforcementTest` | Multi-role access control and 90-permission enforcement | **PASS** |
| `StaffTest` & `DoctorTest` | Staff registration, doctor registers, and department mappings | **PASS** |
| `ReportTest` | Occupancy rates, drug usage, debtors, and outstanding lab work | **PASS** |

---

## 11. Conclusion

**Smart Healthcare Assistance System** demonstrates the application of modern software engineering principles, full-stack architecture, relational database design, and assistive artificial intelligence to solve real-world healthcare challenges. Built and verified from the ground up, the platform provides a complete, reliable, and secure solution for modern clinical management.
