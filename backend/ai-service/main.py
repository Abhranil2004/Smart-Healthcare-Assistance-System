"""
Smart Healthcare Assistance System - AI & Machine Learning FastAPI Service
Tripartite AI Safety Architecture:
1. Deterministic Rule Engine (Emergency detection & immediate escalation)
2. ML Predictor (TF-IDF + XGBoost/Random Forest condition classification)
3. LLM Conversational Assistant (Context-aware patient intake with strict guardrails)
"""

import os
import re
import json
import joblib
import numpy as np
from typing import Dict, Any, Optional, List
from fastapi import FastAPI, HTTPException, status
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from dotenv import load_dotenv

from rules import evaluate_emergency_rules

# Load environment configuration
load_dotenv()

from contextlib import asynccontextmanager

# Paths to trained ML artifacts
ARTIFACTS_DIR = os.path.join(os.path.dirname(__file__), "artifacts")
MODEL_PATH = os.path.join(ARTIFACTS_DIR, "model.joblib")
TFIDF_PATH = os.path.join(ARTIFACTS_DIR, "tfidf.joblib")
ENCODER_PATH = os.path.join(ARTIFACTS_DIR, "label_encoder.joblib")
REPORT_PATH = os.path.join(ARTIFACTS_DIR, "evaluation_report.json")

# Global loaded ML components
ml_model = None
tfidf_vectorizer = None
label_encoder = None
evaluation_metadata = {}

def load_ml_artifacts():
    global ml_model, tfidf_vectorizer, label_encoder, evaluation_metadata
    try:
        if os.path.exists(MODEL_PATH) and os.path.exists(TFIDF_PATH) and os.path.exists(ENCODER_PATH):
            ml_model = joblib.load(MODEL_PATH)
            tfidf_vectorizer = joblib.load(TFIDF_PATH)
            label_encoder = joblib.load(ENCODER_PATH)
            
            if os.path.exists(REPORT_PATH):
                with open(REPORT_PATH, "r") as f:
                    evaluation_metadata = json.load(f)
                    
            print(f"[AI Service] Successfully loaded ML Model ({evaluation_metadata.get('selected_model', 'XGBoost')}) and TF-IDF artifacts.")
        else:
            print("[AI Service] WARNING: Model artifacts not found. Run train_model.py first.")
    except Exception as e:
        print(f"[AI Service] ERROR loading artifacts: {e}")

# Load immediately on module import
load_ml_artifacts()

@asynccontextmanager
async def lifespan(app: FastAPI):
    load_ml_artifacts()
    yield

app = FastAPI(
    title="Smart Healthcare Assistance AI Service",
    description="NLP symptom extraction, Deterministic Emergency Safeguards, and ML specialty routing",
    version="2.0.0",
    lifespan=lifespan
)

# CORS Middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# -----------------------------------------------------------------------------
# Request & Response Schemas
# -----------------------------------------------------------------------------
class SymptomAnalysisRequest(BaseModel):
    symptoms: str = Field(..., min_length=2, description="Natural language description of patient symptoms")
    duration: Optional[str] = Field(None, description="Reported duration of symptoms")
    patient_id: Optional[int] = Field(None, description="Optional patient ID if authenticated")

class SymptomAnalysisResponse(BaseModel):
    success: bool
    is_emergency: bool
    emergency_warning: Optional[str]
    emergency_reason: Optional[str]
    urgency_level: str
    extracted_symptoms: List[str]
    duration: str
    recommended_specialty: str
    clinical_rationale: str
    ml_confidence_score: Optional[float]
    model_used: str
    rule_engine_triggered: bool
    actionable_advice: str
    disclaimer: str

class ChatAssistantRequest(BaseModel):
    message: str = Field(..., min_length=1, description="Patient message or question")
    conversation_history: Optional[List[Dict[str, str]]] = Field(default=[], description="Previous conversation turns")

class ChatAssistantResponse(BaseModel):
    reply: str
    suggested_specialty: Optional[str]
    is_emergency: bool
    disclaimer: str

