package com.smarthealthcare.hospital;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.smarthealthcare.hospital.dto.LoginRequest;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.http.MediaType;
import org.springframework.test.web.servlet.MockMvc;

import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@SpringBootTest
@AutoConfigureMockMvc
class HospitalBackendApplicationTests {

    @Autowired
    private MockMvc mockMvc;

    @Autowired
    private ObjectMapper objectMapper;

    @Test
    void contextLoads() {
        // Verifies Spring application context and JPA bootstrap
    }

    @Test
    void testPublicDepartmentsEndpoint() throws Exception {
        mockMvc.perform(get("/api/hospital/departments"))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$").isArray())
                .andExpect(jsonPath("$[0].name").exists());
    }

    @Test
    void testPublicDoctorsEndpoint() throws Exception {
        mockMvc.perform(get("/api/hospital/doctors"))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$").isArray())
                .andExpect(jsonPath("$[0].specialization").exists());
    }

    @Test
    void testAdminLoginSuccessReturnsJwtToken() throws Exception {
        LoginRequest req = new LoginRequest("admin", "123456");

        mockMvc.perform(post("/api/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(objectMapper.writeValueAsString(req)))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.token").isString())
                .andExpect(jsonPath("$.role").value("ROLE_ADMIN"))
                .andExpect(jsonPath("$.username").value("admin"));
    }

    @Test
    void testInvalidPasswordRejectedWithUnauthorized() throws Exception {
        LoginRequest req = new LoginRequest("admin", "wrongpassword");

        mockMvc.perform(post("/api/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(objectMapper.writeValueAsString(req)))
                .andExpect(status().isUnauthorized());
    }

    @Test
    void testDoctorLoginSuccess() throws Exception {
        LoginRequest req = new LoginRequest("dr.sarah", "123456");

        mockMvc.perform(post("/api/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(objectMapper.writeValueAsString(req)))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.role").value("ROLE_DOCTOR"))
                .andExpect(jsonPath("$.doctorId").isNumber());
    }
}
