package com.smarthealthcare.hospital.controller;

import com.smarthealthcare.hospital.dto.MedicalRecordRequest;
import com.smarthealthcare.hospital.model.*;
import com.smarthealthcare.hospital.repository.*;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/hospital/medical-records")
public class MedicalRecordController {

    private final MedicalRecordRepository medicalRecordRepository;
    private final PatientRepository patientRepository;
    private final DoctorRepository doctorRepository;
    private final AppointmentRepository appointmentRepository;
    private final UserRepository userRepository;

    public MedicalRecordController(
            MedicalRecordRepository medicalRecordRepository,
            PatientRepository patientRepository,
            DoctorRepository doctorRepository,
            AppointmentRepository appointmentRepository,
            UserRepository userRepository) {
        this.medicalRecordRepository = medicalRecordRepository;
        this.patientRepository = patientRepository;
        this.doctorRepository = doctorRepository;
        this.appointmentRepository = appointmentRepository;
        this.userRepository = userRepository;
    }

    @GetMapping
    public ResponseEntity<List<MedicalRecord>> getMedicalRecords(
            @RequestParam(required = false) Long patientId,
            Authentication authentication) {

        if (authentication == null) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();

        Optional<User> userOpt = userRepository.findByUsername(authentication.getName());
        if (userOpt.isEmpty()) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();
        User currentUser = userOpt.get();

        // 1. Patient can only see their own records
        if (currentUser.getRole() == Role.ROLE_PATIENT) {
            Optional<Patient> patOpt = patientRepository.findByUserId(currentUser.getId());
            if (patOpt.isEmpty()) return ResponseEntity.ok(List.of());
            return ResponseEntity.ok(medicalRecordRepository.findByPatientIdOrderByCreatedAtDesc(patOpt.get().getId()));
        }

        // 2. Doctor / Admin / Receptionist with patientId
        if (patientId != null) {
            return ResponseEntity.ok(medicalRecordRepository.findByPatientIdOrderByCreatedAtDesc(patientId));
        }

        if (currentUser.getRole() == Role.ROLE_DOCTOR) {
            Optional<Doctor> docOpt = doctorRepository.findByUserId(currentUser.getId());
            if (docOpt.isPresent()) {
                return ResponseEntity.ok(medicalRecordRepository.findByDoctorIdOrderByCreatedAtDesc(docOpt.get().getId()));
            }
        }

        return ResponseEntity.ok(medicalRecordRepository.findAll());
    }

    @PostMapping
    @Transactional
    @PreAuthorize("hasAnyRole('DOCTOR', 'ADMIN')")
    public ResponseEntity<?> createMedicalRecord(
            @Valid @RequestBody MedicalRecordRequest req,
            Authentication authentication) {

        Optional<User> userOpt = userRepository.findByUsername(authentication.getName());
        if (userOpt.isEmpty()) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();
        User currentUser = userOpt.get();

        Doctor doctor = null;
        if (currentUser.getRole() == Role.ROLE_DOCTOR) {
            doctor = doctorRepository.findByUserId(currentUser.getId()).orElse(null);
        } else {
            // Admin default doctor fallback if creating
            doctor = doctorRepository.findAll().stream().findFirst().orElse(null);
        }

        if (doctor == null) {
            return ResponseEntity.badRequest().body(Map.of("message", "Physician profile not found."));
        }

        Optional<Patient> patientOpt = patientRepository.findById(req.getPatientId());
        if (patientOpt.isEmpty()) {
            return ResponseEntity.badRequest().body(Map.of("message", "Patient record not found."));
        }

        MedicalRecord record = new MedicalRecord();
        record.setRecordNumber("EMR-" + System.currentTimeMillis());
        record.setPatient(patientOpt.get());
        record.setDoctor(doctor);
        record.setChiefComplaint(req.getChiefComplaint());
        record.setClinicalExamination(req.getClinicalExamination());
        record.setDiagnosis(req.getDiagnosis());
        record.setPrescriptionNotes(req.getPrescriptionNotes());
        record.setLabTestsOrdered(req.getLabTestsOrdered());
        record.setVitalsBp(req.getVitalsBp());
        record.setVitalsHeartRate(req.getVitalsHeartRate());
        record.setVitalsTempCelsius(req.getVitalsTempCelsius());
        record.setFollowUpDate(req.getFollowUpDate());

        if (req.getAppointmentId() != null) {
            appointmentRepository.findById(req.getAppointmentId()).ifPresent(record::setAppointment);
        }

        MedicalRecord saved = medicalRecordRepository.save(record);
        return ResponseEntity.status(HttpStatus.CREATED).body(saved);
    }
}
