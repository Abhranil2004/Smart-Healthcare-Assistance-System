package com.smarthealthcare.hospital.config;

import com.smarthealthcare.hospital.model.*;
import com.smarthealthcare.hospital.repository.*;
import org.springframework.boot.CommandLineRunner;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.LocalTime;
import java.util.Arrays;
import java.util.List;

@Component
public class DataInitializer implements CommandLineRunner {

    private final UserRepository userRepository;
    private final DepartmentRepository departmentRepository;
    private final DoctorRepository doctorRepository;
    private final PatientRepository patientRepository;
    private final AppointmentRepository appointmentRepository;
    private final MedicalRecordRepository medicalRecordRepository;
    private final PasswordEncoder passwordEncoder;

    public DataInitializer(
            UserRepository userRepository,
            DepartmentRepository departmentRepository,
            DoctorRepository doctorRepository,
            PatientRepository patientRepository,
            AppointmentRepository appointmentRepository,
            MedicalRecordRepository medicalRecordRepository,
            PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.departmentRepository = departmentRepository;
        this.doctorRepository = doctorRepository;
        this.patientRepository = patientRepository;
        this.appointmentRepository = appointmentRepository;
        this.medicalRecordRepository = medicalRecordRepository;
        this.passwordEncoder = passwordEncoder;
    }

    @Override
    public void run(String... args) {
        if (departmentRepository.count() == 0) {
            initDepartments();
        }

        if (userRepository.count() == 0) {
            initUsersAndClinicalData();
        }
    }

    private void initDepartments() {
        List<Department> departments = Arrays.asList(
            new Department("Cardiology", "CARD", "Comprehensive cardiovascular medicine, cardiac telemetry, and heart care.", "Floor 2 - East Wing"),
            new Department("Neurology", "NEUR", "Diagnosis and management of neurological disorders, stroke care, and neurology.", "Floor 3 - North Wing"),
            new Department("Orthopedics", "ORTH", "Bone, joint, spine, and musculoskeletal trauma and reconstruction.", "Floor 1 - West Wing"),
            new Department("General Medicine", "GMED", "Adult internal medicine, primary diagnostic evaluation, and acute illness care.", "Floor 1 - Main Concourse"),
            new Department("Pediatrics", "PEDI", "Infant, child, and adolescent healthcare, developmental tracking, and vaccination.", "Floor 2 - West Wing"),
            new Department("Dermatology", "DERM", "Clinical evaluation and treatment of dermatologic conditions and skin disorders.", "Floor 3 - South Wing"),
            new Department("Gastroenterology", "GAST", "Digestive health, liver care, endoscopy, and abdominal pathology management.", "Floor 2 - North Wing"),
            new Department("Pulmonology", "PULM", "Respiratory health, asthma, COPD, and acute pulmonary consultation.", "Floor 3 - East Wing")
        );
        departmentRepository.saveAll(departments);
        System.out.println("[DataInitializer] Seeded 8 Clinical Departments.");
    }

