package com.smarthealthcare.hospital.dto;

public class AuthResponse {
    private String token;
    private String tokenType = "Bearer";
    private Long userId;
    private String username;
    private String email;
    private String firstName;
    private String lastName;
    private String role;
    private Long patientId;
    private Long doctorId;

    public AuthResponse(String token, Long userId, String username, String email, String firstName, String lastName, String role, Long patientId, Long doctorId) {
        this.token = token;
        this.tokenType = "Bearer";
        this.userId = userId;
        this.username = username;
        this.email = email;
        this.firstName = firstName;
        this.lastName = lastName;
        this.role = role;
        this.patientId = patientId;
        this.doctorId = doctorId;
    }

    public String getToken() { return token; }
    public String getTokenType() { return tokenType; }
    public Long getUserId() { return userId; }
    public String getUsername() { return username; }
    public String getEmail() { return email; }
    public String getFirstName() { return firstName; }
    public String getLastName() { return lastName; }
    public String getRole() { return role; }
    public Long getPatientId() { return patientId; }
    public Long getDoctorId() { return doctorId; }
}
