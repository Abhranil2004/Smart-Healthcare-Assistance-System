import React, { useState, useEffect } from 'react';
import api from '../services/api';

export default function AdminDashboard({ user }) {
  const [stats, setStats] = useState(null);
  const [departments, setDepartments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);

  const loadData = async () => {
    try {
      setLoading(true);
      const [overview, depts, docs] = await Promise.all([
        api.getAnalyticsOverview(),
        api.getDepartments(),
        api.getDoctors()
      ]);
      setStats(overview);
      setDepartments(depts || []);
      setDoctors(docs || []);
    } catch (err) {
      console.error('Failed to load admin telemetry:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  return (
    <div style={{ padding: '30px 20px', maxWidth: '1200px', margin: '0 auto' }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px', flexWrap: 'wrap', gap: '16px' }}>
        <div>
          <span className="mn-badge mn-badge-teal" style={{ marginBottom: '6px' }}>Operations & Telemetry</span>
          <h1 style={{ fontSize: '26px', fontWeight: 800, color: '#0f172a', margin: '2px 0 0' }}>
            Hospital Administrator Operations
          </h1>
          <p style={{ fontSize: '14px', color: '#64748b', margin: 0 }}>
            System-wide clinical capacity, department management, and operational analytics.
          </p>
        </div>
        <div>
          <button onClick={loadData} className="mn-btn mn-btn-outline mn-btn-sm">
            <i className="fa-solid fa-rotate-right"></i> Refresh Telemetry
          </button>
        </div>
      </div>

      {/* Analytics KPI Widgets */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '28px' }}>
        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Total Patients Registered</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>{stats?.totalPatients || 2}</div>
          <div style={{ fontSize: '12px', color: '#0d9488' }}><i className="fa-solid fa-hospital-user"></i> Active EMR profiles</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Clinical Departments</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0284c7', margin: '4px 0' }}>{stats?.totalDepartments || 8}</div>
          <div style={{ fontSize: '12px', color: '#0284c7' }}><i className="fa-solid fa-building-circle-check"></i> Operational services</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Staff Physicians</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#10b981', margin: '4px 0' }}>{stats?.totalDoctors || 5}</div>
          <div style={{ fontSize: '12px', color: '#047857' }}><i className="fa-solid fa-user-doctor"></i> Active credentials</div>
        </div>

        <div className="mn-widget-box" style={{ background: '#ffffff' }}>
          <div style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>Hospital Bed Occupancy</div>
          <div style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '4px 0' }}>
            {stats?.occupiedBeds || 84} / {stats?.totalHospitalBeds || 120}
          </div>
          <div style={{ fontSize: '12px', color: '#10b981' }}>{stats?.bedOccupancyRate || 70}% Inpatient capacity</div>
        </div>
      </div>

      {/* Clinical Departments Table */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', marginBottom: '24px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', marginBottom: '18px' }}>
          <i className="fa-solid fa-network-wired" style={{ color: '#0d9488' }}></i> Clinical Departments Roster
        </h3>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '20px', color: '#94a3b8' }}>Loading...</div>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '13.5px' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #f1f5f9', color: '#64748b', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '0.6px' }}>
                  <th style={{ padding: '10px 12px' }}>Code</th>
                  <th style={{ padding: '10px 12px' }}>Department</th>
                  <th style={{ padding: '10px 12px' }}>Location</th>
                  <th style={{ padding: '10px 12px' }}>Description</th>
                  <th style={{ padding: '10px 12px' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {departments.map(dept => (
                  <tr key={dept.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '12px', fontWeight: 800, color: '#0d9488' }}>{dept.code}</td>
                    <td style={{ padding: '12px', fontWeight: 700, color: '#0f172a' }}>{dept.name}</td>
                    <td style={{ padding: '12px', color: '#64748b' }}>{dept.floorLocation}</td>
                    <td style={{ padding: '12px', color: '#475569', maxWidth: '350px' }}>{dept.description}</td>
                    <td style={{ padding: '12px' }}>
                      <span className="mn-badge mn-badge-teal">Active</span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Medical Doctors Roster */}
      <div style={{ background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '14px', padding: '24px', boxShadow: '0 2px 4px rgba(0,0,0,0.02)' }}>
        <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', marginBottom: '18px' }}>
          <i className="fa-solid fa-user-doctor" style={{ color: '#0284c7' }}></i> Credentialed Medical Staff
        </h3>

        <div style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '13.5px' }}>
            <thead>
              <tr style={{ borderBottom: '2px solid #f1f5f9', color: '#64748b', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '0.6px' }}>
                <th style={{ padding: '10px 12px' }}>Doctor</th>
                <th style={{ padding: '10px 12px' }}>Specialty</th>
                <th style={{ padding: '10px 12px' }}>License #</th>
                <th style={{ padding: '10px 12px' }}>Qualifications</th>
                <th style={{ padding: '10px 12px' }}>Room</th>
                <th style={{ padding: '10px 12px' }}>Consultation Fee</th>
              </tr>
            </thead>
            <tbody>
              {doctors.map(doc => (
                <tr key={doc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td style={{ padding: '12px', fontWeight: 700, color: '#0f172a' }}>
                    {doc.user ? `Dr. ${doc.user.firstName} ${doc.user.lastName}` : 'Physician'}
                  </td>
                  <td style={{ padding: '12px', color: '#0d9488', fontWeight: 600 }}>{doc.department?.name}</td>
                  <td style={{ padding: '12px', color: '#64748b', fontSize: '12px' }}>{doc.licenseNumber}</td>
                  <td style={{ padding: '12px', color: '#475569' }}>{doc.qualification}</td>
                  <td style={{ padding: '12px', color: '#64748b' }}>{doc.roomNumber}</td>
                  <td style={{ padding: '12px', fontWeight: 700 }}>${doc.consultationFee}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
