package com.smarthealthcare.hospital.dto;

import com.smarthealthcare.hospital.model.AppointmentStatus;
import jakarta.validation.constraints.NotNull;

public class AppointmentStatusUpdateRequest {
    @NotNull(message = "Status is required")
    private AppointmentStatus status;

    private String cancellationReason;

    public AppointmentStatusUpdateRequest() {}

    public AppointmentStatus getStatus() { return status; }
    public void setStatus(AppointmentStatus status) { this.status = status; }

    public String getCancellationReason() { return cancellationReason; }
    public void setCancellationReason(String cancellationReason) { this.cancellationReason = cancellationReason; }
}
