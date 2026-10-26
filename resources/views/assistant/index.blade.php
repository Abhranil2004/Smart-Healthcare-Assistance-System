<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Healthcare Assistant | Smart Healthcare Assistance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/medinexa-theme.css') }}">
    <style>
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #0f172a; padding-bottom: 60px; }
        .mn-assistant-page-header { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 0; margin-bottom: 36px; }
        .mn-container { max-width: 900px; margin: 0 auto; padding: 0 20px; }
    </style>
</head>
<body class="medinexa-app">

    <div class="mn-assistant-page-header">
        <div class="mn-container" style="display:flex;justify-content:space-between;align-items:center;">
            <a href="{{ url('/') }}" class="mn-brand-wrapper">
                <div class="mn-brand-logo-icon"><i class="fa-solid fa-hospital-user"></i></div>
                <div class="mn-brand-text">
                    <div class="mn-brand-title">Smart <span style="color:#0d9488;">Healthcare</span></div>
                    <div class="mn-brand-subtitle">Assistance System</div>
                </div>
            </a>
            <div style="display:flex;gap:10px;">
                <a href="{{ url('/') }}" class="mn-btn mn-btn-outline mn-btn-sm"><i class="fa-solid fa-house"></i> Home</a>
                <a href="{{ url('/login') }}" class="mn-btn mn-btn-primary mn-btn-sm"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
            </div>
        </div>
    </div>

    <div class="mn-container">
        <div style="text-align:center;margin-bottom:28px;">
            <span class="mn-badge mn-badge-teal" style="margin-bottom:8px;">Clinical Triage Engine</span>
            <h1 style="font-size:30px;font-weight:800;color:#0f172a;margin-bottom:8px;">Describe Your Health Symptoms</h1>
            <p style="font-size:15px;color:#64748b;max-width:620px;margin:0 auto;">
                Smart Healthcare Assistance System analyzes symptom patterns, evaluates urgency levels, suggests the matching medical specialty, and connects you with available hospital doctors.
            </p>
        </div>

        <div class="mn-assistant-container">
            <div class="mn-assistant-header">
                <div>
                    <h3><i class="fa-solid fa-sparkles" style="color:#2dd4bf;"></i> Smart Healthcare Assistant</h3>
                    <p>Natural Language Symptom Evaluation & Specialty Mapping</p>
                </div>
                <span class="mn-badge mn-badge-teal" style="background:rgba(255,255,255,0.15);color:#ffffff;">Confidential & Protected</span>
            </div>

            <div class="mn-assistant-body">
                <!-- Emergency Alert -->
                <div class="mn-emergency-alert" id="emergencyBanner" style="display:none;">
                    <i class="fa-solid fa-triangle-exclamation mn-emergency-icon"></i>
                    <div class="mn-emergency-content">
                        <h4 id="emergencyTitle">EMERGENCY WARNING DETECTED</h4>
                        <p id="emergencyText"></p>
                    </div>
                </div>

                <form id="pageAssistantForm">
                    <div class="mn-assistant-input-group">
                        <label for="pageUserSymptoms">
                            <i class="fa-solid fa-notes-medical" style="color:#0d9488;"></i> Describe your symptoms in detail:
                        </label>
                        <textarea 
                            id="pageUserSymptoms" 
                            class="mn-assistant-textarea" 
                            placeholder="e.g., I've had a persistent dry cough and fever for three days, or sharp pain in my lower back radiating to my leg..."
                            required></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                        <div class="mn-assistant-input-group" style="margin-bottom:0;">
                            <label for="pageSymptomDuration">Approximate Duration</label>
                            <select id="pageSymptomDuration" class="mn-assistant-textarea" style="min-height:44px;padding:8px 12px;">
                                <option value="Started today">Started today</option>
                                <option value="2-3 days">2-3 days</option>
                                <option value="Past week">Past week</option>
                                <option value="Over a month (chronic)">Over a month (chronic)</option>
                            </select>
                        </div>
                        <div style="display:flex;align-items:flex-end;">
                            <button type="submit" id="pageBtnAnalyze" class="mn-btn mn-btn-teal" style="width:100%;height:44px;">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Analyze & Recommend
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Triage Result Container -->
                <div id="pageTriageResult" class="mn-triage-result">
                    <div class="mn-triage-badge-row">
                        <span id="pageResUrgencyBadge" class="mn-badge mn-badge-teal">Urgency: Routine</span>
                        <span id="pageResSpecialtyBadge" class="mn-badge mn-badge-sky">Specialty: General Medicine</span>
                        <span id="pageResDurationBadge" class="mn-badge mn-badge-amber">Duration: 2-3 days</span>
                    </div>

                    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:16px;">
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Identified Symptoms</h4>
                        <div id="pageResSymptomsList" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;"></div>
                        <p id="pageResRationale" style="font-size:13.5px;color:#475569;margin:0;"></p>
                    </div>

                    <div style="margin-bottom:16px;">
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Recommended Next Actions</h4>
                        <ul id="pageResAdviceList" style="padding-left:20px;font-size:13.5px;color:#334155;"></ul>
                    </div>

                    <div id="pageMatchedDoctorsWrap">
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;">
                            Consultant Doctors in Hospital Register
                        </h4>
                        <div id="pageResDoctorsList" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:12px;"></div>
                    </div>

                    <div class="mn-disclaimer-note">
                        <i class="fa-solid fa-circle-info" style="color:#0d9488;"></i>
                        <span id="pageResDisclaimer">Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pageAssistantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const symptoms = document.getElementById('pageUserSymptoms').value.trim();
            const duration = document.getElementById('pageSymptomDuration').value;
            const btn = document.getElementById('pageBtnAnalyze');
            const resultBox = document.getElementById('pageTriageResult');
            const emergencyBanner = document.getElementById('emergencyBanner');
            
            if (!symptoms) return;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analyzing...';

            fetch('{{ url("/assistant/analyze") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ symptoms: symptoms, duration: duration })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze & Recommend';

                if (data.success) {
                    resultBox.style.display = 'block';

                    if (data.is_emergency) {
                        emergencyBanner.style.display = 'flex';
                        document.getElementById('emergencyTitle').innerText = 'CRITICAL EMERGENCY WARNING';
                        document.getElementById('emergencyText').innerText = data.emergency_warning || data.emergency_reason;
                    } else {
                        emergencyBanner.style.display = 'none';
                    }

                    const urgencyBadge = document.getElementById('pageResUrgencyBadge');
                    urgencyBadge.innerText = 'Urgency: ' + data.urgency_level;
                    urgencyBadge.className = 'mn-badge ' + (
                        data.urgency_level === 'Emergency' ? 'mn-badge-rose' :
                        data.urgency_level === 'Urgent' ? 'mn-badge-amber' : 'mn-badge-emerald'
                    );

                    document.getElementById('pageResSpecialtyBadge').innerText = 'Specialty: ' + data.recommended_specialty;
                    document.getElementById('pageResDurationBadge').innerText = 'Duration: ' + data.duration;

                    const symptomsList = document.getElementById('pageResSymptomsList');
                    symptomsList.innerHTML = '';
                    data.extracted_symptoms.forEach(s => {
                        const pill = document.createElement('span');
                        pill.className = 'mn-badge mn-badge-teal';
                        pill.innerText = s;
                        symptomsList.appendChild(pill);
                    });

                    document.getElementById('pageResRationale').innerText = data.clinical_rationale;

                    const adviceList = document.getElementById('pageResAdviceList');
                    adviceList.innerHTML = '';
                    data.actionable_advice.forEach(a => {
                        const li = document.createElement('li');
                        li.innerText = a;
                        adviceList.appendChild(li);
                    });

                    const docList = document.getElementById('pageResDoctorsList');
                    docList.innerHTML = '';
                    if (data.recommended_doctors && data.recommended_doctors.length > 0) {
                        data.recommended_doctors.forEach(doc => {
                            const card = document.createElement('div');
                            card.style.background = '#ffffff';
                            card.style.border = '1px solid #e2e8f0';
                            card.style.borderRadius = '8px';
                            card.style.padding = '12px';
                            card.innerHTML = `
                                <div style="font-size:14px;font-weight:700;color:#0f172a;">${doc.name}</div>
                                <div style="font-size:12px;color:#0d9488;font-weight:600;">${doc.department}</div>
                                <div style="font-size:11.5px;color:#64748b;margin:4px 0 8px;">${doc.profile}</div>
                                <a href="${doc.booking_url}" class="mn-btn mn-btn-primary mn-btn-sm" style="width:100%;font-size:12px;">
                                    <i class="fa-regular fa-calendar-plus"></i> Book Consultation
                                </a>
                            `;
                            docList.appendChild(card);
                        });
                    }

                    document.getElementById('pageResDisclaimer').innerText = data.disclaimer;
                    resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze & Recommend';
                alert('Analysis failed. Please check your connection.');
            });
        });
    </script>
</body>
</html>
