"""
Smart Healthcare Assistance System - ML Model Training & Comparative Evaluation
Compares Random Forest and XGBoost classifiers on clinical symptom text features.
Extracts TF-IDF features, computes Precision, Recall, F1-Score, and exports artifacts.
"""

import os
import json
import joblib
import numpy as np
import pandas as pd
from typing import Dict, Any

from sklearn.model_selection import train_test_split, cross_validate
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import classification_report, accuracy_score, precision_recall_fscore_support
from xgboost import XGBClassifier

# Curated Clinical Training Corpus across 8 Hospital Specialties
CLINICAL_DATASET = [
    # Cardiology
    ("mild chest tightness when climbing stairs or walking uphill", "Cardiology", "Angina / Ischemic Heart Concern", "Moderate"),
    ("palpitations and racing heart rate while resting", "Cardiology", "Arrhythmia / Palpitation", "Moderate"),
    ("irregular heartbeat and occasional fluttering sensation in chest", "Cardiology", "Cardiac Dysrhythmia", "Moderate"),
    ("persistent high blood pressure with lightheadedness and mild chest pressure", "Cardiology", "Hypertensive Evaluation", "Moderate"),
    ("shortness of breath when lying flat in bed and swollen ankles", "Cardiology", "Congestive Cardiac Concern", "Urgent"),
    ("dull ache in the center of the chest after physical exertion", "Cardiology", "Exertional Angina", "Moderate"),
    ("rapid pulse pounding in ears during exercise", "Cardiology", "Sinus Tachycardia", "Routine"),
    ("mild chest discomfort after fast walking, relieved by resting", "Cardiology", "Stable Angina Profile", "Moderate"),

    # Neurology
    ("throbbing one sided headache with light sensitivity and visual aura", "Neurology", "Migraine with Aura", "Moderate"),
    ("persistent tension headache at back of neck and temples for five days", "Neurology", "Tension Headache", "Routine"),
    ("pins and needles tingling sensation in left fingers and toes", "Neurology", "Peripheral Neuropathy", "Moderate"),
    ("dizziness and spinning room sensation when turning head in bed", "Neurology", "Benign Paroxysmal Positional Vertigo", "Moderate"),
    ("tremor in right hand when resting and stiffness in walking", "Neurology", "Movement Disorder / Parkinsonian Tremor", "Moderate"),
    ("frequent cluster headaches behind the right eye with watery eye", "Neurology", "Cluster Headache", "Moderate"),
    ("unexplained numbness in both feet and loss of balance in dark", "Neurology", "Sensorimotor Neuropathy", "Moderate"),
    ("memory lapses and confusion regarding daily routine tasks", "Neurology", "Cognitive Assessment Needed", "Routine"),

    # Orthopedics
    ("severe aching pain in right knee joint with swelling after running", "Orthopedics", "Knee Osteoarthritis / Meniscal Sprain", "Moderate"),
    ("sharp lower back pain radiating down the buttock into the right thigh", "Orthopedics", "Lumbar Radiculopathy / Sciatica", "Moderate"),
    ("stiff shoulder unable to lift arm overhead with dull nocturnal ache", "Orthopedics", "Rotator Cuff Tendinopathy / Frozen Shoulder", "Routine"),
    ("twisted right ankle while stepping off curb with localized swelling and bruising", "Orthopedics", "Lateral Ankle Ligament Sprain", "Moderate"),
    ("hip joint pain when standing up from seated chair position", "Orthopedics", "Hip Osteoarthritis", "Routine"),
    ("morning stiffness in finger joints lasting more than one hour", "Orthopedics", "Inflammatory Joint Disease", "Moderate"),
    ("clicking popping sound in jaw joint and pain when chewing food", "Orthopedics", "Temporomandibular Joint Disorder", "Routine"),
    ("acute neck spasm unable to rotate head to the left side", "Orthopedics", "Cervical Muscle Strain", "Routine"),

    # Gastroenterology
    ("burning pain in center of chest and throat after eating spicy food", "Gastroenterology", "Gastroesophageal Reflux Disease (GERD)", "Routine"),
    ("burning acid reflux and heartburn in throat after eating meals", "Gastroenterology", "Gastroesophageal Reflux", "Routine"),
    ("sour stomach heartburn acid indigestion and food regurgitation", "Gastroenterology", "GERD / Acid Indigestion", "Routine"),
    ("cramping lower abdominal pain relieved after bowel movement", "Gastroenterology", "Irritable Bowel Syndrome", "Routine"),
    ("sharp pain in upper right abdomen radiating to shoulder blade after fatty meals", "Gastroenterology", "Biliary Colic / Gallbladder Evaluation", "Moderate"),
    ("persistent nausea, stomach bloating, and indigestion for over two weeks", "Gastroenterology", "Functional Dyspepsia / Gastritis", "Moderate"),
    ("constipation alternating with loose watery stools and gas for a month", "Gastroenterology", "Chronic Colonic Motility Disorder", "Routine"),
    ("epigastric gnawing hunger pain improved immediately by eating food", "Gastroenterology", "Peptic Ulcer Disease", "Moderate"),
    ("yellowish discoloration of whites of eyes and dark colored urine", "Gastroenterology", "Hepatic / Biliary Jaundice", "Urgent"),
    ("severe heartburn burning in upper chest and acid regurgitation", "Gastroenterology", "Reflux Esophagitis", "Moderate"),
    ("acid reflux waking up at night with bitter fluid in mouth", "Gastroenterology", "Severe Nocturnal GERD", "Moderate"),

    # Pulmonology
    ("chronic dry hacking cough lasting more than four weeks with wheezing", "Pulmonology", "Bronchial Asthma / Chronic Bronchitis", "Moderate"),
    ("wheezing sounds when breathing out and tightness across ribcage at night", "Pulmonology", "Asthmatic Bronchospasm", "Moderate"),
    ("cough productive of thick green yellow phlegm and low grade fever for five days", "Pulmonology", "Acute Bacterial Bronchitis", "Moderate"),
    ("shortness of breath when climbing stairs in long time cigarette smoker", "Pulmonology", "Chronic Obstructive Pulmonary Disease (COPD)", "Moderate"),
    ("tickle in throat with persistent dry nighttime cough after cold", "Pulmonology", "Post-Infectious Chronic Cough", "Routine"),
    ("mild chest tightness after cold air exposure with whistling breath", "Pulmonology", "Cold-Induced Airway Hyperreactivity", "Routine"),
    ("breathlessness during mild walking and persistent morning sputum", "Pulmonology", "Emphysema / Airflow Limitation", "Moderate"),
    ("seasonal chest congestion and allergy related cough", "Pulmonology", "Allergic Bronchitis", "Routine"),

    # Dermatology
    ("itchy red scaly patches on elbows and knees with silvery flakes", "Dermatology", "Plaque Psoriasis", "Routine"),
    ("red inflamed itchy rash in skin folds and underarms with small pustules", "Dermatology", "Cutaneous Candidiasis / Intertrigo", "Routine"),
    ("painful cystic acne breakout on cheeks and jawline for several months", "Dermatology", "Severe Nodulocystic Acne", "Routine"),
    ("circular scaly ring shaped rash on forearm spreading outward", "Dermatology", "Tinea Corporis (Ringworm)", "Routine"),
    ("dry itchy cracked skin on hands and fingers aggravated by soap", "Dermatology", "Contact Dermatitis / Hand Eczema", "Routine"),
    ("raised itchy red welts all over body appearing and disappearing", "Dermatology", "Acute Urticaria (Hives)", "Moderate"),
    ("pigmented mole on back that has recently grown darker and irregular in border", "Dermatology", "Atypical Dysplastic Nevus", "Moderate"),
    ("flaking dandruff and red patches along hairline and sides of nose", "Dermatology", "Seborrheic Dermatitis", "Routine"),

    # General Medicine
    ("mild fever, body aches, runny nose and sore throat for three days", "General Medicine", "Viral Upper Respiratory Infection", "Routine"),
    ("generalized fatigue, unrefreshing sleep, and lack of energy for two months", "General Medicine", "Chronic Fatigue / Nutritional Deficiency", "Routine"),
    ("frequent urination and excessive thirst with unexplained weight loss", "General Medicine", "Diabetes Mellitus Screen Required", "Moderate"),
    ("mild headache, low grade fever and shivering after viral cold", "General Medicine", "Viral Illness Syndrome", "Routine"),
    ("loss of appetite, general malaise, and low grade afternoon fever", "General Medicine", "Subacute Systemic Evaluation", "Moderate"),
    ("swollen tender lymph nodes in neck with painful throat when swallowing", "General Medicine", "Acute Pharyngitis / Tonsillitis", "Routine"),
    ("intermittent fever with chills and night sweats for one week", "General Medicine", "Infectious Fever Evaluation", "Moderate"),
    ("general weakness, pale complexion, and cold hands and feet", "General Medicine", "Iron Deficiency Anemia Suspect", "Routine"),

    # Pediatrics
    ("four year old child with barky seal like cough and hoarse voice", "Pediatrics", "Croup (Laryngotracheobronchitis)", "Moderate"),
    ("toddler with running nose, high fever of 101F, and ear tugging crying", "Pediatrics", "Acute Otitis Media", "Moderate"),
    ("six month infant with red rash in diaper region with satellite spots", "Pediatrics", "Pediatric Diaper Dermatitis", "Routine"),
    ("three year old with sudden red rash on cheeks looking like slapped cheeks", "Pediatrics", "Fifth Disease (Erythema Infectiosum)", "Routine"),
    ("infant crying inconsolably in evenings drawing legs up to belly", "Pediatrics", "Infant Colic Syndrome", "Routine"),
    ("eight year old child with itchy blisters on chest spreading to face", "Pediatrics", "Varicella (Chickenpox)", "Moderate"),
    ("child with loss of appetite, mouth ulcers, and rash on palms and soles", "Pediatrics", "Hand, Foot, and Mouth Disease", "Moderate"),
    ("five year old with persistent dry nighttime cough after preschool flu", "Pediatrics", "Pediatric Post-Viral Reactive Airway", "Routine")
]

