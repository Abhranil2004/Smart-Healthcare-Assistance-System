package com.smarthealthcare.hospital.dto;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import java.time.LocalDate;

public class MedicalRecordRequest {
    private Long appointmentId;

    @NotNull(message = "Patient ID is required")
    private Long patientId;

    @NotBlank(message = "Chief complaint is required")
    private String chiefComplaint;

    private String clinicalExamination;

    @NotBlank(message = "Diagnosis is required")
    private String diagnosis;

    private String prescriptionNotes;
    private String labTestsOrdered;
    private String vitalsBp;
    private Integer vitalsHeartRate;
    private Double vitalsTempCelsius;
    private LocalDate followUpDate;

    public MedicalRecordRequest() {}

    public Long getAppointmentId() { return appointmentId; }
    public void setAppointmentId(Long appointmentId) { this.appointmentId = appointmentId; }

    public Long getPatientId() { return patientId; }
    public void setPatientId(Long patientId) { this.patientId = patientId; }

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
}