    private void initUsersAndClinicalData() {
        String defaultHash = passwordEncoder.encode("123456");

        // 1. Admin
        User admin = new User("admin", "admin@healthcare.system", defaultHash, "Operations", "Administrator", "+1-555-0100", Role.ROLE_ADMIN);
        userRepository.save(admin);

        // 2. Doctors
        Department card = departmentRepository.findByNameIgnoreCase("Cardiology").orElse(null);
        Department neur = departmentRepository.findByNameIgnoreCase("Neurology").orElse(null);
        Department orth = departmentRepository.findByNameIgnoreCase("Orthopedics").orElse(null);
        Department gmed = departmentRepository.findByNameIgnoreCase("General Medicine").orElse(null);
        Department gast = departmentRepository.findByNameIgnoreCase("Gastroenterology").orElse(null);

        User drSarahUser = new User("dr.sarah", "sarah.jenkins@healthcare.system", defaultHash, "Sarah", "Jenkins", "+1-555-0101", Role.ROLE_DOCTOR);
        userRepository.save(drSarahUser);
        Doctor drSarah = new Doctor(drSarahUser, card, "MED-LIC-CARD-081", "Interventional Cardiology", "MD, FACC, Board Certified Cardiologist", BigDecimal.valueOf(75.00), "Suite 204B");
        doctorRepository.save(drSarah);

        User drMarcusUser = new User("dr.marcus", "marcus.vance@healthcare.system", defaultHash, "Marcus", "Vance", "+1-555-0102", Role.ROLE_DOCTOR);
        userRepository.save(drMarcusUser);
        Doctor drMarcus = new Doctor(drMarcusUser, neur, "MED-LIC-NEUR-094", "Clinical Neurophysiology & Stroke", "MD, PhD, Board Certified Neurologist", BigDecimal.valueOf(85.00), "Suite 310A");
        doctorRepository.save(drMarcus);

        User drElenaUser = new User("dr.elena", "elena.rostova@healthcare.system", defaultHash, "Elena", "Rostova", "+1-555-0103", Role.ROLE_DOCTOR);
        userRepository.save(drElenaUser);
        Doctor drElena = new Doctor(drElenaUser, orth, "MED-LIC-ORTH-112", "Joint Reconstruction & Sports Med", "MS (Ortho), FRCS", BigDecimal.valueOf(70.00), "Suite 108");
        doctorRepository.save(drElena);

        User drDavidUser = new User("dr.david", "david.kim@healthcare.system", defaultHash, "David", "Kim", "+1-555-0104", Role.ROLE_DOCTOR);
        userRepository.save(drDavidUser);
        Doctor drDavid = new Doctor(drDavidUser, gmed, "MED-LIC-GMED-055", "Internal Medicine & Primary Care", "MBBS, MD (General Medicine)", BigDecimal.valueOf(50.00), "Suite 102A");
        doctorRepository.save(drDavid);

        User drPriyaUser = new User("dr.priya", "priya.patel@healthcare.system", defaultHash, "Priya", "Patel", "+1-555-0105", Role.ROLE_DOCTOR);
        userRepository.save(drPriyaUser);
        Doctor drPriya = new Doctor(drPriyaUser, gast, "MED-LIC-GAST-073", "Gastroenterology & Hepatology", "MD, DM (Gastroenterology)", BigDecimal.valueOf(80.00), "Suite 215");
        doctorRepository.save(drPriya);

        // 3. Receptionist
        User receptionist = new User("receptionist", "frontdesk@healthcare.system", defaultHash, "Emily", "Clark", "+1-555-0120", Role.ROLE_RECEPTIONIST);
        userRepository.save(receptionist);

        // 4. Patients
        User patJohnUser = new User("patient.john", "john.doe@patient.system", defaultHash, "John", "Doe", "+1-555-0130", Role.ROLE_PATIENT);
        userRepository.save(patJohnUser);
        Patient patJohn = new Patient(patJohnUser, "PAT-2026-0001", "John", "Doe", LocalDate.of(1988, 4, 12), "Male", "O+");
        patJohn.setAddress("742 Evergreen Terrace, Springfield");
        patJohn.setEmergencyContact("Mary Doe (Spouse)");
        patJohn.setEmergencyPhone("+1-555-0199");
        patientRepository.save(patJohn);

        User patAliceUser = new User("patient.alice", "alice.smith@patient.system", defaultHash, "Alice", "Smith", "+1-555-0131", Role.ROLE_PATIENT);
        userRepository.save(patAliceUser);
        Patient patAlice = new Patient(patAliceUser, "PAT-2026-0002", "Alice", "Smith", LocalDate.of(1995, 9, 23), "Female", "A+");
        patAlice.setAddress("1088 Rosewood Way, Metropolis");
        patAlice.setEmergencyContact("Robert Smith (Father)");
        patAlice.setEmergencyPhone("+1-555-0198");
        patientRepository.save(patAlice);

        // 5. Initial Appointments
        Appointment apt1 = new Appointment(
            "APT-2026-8801",
            patJohn,
            drSarah,
            card,
            LocalDate.now(),
            LocalTime.of(10, 0),
            LocalTime.of(10, 30),
            "Moderate",
            "Pre-triage: Mild chest tightness on exertion, duration 4 days. Non-radiating."
        );
        apt1.setStatus(AppointmentStatus.CONFIRMED);
        appointmentRepository.save(apt1);

        Appointment apt2 = new Appointment(
            "APT-2026-8802",
            patAlice,
            drPriya,
            gast,
            LocalDate.now(),
            LocalTime.of(11, 0),
            LocalTime.of(11, 30),
            "Routine",
            "Pre-triage: Abdominal cramping after heavy meals for 2 weeks. No red flags."
        );
        apt2.setStatus(AppointmentStatus.SCHEDULED);
        appointmentRepository.save(apt2);

        // 6. EMR Medical Record
        MedicalRecord record1 = new MedicalRecord();
        record1.setRecordNumber("EMR-2026-001");
        record1.setAppointment(apt1);
        record1.setPatient(patJohn);
        record1.setDoctor(drSarah);
        record1.setChiefComplaint("Substernal chest tightness worsening during stair climbing.");
        record1.setClinicalExamination("Regular heart rhythm, normal S1/S2, clear lung sounds bilaterally. No peripheral edema.");
        record1.setDiagnosis("Exertional Angina Suspect - Stable Profile");
        record1.setPrescriptionNotes("Aspirin 81mg PO Daily; Atorvastatin 20mg PO at bedtime.");
        record1.setLabTestsOrdered("12-Lead ECG, Lipid Profile, Troponin-I, Stress Echocardiogram.");
        record1.setVitalsBp("128/82 mmHg");
        record1.setVitalsHeartRate(72);
        record1.setVitalsTempCelsius(36.8);
        medicalRecordRepository.save(record1);

        System.out.println("[DataInitializer] Seeded synthetic users, doctors, patients, appointments, and EMR records.");
    }
}