def train_and_evaluate_models() -> Dict[str, Any]:
    print("Starting ML Model Training & Comparative Evaluation...")
    
    # 1. Prepare DataFrame
    df = pd.DataFrame(CLINICAL_DATASET, columns=["symptoms", "department", "condition", "urgency"])
    print(f"Total training samples: {len(df)}")
    print(f"Distribution across specialties:\n{df['department'].value_counts()}")
    
    X_raw = df["symptoms"]
    y_dept = df["department"]
    
    # Encode target labels
    label_encoder = LabelEncoder()
    y_encoded = label_encoder.fit_transform(y_dept)
    
    # 2. Feature Extraction: TF-IDF Vectorizer
    tfidf = TfidfVectorizer(
        ngram_range=(1, 2),
        lowercase=True,
        stop_words="english",
        max_features=500
    )
    X_tfidf = tfidf.fit_transform(X_raw)
    
    # Split train/test (80/20 stratified)
    X_train, X_test, y_train, y_test = train_test_split(
        X_tfidf, y_encoded, test_size=0.20, random_state=42, stratify=y_encoded
    )
    
    # 3. Model 1: Random Forest
    rf_model = RandomForestClassifier(
        n_estimators=120,
        max_depth=15,
        random_state=42,
        class_weight="balanced"
    )
    rf_model.fit(X_train, y_train)
    rf_pred = rf_model.predict(X_test)
    
    rf_acc = accuracy_score(y_test, rf_pred)
    rf_prec, rf_rec, rf_f1, _ = precision_recall_fscore_support(y_test, rf_pred, average="weighted", zero_division=0)
    
    # 4. Model 2: XGBoost
    xgb_model = XGBClassifier(
        n_estimators=100,
        max_depth=6,
        learning_rate=0.1,
        random_state=42,
        eval_metric="mlogloss"
    )
    xgb_model.fit(X_train, y_train)
    xgb_pred = xgb_model.predict(X_test)
    
    xgb_acc = accuracy_score(y_test, xgb_pred)
    xgb_prec, xgb_rec, xgb_f1, _ = precision_recall_fscore_support(y_test, xgb_pred, average="weighted", zero_division=0)
    
    print("\n--- MODEL COMPARISON RESULTS ---")
    print(f"Random Forest -> Accuracy: {rf_acc:.4f}, Precision: {rf_prec:.4f}, Recall: {rf_rec:.4f}, F1-Score: {rf_f1:.4f}")
    print(f"XGBoost       -> Accuracy: {xgb_acc:.4f}, Precision: {xgb_prec:.4f}, Recall: {xgb_rec:.4f}, F1-Score: {xgb_f1:.4f}")
    
    # Select Best Model based on F1-Score
    if xgb_f1 > rf_f1:
        best_model_name = "XGBoost"
        best_model = xgb_model
        best_metrics = {"accuracy": xgb_acc, "precision": xgb_prec, "recall": xgb_rec, "f1_score": xgb_f1}
    else:
        best_model_name = "RandomForest"
        best_model = rf_model
        best_metrics = {"accuracy": rf_acc, "precision": rf_prec, "recall": rf_rec, "f1_score": rf_f1}
        
    print(f"\nWinning Model Selected for Deployment: {best_model_name}")
    
    # 5. Save Artifacts
    artifacts_dir = os.path.join(os.path.dirname(__file__), "artifacts")
    os.makedirs(artifacts_dir, exist_ok=True)
    
    model_path = os.path.join(artifacts_dir, "model.joblib")
    tfidf_path = os.path.join(artifacts_dir, "tfidf.joblib")
    encoder_path = os.path.join(artifacts_dir, "label_encoder.joblib")
    report_path = os.path.join(artifacts_dir, "evaluation_report.json")
    
    joblib.dump(best_model, model_path)
    joblib.dump(tfidf, tfidf_path)
    joblib.dump(label_encoder, encoder_path)
    
    evaluation_report = {
        "dataset_sample_count": len(df),
        "departments_count": len(label_encoder.classes_),
        "department_classes": list(label_encoder.classes_),
        "selected_model": best_model_name,
        "models_evaluated": {
            "RandomForest": {
                "accuracy": float(rf_acc),
                "precision": float(rf_prec),
                "recall": float(rf_rec),
                "f1_score": float(rf_f1)
            },
            "XGBoost": {
                "accuracy": float(xgb_acc),
                "precision": float(xgb_prec),
                "recall": float(xgb_rec),
                "f1_score": float(xgb_f1)
            }
        },
        "deployed_metrics": {k: float(v) for k, v in best_metrics.items()}
    }
    
    with open(report_path, "w") as f:
        json.dump(evaluation_report, f, indent=4)
        
    print(f"Artifacts saved successfully in: {artifacts_dir}")
    return evaluation_report

if __name__ == "__main__":
    train_and_evaluate_models()
