import React, { useState, useEffect } from 'react';
import api from '../services/api';

export default function ReceptionistDashboard({ user }) {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [patients, setPatients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showWalkinModal, setShowWalkinModal] = useState(false);

  // Walk-in booking state
  const [patientId, setPatientId] = useState('');
  const [doctorId, setDoctorId] = useState('');
  const [date, setDate] = useState(new Date().toISOString().split('T')[0]);
  const [time, setTime] = useState('10:00');
  const [triageNotes, setTriageNotes] = useState('Walk-in intake checkup');

  const loadData = async () => {
    try {
      setLoading(true);
      const [apts, docs, pats] = await Promise.all([
        api.getAppointments(),
        api.getDoctors(),
        api.getDepartments()
      ]);
      setAppointments(apts || []);
      setDoctors(docs || []);
    } catch (err) {
      console.error('Failed to load reception data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleCheckIn = async (aptId) => {
    try {
      await api.updateAppointmentStatus(aptId, 'CHECKED_IN');
      loadData();
    } catch (err) {
      alert('Failed to check in patient: ' + (err.response?.data?.message || err.message));
    }
  };

  return (
    <div style={{ padding: '30px 20px', maxWidth: '1200px', margin: '0 auto' }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px', flexWrap: 'wrap', gap: '16px' }}>
        <div>
          <span className="mn-badge mn-badge-teal" style={{ marginBottom: '6px' }}>Front-Desk Intake & Triage</span>
          <h1 style={{ fontSize: '26px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
            Hospital Front-Desk Reception Portal
          </h1>
          <p style={{ fontSize: '14px', color: '#64748b', margin: 0 }}>
            Patient arrivals, waiting room queue coordination, and emergency walk-in registration.
          </p>
        </div>
        <div>
          <button onClick={loadData} className="mn-btn mn-btn-outline mn-btn-sm">
            <i className="fa-solid fa-rotate-right"></i> Refresh Queue
          </button>
        </div>
      </div>

      {/* Front-Desk Overview Metrics */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '28px' }}>
        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Waiting in Lobby</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#f59e0b', margin: '4px 0' }}>
            {appointments.filter(a => a.status === 'CHECKED_IN').length}
          </div>
          <div style={{ fontSize: '12px', color: '#b45309' }}>Arrival confirmed</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Confirmed Scheduled</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0284c7', margin: '4px 0' }}>
            {appointments.filter(a => a.status === 'CONFIRMED').length}
          </div>
          <div style={{ fontSize: '12px', color: '#0284c7' }}>Expected arrivals today</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Hospital Bed Occupancy</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0d9488', margin: '4px 0' }}>
            84 / 120 Beds
          </div>
          <div style={{ fontSize: '12px', color: '#10b981' }}>70% Census capacity normal</div>
        </div>
      </div>

      {/* Queue & Check-in Table */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', marginBottom: '18px' }}>
          <i className="fa-solid fa-clipboard-user" style={{ color: '#0d9488' }}></i> Patient Arrival & Queue Management
        </h3>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '30px', color: '#94a3b8' }}>
            <i className="fa-solid fa-spinner fa-spin"></i> Loading roster...
          </div>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '13.5px' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #f1f5f9', color: '#64748b', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '0.6px' }}>
                  <th style={{ padding: '10px 12px' }}>Appt #</th>
                  <th style={{ padding: '10px 12px' }}>Patient</th>
                  <th style={{ padding: '10px 12px' }}>Doctor Assigned</th>
                  <th style={{ padding: '10px 12px' }}>Room</th>
                  <th style={{ padding: '10px 12px' }}>Status</th>
                  <th style={{ padding: '10px 12px' }}>Arrival Check-In</th>
                </tr>
              </thead>
              <tbody>
                {appointments.map(apt => (
                  <tr key={apt.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '12px', fontWeight: 700, color: '#0f172a' }}>{apt.appointmentNumber}</td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 700 }}>{apt.patient?.firstName} {apt.patient?.lastName}</div>
                      <div style={{ fontSize: '11px', color: '#64748b' }}>{apt.patient?.patientIdentifier}</div>
                    </td>
                    <td style={{ padding: '12px' }}>
                      <div style={{ fontWeight: 600 }}>{apt.doctor?.user ? `Dr. ${apt.doctor.user.firstName} ${apt.doctor.user.lastName}` : 'Physician'}</div>
                      <div style={{ fontSize: '11px', color: '#64748b' }}>{apt.department?.name}</div>
                    </td>
                    <td style={{ padding: '12px', color: '#64748b' }}>{apt.doctor?.roomNumber || 'Room 101'}</td>
                    <td style={{ padding: '12px' }}>
                      <span className={`mn-badge ${apt.status === 'CHECKED_IN' ? 'mn-badge-warning' : apt.status === 'COMPLETED' ? 'mn-badge-teal' : 'mn-badge-primary'}`}>
                        {apt.status}
                      </span>
                    </td>
                    <td style={{ padding: '12px' }}>
                      {apt.status === 'CONFIRMED' || apt.status === 'SCHEDULED' ? (
                        <button 
                          onClick={() => handleCheckIn(apt.id)}
                          className="mn-btn mn-btn-teal mn-btn-sm" 
                          style={{ fontSize: '11.5px', padding: '5px 10px' }}
                        >
                          <i className="fa-solid fa-person-circle-check"></i> Mark Arrived
                        </button>
                      ) : (
                        <span style={{ fontSize: '12px', color: '#64748b' }}>Checked In</span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}
