package com.smarthealthcare.hospital.controller;

import com.smarthealthcare.hospital.config.JwtUtil;
import com.smarthealthcare.hospital.dto.AuthResponse;
import com.smarthealthcare.hospital.dto.LoginRequest;
import com.smarthealthcare.hospital.dto.RegisterRequest;
import com.smarthealthcare.hospital.model.Doctor;
import com.smarthealthcare.hospital.model.Patient;
import com.smarthealthcare.hospital.model.Role;
import com.smarthealthcare.hospital.model.User;
import com.smarthealthcare.hospital.repository.DoctorRepository;
import com.smarthealthcare.hospital.repository.PatientRepository;
import com.smarthealthcare.hospital.repository.UserRepository;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.Authentication;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/auth")
public class AuthController {

    private final UserRepository userRepository;
    private final PatientRepository patientRepository;
    private final DoctorRepository doctorRepository;
    private final PasswordEncoder passwordEncoder;
    private final JwtUtil jwtUtil;

    public AuthController(
            UserRepository userRepository,
            PatientRepository patientRepository,
            DoctorRepository doctorRepository,
            PasswordEncoder passwordEncoder,
            JwtUtil jwtUtil) {
        this.userRepository = userRepository;
        this.patientRepository = patientRepository;
        this.doctorRepository = doctorRepository;
        this.passwordEncoder = passwordEncoder;
        this.jwtUtil = jwtUtil;
    }

    @PostMapping("/login")
    public ResponseEntity<?> login(@Valid @RequestBody LoginRequest req) {
        Optional<User> userOpt = userRepository.findByUsername(req.getLogin());
        if (userOpt.isEmpty()) {
            userOpt = userRepository.findByEmail(req.getLogin());
        }

        if (userOpt.isEmpty() || !passwordEncoder.matches(req.getPassword(), userOpt.get().getPasswordHash())) {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED)
                    .body(Map.of("message", "Invalid username/email or password"));
        }

        User user = userOpt.get();
        if (!user.isActive()) {
            return ResponseEntity.status(HttpStatus.FORBIDDEN)
                    .body(Map.of("message", "Account is deactivated. Please contact administration."));
        }

        Long patientId = null;
        Long doctorId = null;

        if (user.getRole() == Role.ROLE_PATIENT) {
            patientId = patientRepository.findByUserId(user.getId()).map(Patient::getId).orElse(null);
        } else if (user.getRole() == Role.ROLE_DOCTOR) {
            doctorId = doctorRepository.findByUserId(user.getId()).map(Doctor::getId).orElse(null);
        }

        String token = jwtUtil.generateToken(user, patientId, doctorId);

        return ResponseEntity.ok(new AuthResponse(
                token,
                user.getId(),
                user.getUsername(),
                user.getEmail(),
                user.getFirstName(),
                user.getLastName(),
                user.getRole().name(),
                patientId,
                doctorId
        ));
    }

    @PostMapping("/register")
    public ResponseEntity<?> register(@Valid @RequestBody RegisterRequest req) {
        if (userRepository.existsByUsername(req.getUsername())) {
            return ResponseEntity.badRequest().body(Map.of("message", "Username already taken"));
        }
        if (userRepository.existsByEmail(req.getEmail())) {
            return ResponseEntity.badRequest().body(Map.of("message", "Email already registered"));
        }

        Role role = Role.ROLE_PATIENT;
        if (req.getRole() != null && req.getRole().equalsIgnoreCase("ROLE_DOCTOR")) {
            role = Role.ROLE_DOCTOR;
        }

        User newUser = new User(
                req.getUsername(),
                req.getEmail(),
                passwordEncoder.encode(req.getPassword()),
                req.getFirstName(),
                req.getLastName(),
                req.getPhone(),
                role
        );
        userRepository.save(newUser);

        Long patientId = null;
        if (role == Role.ROLE_PATIENT) {
            String patIdentifier = "PAT-" + System.currentTimeMillis();
            Patient patient = new Patient(newUser, patIdentifier, req.getFirstName(), req.getLastName(), null, "Undisclosed", null);
            patientRepository.save(patient);
            patientId = patient.getId();
        }

        String token = jwtUtil.generateToken(newUser, patientId, null);

        return ResponseEntity.status(HttpStatus.CREATED).body(new AuthResponse(
                token,
                newUser.getId(),
                newUser.getUsername(),
                newUser.getEmail(),
                newUser.getFirstName(),
                newUser.getLastName(),
                newUser.getRole().name(),
                patientId,
                null
        ));
    }

    @GetMapping("/me")
    public ResponseEntity<?> getCurrentUser(Authentication authentication) {
        if (authentication == null || !authentication.isAuthenticated()) {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of("message", "Not authenticated"));
        }

        String username = authentication.getName();
        Optional<User> userOpt = userRepository.findByUsername(username);
        if (userOpt.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(Map.of("message", "User not found"));
        }

        User user = userOpt.get();
        Long patientId = patientRepository.findByUserId(user.getId()).map(Patient::getId).orElse(null);
        Long doctorId = doctorRepository.findByUserId(user.getId()).map(Doctor::getId).orElse(null);

        return ResponseEntity.ok(Map.of(
                "userId", user.getId(),
                "username", user.getUsername(),
                "email", user.getEmail(),
                "firstName", user.getFirstName(),
                "lastName", user.getLastName(),
                "role", user.getRole().name(),
                "patientId", patientId != null ? patientId : false,
                "doctorId", doctorId != null ? doctorId : false
        ));
    }
}
