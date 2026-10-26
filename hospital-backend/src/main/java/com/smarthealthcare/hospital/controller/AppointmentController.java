package com.smarthealthcare.hospital.controller;

import com.smarthealthcare.hospital.dto.AppointmentRequest;
import com.smarthealthcare.hospital.dto.AppointmentStatusUpdateRequest;
import com.smarthealthcare.hospital.model.*;
import com.smarthealthcare.hospital.repository.*;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.Authentication;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.bind.annotation.*;

import java.time.LocalDate;
import java.time.LocalTime;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/hospital/appointments")
public class AppointmentController {

    private final AppointmentRepository appointmentRepository;
    private final DoctorRepository doctorRepository;
    private final PatientRepository patientRepository;
    private final UserRepository userRepository;

    public AppointmentController(
            AppointmentRepository appointmentRepository,
            DoctorRepository doctorRepository,
            PatientRepository patientRepository,
            UserRepository userRepository) {
        this.appointmentRepository = appointmentRepository;
        this.doctorRepository = doctorRepository;
        this.patientRepository = patientRepository;
        this.userRepository = userRepository;
    }

    @GetMapping
    public ResponseEntity<List<Appointment>> getAppointments(
            @RequestParam(required = false) Long patientId,
            @RequestParam(required = false) Long doctorId,
            @RequestParam(required = false) String date,
            @RequestParam(required = false) String status,
            Authentication authentication) {

        if (authentication == null) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();

        Optional<User> userOpt = userRepository.findByUsername(authentication.getName());
        if (userOpt.isEmpty()) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();
        User currentUser = userOpt.get();

        // 1. Patient role can ONLY view their own appointments
        if (currentUser.getRole() == Role.ROLE_PATIENT) {
            Optional<Patient> patOpt = patientRepository.findByUserId(currentUser.getId());
            if (patOpt.isEmpty()) return ResponseEntity.ok(List.of());
            return ResponseEntity.ok(appointmentRepository.findByPatientIdOrderByAppointmentDateDescStartTimeDesc(patOpt.get().getId()));
        }

        // 2. Doctor role can view their assigned appointments
        if (currentUser.getRole() == Role.ROLE_DOCTOR) {
            Optional<Doctor> docOpt = doctorRepository.findByUserId(currentUser.getId());
            if (docOpt.isEmpty()) return ResponseEntity.ok(List.of());
            return ResponseEntity.ok(appointmentRepository.findByDoctorIdOrderByAppointmentDateDescStartTimeDesc(docOpt.get().getId()));
        }

        // 3. Receptionist and Admin can filter or view all
        if (patientId != null) {
            return ResponseEntity.ok(appointmentRepository.findByPatientIdOrderByAppointmentDateDescStartTimeDesc(patientId));
        }
        if (doctorId != null) {
            return ResponseEntity.ok(appointmentRepository.findByDoctorIdOrderByAppointmentDateDescStartTimeDesc(doctorId));
        }
        if (date != null) {
            return ResponseEntity.ok(appointmentRepository.findByAppointmentDateOrderByStartTimeAsc(LocalDate.parse(date)));
        }

        return ResponseEntity.ok(appointmentRepository.findAll());
    }

    @PostMapping
    @Transactional
    public ResponseEntity<?> bookAppointment(@Valid @RequestBody AppointmentRequest req, Authentication authentication) {
        if (authentication == null) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();

        Optional<User> userOpt = userRepository.findByUsername(authentication.getName());
        if (userOpt.isEmpty()) return ResponseEntity.status(HttpStatus.UNAUTHORIZED).build();
        User currentUser = userOpt.get();

        // Determine patient
        Patient patient;
        if (currentUser.getRole() == Role.ROLE_PATIENT) {
            Optional<Patient> patOpt = patientRepository.findByUserId(currentUser.getId());
            if (patOpt.isEmpty()) return ResponseEntity.badRequest().body(Map.of("message", "Patient profile not found."));
            patient = patOpt.get();
        } else {
            // Receptionist/Admin booking for a patient
            if (req.getPatientId() == null) {
                return ResponseEntity.badRequest().body(Map.of("message", "Patient ID is required for administrative booking."));
            }
            Optional<Patient> patOpt = patientRepository.findById(req.getPatientId());
            if (patOpt.isEmpty()) return ResponseEntity.badRequest().body(Map.of("message", "Patient not found."));
            patient = patOpt.get();
        }

        Optional<Doctor> doctorOpt = doctorRepository.findById(req.getDoctorId());
        if (doctorOpt.isEmpty()) {
            return ResponseEntity.badRequest().body(Map.of("message", "Doctor not found."));
        }
        Doctor doctor = doctorOpt.get();

        // Concurrency Guard: Check for duplicate slot
        if (appointmentRepository.existsByDoctorIdAndAppointmentDateAndStartTime(doctor.getId(), req.getAppointmentDate(), req.getStartTime())) {
            return ResponseEntity.status(HttpStatus.CONFLICT).body(Map.of(
                    "message", "Appointment slot conflict: The requested time slot is already reserved for this doctor."
            ));
        }

        LocalTime endTime = req.getStartTime().plusMinutes(doctor.getSlotDurationMinutes() != null ? doctor.getSlotDurationMinutes() : 30);
        String aptNum = "APT-" + System.currentTimeMillis();

        Appointment appointment = new Appointment(
                aptNum,
                patient,
                doctor,
                doctor.getDepartment(),
                req.getAppointmentDate(),
                req.getStartTime(),
                endTime,
                req.getUrgencyLevel() != null ? req.getUrgencyLevel() : "Routine",
                req.getTriageNotes()
        );
        appointment.setStatus(AppointmentStatus.CONFIRMED);

        Appointment saved = appointmentRepository.save(appointment);
        return ResponseEntity.status(HttpStatus.CREATED).body(saved);
    }

    @PatchMapping("/{id}/status")
    @Transactional
    public ResponseEntity<?> updateAppointmentStatus(
            @PathVariable Long id,
            @Valid @RequestBody AppointmentStatusUpdateRequest req) {

        Optional<Appointment> aptOpt = appointmentRepository.findById(id);
        if (aptOpt.isEmpty()) return ResponseEntity.notFound().build();

        Appointment appointment = aptOpt.get();
        appointment.setStatus(req.getStatus());

        if (req.getCancellationReason() != null) {
            appointment.setCancellationReason(req.getCancellationReason());
        }

        Appointment updated = appointmentRepository.save(appointment);
        return ResponseEntity.ok(updated);
    }
}
