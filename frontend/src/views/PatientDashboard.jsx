import React, { useState, useEffect } from 'react';
import api from '../services/api';

export default function PatientDashboard({ user, setView, initialBookingData }) {
  const [appointments, setAppointments] = useState([]);
  const [records, setRecords] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showBookingModal, setShowBookingModal] = useState(!!initialBookingData);

  // Booking Form State
  const [selectedDoctorId, setSelectedDoctorId] = useState(initialBookingData?.doctor?.id || '');
  const [bookingDate, setBookingDate] = useState(new Date().toISOString().split('T')[0]);
  const [bookingTime, setBookingTime] = useState('14:00');
  const [triageNotes, setTriageNotes] = useState(initialBookingData?.triage?.clinical_rationale || '');
  const [urgencyLevel, setUrgencyLevel] = useState(initialBookingData?.triage?.urgency_level || 'Routine');
  const [bookingMessage, setBookingMessage] = useState(null);
  const [bookingError, setBookingError] = useState(null);

  const loadData = async () => {
    try {
      setLoading(true);
      const [aptsData, recsData, docsData] = await Promise.all([
        api.getAppointments(),
        api.getMedicalRecords(),
        api.getDoctors()
      ]);
      setAppointments(aptsData || []);
      setRecords(recsData || []);
      setDoctors(docsData || []);
    } catch (err) {
      console.error('Failed to load patient data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleBook = async (e) => {
    e.preventDefault();
    setBookingMessage(null);
    setBookingError(null);

    try {
      await api.bookAppointment({
        doctorId: parseInt(selectedDoctorId),
        appointmentDate: bookingDate,
        startTime: bookingTime.length === 5 ? `${bookingTime}:00` : bookingTime,
        urgencyLevel,
        triageNotes
      });
      setBookingMessage('Appointment booked successfully!');
      setTimeout(() => {
        setShowBookingModal(false);
        setBookingMessage(null);
        loadData();
      }, 1500);
    } catch (err) {
      setBookingError(err.response?.data?.message || 'Failed to book appointment. Slot may be conflicted.');
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case 'CONFIRMED':
        return <span className="mn-badge mn-badge-teal">Confirmed</span>;
      case 'CHECKED_IN':
        return <span className="mn-badge mn-badge-primary">Checked In</span>;
      case 'IN_CONSULTATION':
        return <span className="mn-badge mn-badge-warning">In Consultation</span>;
      case 'COMPLETED':
        return <span className="mn-badge mn-badge-teal" style={{ background: '#d1fae5', color: '#065f46' }}>Completed</span>;
      case 'CANCELLED':
        return <span className="mn-badge mn-badge-danger">Cancelled</span>;
      default:
        return <span className="mn-badge mn-badge-teal">{status}</span>;
    }
  };

  return (
    <div style={{ padding: '30px 20px', maxWidth: '1200px', margin: '0 auto' }}>
      {/* Dashboard Top Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '28px', flexWrap: 'wrap', gap: '16px' }}>
        <div>
          <span className="mn-badge mn-badge-teal" style={{ marginBottom: '6px' }}>Patient Healthcare Portal</span>
          <h1 style={{ fontSize: '26px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
            Welcome back, {user?.firstName || 'Patient'}
          </h1>
          <p style={{ fontSize: '14px', color: '#64748b', margin: 0 }}>
            Access upcoming clinical visits, verified medical records, and digital prescriptions.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '10px' }}>
          <button 
            onClick={() => setShowBookingModal(true)} 
            className="mn-btn mn-btn-teal mn-btn-sm"
          >
            <i className="fa-regular fa-calendar-plus"></i> Book Consultation
          </button>
          <button 
            onClick={() => setView('landing')} 
            className="mn-btn mn-btn-outline mn-btn-sm"
          >
            <i className="fa-solid fa-wand-magic-sparkles"></i> AI Symptom Triage
          </button>
        </div>
      </div>

      {/* Stats Overview */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '30px' }}>
        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Upcoming Appointments</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>
            {appointments.filter(a => a.status !== 'COMPLETED' && a.status !== 'CANCELLED').length}
          </div>
          <div style={{ fontSize: '12px', color: '#0d9488' }}><i className="fa-regular fa-clock"></i> Next visit confirmed</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Medical EMR Records</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>
            {records.length}
          </div>
          <div style={{ fontSize: '12px', color: '#0284c7' }}><i className="fa-solid fa-file-medical"></i> Verified clinical diagnoses</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Digital Prescriptions</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>
            {records.filter(r => r.prescriptionNotes).length}
          </div>
          <div style={{ fontSize: '12px', color: '#10b981' }}><i className="fa-solid fa-pills"></i> Pharmacy verified</div>
        </div>
      </div>

      {/* Appointments Table Section */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', marginBottom: '30px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '18px' }}>
          <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', margin: 0 }}>
            <i className="fa-regular fa-calendar-check" style={{ color: '#0d9488' }}></i> Your Clinical Consultations
          </h3>
          <span style={{ fontSize: '12px', color: '#64748b' }}>{appointments.length} Total Registered</span>
        </div>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '30px', color: '#94a3b8' }}>
            <i className="fa-solid fa-spinner fa-spin"></i> Loading appointments...
          </div>
        ) : appointments.length === 0 ? (
          <div style={{ textAlign: 'center', padding: '30px', color: '#64748b' }}>
            <p>No appointments booked yet. Click "Book Consultation" to schedule a visit.</p>
          </div>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '13.5px' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #f1f5f9', color: '#64748b', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '0.6px' }}>
                  <th style={{ padding: '10px 12px' }}>Appt #</th>
                  <th style={{ padding: '10px 12px' }}>Doctor</th>
                  <th style={{ padding: '10px 12px' }}>Department</th>
                  <th style={{ padding: '10px 12px' }}>Date & Time</th>
                  <th style={{ padding: '10px 12px' }}>Location</th>
                  <th style={{ padding: '10px 12px' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {appointments.map((apt) => (
                  <tr key={apt.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '12px', fontWeight: 700, color: '#0f172a' }}>{apt.appointmentNumber}</td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 700, color: '#0f172a' }}>
                        {apt.doctor?.user ? `Dr. ${apt.doctor.user.firstName} ${apt.doctor.user.lastName}` : 'Physician'}
                      </div>
                      <div style={{ fontSize: '11.5px', color: '#64748b' }}>{apt.doctor?.specialization}</div>
                    </td>
                    <td style={{ padding: '12px', color: '#475569' }}>{apt.department?.name}</td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 600 }}>{apt.appointmentDate}</div>
                      <div style={{ fontSize: '11.5px', color: '#64748b' }}>{apt.startTime} - {apt.endTime}</div>
                    </td>
                    <td style={{ padding: '12px', color: '#64748b' }}>{apt.doctor?.roomNumber || 'Room 101'}</td>
                    <td style={{ padding: '12px' }}>{getStatusBadge(apt.status)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* EMR Medical Records Section */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', marginBottom: '18px' }}>
          <i className="fa-solid fa-file-waveform" style={{ color: '#0284c7' }}></i> Electronic Health Records (EMR)
        </h3>

        {records.length === 0 ? (
          <p style={{ color: '#64748b', fontSize: '13.5px' }}>No clinical records recorded yet.</p>
        ) : (
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '16px' }}>
            {records.map((rec) => (
              <div key={rec.id} style={{ background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '10px', padding: '16px' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
                  <span style={{ fontSize: '11.5px', fontWeight: 700, color: '#0284c7', textTransform: 'uppercase' }}>{rec.recordNumber}</span>
                  <span style={{ fontSize: '11.5px', color: '#64748b' }}>{rec.createdAt?.split('T')[0]}</span>
                </div>
                <h4 style={{ fontSize: '15px', fontWeight: 800, color: '#0f172a', margin: '0 0 6px' }}>{rec.diagnosis}</h4>
                <p style={{ fontSize: '13px', color: '#475569', marginBottom: '8px' }}>
                  <strong>Complaint:</strong> {rec.chiefComplaint}
                </p>
                {rec.vitalsBp && (
                  <div style={{ display: 'flex', gap: '10px', fontSize: '12px', color: '#64748b', marginBottom: '8px', background: '#ffffff', padding: '6px 8px', borderRadius: '6px' }}>
                    <span>BP: <strong>{rec.vitalsBp}</strong></span>
                    <span>HR: <strong>{rec.vitalsHeartRate} bpm</strong></span>
                    <span>Temp: <strong>{rec.vitalsTempCelsius}°C</strong></span>
                  </div>
                )}
                {rec.prescriptionNotes && (
                  <div style={{ fontSize: '12.5px', color: '#0f766e', background: '#f0fdfa', padding: '8px', borderRadius: '6px' }}>
                    <strong>Prescription:</strong> {rec.prescriptionNotes}
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Booking Modal */}
      {showBookingModal && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(15, 23, 42, 0.6)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000, padding: '20px' }}>
          <div style={{ background: '#ffffff', borderRadius: '16px', maxWidth: '520px', width: '100%', padding: '28px', maxHeight: '90vh', overflowY: 'auto' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 style={{ fontSize: '19px', fontWeight: 800, color: '#0f172a', margin: 0 }}>
                Book Specialist Consultation
              </h3>
              <button onClick={() => setShowBookingModal(false)} style={{ background: 'none', border: 'none', fontSize: '18px', cursor: 'pointer', color: '#64748b' }}>&times;</button>
            </div>

            {bookingMessage && (
              <div style={{ background: '#f0fdf4', border: '1px solid #bbf7d0', color: '#166534', padding: '10px', borderRadius: '8px', marginBottom: '14px', fontSize: '13px' }}>
                <i className="fa-solid fa-circle-check"></i> {bookingMessage}
              </div>
            )}
            {bookingError && (
              <div style={{ background: '#fef2f2', border: '1px solid #fee2e2', color: '#b91c1c', padding: '10px', borderRadius: '8px', marginBottom: '14px', fontSize: '13px' }}>
                <i className="fa-solid fa-circle-exclamation"></i> {bookingError}
              </div>
            )}

            <form onSubmit={handleBook}>
              <div className="mn-form-group">
                <label className="mn-form-label">Select Specialist Doctor</label>
                <select 
                  className="mn-form-select" 
                  value={selectedDoctorId} 
                  onChange={(e) => setSelectedDoctorId(e.target.value)} 
                  required
                >
                  <option value="">-- Choose Doctor --</option>
                  {doctors.map(d => (
                    <option key={d.id} value={d.id}>
                      {d.user ? `Dr. ${d.user.firstName} ${d.user.lastName}` : 'Doctor'} - {d.department?.name} (${d.consultationFee})
                    </option>
                  ))}
                </select>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                <div className="mn-form-group">
                  <label className="mn-form-label">Date</label>
                  <input 
                    type="date" 
                    className="mn-form-input" 
                    value={bookingDate} 
                    onChange={(e) => setBookingDate(e.target.value)} 
                    required 
                  />
                </div>
                <div className="mn-form-group">
                  <label className="mn-form-label">Time</label>
                  <select 
                    className="mn-form-select" 
                    value={bookingTime} 
                    onChange={(e) => setBookingTime(e.target.value)} 
                    required
                  >
                    <option value="09:00">09:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="14:00">02:00 PM</option>
                    <option value="15:00">03:00 PM</option>
                    <option value="16:00">04:00 PM</option>
                  </select>
                </div>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Pre-Triage Notes / Symptoms</label>
                <textarea 
                  className="mn-form-textarea" 
                  rows="3" 
                  placeholder="Describe reasons for visit or symptoms..." 
                  value={triageNotes} 
                  onChange={(e) => setTriageNotes(e.target.value)}
                ></textarea>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px', marginTop: '16px' }}>
                <button type="button" onClick={() => setShowBookingModal(false)} className="mn-btn mn-btn-outline mn-btn-sm">Cancel</button>
                <button type="submit" className="mn-btn mn-btn-teal mn-btn-sm">Confirm Booking</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
