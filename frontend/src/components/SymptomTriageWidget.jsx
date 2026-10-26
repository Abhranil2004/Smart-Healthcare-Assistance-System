import React, { useState } from 'react';
import api from '../services/api';

export default function SymptomTriageWidget({ onSelectDoctor }) {
  const [symptoms, setSymptoms] = useState('');
  const [duration, setDuration] = useState('Today');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [result, setResult] = useState(null);

  const handleAnalyze = async (e) => {
    e.preventDefault();
    if (!symptoms.trim()) {
      setError('Please describe your symptoms to receive guidance.');
      return;
    }

    setLoading(true);
    setError(null);

    try {
      const data = await api.triageAndRecommend(symptoms, duration);
      setResult(data);
    } catch (err) {
      console.error('Triage error:', err);
      setError('Unable to analyze symptoms at this moment. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const getUrgencyBadge = (level) => {
    switch (level) {
      case 'Emergency':
        return <span className="mn-badge mn-badge-danger"><i className="fa-solid fa-triangle-exclamation"></i> Emergency Alert</span>;
      case 'Urgent':
        return <span className="mn-badge mn-badge-warning"><i className="fa-solid fa-clock-rotate-left"></i> Urgent (Within 24 Hours)</span>;
      case 'Moderate':
        return <span className="mn-badge mn-badge-teal"><i className="fa-solid fa-notes-medical"></i> Moderate (1-3 Days)</span>;
      default:
        return <span className="mn-badge mn-badge-primary"><i className="fa-solid fa-check"></i> Routine Consultation</span>;
    }
  };

  return (
    <div className="mn-assistant-container" id="assistant">
      <div className="mn-assistant-header">
        <div>
          <h3><i className="fa-solid fa-sparkles" style={{ color: '#2dd4bf' }}></i> Smart Healthcare Assistant</h3>
          <p>Rule-guided clinical triage & specialty recommendation</p>
        </div>
        <span className="mn-badge mn-badge-teal" style={{ background: 'rgba(255,255,255,0.15)', color: '#ffffff' }}>
          AI Guidance Engine
        </span>
      </div>

      <div className="mn-assistant-body">
        {/* Emergency Alert Banner */}
        {result && result.is_emergency && (
          <div className="mn-emergency-alert" style={{ display: 'flex' }}>
            <i className="fa-solid fa-triangle-exclamation mn-emergency-icon"></i>
            <div className="mn-emergency-content">
              <h4>CRITICAL EMERGENCY WARNING DETECTED</h4>
              <p>{result.emergency_warning}</p>
              {result.emergency_reason && (
                <div style={{ marginTop: '8px', fontSize: '13px', background: 'rgba(255,255,255,0.2)', padding: '6px 10px', borderRadius: '4px' }}>
                  <strong>Trigger:</strong> {result.emergency_reason}
                </div>
              )}
            </div>
          </div>
        )}

        {/* Input Form */}
        <form onSubmit={handleAnalyze}>
          <div className="mn-form-group">
            <label className="mn-form-label" htmlFor="userSymptoms">
              Describe your symptoms in your own words:
            </label>
            <textarea 
              id="userSymptoms" 
              className="mn-form-textarea" 
              rows="3" 
              placeholder="e.g., I have sharp chest discomfort radiating to my left arm, or persistent abdominal cramping after meals..."
              value={symptoms}
              onChange={(e) => setSymptoms(e.target.value)}
              required
            ></textarea>
            <span style={{ fontSize: '11.5px', color: '#64748b', marginTop: '4px', display: 'block' }}>
              Tip: Include where the discomfort is located, how it feels, and what makes it better or worse.
            </span>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '20px' }}>
            <div className="mn-form-group" style={{ marginBottom: 0 }}>
              <label className="mn-form-label" htmlFor="symptomDuration">How long have you experienced this?</label>
              <select 
                id="symptomDuration" 
                className="mn-form-select"
                value={duration}
                onChange={(e) => setDuration(e.target.value)}
              >
                <option value="Started today">Started today</option>
                <option value="2-3 days">2 - 3 days</option>
                <option value="About a week">About a week</option>
                <option value="Over two weeks">Over two weeks</option>
                <option value="Chronic / recurring">Chronic / recurring</option>
              </select>
            </div>

            <div style={{ display: 'flex', alignItems: 'flex-end' }}>
              <button 
                type="submit" 
                id="btnAnalyze" 
                className="mn-btn mn-btn-teal" 
                style={{ width: '100%', height: '44px', fontSize: '15px' }}
                disabled={loading}
              >
                {loading ? (
                  <span><i className="fa-solid fa-spinner fa-spin"></i> Analyzing Clinical Patterns...</span>
                ) : (
                  <span><i className="fa-solid fa-wand-magic-sparkles"></i> Analyze & Recommend</span>
                )}
              </button>
            </div>
          </div>

          {error && (
            <div style={{ background: '#fef2f2', border: '1px solid #fee2e2', color: '#b91c1c', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '13.5px' }}>
              <i className="fa-solid fa-circle-exclamation"></i> {error}
            </div>
          )}
        </form>

        {/* Dynamic Triage Output */}
        {result && (
          <div className="mn-triage-card" style={{ display: 'block', animation: 'fadeIn 0.3s ease' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', flexWrap: 'wrap', gap: '10px', marginBottom: '14px' }}>
              <div>
                <span style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', letterSpacing: '0.6px' }}>
                  Preliminary Clinical Assessment
                </span>
                <h4 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
                  Recommended Specialty: <span style={{ color: '#0d9488' }}>{result.recommended_specialty}</span>
                </h4>
              </div>
              <div>{getUrgencyBadge(result.urgency_level)}</div>
            </div>

            <div style={{ background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '8px', padding: '14px', marginBottom: '16px' }}>
              <div style={{ fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '4px' }}>
                <i className="fa-solid fa-stethoscope" style={{ color: '#0d9488' }}></i> Clinical Triage Rationale
              </div>
              <p style={{ fontSize: '13.5px', color: '#475569', margin: 0, lineHeight: 1.5 }}>
                {result.clinical_rationale}
              </p>
              {result.actionable_advice && (
                <div style={{ marginTop: '8px', fontSize: '13px', color: '#0f766e', fontWeight: 600 }}>
                  <i className="fa-solid fa-arrow-right"></i> {result.actionable_advice}
                </div>
              )}
            </div>

            {/* Consultant Doctors from Real Database */}
            {result.recommended_doctors && result.recommended_doctors.length > 0 && (
              <div style={{ marginBottom: '16px' }}>
                <h4 style={{ fontSize: '13px', fontWeight: 700, color: '#0f172a', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: '10px' }}>
                  <i className="fa-solid fa-user-doctor" style={{ color: '#0d9488' }}></i> Available Hospital Specialists for {result.recommended_specialty}
                </h4>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '12px' }}>
                  {result.recommended_doctors.map((doc, idx) => (
                    <div 
                      key={idx} 
                      style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '8px', padding: '12px', display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}
                    >
                      <div>
                        <div style={{ fontWeight: 800, fontSize: '14px', color: '#0f172a' }}>{doc.name}</div>
                        <div style={{ fontSize: '12px', color: '#0d9488', fontWeight: 600 }}>{doc.specialization}</div>
                        <div style={{ fontSize: '11px', color: '#64748b', marginTop: '2px' }}>{doc.qualification} · {doc.roomNumber}</div>
                        <div style={{ fontSize: '11.5px', color: '#475569', marginTop: '4px' }}>Fee: ${doc.consultationFee}</div>
                      </div>
                      <button 
                        onClick={() => onSelectDoctor && onSelectDoctor(doc, result)}
                        className="mn-btn mn-btn-outline mn-btn-sm" 
                        style={{ marginTop: '10px', width: '100%', fontSize: '12px', padding: '6px' }}
                      >
                        <i className="fa-regular fa-calendar-check"></i> Book Slot
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            )}

            <div className="mn-disclaimer-note">
              <i className="fa-solid fa-circle-info" style={{ color: '#0d9488' }}></i>
              <span>{result.disclaimer}</span>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
