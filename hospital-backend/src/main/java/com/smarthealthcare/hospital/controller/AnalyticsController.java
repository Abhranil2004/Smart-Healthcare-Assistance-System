package com.smarthealthcare.hospital.controller;

import com.smarthealthcare.hospital.model.AppointmentStatus;
import com.smarthealthcare.hospital.repository.*;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/hospital/analytics")
public class AnalyticsController {

    private final DepartmentRepository departmentRepository;
    private final DoctorRepository doctorRepository;
    private final PatientRepository patientRepository;
    private final AppointmentRepository appointmentRepository;
    private final MedicalRecordRepository medicalRecordRepository;

    public AnalyticsController(
            DepartmentRepository departmentRepository,
            DoctorRepository doctorRepository,
            PatientRepository patientRepository,
            AppointmentRepository appointmentRepository,
            MedicalRecordRepository medicalRecordRepository) {
        this.departmentRepository = departmentRepository;
        this.doctorRepository = doctorRepository;
        this.patientRepository = patientRepository;
        this.appointmentRepository = appointmentRepository;
        this.medicalRecordRepository = medicalRecordRepository;
    }

    @GetMapping("/overview")
    public ResponseEntity<Map<String, Object>> getOverviewStats() {
        Map<String, Object> stats = new HashMap<>();

        long totalDepartments = departmentRepository.count();
        long totalDoctors = doctorRepository.count();
        long totalPatients = patientRepository.count();
        long totalAppointments = appointmentRepository.count();
        long totalRecords = medicalRecordRepository.count();

        long confirmedCount = appointmentRepository.findByStatus(AppointmentStatus.CONFIRMED).size();
        long checkedInCount = appointmentRepository.findByStatus(AppointmentStatus.CHECKED_IN).size();
        long completedCount = appointmentRepository.findByStatus(AppointmentStatus.COMPLETED).size();
        long inConsultationCount = appointmentRepository.findByStatus(AppointmentStatus.IN_CONSULTATION).size();

        stats.put("totalDepartments", totalDepartments);
        stats.put("totalDoctors", totalDoctors);
        stats.put("totalPatients", totalPatients);
        stats.put("totalAppointments", totalAppointments);
        stats.put("totalMedicalRecords", totalRecords);
        stats.put("totalHospitalBeds", 120);
        stats.put("occupiedBeds", 84);
        stats.put("bedOccupancyRate", 70.0);

        Map<String, Long> statusBreakdown = new HashMap<>();
        statusBreakdown.put("CONFIRMED", confirmedCount);
        statusBreakdown.put("CHECKED_IN", checkedInCount);
        statusBreakdown.put("IN_CONSULTATION", inConsultationCount);
        statusBreakdown.put("COMPLETED", completedCount);
        stats.put("appointmentStatusBreakdown", statusBreakdown);

        return ResponseEntity.ok(stats);
    }

    @GetMapping("/public-summary")
    public ResponseEntity<Map<String, Object>> getPublicSummary() {
        return ResponseEntity.ok(Map.of(
                "departmentsCount", departmentRepository.count(),
                "doctorsCount", doctorRepository.count(),
                "emergencyServicesAvailable", true,
                "aiTriageActive", true
        ));
    }
}
