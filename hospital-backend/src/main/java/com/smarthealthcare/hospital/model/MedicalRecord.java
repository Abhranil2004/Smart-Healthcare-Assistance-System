package com.smarthealthcare.hospital.model;

import jakarta.persistence.*;
import java.time.LocalDate;
import java.time.LocalDateTime;

@Entity
@Table(name = "medical_records")
public class MedicalRecord {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(name = "record_number", nullable = false, unique = true, length = 50)
    private String recordNumber;

    @OneToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "appointment_id")
    private Appointment appointment;

    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "patient_id", nullable = false)
    private Patient patient;

    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "doctor_id", nullable = false)
    private Doctor doctor;

    @Column(name = "chief_complaint", nullable = false, columnDefinition = "TEXT")
    private String chiefComplaint;

    @Column(name = "clinical_examination", columnDefinition = "TEXT")
    private String clinicalExamination;

    @Column(nullable = false, columnDefinition = "TEXT")
    private String diagnosis;

    @Column(name = "prescription_notes", columnDefinition = "TEXT")
    private String prescriptionNotes;

    @Column(name = "lab_tests_ordered", columnDefinition = "TEXT")
    private String labTestsOrdered;

    @Column(name = "vitals_bp", length = 20)
    private String vitalsBp;

    @Column(name = "vitals_heart_rate")
    private Integer vitalsHeartRate;

    @Column(name = "vitals_temp_celsius")
    private Double vitalsTempCelsius;

    @Column(name = "follow_up_date")
    private LocalDate followUpDate;

    @Column(name = "created_at")
    private LocalDateTime createdAt = LocalDateTime.now();

    public MedicalRecord() {}

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getRecordNumber() { return recordNumber; }
    public void setRecordNumber(String recordNumber) { this.recordNumber = recordNumber; }

    public Appointment getAppointment() { return appointment; }
    public void setAppointment(Appointment appointment) { this.appointment = appointment; }

    public Patient getPatient() { return patient; }
    public void setPatient(Patient patient) { this.patient = patient; }

    public Doctor getDoctor() { return doctor; }
    public void setDoctor(Doctor doctor) { this.doctor = doctor; }

    public String getChiefComplaint() { return chiefComplaint; }
    public void setChiefComplaint(String chiefComplaint) { this.chiefComplaint = chiefComplaint; }

    public String getClinicalExamination() { return clinicalExamination; }
    public void setClinicalExamination(String clinicalExamination) { this.clinicalExamination = clinicalExamination; }

    public String getDiagnosis() { return diagnosis; }
    public void setDiagnosis(String diagnosis) { this.diagnosis = diagnosis; }

    public String getPrescriptionNotes() { return prescriptionNotes; }
    public void setPrescriptionNotes(String prescriptionNotes) { this.prescriptionNotes = prescriptionNotes; }

    public String getLabTestsOrdered() { return labTestsOrdered; }
    public void setLabTestsOrdered(String labTestsOrdered) { this.labTestsOrdered = labTestsOrdered; }

    public String getVitalsBp() { return vitalsBp; }
    public void setVitalsBp(String vitalsBp) { this.vitalsBp = vitalsBp; }

    public Integer getVitalsHeartRate() { return vitalsHeartRate; }
    public void setVitalsHeartRate(Integer vitalsHeartRate) { this.vitalsHeartRate = vitalsHeartRate; }

    public Double getVitalsTempCelsius() { return vitalsTempCelsius; }
    public void setVitalsTempCelsius(Double vitalsTempCelsius) { this.vitalsTempCelsius = vitalsTempCelsius; }

    public LocalDate getFollowUpDate() { return followUpDate; }
    public void setFollowUpDate(LocalDate followUpDate) { this.followUpDate = followUpDate; }

    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
}
