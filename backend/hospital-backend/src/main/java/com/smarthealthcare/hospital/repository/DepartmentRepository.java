package com.smarthealthcare.hospital.repository;

import com.smarthealthcare.hospital.model.Department;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.List;
import java.util.Optional;

@Repository
public interface DepartmentRepository extends JpaRepository<Department, Long> {
    List<Department> findByIsActiveTrue();
    Optional<Department> findByNameIgnoreCase(String name);
    boolean existsByNameIgnoreCase(String name);
}
