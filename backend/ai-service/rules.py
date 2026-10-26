"""
Smart Healthcare Assistance System - Deterministic AI Safety Rule Engine
Part of the Tripartite AI Safety Architecture:
[Deterministic Rule Engine] -> [ML Condition Predictor] -> [LLM Conversational Engine]

CRITICAL CLINICAL PRINCIPLE:
Rule-based emergency detection executes BEFORE and INDEPENDENTLY of any ML or LLM
inference. ML model predictions and probabilistic scores are NEVER allowed to override
or dilute an emergency alert when red-flag criteria are met.
"""

import re
from typing import Dict, Any, Optional, Tuple, List

# Explicitly documented clinical emergency patterns
EMERGENCY_RULES: List[Dict[str, Any]] = [
    {
        "category": "Cardiac / Myocardial Infarction",
        "patterns": [
            r"\b(crushing|severe|squeezing|radiating)\s+(chest\s+pain|chest\s+tightness|chest\s+pressure)\b",
            r"\b(chest\s+pain|chest\s+pressure).*(left\s+arm|jaw|neck|back|diaphoresis|cold\s+sweat)\b",
            r"\b(left\s+arm\s+pain).*(chest\s+pain|chest\s+tightness)\b",
            r"\bheart\s+attack\b"
        ],
        "emergency_reason": "Symptoms exhibit classical red flags for acute coronary syndrome / myocardial infarction.",
        "recommended_specialty": "Cardiology",
        "urgency_level": "Emergency",
        "action": "Immediate emergency medical evaluation (ER/Cath Lab). Dial 911 / 112 / 108 immediately."
    },
    {
        "category": "Neurological / Acute Cerebrovascular (FAST)",
        "patterns": [
            r"\b(facial\s+droop|face\s+droop|slurred\s+speech|speech\s+slurred)\b",
            r"\b(sudden\s+weakness|sudden\s+numbness).*(one\s+side|arm|face|leg)\b",
            r"\b(stroke|mini\s+stroke|tia)\b",
            r"\b(thunderclap|worst\s+headache\s+of\s+my\s+life)\b"
        ],
        "emergency_reason": "High-risk signs of acute cerebrovascular event (Stroke) or subarachnoid hemorrhage.",
        "recommended_specialty": "Neurology",
        "urgency_level": "Emergency",
        "action": "Time-critical neurological emergency. Call emergency medical services immediately."
    },
    {
        "category": "Respiratory / Acute Airway Compromise",
        "patterns": [
            r"\b(cannot\s+breathe|gasping\s+for\s+air|suffocating|blue\s+lips|cyanosis)\b",
            r"\b(severe\s+shortness\s+of\s+breath|severe\s+dyspnea|stridor)\b",
            r"\b(throat\s+closing|choking)\b"
        ],
        "emergency_reason": "Signs of critical airway compromise or acute respiratory failure.",
        "recommended_specialty": "Pulmonology",
        "urgency_level": "Emergency",
        "action": "Requires emergent airway stabilization. Seek immediate emergency care."
    },
    {
        "category": "Immunological / Anaphylaxis",
        "patterns": [
            r"\b(swollen\s+lips|swollen\s+tongue|throat\s+swelling).*(allergy|allergic|rash|hives|bee\s+sting|peanut)\b",
            r"\banaphylaxis\b"
        ],
        "emergency_reason": "Potential life-threatening systemic anaphylactic reaction.",
        "recommended_specialty": "General Medicine",
        "urgency_level": "Emergency",
        "action": "Administer intramuscular epinephrine if prescribed and transport to nearest emergency department."
    },
    {
        "category": "Trauma / Acute Severe Hemorrhage",
        "patterns": [
            r"\b(uncontrolled\s+bleeding|profuse\s+bleeding|coughing\s+up\s+blood|vomiting\s+blood|hematemesis)\b",
            r"\b(loss\s+of\s+consciousness|unresponsive|passed\s+out|seizure|convulsions)\b"
        ],
        "emergency_reason": "Acute hemodynamic instability, severe hemorrhage, or neurological loss of consciousness.",
        "recommended_specialty": "General Medicine",
        "urgency_level": "Emergency",
        "action": "Direct transfer to nearest emergency trauma center."
    }
]

def evaluate_emergency_rules(symptoms_text: str) -> Optional[Dict[str, Any]]:
    """
    Evaluates patient text against explicit deterministic emergency red-flag triggers.
    Returns structured emergency dict if triggered, otherwise None.
    """
    if not symptoms_text:
        return None
        
    normalized_text = symptoms_text.lower().strip()
    
    for rule in EMERGENCY_RULES:
        for pattern in rule["patterns"]:
            if re.search(pattern, normalized_text, re.IGNORECASE):
                return {
                    "is_emergency": True,
                    "urgency_level": rule["urgency_level"],
                    "emergency_reason": rule["emergency_reason"],
                    "recommended_specialty": rule["recommended_specialty"],
                    "action_required": rule["action"],
                    "rule_category": rule["category"],
                    "matched_pattern": pattern,
                    "rule_engine_triggered": True
                }
                
    return None
