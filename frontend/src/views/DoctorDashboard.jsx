import React, { useState, useEffect } from 'react';
import api from '../services/api';

export default function DoctorDashboard({ user }) {
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedApt, setSelectedApt] = useState(null);
  const [showEmrModal, setShowEmrModal] = useState(false);

  // EMR Form State
  const [chiefComplaint, setChiefComplaint] = useState('');
  const [clinicalExam, setClinicalExam] = useState('');
  const [diagnosis, setDiagnosis] = useState('');
  const [prescription, setPrescription] = useState('');
  const [labTests, setLabTests] = useState('');
  const [bp, setBp] = useState('120/80');
  const [heartRate, setHeartRate] = useState(75);
  const [temp, setTemp] = useState(36.8);
  const [emrSuccess, setEmrSuccess] = useState(null);

  const loadData = async () => {
    try {
      setLoading(true);
      const data = await api.getAppointments();
      setAppointments(data || []);
    } catch (err) {
      console.error('Failed to load doctor appointments:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleStatusChange = async (aptId, newStatus) => {
    try {
      await api.updateAppointmentStatus(aptId, newStatus);
      loadData();
    } catch (err) {
      alert('Failed to update status: ' + (err.response?.data?.message || err.message));
    }
  };

  const openEmrForAppointment = (apt) => {
    setSelectedApt(apt);
    setChiefComplaint(apt.triageNotes || 'Consultation review');
    setClinicalExam('Patient alert and oriented. Normal heart and lung auscultation.');
    setDiagnosis('');
    setPrescription('');
    setLabTests('');
    setShowEmrModal(true);
  };

  const handleSaveEmr = async (e) => {
    e.preventDefault();
    if (!selectedApt || !selectedApt.patient) return;

    try {
      await api.createMedicalRecord({
        appointmentId: selectedApt.id,
        patientId: selectedApt.patient.id,
        chiefComplaint,
        clinicalExamination: clinicalExam,
        diagnosis,
        prescriptionNotes: prescription,
        labTestsOrdered: labTests,
        vitalsBp: bp,
        vitalsHeartRate: parseInt(heartRate),
        vitalsTempCelsius: parseFloat(temp)
      });

      // Also mark appointment completed
      await api.updateAppointmentStatus(selectedApt.id, 'COMPLETED');

      setEmrSuccess('Clinical record saved and appointment marked as completed!');
      setTimeout(() => {
        setShowEmrModal(false);
        setEmrSuccess(null);
        loadData();
      }, 1500);
    } catch (err) {
      alert('Failed to save EMR record: ' + (err.response?.data?.message || err.message));
    }
  };

  return (
    <div style={{ padding: '30px 20px', maxWidth: '1200px', margin: '0 auto' }}>
      {/* Top Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px', flexWrap: 'wrap', gap: '16px' }}>
        <div>
          <span className="mn-badge mn-badge-teal" style={{ marginBottom: '6px' }}>Clinical Care Operations</span>
          <h1 style={{ fontSize: '26px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
            Doctor Clinical Portal: Dr. {user?.firstName} {user?.lastName}
          </h1>
          <p style={{ fontSize: '14px', color: '#64748b', margin: 0 }}>
            Daily consultation queue, patient pre-triage summaries, and direct EMR clinical notes.
          </p>
        </div>
        <div>
          <button onClick={loadData} className="mn-btn mn-btn-outline mn-btn-sm">
            <i className="fa-solid fa-rotate-right"></i> Refresh Queue
          </button>
        </div>
      </div>

      {/* Queue Metrics */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '28px' }}>
        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Total Assigned</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>{appointments.length}</div>
          <div style={{ fontSize: '12px', color: '#0284c7' }}>Scheduled consultations</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Waiting / Checked In</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#f59e0b', margin: '4px 0' }}>
            {appointments.filter(a => a.status === 'CHECKED_IN').length}
          </div>
          <div style={{ fontSize: '12px', color: '#b45309' }}>Physically in waiting room</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Completed Today</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#10b981', margin: '4px 0' }}>
            {appointments.filter(a => a.status === 'COMPLETED').length}
          </div>
          <div style={{ fontSize: '12px', color: '#047857' }}>Consultations finalized</div>
        </div>
      </div>

      {/* Consultation Queue Table */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', marginBottom: '18px' }}>
          <i className="fa-solid fa-list-check" style={{ color: '#0d9488' }}></i> Patient Consultation Queue
        </h3>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '30px', color: '#94a3b8' }}>
            <i className="fa-solid fa-spinner fa-spin"></i> Loading queue...
          </div>
        ) : appointments.length === 0 ? (
          <p style={{ color: '#64748b', textAlign: 'center', padding: '20px' }}>No active patients currently in your queue.</p>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '13.5px' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #f1f5f9', color: '#64748b', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '0.6px' }}>
                  <th style={{ padding: '10px 12px' }}>Patient</th>
                  <th style={{ padding: '10px 12px' }}>Slot Time</th>
                  <th style={{ padding: '10px 12px' }}>Urgency</th>
                  <th style={{ padding: '10px 12px' }}>AI Pre-Triage Notes</th>
                  <th style={{ padding: '10px 12px' }}>Status</th>
                  <th style={{ padding: '10px 12px' }}>Clinical Action</th>
                </tr>
              </thead>
              <tbody>
                {appointments.map(apt => (
                  <tr key={apt.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 700, color: '#0f172a' }}>
                        {apt.patient ? `${apt.patient.firstName} ${apt.patient.lastName}` : 'Walk-in Patient'}
                      </div>
                      <div style={{ fontSize: '11px', color: '#64748b' }}>{apt.patient?.patientIdentifier} · {apt.patient?.gender}</div>
                    </td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 600 }}>{apt.startTime}</div>
                      <div style={{ fontSize: '11px', color: '#64748b' }}>{apt.appointmentDate}</div>
                    </td>
                    <td style={{ padding: '12px' }}>
                      <span className={`mn-badge ${apt.urgencyLevel === 'Emergency' ? 'mn-badge-danger' : apt.urgencyLevel === 'Urgent' ? 'mn-badge-warning' : 'mn-badge-teal'}`}>
                        {apt.urgencyLevel}
                      </span>
                    </td>
                    <td style={{ padding: '12px', maxWidth: '300px' }}>
                      <div style={{ fontSize: '12.5px', color: '#475569', lineHeight: 1.4 }}>
                        {apt.triageNotes || 'Standard checkup.'}
                      </div>
                    </td>
                    <td style={{ padding: '12px' }}>
                      <span className="mn-badge mn-badge-teal" style={{ fontSize: '11px' }}>{apt.status}</span>
                    </td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ display: 'flex', gap: '6px', flexWrap: 'wrap' }}>
                        {apt.status === 'CONFIRMED' && (
                          <button 
                            onClick={() => handleStatusChange(apt.id, 'IN_CONSULTATION')}
                            className="mn-btn mn-btn-outline mn-btn-sm" 
                            style={{ fontSize: '11.5px', padding: '5px 8px' }}
                          >
                            Start Consult
                          </button>
                        )}
                        {apt.status === 'CHECKED_IN' && (
                          <button 
                            onClick={() => handleStatusChange(apt.id, 'IN_CONSULTATION')}
                            className="mn-btn mn-btn-teal mn-btn-sm" 
                            style={{ fontSize: '11.5px', padding: '5px 8px' }}
                          >
                            Call Patient
                          </button>
                        )}
                        {apt.status !== 'COMPLETED' && (
                          <button 
                            onClick={() => openEmrForAppointment(apt)}
                            className="mn-btn mn-btn-teal mn-btn-sm" 
                            style={{ fontSize: '11.5px', padding: '5px 8px', background: '#0284c7', borderColor: '#0284c7' }}
                          >
                            <i className="fa-solid fa-file-pen"></i> Record EMR
                          </button>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* EMR Modal */}
      {showEmrModal && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(15, 23, 42, 0.6)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000, padding: '20px' }}>
          <div style={{ background: '#ffffff', borderRadius: '16px', maxWidth: '640px', width: '100%', padding: '28px', maxHeight: '90vh', overflowY: 'auto' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <div>
                <span style={{ fontSize: '11px', color: '#0284c7', fontWeight: 700, textTransform: 'uppercase' }}>Electronic Medical Record</span>
                <h3 style={{ fontSize: '19px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
                  Consultation Note: {selectedApt?.patient?.firstName} {selectedApt?.patient?.lastName}
                </h3>
              </div>
              <button onClick={() => setShowEmrModal(false)} style={{ background: 'none', border: 'none', fontSize: '18px', cursor: 'pointer', color: '#64748b' }}>&times;</button>
            </div>

            {emrSuccess && (
              <div style={{ background: '#f0fdf4', border: '1px solid #bbf7d0', color: '#166534', padding: '10px', borderRadius: '8px', marginBottom: '14px', fontSize: '13px' }}>
                <i className="fa-solid fa-circle-check"></i> {emrSuccess}
              </div>
            )}

            <form onSubmit={handleSaveEmr}>
              <div className="mn-form-group">
                <label className="mn-form-label">Chief Complaint</label>
                <input 
                  type="text" 
                  className="mn-form-input" 
                  value={chiefComplaint} 
                  onChange={(e) => setChiefComplaint(e.target.value)} 
                  required 
                />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '10px', marginBottom: '14px' }}>
                <div>
                  <label className="mn-form-label" style={{ fontSize: '11px' }}>Vitals: BP</label>
                  <input type="text" className="mn-form-input" value={bp} onChange={(e) => setBp(e.target.value)} />
                </div>
                <div>
                  <label className="mn-form-label" style={{ fontSize: '11px' }}>Vitals: HR (bpm)</label>
                  <input type="number" className="mn-form-input" value={heartRate} onChange={(e) => setHeartRate(e.target.value)} />
                </div>
                <div>
                  <label className="mn-form-label" style={{ fontSize: '11px' }}>Vitals: Temp (°C)</label>
                  <input type="number" step="0.1" className="mn-form-input" value={temp} onChange={(e) => setTemp(e.target.value)} />
                </div>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Clinical Examination</label>
                <textarea 
                  className="mn-form-textarea" 
                  rows="2" 
                  value={clinicalExam} 
                  onChange={(e) => setClinicalExam(e.target.value)}
                ></textarea>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Final Clinical Diagnosis</label>
                <input 
                  type="text" 
                  className="mn-form-input" 
                  placeholder="e.g., Acute Gastritis, Exertional Angina, Tension Headache" 
                  value={diagnosis} 
                  onChange={(e) => setDiagnosis(e.target.value)} 
                  required 
                />
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Digital Prescription Orders</label>
                <textarea 
                  className="mn-form-textarea" 
                  rows="2" 
                  placeholder="e.g., Omeprazole 20mg PO Daily x 14 days" 
                  value={prescription} 
                  onChange={(e) => setPrescription(e.target.value)}
                ></textarea>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Diagnostic Laboratory Tests Ordered</label>
                <input 
                  type="text" 
                  className="mn-form-input" 
                  placeholder="e.g., CBC, Lipid Panel, Upper GI Endoscopy" 
                  value={labTests} 
                  onChange={(e) => setLabTests(e.target.value)} 
                />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px', marginTop: '16px' }}>
                <button type="button" onClick={() => setShowEmrModal(false)} className="mn-btn mn-btn-outline mn-btn-sm">Cancel</button>
                <button type="submit" className="mn-btn mn-btn-teal mn-btn-sm">Save EMR & Finalize</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
