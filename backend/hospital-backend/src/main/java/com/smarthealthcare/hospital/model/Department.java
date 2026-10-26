package com.smarthealthcare.hospital.model;

import jakarta.persistence.*;

@Entity
@Table(name = "departments")
public class Department {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false, unique = true, length = 120)
    private String name;

    @Column(nullable = false, unique = true, length = 30)
    private String code;

    @Column(columnDefinition = "TEXT")
    private String description;

    @Column(name = "floor_location", length = 50)
    private String floorLocation;

    @Column(name = "is_active", nullable = false)
    private boolean isActive = true;

    public Department() {}

    public Department(String name, String code, String description, String floorLocation) {
        this.name = name;
        this.code = code;
        this.description = description;
        this.floorLocation = floorLocation;
        this.isActive = true;
    }

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getCode() { return code; }
    public void setCode(String code) { this.code = code; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public String getFloorLocation() { return floorLocation; }
    public void setFloorLocation(String floorLocation) { this.floorLocation = floorLocation; }

    public boolean isActive() { return isActive; }
    public void setActive(boolean active) { isActive = active; }
}
