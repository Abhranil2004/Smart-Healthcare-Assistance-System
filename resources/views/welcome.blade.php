<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Healthcare Assistance System - AI-Powered Healthcare & Hospital Management Platform</title>
    <meta name="description" content="Understand your symptoms, discover the right medical specialty, manage appointments, and access connected clinical healthcare through Smart Healthcare Assistance System.">
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- MediNexa Design System -->
    <link rel="stylesheet" href="{{ asset('css/medinexa-theme.css') }}">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.6;
        }
        
        /* Sticky Navigation */
        .mn-public-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
            transition: all 0.3s ease;
        }
        .mn-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .mn-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mn-nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
        }
        .mn-nav-links a {
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 600;
            color: #334155;
            transition: color 0.2s ease;
        }
        .mn-nav-links a:hover {
            color: #0d9488;
        }
        .mn-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Hero Section */
        .mn-hero-section {
            padding: 80px 0 60px;
            background: linear-gradient(180deg, #f0fdfa 0%, #f8fafc 100%);
            border-bottom: 1px solid #e2e8f0;
        }
        .mn-hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 48px;
            align-items: center;
        }
        .mn-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ccfbf1;
            color: #0f766e;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .mn-hero-title {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.18;
            color: #0f172a;
            margin-bottom: 18px;
            letter-spacing: -1px;
        }
        .mn-hero-title span {
            background: linear-gradient(135deg, #0284c7, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .mn-hero-lead {
            font-size: 18px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .mn-hero-cta-group {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .mn-hero-metrics {
            display: flex;
            align-items: center;
            gap: 32px;
            padding-top: 24px;
            border-top: 1px solid #cbd5e1;
        }
        .mn-metric-item h4 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .mn-metric-item p {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Interface Preview Mockup */
        .mn-preview-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 35px -5px rgba(15, 23, 42, 0.1);
            overflow: hidden;
            position: relative;
        }
        .mn-preview-topbar {
            background: #0f172a;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mn-preview-dots {
            display: flex;
            gap: 6px;
        }
        .mn-preview-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #475569;
        }
        .mn-preview-body {
            padding: 22px;
            background: #f8fafc;
        }
        .mn-widget-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        /* Section Headings */
        .mn-section {
            padding: 72px 0;
        }
        .mn-section-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 50px;
        }
        .mn-section-tag {
            color: #0d9488;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }
        .mn-section-title {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }
        .mn-section-desc {
            font-size: 16px;
            color: #64748b;
            line-height: 1.6;
        }

        /* Feature Cards */
        .mn-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }
        .mn-feature-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 26px;
            transition: all 0.25s ease;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }
        .mn-feature-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -4px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }
        .mn-feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 18px;
        }

        /* How It Works Journey */
        .mn-steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            position: relative;
        }
        .mn-step-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px;
            position: relative;
        }
        .mn-step-num {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #0d9488;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 14px;
        }

        /* Role Tabs */
        .mn-role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }
        .mn-role-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 26px;
            border-top: 4px solid #0284c7;
        }
        .mn-role-card.doctor { border-top-color: #0d9488; }
        .mn-role-card.reception { border-top-color: #f59e0b; }
        .mn-role-card.admin { border-top-color: #6366f1; }

        /* FAQ Accordion */
        .mn-faq-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 18px 22px;
        }
        .mn-faq-question {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mn-faq-answer {
            font-size: 14.5px;
            color: #475569;
            line-height: 1.55;
        }

        /* Footer */
        .mn-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 60px 0 30px;
            border-top: 1px solid #1e293b;
        }
        .mn-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .mn-footer a:hover {
            color: #2dd4bf;
        }
        .mn-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        @media (max-width: 991px) {
            .mn-hero-grid { grid-template-columns: 1fr; }
            .mn-nav-links { display: none; }
            .mn-footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .mn-hero-title { font-size: 34px; }
            .mn-footer-grid { grid-template-columns: 1fr; }
            .mn-hero-metrics { flex-direction: column; align-items: flex-start; gap: 16px; }
        }
    </style>
</head>
<body class="medinexa-app">

    <!-- Sticky Navigation Bar -->
    <header class="mn-public-nav">
        <div class="mn-container">
            <div class="mn-nav-inner">
                <a href="{{ url('/') }}" class="mn-brand-wrapper">
                    <div class="mn-brand-logo-icon">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <div class="mn-brand-text">
                        <div class="mn-brand-title">Smart <span style="color:#0d9488;">Healthcare</span></div>
                        <div class="mn-brand-subtitle">Assistance System</div>
                    </div>
                </a>

                <ul class="mn-nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#assistant">Healthcare Assistant</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#specialties">Specialties</a></li>
                    <li><a href="#roles">Roles</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>

                <div class="mn-nav-actions">
                    <a href="{{ url('/login') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                        <i class="fa-solid fa-right-to-bracket"></i> Sign In
                    </a>
                    <a href="#assistant" class="mn-btn mn-btn-teal mn-btn-sm">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Assistant
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="mn-hero-section" id="home">
        <div class="mn-container">
            <div class="mn-hero-grid">
                <div>
                    <div class="mn-pill-badge">
                        <i class="fa-solid fa-shield-halved"></i> AI-Powered Healthcare & Hospital Management
                    </div>
                    <h1 class="mn-hero-title">Smarter Healthcare <span>Starts Here.</span></h1>
                    <p class="mn-hero-lead">
                        Understand your symptoms, discover the right medical specialty, manage appointments, and access your healthcare information through one connected platform.
                    </p>

                    <div class="mn-hero-cta-group">
                        <a href="{{ url('/login') }}" class="mn-btn mn-btn-primary" style="padding:13px 26px;font-size:15px;">
                            <i class="fa-solid fa-arrow-right"></i> Get Started
                        </a>
                        <a href="#assistant" class="mn-btn mn-btn-teal" style="padding:13px 26px;font-size:15px;">
                            <i class="fa-solid fa-sparkles"></i> Explore Healthcare Assistant
                        </a>
                    </div>

                    <!-- Live Metrics from Database -->
                    <div class="mn-hero-metrics">
                        <div class="mn-metric-item">
                            <h4>{{ $doctorsCount > 0 ? $doctorsCount : 'Multi' }}</h4>
                            <p>Consultant Doctors</p>
                        </div>
                        <div class="mn-metric-item">
                            <h4>{{ count($departments) > 0 ? count($departments) : '4+' }}</h4>
                            <p>Active Specialties</p>
                        </div>
                        <div class="mn-metric-item">
                            <h4>{{ $bedsCount > 0 ? $bedsCount : '24/7' }}</h4>
                            <p>Inpatient Bed Capacity</p>
                        </div>
                        <div class="mn-metric-item">
                            <h4>100%</h4>
                            <p>Verified Records</p>
                        </div>
                    </div>
                </div>

                <!-- Live Interface Preview Card -->
                <div>
                    <div class="mn-preview-card">
                        <div class="mn-preview-topbar">
                            <div class="mn-preview-dots">
                                <div class="mn-preview-dot" style="background:#ef4444;"></div>
                                <div class="mn-preview-dot" style="background:#f59e0b;"></div>
                                <div class="mn-preview-dot" style="background:#10b981;"></div>
                            </div>
                            <span style="color:#94a3b8;font-size:11px;font-weight:600;">Smart Healthcare Clinical Overview</span>
                        </div>
                        <div class="mn-preview-body">
                            <!-- Mini widget 1 -->
                            <div class="mn-widget-box" style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Next Patient Consultation</div>
                                    <div style="font-size:15px;font-weight:700;color:#0f172a;margin-top:2px;">Routine Cardiology Checkup</div>
                                    <div style="font-size:12px;color:#0d9488;"><i class="fa-regular fa-clock"></i> 10:30 AM · Slot Confirmed</div>
                                </div>
                                <span class="mn-badge mn-badge-teal">Active Queue</span>
                            </div>

                            <!-- Mini widget 2 -->
                            <div class="mn-widget-box" style="border-left:4px solid #0284c7;">
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="font-size:12px;font-weight:700;color:#0284c7;">AI Symptom Triage</span>
                                    <span class="mn-badge mn-badge-sky">Preliminary</span>
                                </div>
                                <p style="font-size:12.5px;color:#334155;margin:6px 0 0;">
                                    <strong>Suspected Specialty:</strong> Paediatrics & General Medicine. Clinical workup advised.
                                </p>
                            </div>

                            <!-- Mini widget 3 -->
                            <div class="mn-widget-box" style="margin-bottom:0;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:12px;font-weight:600;color:#475569;">Bed Occupancy</span>
                                    <span style="font-size:12px;font-weight:700;color:#0f172a;">Live Derived</span>
                                </div>
                                <div style="height:6px;background:#e2e8f0;border-radius:3px;margin:8px 0;overflow:hidden;">
                                    <div style="width:65%;height:100%;background:#0d9488;"></div>
                                </div>
                                <div style="font-size:11px;color:#64748b;">Point-in-time relational records without status drift.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Healthcare Assistant Interactive Section -->
    <section class="mn-section" id="assistant" style="background:#ffffff;border-bottom:1px solid #e2e8f0;">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Interactive Clinical Assistant</span>
                <h2 class="mn-section-title">Describe Your Symptoms for Preliminary Guidance</h2>
                <p class="mn-section-desc">
                    Get an instant assessment of urgency, discover the recommended hospital department, and see available consultant doctors.
                </p>
            </div>

            <div style="max-width:840px;margin:0 auto;">
                <div class="mn-assistant-container">
                    <div class="mn-assistant-header">
                        <div>
                            <h3><i class="fa-solid fa-sparkles" style="color:#2dd4bf;"></i> Smart Healthcare Assistant</h3>
                            <p>Rule-guided clinical triage & specialty recommendation</p>
                        </div>
                        <span class="mn-badge mn-badge-teal" style="background:rgba(255,255,255,0.15);color:#ffffff;">
                            AI Guidance Engine
                        </span>
                    </div>

                    <div class="mn-assistant-body">
                        <!-- Emergency Prompt Warning -->
                        <div class="mn-emergency-alert" id="emergencyBanner" style="display:none;">
                            <i class="fa-solid fa-triangle-exclamation mn-emergency-icon"></i>
                            <div class="mn-emergency-content">
                                <h4 id="emergencyTitle">EMERGENCY WARNING DETECTED</h4>
                                <p id="emergencyText"></p>
                            </div>
                        </div>

                        <form id="assistantForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="mn-assistant-input-group">
                                <label for="userSymptoms">
                                    <i class="fa-solid fa-notes-medical" style="color:#0d9488;"></i> What symptoms are you experiencing?
                                </label>
                                <textarea 
                                    id="userSymptoms" 
                                    class="mn-assistant-textarea" 
                                    placeholder="e.g., I have been feeling mild chest discomfort and fatigue after climbing stairs, or severe joint ache in my right knee..."
                                    required></textarea>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                                <div class="mn-assistant-input-group" style="margin-bottom:0;">
                                    <label for="symptomDuration">Approximate Duration</label>
                                    <select id="symptomDuration" class="mn-assistant-textarea" style="min-height:44px;padding:8px 12px;">
                                        <option value="Started today">Started today</option>
                                        <option value="2-3 days">2-3 days</option>
                                        <option value="Past week">Past week</option>
                                        <option value="Over a month (chronic)">Over a month (chronic)</option>
                                    </select>
                                </div>
                                <div style="display:flex;align-items:flex-end;">
                                    <button type="submit" id="btnAnalyze" class="mn-btn mn-btn-teal" style="width:100%;height:44px;">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Dynamic Triage Result Container -->
                        <div id="triageResult" class="mn-triage-result">
                            <div class="mn-triage-badge-row">
                                <span id="resUrgencyBadge" class="mn-badge mn-badge-teal">Urgency: Routine</span>
                                <span id="resSpecialtyBadge" class="mn-badge mn-badge-sky">Specialty: General Medicine</span>
                                <span id="resDurationBadge" class="mn-badge mn-badge-amber">Duration: 2-3 days</span>
                            </div>

                            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:16px;">
                                <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Extracted Clinical Context</h4>
                                <div id="resSymptomsList" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;"></div>
                                <p id="resRationale" style="font-size:13.5px;color:#475569;margin:0;"></p>
                            </div>

                            <!-- Next Steps Recommendations -->
                            <div style="margin-bottom:16px;">
                                <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Recommended Next Actions</h4>
                                <ul id="resAdviceList" style="padding-left:20px;font-size:13.5px;color:#334155;"></ul>
                            </div>

                            <!-- Matched Doctors from Hospital Database -->
                            <div id="matchedDoctorsWrap">
                                <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;">
                                    Consultant Doctors Available for This Specialty
                                </h4>
                                <div id="resDoctorsList" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:12px;"></div>
                            </div>

                            <div class="mn-disclaimer-note">
                                <i class="fa-solid fa-circle-info" style="color:#0d9488;"></i>
                                <span id="resDisclaimer">Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Platform Capabilities -->
    <section class="mn-section" id="services">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Comprehensive Healthcare Modules</span>
                <h2 class="mn-section-title">Built for Patients, Clinicians & Operations</h2>
                <p class="mn-section-desc">
                    Every operational workflow in Smart Healthcare Assistance System is backed by transactional integrity, strict row-locking, and synchronized medical records.
                </p>
            </div>

            <div class="mn-features-grid">
                <!-- 1. Symptom Analysis -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#ccfbf1;color:#0f766e;">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">AI-Assisted Triage</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Natural language symptom evaluation with red-flag emergency detection and probabilistic specialty recommendations.
                    </p>
                </div>

                <!-- 2. Appointment Booking -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#e0f2fe;color:#0369a1;">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Conflict-Free Appointments</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Pessimistic database locking guards every booking slot, preventing doctor double-booking while supporting back-to-back clinic visits.
                    </p>
                </div>

                <!-- 3. Electronic Medical Records -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#fef3c7;color:#b45309;">
                        <i class="fa-solid fa-file-medical"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Point-in-Time EMR</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Allergies, chronic conditions, and past surgical histories stamped with clinical notes that never drift or distort over time.
                    </p>
                </div>

                <!-- 4. Inpatient Bed Management -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#d1fae5;color:#047857;">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Live Ward & Bed Board</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Bed occupancy is strictly derived from active admissions. Enforces 1 patient per bed and 1 bed per patient.
                    </p>
                </div>

                <!-- 5. Laboratory & Diagnostics -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#ede9fe;color:#6d28d9;">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Partial Lab Reporting</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Multi-test requests with point-in-time reference ranges, allowing blood counts to be posted while microbiological cultures grow.
                    </p>
                </div>

                <!-- 6. Pharmacy & Dispensing -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#fee2e2;color:#b91c1c;">
                        <i class="fa-solid fa-pills"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Atomic Pharmacy Dispensing</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Dispensing sales automatically decrement catalog shelf stock in the same transaction, prohibiting negative inventory.
                    </p>
                </div>

                <!-- 7. Billing & Invoicing -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#e0e7ff;color:#4338ca;">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Installment Invoicing</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Itemized charges with frozen snapshot pricing and flexible installment payments, tracking settled vs outstanding debt balances.
                    </p>
                </div>

                <!-- 8. Unified Communications -->
                <div class="mn-feature-box">
                    <div class="mn-feature-icon" style="background:#fce7f3;color:#be185d;">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">Automated Reminders</h3>
                    <p style="font-size:14px;color:#64748b;line-height:1.5;">
                        Integrated SMS and voice notification triggers via ICTCore with scheduled automated reminders for consultations and dues.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Visual Journey -->
    <section class="mn-section" id="how-it-works" style="background:#ffffff;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Clinical Workflow</span>
                <h2 class="mn-section-title">How Smart Healthcare Assistance System Works</h2>
                <p class="mn-section-desc">
                    A streamlined journey connecting patients from their first symptom query to in-clinic care and medical record retention.
                </p>
            </div>

            <div class="mn-steps-grid">
                <div class="mn-step-card">
                    <div class="mn-step-num">1</div>
                    <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Describe Symptoms</h4>
                    <p style="font-size:13px;color:#64748b;">Enter symptoms naturally into the assistant or register as a walk-in at reception.</p>
                </div>

                <div class="mn-step-card">
                    <div class="mn-step-num">2</div>
                    <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Triage Guidance</h4>
                    <p style="font-size:13px;color:#64748b;">Receive preliminary urgency rating, clinical rationale, and recommended department.</p>
                </div>

                <div class="mn-step-card">
                    <div class="mn-step-num">3</div>
                    <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Select Doctor & Slot</h4>
                    <p style="font-size:13px;color:#64748b;">Pick an on-duty specialist doctor and reserve a guaranteed, conflict-free appointment.</p>
                </div>

                <div class="mn-step-card">
                    <div class="mn-step-num">4</div>
                    <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Consultation & Care</h4>
                    <p style="font-size:13px;color:#64748b;">Doctor accesses patient medical history, issues prescriptions, and orders diagnostics.</p>
                </div>

                <div class="mn-step-card">
                    <div class="mn-step-num">5</div>
                    <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Pharmacy & Billing</h4>
                    <p style="font-size:13px;color:#64748b;">Pharmacy dispenses verified stock while billing tracks itemized settlement accurately.</p>
                </div>
            </div>

            <div style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:10px;padding:16px 20px;margin-top:36px;text-align:center;">
                <p style="font-size:13.5px;color:#0f766e;margin:0;font-weight:600;">
                    <i class="fa-solid fa-circle-check"></i> Important Medical Notice: Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis, clinical evaluation, or emergency treatment.
                </p>
            </div>
        </div>
    </section>

    <!-- Specialties from Database -->
    <section class="mn-section" id="specialties">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Hospital Specialties</span>
                <h2 class="mn-section-title">Clinical Departments Available</h2>
                <p class="mn-section-desc">
                    Our platform organizes doctors and clinical consultations across specialized medical departments.
                </p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:20px;">
                @forelse($departments as $dept)
                    <div class="mn-card" style="margin-bottom:0;">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                            <div style="width:38px;height:38px;border-radius:8px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:16px;">
                                <i class="fa-solid fa-stethoscope"></i>
                            </div>
                            <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0;">{{ $dept->name }}</h3>
                        </div>
                        <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;min-height:42px;">
                            {{ $dept->description ?: 'Comprehensive clinical diagnostics, consultation, and patient care.' }}
                        </p>
                        <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="width:100%;">
                            <i class="fa-solid fa-calendar-plus"></i> View Available Slots
                        </a>
                    </div>
                @empty
                    <div class="mn-card" style="grid-column:1/-1;text-align:center;">
                        <p style="color:#64748b;font-style:italic;">General Medicine, Cardiology, Orthopaedics, and Paediatrics are active.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Role-Based Experiences -->
    <section class="mn-section" id="roles" style="background:#ffffff;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Role-Tailored Dashboards</span>
                <h2 class="mn-section-title">One Platform, Dedicated Experiences</h2>
                <p class="mn-section-desc">
                    Every member of the healthcare ecosystem operates with purpose-built dashboards and permissions.
                </p>
            </div>

            <div class="mn-role-grid">
                <!-- Patient -->
                <div class="mn-role-card">
                    <span class="mn-badge mn-badge-sky" style="margin-bottom:12px;">Patient Portal</span>
                    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px;">Patients</h3>
                    <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;">
                        Access medical history, review doctor prescriptions, track lab results, book appointments, and interact with the AI symptom assistant.
                    </p>
                    <ul style="padding-left:18px;font-size:13px;color:#334155;line-height:1.7;">
                        <li>Self-service appointment booking</li>
                        <li>Digital prescription records</li>
                        <li>Laboratory report access</li>
                    </ul>
                </div>

                <!-- Doctor -->
                <div class="mn-role-card doctor">
                    <span class="mn-badge mn-badge-teal" style="margin-bottom:12px;">Clinician Portal</span>
                    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px;">Doctors</h3>
                    <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;">
                        Manage daily appointment queues, review patient medical history and allergies, write prescriptions, and order diagnostics.
                    </p>
                    <ul style="padding-left:18px;font-size:13px;color:#334155;line-height:1.7;">
                        <li>Real-time patient queue view</li>
                        <li>Pre-consultation symptom summaries</li>
                        <li>Direct prescription generation</li>
                    </ul>
                </div>

                <!-- Receptionist -->
                <div class="mn-role-card reception">
                    <span class="mn-badge mn-badge-amber" style="margin-bottom:12px;">Front-Desk Portal</span>
                    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px;">Receptionists</h3>
                    <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;">
                        Handle walk-in patient registrations with minimal required fields, schedule doctor visits, and manage clinic arrival queues.
                    </p>
                    <ul style="padding-left:18px;font-size:13px;color:#334155;line-height:1.7;">
                        <li>Rapid walk-in registration</li>
                        <li>Conflict-free calendar bookings</li>
                        <li>Queue arrival status check-in</li>
                    </ul>
                </div>

                <!-- Administrator -->
                <div class="mn-role-card admin">
                    <span class="mn-badge mn-badge-rose" style="margin-bottom:12px;background:#ede9fe;color:#5b21b6;">Operations Portal</span>
                    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px;">Administrators</h3>
                    <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;">
                        Complete hospital oversight with 90 granular permissions, bed occupancy metrics, pharmacy stock monitoring, and financial ledgers.
                    </p>
                    <ul style="padding-left:18px;font-size:13px;color:#334155;line-height:1.7;">
                        <li>Live bed census & ALOS reports</li>
                        <li>Granular RBAC role matrices</li>
                        <li>Financial balance & aged debtors</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Frequently Asked Questions -->
    <section class="mn-section" id="faq">
        <div class="mn-container">
            <div class="mn-section-header">
                <span class="mn-section-tag">Frequently Asked Questions</span>
                <h2 class="mn-section-title">Transparent & Honest Answers</h2>
                <p class="mn-section-desc">
                    Learn how Smart Healthcare Assistance System handles data safety, appointment collisions, and clinical workflows.
                </p>
            </div>

            <div style="max-width:800px;margin:0 auto;">
                <div class="mn-faq-item">
                    <div class="mn-faq-question">
                        <i class="fa-solid fa-circle-question" style="color:#0d9488;"></i>
                        Does Smart Healthcare Assistance System replace a medical doctor's diagnosis?
                    </div>
                    <div class="mn-faq-answer">
                        No. The Smart Healthcare Assistance System provides preliminary triage guidance only. It helps patients understand their urgency level and find the appropriate medical specialty. All clinical decisions and final diagnoses must be performed by a qualified physician.
                    </div>
                </div>

                <div class="mn-faq-item">
                    <div class="mn-faq-question">
                        <i class="fa-solid fa-circle-question" style="color:#0d9488;"></i>
                        What happens if two users try to book the same doctor slot at the same time?
                    </div>
                    <div class="mn-faq-answer">
                        Smart Healthcare Assistance System implements pessimistic database row locks during appointment writes. One booking transaction succeeds while the conflicting transaction is rejected cleanly with an informative message.
                    </div>
                </div>

                <div class="mn-faq-item">
                    <div class="mn-faq-question">
                        <i class="fa-solid fa-circle-question" style="color:#0d9488;"></i>
                        Can an emergency patient be registered without a date of birth?
                    </div>
                    <div class="mn-faq-answer">
                        Yes. Only the patient's name is strictly required for intake so that emergency care is never delayed by paperwork. Additional demographic fields can be recorded later.
                    </div>
                </div>

                <div class="mn-faq-item">
                    <div class="mn-faq-question">
                        <i class="fa-solid fa-circle-question" style="color:#0d9488;"></i>
                        How does Smart Healthcare Assistance System ensure pharmacy stock integrity?
                    </div>
                    <div class="mn-faq-answer">
                        Stock is decremented in the exact same atomic transaction as the dispensing sale. The system rejects sales that would cause stock to go negative, and reversing a voided sale safely returns stock to the catalog.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section style="background:linear-gradient(135deg, #0f172a, #1e293b);padding:70px 0;color:#ffffff;text-align:center;">
        <div class="mn-container">
            <h2 style="font-size:36px;font-weight:800;margin-bottom:14px;letter-spacing:-0.5px;">Ready to Experience Connected Healthcare?</h2>
            <p style="font-size:17px;color:#cbd5e1;max-width:650px;margin:0 auto 30px;">
                Sign in to your dashboard to manage appointments, clinical records, pharmacy inventory, and hospital administration.
            </p>
            <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
                <a href="{{ url('/login') }}" class="mn-btn mn-btn-teal" style="padding:14px 30px;font-size:16px;">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In to Smart Healthcare Assistance System
                </a>
                <a href="#assistant" class="mn-btn mn-btn-outline" style="border-color:#475569;color:#ffffff !important;padding:14px 30px;font-size:16px;">
                    <i class="fa-solid fa-heart-pulse"></i> Try Symptom Triage
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mn-footer">
        <div class="mn-container">
            <div class="mn-footer-grid">
                <div>
                    <div class="mn-brand-wrapper" style="margin-bottom:16px;">
                        <div class="mn-brand-logo-icon">
                            <i class="fa-solid fa-hospital-user"></i>
                        </div>
                        <div class="mn-brand-text">
                            <div class="mn-brand-title" style="color:#ffffff;">Smart <span style="color:#2dd4bf;">Healthcare</span></div>
                            <div class="mn-brand-subtitle" style="color:#94a3b8;">Assistance System</div>
                        </div>
                    </div>
                    <p style="font-size:13.5px;line-height:1.6;color:#94a3b8;max-width:320px;">
                        An AI-Powered Healthcare and Hospital Management Platform integrating clinical care, diagnostics, and patient communication.
                    </p>
                </div>

                <div>
                    <h4 style="color:#ffffff;font-size:14px;font-weight:700;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.6px;">Platform</h4>
                    <ul style="list-style:none;font-size:13.5px;line-height:2;">
                        <li><a href="#assistant">Healthcare Assistant</a></li>
                        <li><a href="#services">Clinical Modules</a></li>
                        <li><a href="#specialties">Specialties</a></li>
                        <li><a href="{{ url('/login') }}">Hospital Sign In</a></li>
                    </ul>
                </div>

                <div>
                    <h4 style="color:#ffffff;font-size:14px;font-weight:700;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.6px;">Roles</h4>
                    <ul style="list-style:none;font-size:13.5px;line-height:2;">
                        <li><a href="{{ url('/login') }}">Patient Portal</a></li>
                        <li><a href="{{ url('/login') }}">Doctor Portal</a></li>
                        <li><a href="{{ url('/login') }}">Receptionist Portal</a></li>
                        <li><a href="{{ url('/login') }}">Administrator</a></li>
                    </ul>
                </div>

                <div>
                    <h4 style="color:#ffffff;font-size:14px;font-weight:700;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.6px;">Safety & Privacy</h4>
                    <ul style="list-style:none;font-size:13.5px;line-height:2;">
                        <li><a href="#faq">AI Safety Guidelines</a></li>
                        <li><a href="#faq">Privacy & Records</a></li>
                        <li><a href="#faq">Emergency Protocols</a></li>
                    </ul>
                </div>
            </div>

            <div style="padding-top:24px;border-top:1px solid #1e293b;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;font-size:12.5px;">
                <p style="margin:0;">&copy; {{ date('Y') }} Smart Healthcare Assistance System. All rights reserved. Intelligent care. Connected healthcare.</p>
                <p style="margin:0;color:#64748b;">Preliminary AI guidance only. In an emergency, dial 911 / 112 / 108 immediately.</p>
            </div>
        </div>
    </footer>

    <!-- Interactive Assistant AJAX Script -->
    <script>
        document.getElementById('assistantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const symptoms = document.getElementById('userSymptoms').value.trim();
            const duration = document.getElementById('symptomDuration').value;
            const btn = document.getElementById('btnAnalyze');
            const resultBox = document.getElementById('triageResult');
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
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms';

                if (data.success) {
                    resultBox.style.display = 'block';

                    // Emergency Banner
                    if (data.is_emergency) {
                        emergencyBanner.style.display = 'flex';
                        document.getElementById('emergencyTitle').innerText = 'CRITICAL EMERGENCY WARNING';
                        document.getElementById('emergencyText').innerText = data.emergency_warning || data.emergency_reason;
                    } else {
                        emergencyBanner.style.display = 'none';
                    }

                    // Urgency Badge
                    const urgencyBadge = document.getElementById('resUrgencyBadge');
                    urgencyBadge.innerText = 'Urgency: ' + data.urgency_level;
                    urgencyBadge.className = 'mn-badge ' + (
                        data.urgency_level === 'Emergency' ? 'mn-badge-rose' :
                        data.urgency_level === 'Urgent' ? 'mn-badge-amber' : 'mn-badge-emerald'
                    );

                    // Specialty & Duration
                    document.getElementById('resSpecialtyBadge').innerText = 'Specialty: ' + data.recommended_specialty;
                    document.getElementById('resDurationBadge').innerText = 'Duration: ' + data.duration;

                    // Symptoms Pill List
                    const symptomsList = document.getElementById('resSymptomsList');
                    symptomsList.innerHTML = '';
                    data.extracted_symptoms.forEach(s => {
                        const pill = document.createElement('span');
                        pill.className = 'mn-badge mn-badge-teal';
                        pill.innerText = s;
                        symptomsList.appendChild(pill);
                    });

                    // Clinical Rationale
                    document.getElementById('resRationale').innerText = data.clinical_rationale;

                    // Actionable Advice
                    const adviceList = document.getElementById('resAdviceList');
                    adviceList.innerHTML = '';
                    data.actionable_advice.forEach(a => {
                        const li = document.createElement('li');
                        li.innerText = a;
                        adviceList.appendChild(li);
                    });

                    // Doctors List
                    const docList = document.getElementById('resDoctorsList');
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
                    } else {
                        docList.innerHTML = '<p style="color:#64748b;font-size:13px;grid-column:1/-1;">General consultant doctors on duty. Book through front desk.</p>';
                    }

                    // Disclaimer
                    document.getElementById('resDisclaimer').innerText = data.disclaimer;

                    // Smooth scroll to results
                    resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms';
                alert('Analysis request could not be processed. Please check your network and try again.');
            });
        });
    </script>
</body>
</html>