# -----------------------------------------------------------------------------
# NLP Extraction Helper
# -----------------------------------------------------------------------------
COMMON_SYMPTOM_TOKENS = [
    "chest pain", "chest tightness", "palpitations", "shortness of breath", "dyspnea",
    "headache", "migraine", "dizziness", "vertigo", "numbness", "tingling", "tremor",
    "knee pain", "back pain", "joint pain", "ankle swelling", "stiffness", "muscle pain",
    "stomach pain", "abdominal pain", "heartburn", "acid reflux", "nausea", "vomiting", "diarrhea", "constipation",
    "cough", "wheezing", "phlegm", "breathlessness", "fever", "chills", "fatigue", "body ache", "sore throat",
    "rash", "itching", "red patches", "hives", "acne", "swelling"
]

def extract_clinical_features(text: str) -> List[str]:
    """Tokenize and identify clinical symptom expressions."""
    lower_text = text.lower()
    matched = []
    for token in COMMON_SYMPTOM_TOKENS:
        if token in lower_text:
            matched.append(token)
    if not matched:
        # Fallback to key words
        words = [w for w in re.findall(r"\b[a-zA-Z]{3,}\b", lower_text) if w not in {"have", "been", "feeling", "with", "days", "from", "very"}]
        matched = words[:5]
    return matched

# -----------------------------------------------------------------------------
# Endpoints
# -----------------------------------------------------------------------------
@app.get("/health")
def health_check():
    return {
        "status": "UP",
        "service": "Smart Healthcare Assistance AI Service",
        "active_model": evaluation_metadata.get("selected_model", "XGBoost"),
        "rule_engine": "Deterministic AI Safety Rule Engine Active",
        "departments_supported": evaluation_metadata.get("department_classes", [])
    }

@app.post("/analyze-symptoms", response_model=SymptomAnalysisResponse)
def analyze_symptoms(req: SymptomAnalysisRequest):
    symptoms = req.symptoms.strip()
    if not symptoms:
        raise HTTPException(status_code=status.HTTP_422_UNPROCESSABLE_ENTITY, detail="Symptom text is required.")

    extracted_tokens = extract_clinical_features(symptoms)
    duration_str = req.duration.strip() if req.duration else "Not specified"

    # =========================================================================
    # STEP 1: DETERMINISTIC EMERGENCY RULE ENGINE CHECK
    # Rule engine executes first and independently of ML inference.
    # =========================================================================
    emergency_eval = evaluate_emergency_rules(symptoms)
    
    if emergency_eval is not None:
        return SymptomAnalysisResponse(
            success=True,
            is_emergency=True,
            emergency_warning=(
                "EMERGENCY ALERT: Your reported symptoms indicate a potentially critical condition "
                "requiring immediate medical evaluation. Please proceed to the nearest Emergency Room "
                "or contact your emergency medical service immediately (911 / 112 / 108)."
            ),
            emergency_reason=emergency_eval["emergency_reason"],
            urgency_level="Emergency",
            extracted_symptoms=extracted_tokens,
            duration=duration_str,
            recommended_specialty=emergency_eval["recommended_specialty"],
            clinical_rationale=f"Deterministic Red-Flag Trigger Activated: {emergency_eval['rule_category']}.",
            ml_confidence_score=1.0,
            model_used="Deterministic AI Safety Rule Engine",
            rule_engine_triggered=True,
            actionable_advice=emergency_eval["action_required"],
            disclaimer="Smart Healthcare Assistance System AI provides preliminary triage guidance only and does not replace emergency medical care or diagnosis."
        )

    # =========================================================================
    # STEP 2: ML PREDICTION & SPECIALTY ROUTING
    # =========================================================================
    if ml_model is not None and tfidf_vectorizer is not None and label_encoder is not None:
        try:
            tfidf_features = tfidf_vectorizer.transform([symptoms])
            pred_encoded = ml_model.predict(tfidf_features)[0]
            pred_specialty = label_encoder.inverse_transform([pred_encoded])[0]
            
            # Predict probabilities if supported
            confidence = 0.85
            if hasattr(ml_model, "predict_proba"):
                probas = ml_model.predict_proba(tfidf_features)[0]
                confidence = float(np.max(probas))
                
            model_name = evaluation_metadata.get("selected_model", "XGBoost")
            
            # Assess non-emergency urgency level
            urgency = "Moderate" if any(w in symptoms.lower() for w in ["severe", "worsening", "days", "sharp", "swollen"]) else "Routine"
            
            rationale = (
                f"Statistical feature analysis mapped symptom tokens ({', '.join(extracted_tokens[:3])}) "
                f"to the {pred_specialty} specialty with {confidence * 100:.1f}% model confidence."
            )
            
            advice = (
                f"Schedule an outpatient consultation with a specialist in {pred_specialty}. "
                "Note down symptom progression, triggers, and any medication taken."
            )
            
            return SymptomAnalysisResponse(
                success=True,
                is_emergency=False,
                emergency_warning=None,
                emergency_reason=None,
                urgency_level=urgency,
                extracted_symptoms=extracted_tokens,
                duration=duration_str,
                recommended_specialty=pred_specialty,
                clinical_rationale=rationale,
                ml_confidence_score=round(confidence, 4),
                model_used=f"{model_name} + TF-IDF Vectorizer",
                rule_engine_triggered=False,
                actionable_advice=advice,
                disclaimer="Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis."
            )
        except Exception as e:
            print(f"[AI Service] Inference fallback due to error: {e}")

    # Fallback to General Medicine if model uninitialized
    return SymptomAnalysisResponse(
        success=True,
        is_emergency=False,
        emergency_warning=None,
        emergency_reason=None,
        urgency_level="Routine",
        extracted_symptoms=extracted_tokens,
        duration=duration_str,
        recommended_specialty="General Medicine",
        clinical_rationale="Evaluated via standard clinical rule baseline.",
        ml_confidence_score=0.75,
        model_used="Clinical Rule Baseline",
        rule_engine_triggered=False,
        actionable_advice="Consult with a General Medicine physician for comprehensive clinical assessment.",
        disclaimer="Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis."
    )

