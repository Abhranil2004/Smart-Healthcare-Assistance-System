<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Support\Collection;

/**
 * MediNexa AI Healthcare Assistant Service.
 *
 * Provides clinical natural language symptom triage, urgency assessment,
 * medical specialty recommendations, and hospital doctor discovery.
 *
 * Safety Notice:
 * This service provides probabilistic, preliminary guidance for triage
 * and does not replace professional medical diagnosis or clinical judgment.
 */
class HealthcareAssistantService
{
    /**
     * Emergency triggers that require immediate emergency care.
     */
    protected array $emergencyTriggers = [
        'chest pain radiating', 'crushing chest pain', 'pressure in chest',
        'shortness of breath at rest', 'cannot breathe', 'difficulty breathing severe',
        'sudden numbness', 'facial drooping', 'slurred speech', 'arm weakness',
        'coughing up blood', 'vomiting blood', 'severe bleeding',
        'anaphylaxis', 'throat swelling', 'loss of consciousness', 'seizure',
        'worst headache of life', 'unresponsive', 'bluish lips',
    ];

    /**
     * Symptom dictionary mapping keywords to specialties, urgency, and clinical notes.
     */
    protected array $specialtyKnowledgeBase = [
        'Cardiology' => [
            'keywords' => ['chest pain', 'palpitation', 'palpitations', 'irregular heartbeat', 'high blood pressure', 'hypertension', 'heart flutter', 'angina', 'rapid pulse'],
            'urgency' => 'Urgent',
            'rationale' => 'Symptoms relate to cardiovascular function. Prompt evaluation of heart rate, rhythm, and cardiac perfusion is recommended.',
        ],
        'Orthopaedics' => [
            'keywords' => ['joint pain', 'back pain', 'knee pain', 'fracture', 'bone pain', 'sprain', 'swollen ankle', 'shoulder pain', 'ligament', 'arthritis', 'spine', 'stiff neck'],
            'urgency' => 'Routine',
            'rationale' => 'Symptoms involve the musculoskeletal system, joints, or spine. Imaging and orthopaedic consultation may be required.',
        ],
        'Paediatrics' => [
            'keywords' => ['child fever', 'baby crying', 'infant rash', 'child cough', 'toddler vomiting', 'pediatric', 'vaccination baby', 'ear infection child'],
            'urgency' => 'Urgent',
            'rationale' => 'Symptoms occur in a pediatric patient where rapid clinical assessment is indicated to prevent dehydration or complications.',
        ],
        'General Medicine' => [
            'keywords' => ['fever', 'cold', 'flu', 'fatigue', 'weakness', 'body ache', 'mild headache', 'chills', 'malaise', 'runny nose'],
            'urgency' => 'Routine',
            'rationale' => 'General non-specific constitutional symptoms best evaluated initially by a primary care or general medicine physician.',
        ],
        'Gastroenterology' => [
            'keywords' => ['stomach ache', 'abdominal pain', 'acid reflux', 'heartburn', 'nausea', 'vomiting', 'diarrhea', 'constipation', 'bloating', 'indigestion', 'gastritis'],
            'urgency' => 'Routine',
            'rationale' => 'Symptoms relate to the digestive tract. Clinical evaluation of GI motility and inflammatory causes is advised.',
        ],
        'Pulmonology' => [
            'keywords' => ['chronic cough', 'wheezing', 'asthma', 'bronchitis', 'phlegm', 'chest congestion', 'shortness of breath on exertion'],
            'urgency' => 'Urgent',
            'rationale' => 'Symptoms involve the respiratory airways. Pulmonary function and oxygenation should be evaluated.',
        ],
        'Neurology' => [
            'keywords' => ['migraine', 'severe headache', 'dizziness', 'vertigo', 'tremor', 'tingling', 'numbness', 'memory loss', 'blackout'],
            'urgency' => 'Urgent',
            'rationale' => 'Symptoms involve the central or peripheral nervous system warranting neurological assessment.',
        ],
        'Dermatology' => [
            'keywords' => ['skin rash', 'itching', 'eczema', 'acne', 'hives', 'skin lesion', 'psoriasis', 'blister', 'mole change'],
            'urgency' => 'Routine',
            'rationale' => 'Symptoms affect the skin or mucosal barrier. Dermatological visual examination is recommended.',
        ],
        'ENT' => [
            'keywords' => ['sore throat', 'ear ache', 'ear pain', 'sinus', 'blocked nose', 'tonsillitis', 'hoarse voice', 'hearing loss', 'ringing in ear'],
            'urgency' => 'Routine',
            'rationale' => 'Symptoms involve the ear, nose, or throat regions.',
        ],
        'Ophthalmology' => [
            'keywords' => ['eye pain', 'blurry vision', 'red eye', 'dry eyes', 'double vision', 'eye discharge', 'flashes in eye'],
            'urgency' => 'Urgent',
            'rationale' => 'Symptoms involve the visual apparatus; prompt ophthalmic evaluation prevents vision impairment.',
        ],
    ];

