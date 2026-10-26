package com.smarthealthcare.hospital.dto;

import jakarta.validation.constraints.NotNull;
import java.time.LocalDate;
import java.time.LocalTime;

public class AppointmentRequest {
    @NotNull(message = "Doctor ID is required")
    private Long doctorId;

    private Long patientId; // optional if authenticated as patient

    @NotNull(message = "Appointment date is required")
    private LocalDate appointmentDate;

    @NotNull(message = "Start time is required")
    private LocalTime startTime;

    private String urgencyLevel;
    private String triageNotes;

    public AppointmentRequest() {}

    public Long getDoctorId() { return doctorId; }
    public void setDoctorId(Long doctorId) { this.doctorId = doctorId; }

    public Long getPatientId() { return patientId; }
    public void setPatientId(Long patientId) { this.patientId = patientId; }

    public LocalDate getAppointmentDate() { return appointmentDate; }
    public void setAppointmentDate(LocalDate appointmentDate) { this.appointmentDate = appointmentDate; }

    public LocalTime getStartTime() { return startTime; }
    public void setStartTime(LocalTime startTime) { this.startTime = startTime; }

    public String getUrgencyLevel() { return urgencyLevel; }
    public void setUrgencyLevel(String urgencyLevel) { this.urgencyLevel = urgencyLevel; }

    public String getTriageNotes() { return triageNotes; }
    public void setTriageNotes(String triageNotes) { this.triageNotes = triageNotes; }
}