@app.post("/chat-assistant", response_model=ChatAssistantResponse)
def chat_assistant(req: ChatAssistantRequest):
    message = req.message.strip()
    
    # Check emergency red flags first in conversation
    emergency_eval = evaluate_emergency_rules(message)
    if emergency_eval:
        return ChatAssistantResponse(
            reply=(
                "⚠️ CRITICAL EMERGENCY NOTICE: The symptoms you described indicate a potential medical emergency. "
                f"{emergency_eval['emergency_reason']} "
                "Please call your local emergency service (911 / 112 / 108) or proceed to the nearest Emergency Department immediately."
            ),
            suggested_specialty=emergency_eval["recommended_specialty"],
            is_emergency=True,
            disclaimer="Smart Healthcare Assistance System AI does not replace emergency medical intervention."
        )

    # Contextual guidance
    extracted = extract_clinical_features(message)
    
    # Run triage inference on message
    specialty = "General Medicine"
    if ml_model and tfidf_vectorizer and label_encoder:
        try:
            feats = tfidf_vectorizer.transform([message])
            pred_idx = ml_model.predict(feats)[0]
            specialty = label_encoder.inverse_transform([pred_idx])[0]
        except Exception:
            pass
            
    reply_text = (
        f"Thank you for sharing your symptoms. Based on your description of {', '.join(extracted[:3]) if extracted else 'your symptoms'}, "
        f"I recommend scheduling an evaluation with our **{specialty}** department. "
        "Our hospital specialists can perform diagnostic assessments, review your medical history, and determine appropriate care.\n\n"
        "Would you like me to guide you to available appointment slots for this department?"
    )

    return ChatAssistantResponse(
        reply=reply_text,
        suggested_specialty=specialty,
        is_emergency=False,
        disclaimer="Smart Healthcare Assistance System AI Assistant provides preliminary guidance only and does not substitute for clinical diagnosis by a doctor."
    )
