"""
Automated Unit and Integration Tests for AI Service & AI Safety Architecture
"""

import pytest
from fastapi.testclient import TestClient
from main import app

client = TestClient(app)

def test_health_endpoint():
    response = client.get("/health")
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "UP"
    assert "service" in data
    assert "active_model" in data

def test_deterministic_emergency_rule_override():
    """
    CRITICAL SAFETY TEST:
    Verify that an emergency red-flag (e.g. crushing chest pain radiating to left arm)
    triggers the Deterministic Rule Engine immediately, bypasses ML, and flags is_emergency=True.
    """
    payload = {
        "symptoms": "I have crushing chest pain radiating to my left arm with cold sweats",
        "duration": "Started 1 hour ago"
    }
    response = client.post("/analyze-symptoms", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert data["success"] is True
    assert data["is_emergency"] is True
    assert data["urgency_level"] == "Emergency"
    assert data["rule_engine_triggered"] is True
    assert data["recommended_specialty"] == "Cardiology"
    assert "EMERGENCY ALERT" in data["emergency_warning"]
    assert "911 / 112 / 108" in data["emergency_warning"]

def test_stroke_red_flag_triggers_emergency():
    payload = {
        "symptoms": "Sudden facial droop and slurred speech started 20 minutes ago",
        "duration": "20 minutes"
    }
    response = client.post("/analyze-symptoms", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert data["is_emergency"] is True
    assert data["recommended_specialty"] == "Neurology"

def test_routine_symptoms_ml_classification():
    """
    Verify that a routine non-emergency symptom (e.g., knee joint ache)
    is routed to the appropriate specialty without triggering an emergency.
    """
    payload = {
        "symptoms": "Aching pain and stiffness in my right knee joint after walking",
        "duration": "1 week"
    }
    response = client.post("/analyze-symptoms", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert data["success"] is True
    assert data["is_emergency"] is False
    assert data["urgency_level"] in ["Routine", "Moderate"]
    assert data["rule_engine_triggered"] is False
    assert data["recommended_specialty"] == "Orthopedics"
    assert data["disclaimer"] is not None

def test_gastroenterology_symptoms():
    payload = {
        "symptoms": "Burning acid reflux and heartburn in throat after eating meals",
        "duration": "3 weeks"
    }
    response = client.post("/analyze-symptoms", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert data["is_emergency"] is False
    assert data["recommended_specialty"] == "Gastroenterology"

def test_conversational_chat_assistant():
    payload = {
        "message": "Which doctor should I see for persistent joint pain in my knee?"
    }
    response = client.post("/chat-assistant", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert "reply" in data
    assert data["is_emergency"] is False
    assert "Orthopedics" in data["reply"] or data["suggested_specialty"] == "Orthopedics"

def test_conversational_chat_emergency_guardrail():
    payload = {
        "message": "I am having severe crushing chest pain and feel like I am having a heart attack"
    }
    response = client.post("/chat-assistant", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert data["is_emergency"] is True
    assert "CRITICAL EMERGENCY NOTICE" in data["reply"]

def test_validation_empty_symptoms_rejected():
    payload = {"symptoms": "  "}
    response = client.post("/analyze-symptoms", json=payload)
    assert response.status_code == 422
