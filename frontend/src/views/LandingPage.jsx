import React, { useState, useEffect } from 'react';
import SymptomTriageWidget from '../components/SymptomTriageWidget';
import api from '../services/api';

export default function LandingPage({ setView, onSelectDoctorForBooking }) {
  const [departments, setDepartments] = useState([]);
  const [activeFaq, setActiveFaq] = useState(null);

  useEffect(() => {
    api.getDepartments()
      .then(data => setDepartments(data))
      .catch(err => console.warn('Could not load departments:', err.message));
  }, []);

  const toggleFaq = (index) => {
    setActiveFaq(activeFaq === index ? null : index);
  };

  return (
    <>
      {/* Hero Section */}
      <section className="mn-hero-section" id="home">
        <div className="mn-container">
          <div className="mn-hero-grid">
            <div>
              <div className="mn-pill-badge">
                <i className="fa-solid fa-shield-halved"></i> AI-Powered Healthcare & Hospital Management
              </div>
              <h1 className="mn-hero-title">
                Smarter Healthcare <span>Starts Here.</span>
              </h1>
              <p className="mn-hero-lead">
                Understand your symptoms, discover the right medical specialty, manage appointments, and access your healthcare information through one connected platform.
              </p>

              <div className="mn-hero-cta-group">
                <button 
                  onClick={() => setView('login')} 
                  className="mn-btn mn-btn-primary" 
                  style={{ padding: '13px 26px', fontSize: '15px' }}
                >
                  <i className="fa-solid fa-arrow-right"></i> Get Started
                </button>
                <a 
                  href="#assistant" 
                  className="mn-btn mn-btn-outline" 
                  style={{ padding: '13px 24px', fontSize: '15px' }}
                >
                  <i className="fa-solid fa-heart-pulse"></i> Check Symptoms
                </a>
              </div>

              <div className="mn-trust-badges">
                <div className="mn-trust-item">
                  <i className="fa-solid fa-lock"></i>
                  <span>Role-Based Access Control</span>
                </div>
                <div className="mn-trust-item">
                  <i className="fa-solid fa-bolt-lightning"></i>
                  <span>Deterministic Safety Guards</span>
                </div>
                <div className="mn-trust-item">
                  <i className="fa-solid fa-database"></i>
                  <span>Transactional EMR</span>
                </div>
              </div>
            </div>

            {/* Visual Hero Dashboard Card Preview */}
            <div>
              <div className="mn-hero-preview-card">
                <div className="mn-preview-topbar">
                  <div className="mn-preview-dots">
                    <div className="mn-preview-dot" style={{ background: '#ef4444' }}></div>
                    <div className="mn-preview-dot" style={{ background: '#f59e0b' }}></div>
                    <div className="mn-preview-dot" style={{ background: '#10b981' }}></div>
                  </div>
                  <span style={{ color: '#94a3b8', fontSize: '11px', fontWeight: 600 }}>Smart Healthcare Clinical Overview</span>
                </div>
                <div className="mn-preview-body">
                  <div className="mn-widget-box" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div>
                      <div style={{ fontSize: '11px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Next Patient Consultation</div>
                      <div style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginTop: '2px' }}>Routine Cardiology Checkup</div>
                      <div style={{ fontSize: '12px', color: '#0d9488' }}><i className="fa-regular fa-clock"></i> 10:30 AM · Slot Confirmed</div>
                    </div>
                    <span className="mn-badge mn-badge-teal">Confirmed</span>
                  </div>

                  <div className="mn-widget-box" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div>
                      <div style={{ fontSize: '11px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>AI Triage Triage Confidence</div>
                      <div style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginTop: '2px' }}>Gastroenterology Match</div>
                      <div style={{ fontSize: '12px', color: '#0284c7' }}><i className="fa-solid fa-microchip"></i> XGBoost + TF-IDF: 94% Confidence</div>
                    </div>
                    <span className="mn-badge mn-badge-primary">AI Verified</span>
                  </div>

                  <div className="mn-widget-box" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div>
                      <div style={{ fontSize: '11px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Hospital Bed Occupancy</div>
                      <div style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginTop: '2px' }}>84 / 120 Beds Active</div>
                      <div style={{ fontSize: '12px', color: '#10b981' }}><i className="fa-solid fa-bed"></i> 70.0% Capacity Normal</div>
                    </div>
                    <span className="mn-badge mn-badge-teal">Operational</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Embedded Interactive Symptom Assistant */}
      <section className="mn-section" style={{ background: '#f8fafc', paddingTop: '40px' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">AI Healthcare Assistant</span>
            <h2 className="mn-section-title">Check Your Symptoms in Seconds</h2>
            <p className="mn-section-desc">
              Get an instant assessment of urgency, discover the recommended hospital department, and see available consultant doctors.
            </p>
          </div>

          <div style={{ maxWidth: '840px', margin: '0 auto' }}>
            <SymptomTriageWidget onSelectDoctor={onSelectDoctorForBooking} />
          </div>
        </div>
      </section>

      {/* Core Platform Capabilities */}
      <section className="mn-section" id="services" style={{ background: '#ffffff', borderTop: '1px solid #e2e8f0' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">Comprehensive Healthcare Modules</span>
            <h2 className="mn-section-title">Built for Patients, Clinicians & Operations</h2>
            <p className="mn-section-desc">
              Every operational workflow in Smart Healthcare Assistance System is backed by transactional integrity, strict row-locking, and synchronized medical records.
            </p>
          </div>

          <div className="mn-features-grid">
            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#ccfbf1', color: '#0f766e' }}>
                <i className="fa-solid fa-stethoscope"></i>
              </div>
              <h3 className="mn-feature-title">AI Symptom Analysis</h3>
              <p className="mn-feature-desc">
                State-of-the-art NLP extracts clinical keywords from natural language and maps urgency to specialized departments with complete red-flag safety rules.
              </p>
            </div>

            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#e0f2fe', color: '#0369a1' }}>
                <i className="fa-regular fa-calendar-check"></i>
              </div>
              <h3 className="mn-feature-title">Conflict-Free Scheduling</h3>
              <p className="mn-feature-desc">
                Pessimistic row-level database locking ensures guaranteed slot integrity, eliminating double bookings across multiple concurrent doctor schedules.
              </p>
            </div>

            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#fef3c7', color: '#b45309' }}>
                <i className="fa-solid fa-file-waveform"></i>
              </div>
              <h3 className="mn-feature-title">Digital Health Records (EMR)</h3>
              <p className="mn-feature-desc">
                Complete longitudinal medical history capturing diagnostic consult notes, vitals telemetry, prescription orders, and lab investigations.
              </p>
            </div>

            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#f3e8ff', color: '#6b21a8' }}>
                <i className="fa-solid fa-pills"></i>
              </div>
              <h3 className="mn-feature-title">Pharmacy & Inventory</h3>
              <p className="mn-feature-desc">
                Atomic transactions decrement stock upon dispensing, with strict validation preventing negative balances and full reconciliation on returns.
              </p>
            </div>

            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#fee2e2', color: '#b91c1c' }}>
                <i className="fa-solid fa-vial-circle-check"></i>
              </div>
              <h3 className="mn-feature-title">Diagnostic Laboratory</h3>
              <p className="mn-feature-desc">
                Specimen logging and partial result reporting allow pathologists to publish findings as tests complete, expediting doctor review.
              </p>
            </div>

            <div className="mn-feature-box">
              <div className="mn-feature-icon" style={{ background: '#ecfdf5', color: '#047857' }}>
                <i className="fa-solid fa-shield-halved"></i>
              </div>
              <h3 className="mn-feature-title">90-Permission RBAC</h3>
              <p className="mn-feature-desc">
                Role-based access control enforces strict boundary isolation between Patients, Doctors, Front-Desk Receptionists, and Administrators.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* How It Works Visual Journey */}
      <section className="mn-section" id="how-it-works" style={{ background: '#f8fafc', borderTop: '1px solid #e2e8f0', borderBottom: '1px solid #e2e8f0' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">Clinical Workflow</span>
            <h2 className="mn-section-title">How Smart Healthcare Assistance System Works</h2>
            <p className="mn-section-desc">
              A streamlined journey connecting patients from their first symptom query to in-clinic care and medical record retention.
            </p>
          </div>

          <div className="mn-steps-grid">
            <div className="mn-step-card">
              <div className="mn-step-num">1</div>
              <h4 style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginBottom: '6px' }}>Describe Symptoms</h4>
              <p style={{ fontSize: '13px', color: '#64748b' }}>Enter symptoms naturally into the assistant or register as a walk-in at reception.</p>
            </div>

            <div className="mn-step-card">
              <div className="mn-step-num">2</div>
              <h4 style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginBottom: '6px' }}>Triage Guidance</h4>
              <p style={{ fontSize: '13px', color: '#64748b' }}>Receive preliminary urgency rating, clinical rationale, and recommended department.</p>
            </div>

            <div className="mn-step-card">
              <div className="mn-step-num">3</div>
              <h4 style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginBottom: '6px' }}>Select Doctor & Slot</h4>
              <p style={{ fontSize: '13px', color: '#64748b' }}>Pick an on-duty specialist doctor and reserve a guaranteed, conflict-free appointment.</p>
            </div>

            <div className="mn-step-card">
              <div className="mn-step-num">4</div>
              <h4 style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginBottom: '6px' }}>Consultation & Care</h4>
              <p style={{ fontSize: '13px', color: '#64748b' }}>Doctor accesses patient medical history, issues prescriptions, and orders diagnostics.</p>
            </div>

            <div className="mn-step-card">
              <div className="mn-step-num">5</div>
              <h4 style={{ fontSize: '15px', fontWeight: 700, color: '#0f172a', marginBottom: '6px' }}>Pharmacy & Billing</h4>
              <p style={{ fontSize: '13px', color: '#64748b' }}>Pharmacy dispenses verified stock while billing tracks itemized settlement accurately.</p>
            </div>
          </div>

          <div style={{ background: '#f0fdfa', border: '1px solid #ccfbf1', borderRadius: '10px', padding: '16px 20px', marginTop: '36px', textAlign: 'center' }}>
            <p style={{ fontSize: '13.5px', color: '#0f766e', margin: 0, fontWeight: 600 }}>
              <i className="fa-solid fa-circle-check"></i> Important Medical Notice: Smart Healthcare Assistance System provides preliminary guidance only and does not replace professional medical diagnosis, clinical evaluation, or emergency treatment.
            </p>
          </div>
        </div>
      </section>

      {/* Specialties from Real Database */}
      <section className="mn-section" id="specialties" style={{ background: '#ffffff' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">Clinical Departments</span>
            <h2 className="mn-section-title">Specialized Care Across All Disciplines</h2>
            <p className="mn-section-desc">
              Connected departments operating within an integrated electronic medical record ecosystem.
            </p>
          </div>

          <div className="mn-specialties-grid">
            {departments.length > 0 ? (
              departments.map((dept) => (
                <div key={dept.id} className="mn-specialty-card">
                  <div className="mn-specialty-icon">
                    <i className="fa-solid fa-hospital-user"></i>
                  </div>
                  <div>
                    <h4 className="mn-specialty-name">{dept.name}</h4>
                    <p className="mn-specialty-desc">{dept.description}</p>
                    <div style={{ marginTop: '8px' }}>
                      <span className="mn-badge mn-badge-teal" style={{ fontSize: '11px' }}>
                        {dept.floorLocation || 'Main Wing'}
                      </span>
                    </div>
                  </div>
                </div>
              ))
            ) : (
              <div style={{ textAlign: 'center', gridColumn: '1 / -1', color: '#94a3b8' }}>
                <i className="fa-solid fa-spinner fa-spin"></i> Loading departments...
              </div>
            )}
          </div>
        </div>
      </section>

      {/* Role Dashboards Overview */}
      <section className="mn-section" id="roles" style={{ background: '#f8fafc', borderTop: '1px solid #e2e8f0' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">Role-Based Experience</span>
            <h2 className="mn-section-title">Designed for Every Hospital Stakeholder</h2>
            <p className="mn-section-desc">
              Dedicated interfaces tailored to the daily responsibilities of patients, clinicians, and administrative staff.
            </p>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '20px' }}>
            <div className="mn-feature-box" style={{ background: '#ffffff', cursor: 'pointer' }} onClick={() => setView('login')}>
              <div className="mn-feature-icon" style={{ background: '#ccfbf1', color: '#0f766e' }}>
                <i className="fa-solid fa-hospital-user"></i>
              </div>
              <h3 className="mn-feature-title">Patient Portal</h3>
              <p className="mn-feature-desc">Self-service appointment booking, digital prescriptions, test results, and longitudinal medical history.</p>
              <div style={{ marginTop: '14px', fontSize: '13px', fontWeight: 700, color: '#0d9488' }}>
                Access Patient Portal <i className="fa-solid fa-arrow-right"></i>
              </div>
            </div>

            <div className="mn-feature-box" style={{ background: '#ffffff', cursor: 'pointer' }} onClick={() => setView('login')}>
              <div className="mn-feature-icon" style={{ background: '#e0f2fe', color: '#0284c7' }}>
                <i className="fa-solid fa-user-doctor"></i>
              </div>
              <h3 className="mn-feature-title">Doctor Clinical Portal</h3>
              <p className="mn-feature-desc">Active patient queue, AI pre-consultation symptom summaries, diagnostic charts, and rapid prescription orders.</p>
              <div style={{ marginTop: '14px', fontSize: '13px', fontWeight: 700, color: '#0284c7' }}>
                Access Doctor Portal <i className="fa-solid fa-arrow-right"></i>
              </div>
            </div>

            <div className="mn-feature-box" style={{ background: '#ffffff', cursor: 'pointer' }} onClick={() => setView('login')}>
              <div className="mn-feature-icon" style={{ background: '#fef3c7', color: '#b45309' }}>
                <i className="fa-solid fa-clipboard-user"></i>
              </div>
              <h3 className="mn-feature-title">Receptionist Portal</h3>
              <p className="mn-feature-desc">Front-desk intake, physical patient arrival verification, bed census tracking, and duty roster coordination.</p>
              <div style={{ marginTop: '14px', fontSize: '13px', fontWeight: 700, color: '#b45309' }}>
                Access Receptionist Portal <i className="fa-solid fa-arrow-right"></i>
              </div>
            </div>

            <div className="mn-feature-box" style={{ background: '#ffffff', cursor: 'pointer' }} onClick={() => setView('login')}>
              <div className="mn-feature-icon" style={{ background: '#f3e8ff', color: '#6b21a8' }}>
                <i className="fa-solid fa-chart-line"></i>
              </div>
              <h3 className="mn-feature-title">Administrator Portal</h3>
              <p className="mn-feature-desc">Hospital-wide telemetry, department configurations, staff credentials, bed occupancy, and financial balance sheets.</p>
              <div style={{ marginTop: '14px', fontSize: '13px', fontWeight: 700, color: '#6b21a8' }}>
                Access Admin Portal <i className="fa-solid fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* FAQ Section */}
      <section className="mn-section" id="faq" style={{ background: '#ffffff' }}>
        <div className="mn-container">
          <div className="mn-section-header">
            <span className="mn-section-tag">Frequently Asked Questions</span>
            <h2 className="mn-section-title">Transparent & Honest Answers</h2>
            <p className="mn-section-desc">
              Learn how Smart Healthcare Assistance System handles data safety, appointment collisions, and clinical workflows.
            </p>
          </div>

          <div style={{ maxWidth: '800px', margin: '0 auto' }}>
            {[
              {
                q: "Does Smart Healthcare Assistance System replace a medical doctor's diagnosis?",
                a: "No. The Smart Healthcare Assistance System provides preliminary triage guidance only. It helps patients understand their urgency level and find the appropriate medical specialty. All clinical decisions and final diagnoses must be performed by a qualified physician."
              },
              {
                q: "What happens if two users try to book the same doctor slot at the same time?",
                a: "Smart Healthcare Assistance System implements pessimistic database row locks during appointment writes. One booking transaction succeeds while the conflicting transaction is rejected cleanly with an informative message."
              },
              {
                q: "Can an emergency patient be registered without a date of birth?",
                a: "Yes. Only the patient's name is strictly required for intake so that emergency care is never delayed by paperwork. Additional demographic fields can be recorded later."
              },
              {
                q: "How does Smart Healthcare Assistance System ensure pharmacy stock integrity?",
                a: "Stock is decremented in the exact same atomic transaction as the dispensing sale. The system rejects sales that would cause stock to go negative, and reversing a voided sale safely returns stock to the catalog."
              }
            ].map((faq, idx) => (
              <div key={idx} className="mn-faq-item">
                <div 
                  className="mn-faq-question" 
                  onClick={() => toggleFaq(idx)}
                  style={{ cursor: 'pointer', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}
                >
                  <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <i className="fa-solid fa-circle-question" style={{ color: '#0d9488' }}></i>
                    {faq.q}
                  </div>
                  <i className={`fa-solid fa-chevron-${activeFaq === idx ? 'up' : 'down'}`} style={{ color: '#94a3b8', fontSize: '13px' }}></i>
                </div>
                {(activeFaq === idx || activeFaq === null) && (
                  <div className="mn-faq-answer">
                    {faq.a}
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Final Call to Action */}
      <section style={{ background: 'linear-gradient(135deg, #0f172a, #1e293b)', padding: '70px 0', color: '#ffffff', textAlign: 'center' }}>
        <div className="mn-container">
          <h2 style={{ fontSize: '36px', fontWeight: 800, marginBottom: '14px', letterSpacing: '-0.5px' }}>
            Ready to Experience Connected Healthcare?
          </h2>
          <p style={{ fontSize: '17px', color: '#cbd5e1', maxWidth: '650px', margin: '0 auto 30px' }}>
            Sign in to your dashboard to manage appointments, clinical records, pharmacy inventory, and hospital administration.
          </p>
          <div style={{ display: 'flex', justifyContent: 'center', gap: '16px', flexWrap: 'wrap' }}>
            <button 
              onClick={() => setView('login')} 
              className="mn-btn mn-btn-teal" 
              style={{ padding: '14px 30px', fontSize: '16px' }}
            >
              <i className="fa-solid fa-right-to-bracket"></i> Sign In to Smart Healthcare Assistance System
            </button>
            <a 
              href="#assistant" 
              className="mn-btn mn-btn-outline" 
              style={{ borderColor: '#475569', color: '#ffffff', padding: '14px 30px', fontSize: '16px' }}
            >
              <i className="fa-solid fa-heart-pulse"></i> Try Symptom Triage
            </a>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer style={{ background: '#090d16', color: '#cbd5e1', padding: '60px 0 30px', borderTop: '1px solid #1e293b' }}>
        <div className="mn-container">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '40px', marginBottom: '50px' }}>
            <div>
              <div className="mn-brand-wrapper" style={{ marginBottom: '16px' }}>
                <div className="mn-brand-logo-icon">
                  <i className="fa-solid fa-hospital-user"></i>
                </div>
                <div className="mn-brand-text">
                  <div className="mn-brand-title" style={{ color: '#ffffff' }}>Smart <span style={{ color: '#2dd4bf' }}>Healthcare</span></div>
                  <div className="mn-brand-subtitle" style={{ color: '#94a3b8' }}>Assistance System</div>
                </div>
              </div>
              <p style={{ fontSize: '13.5px', lineHeight: 1.6, color: '#94a3b8', maxWidth: '320px' }}>
                An AI-Powered Healthcare and Hospital Management Platform integrating clinical care, diagnostics, and patient communication.
              </p>
            </div>

            <div>
              <h4 style={{ color: '#ffffff', fontSize: '14px', fontWeight: 700, marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '0.6px' }}>Platform</h4>
              <ul style={{ listStyle: 'none', padding: 0, margin: 0, fontSize: '13.5px', lineHeight: 2 }}>
                <li><a href="#assistant" style={{ color: '#94a3b8', textDecoration: 'none' }}>Healthcare Assistant</a></li>
                <li><a href="#services" style={{ color: '#94a3b8', textDecoration: 'none' }}>Clinical Modules</a></li>
                <li><a href="#specialties" style={{ color: '#94a3b8', textDecoration: 'none' }}>Specialties</a></li>
                <li><a href="#home" onClick={() => setView('login')} style={{ color: '#94a3b8', textDecoration: 'none' }}>Hospital Sign In</a></li>
              </ul>
            </div>

            <div>
              <h4 style={{ color: '#ffffff', fontSize: '14px', fontWeight: 700, marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '0.6px' }}>Roles</h4>
              <ul style={{ listStyle: 'none', padding: 0, margin: 0, fontSize: '13.5px', lineHeight: 2 }}>
                <li><a href="#home" onClick={() => setView('login')} style={{ color: '#94a3b8', textDecoration: 'none' }}>Patient Portal</a></li>
                <li><a href="#home" onClick={() => setView('login')} style={{ color: '#94a3b8', textDecoration: 'none' }}>Doctor Portal</a></li>
                <li><a href="#home" onClick={() => setView('login')} style={{ color: '#94a3b8', textDecoration: 'none' }}>Receptionist Portal</a></li>
                <li><a href="#home" onClick={() => setView('login')} style={{ color: '#94a3b8', textDecoration: 'none' }}>Administrator</a></li>
              </ul>
            </div>

            <div>
              <h4 style={{ color: '#ffffff', fontSize: '14px', fontWeight: 700, marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '0.6px' }}>Safety & Privacy</h4>
              <ul style={{ listStyle: 'none', padding: 0, margin: 0, fontSize: '13.5px', lineHeight: 2 }}>
                <li><a href="#faq" style={{ color: '#94a3b8', textDecoration: 'none' }}>AI Safety Guidelines</a></li>
                <li><a href="#faq" style={{ color: '#94a3b8', textDecoration: 'none' }}>Privacy & Records</a></li>
                <li><a href="#faq" style={{ color: '#94a3b8', textDecoration: 'none' }}>Emergency Protocols</a></li>
              </ul>
            </div>
          </div>

          <div style={{ paddingTop: '24px', borderTop: '1px solid #1e293b', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '12px', fontSize: '12.5px' }}>
            <p style={{ margin: 0 }}>&copy; {new Date().getFullYear()} Smart Healthcare Assistance System. All rights reserved. Intelligent care. Connected healthcare.</p>
            <p style={{ margin: 0, color: '#64748b' }}>Preliminary AI guidance only. In an emergency, dial 911 / 112 / 108 immediately.</p>
          </div>
        </div>
      </footer>
    </>
  );
}
