package com.smarthealthcare.hospital.repository;

import com.smarthealthcare.hospital.model.Appointment;
import com.smarthealthcare.hospital.model.AppointmentStatus;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.time.LocalDate;
import java.time.LocalTime;
import java.util.List;
import java.util.Optional;

@Repository
public interface AppointmentRepository extends JpaRepository<Appointment, Long> {
    List<Appointment> findByPatientIdOrderByAppointmentDateDescStartTimeDesc(Long patientId);
    List<Appointment> findByDoctorIdOrderByAppointmentDateDescStartTimeDesc(Long doctorId);
    List<Appointment> findByDoctorIdAndAppointmentDate(Long doctorId, LocalDate appointmentDate);
    List<Appointment> findByAppointmentDateOrderByStartTimeAsc(LocalDate appointmentDate);
    List<Appointment> findByStatus(AppointmentStatus status);
    Optional<Appointment> findByAppointmentNumber(String appointmentNumber);
    boolean existsByDoctorIdAndAppointmentDateAndStartTime(Long doctorId, LocalDate appointmentDate, LocalTime startTime);
}