    /**
     * Analyze user query and return triage output.
     */
    public function analyze(string $userInput, ?string $duration = null): array
    {
        $normalizedText = strtolower(trim($userInput));
        $extractedSymptoms = [];
        $specialtyScores = [];
        $isEmergency = false;
        $emergencyReason = null;

        // 1. Check for life-threatening emergency triggers
        foreach ($this->emergencyTriggers as $trigger) {
            if (str_contains($normalizedText, $trigger)) {
                $isEmergency = true;
                $emergencyReason = "Potential critical warning sign detected: '" . ucwords($trigger) . "'";
                break;
            }
        }

        // 2. Extract symptoms & score specialties
        foreach ($this->specialtyKnowledgeBase as $specialty => $data) {
            $specialtyScores[$specialty] = 0;
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($normalizedText, $keyword)) {
                    $extractedSymptoms[] = ucwords($keyword);
                    $specialtyScores[$specialty] += 2;
                }
            }
        }

        $extractedSymptoms = array_values(array_unique($extractedSymptoms));

        // Fallback if no specific keyword matched
        if (empty($extractedSymptoms)) {
            $extractedSymptoms[] = 'General health concern';
        }

        // Determine recommended specialty
        arsort($specialtyScores);
        $topSpecialty = key($specialtyScores);
        if ($specialtyScores[$topSpecialty] === 0) {
            $topSpecialty = 'General Medicine';
        }

        $specialtyConfig = $this->specialtyKnowledgeBase[$topSpecialty] ?? $this->specialtyKnowledgeBase['General Medicine'];
        
        // Urgency resolution
        $urgency = $isEmergency ? 'Emergency' : $specialtyConfig['urgency'];
        if (!$isEmergency && $duration && (str_contains(strtolower($duration), 'week') || str_contains(strtolower($duration), 'month'))) {
            // Chronic duration might change follow-up advice
            $urgencyNotes = 'Condition reported for extended duration (' . htmlspecialchars($duration) . '). Comprehensive clinical workup advised.';
        } else {
            $urgencyNotes = $specialtyConfig['rationale'];
        }

        // 3. Discover real doctors from database
        $recommendedDoctors = $this->findDoctorsForSpecialty($topSpecialty);

        return [
            'success' => true,
            'is_emergency' => $isEmergency,
            'emergency_warning' => $isEmergency ? 'EMERGENCY ALERT: Your reported symptoms indicate a potentially critical condition requiring immediate medical attention. Please proceed to the nearest Emergency Room or call your local emergency medical service immediately (911 / 112 / 108).' : null,
            'emergency_reason' => $emergencyReason,
            'urgency_level' => $urgency,
            'extracted_symptoms' => $extractedSymptoms,
            'duration' => $duration ?: 'Not specified',
            'recommended_specialty' => $topSpecialty,
            'clinical_rationale' => $urgencyNotes,
            'recommended_doctors' => $recommendedDoctors,
            'actionable_advice' => $this->getActionableAdvice($urgency, $topSpecialty),
            'disclaimer' => 'Smart Healthcare Assistance System provides preliminary triage suggestions only. It does not provide medical diagnosis, medical clearance, or emergency treatment.',
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Query doctors matching the specialty from the real database.
     */
    public function findDoctorsForSpecialty(string $specialty): array
    {
        try {
            $doctors = Doctor::query()
                ->where(function ($query) use ($specialty) {
                    $query->where('department', 'LIKE', '%' . $specialty . '%')
                          ->orWhere('profile', 'LIKE', '%' . $specialty . '%');
                })
                ->limit(4)
                ->get();

            if ($doctors->isEmpty()) {
                // Return any available doctors as general fallback
                $doctors = Doctor::query()->limit(3)->get();
            }

            return $doctors->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'name' => $doc->name,
                    'department' => $doc->department ?: 'General Medicine',
                    'profile' => $doc->profile ?: 'Consultant Physician',
                    'phone' => $doc->phone ?: 'Hospital Desk',
                    'booking_url' => url('/appointments'),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Return next recommended clinical actions based on urgency.
     */
    protected function getActionableAdvice(string $urgency, string $specialty): array
    {
        if ($urgency === 'Emergency') {
            return [
                'Do not wait for a scheduled appointment.',
                'Go to the nearest Hospital Emergency Department immediately.',
                'Have someone accompany or drive you if possible; do not drive if experiencing dizziness or chest discomfort.',
            ];
        }

        if ($urgency === 'Urgent') {
            return [
                "Book a consultation with a {$specialty} specialist today.",
                'Monitor vital signs (temperature, pulse, blood pressure) if home monitoring is available.',
                'Keep records of when symptoms worsen or improve.',
            ];
        }

        return [
            "Schedule a standard consultation with {$specialty}.",
            'Prepare a list of current medications and allergies prior to your visit.',
            'Note the timeline and triggers of your symptoms to discuss with your doctor.',
        ];
    }
}
