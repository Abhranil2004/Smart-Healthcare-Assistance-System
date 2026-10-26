package com.smarthealthcare.hospital.model;

import jakarta.persistence.*;
import java.math.BigDecimal;

@Entity
@Table(name = "doctors")
public class Doctor {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @OneToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "user_id", nullable = false, unique = true)
    private User user;

    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "department_id", nullable = false)
    private Department department;

    @Column(name = "license_number", nullable = false, unique = true, length = 80)
    private String licenseNumber;

    @Column(nullable = false, length = 150)
    private String specialization;

    @Column(nullable = false, length = 200)
    private String qualification;

    @Column(name = "consultation_fee", nullable = false)
    private BigDecimal consultationFee = BigDecimal.valueOf(50.00);

    @Column(name = "room_number", length = 50)
    private String roomNumber;

    @Column(name = "available_days", length = 100)
    private String availableDays = "Mon,Tue,Wed,Thu,Fri";

    @Column(name = "slot_duration_minutes", nullable = false)
    private Integer slotDurationMinutes = 30;

    public Doctor() {}

    public Doctor(User user, Department department, String licenseNumber, String specialization, String qualification, BigDecimal consultationFee, String roomNumber) {
        this.user = user;
        this.department = department;
        this.licenseNumber = licenseNumber;
        this.specialization = specialization;
        this.qualification = qualification;
        this.consultationFee = consultationFee;
        this.roomNumber = roomNumber;
        this.availableDays = "Mon,Tue,Wed,Thu,Fri";
        this.slotDurationMinutes = 30;
    }

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }

    public Department getDepartment() { return department; }
    public void setDepartment(Department department) { this.department = department; }

    public String getLicenseNumber() { return licenseNumber; }
    public void setLicenseNumber(String licenseNumber) { this.licenseNumber = licenseNumber; }

    public String getSpecialization() { return specialization; }
    public void setSpecialization(String specialization) { this.specialization = specialization; }

    public String getQualification() { return qualification; }
    public void setQualification(String qualification) { this.qualification = qualification; }

    public BigDecimal getConsultationFee() { return consultationFee; }
    public void setConsultationFee(BigDecimal consultationFee) { this.consultationFee = consultationFee; }

    public String getRoomNumber() { return roomNumber; }
    public void setRoomNumber(String roomNumber) { this.roomNumber = roomNumber; }

    public String getAvailableDays() { return availableDays; }
    public void setAvailableDays(String availableDays) { this.availableDays = availableDays; }

    public Integer getSlotDurationMinutes() { return slotDurationMinutes; }
    public void setSlotDurationMinutes(Integer slotDurationMinutes) { this.slotDurationMinutes = slotDurationMinutes; }
}
