import React, { useState } from 'react';
import api from '../services/api';

export default function LoginPage({ onLoginSuccess, setView }) {
  const [loginInput, setLoginInput] = useState('admin');
  const [password, setPassword] = useState('123456');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [isRegistering, setIsRegistering] = useState(false);

  // Registration state
  const [regUsername, setRegUsername] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regFirstName, setRegFirstName] = useState('');
  const [regLastName, setRegLastName] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      if (isRegistering) {
        const data = await api.register({
          username: regUsername,
          email: regEmail,
          password: regPassword,
          firstName: regFirstName,
          lastName: regLastName,
          role: 'ROLE_PATIENT'
        });
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data));
        onLoginSuccess(data);
      } else {
        const data = await api.login(loginInput, password);
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data));
        onLoginSuccess(data);
      }
    } catch (err) {
      console.error('Auth error:', err);
      setError(err.response?.data?.message || 'Authentication failed. Please check your credentials.');
    } finally {
      setLoading(false);
    }
  };

  const setDemoCredentials = (u, p) => {
    setIsRegistering(false);
    setLoginInput(u);
    setPassword(p);
  };

  return (
    <div style={{
      background: 'linear-gradient(135deg, #f0fdfa 0%, #f8fafc 50%, #f0f9ff 100%)',
      minHeight: '100vh',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      padding: '24px 16px'
    }}>
      <div className="mn-login-card">
        {/* Logo and Brand */}
        <div className="mn-login-header">
          <div 
            onClick={() => setView('landing')} 
            style={{ display: 'inline-flex', alignItems: 'center', gap: '10px', cursor: 'pointer', marginBottom: '14px' }}
          >
            <div className="mn-brand-logo-icon" style={{ width: '44px', height: '44px' }}>
              <i className="fa-solid fa-hospital-user" style={{ fontSize: '20px' }}></i>
            </div>
            <div style={{ textAlign: 'left' }}>
              <div style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', letterSpacing: '-0.5px' }}>
                Smart <span style={{ color: '#0d9488' }}>Healthcare</span>
              </div>
              <div style={{ fontSize: '10.5px', color: '#0d9488', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.8px' }}>
                Assistance System
              </div>
            </div>
          </div>
          <h2 style={{ fontSize: '20px', fontWeight: 700, color: '#0f172a', marginBottom: '4px' }}>
            {isRegistering ? 'Create your patient account' : 'Sign in to your account'}
          </h2>
          <p style={{ fontSize: '13.5px', color: '#64748b' }}>
            Welcome to Smart Healthcare Assistance System
          </p>
        </div>

        {error && (
          <div className="mn-alert mn-alert-danger" style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <i className="fa-solid fa-circle-exclamation"></i>
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          {isRegistering ? (
            <>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '10px' }}>
                <div className="mn-form-group">
                  <label className="mn-form-label">First Name</label>
                  <input 
                    type="text" 
                    className="mn-form-input" 
                    value={regFirstName}
                    onChange={(e) => setRegFirstName(e.target.value)}
                    required 
                  />
                </div>
                <div className="mn-form-group">
                  <label className="mn-form-label">Last Name</label>
                  <input 
                    type="text" 
                    className="mn-form-input" 
                    value={regLastName}
                    onChange={(e) => setRegLastName(e.target.value)}
                    required 
                  />
                </div>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Username</label>
                <div className="mn-input-wrapper">
                  <i className="fa-regular fa-user mn-input-icon"></i>
                  <input 
                    type="text" 
                    className="mn-form-input" 
                    placeholder="Choose a username"
                    value={regUsername}
                    onChange={(e) => setRegUsername(e.target.value)}
                    required 
                  />
                </div>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Email Address</label>
                <div className="mn-input-wrapper">
                  <i className="fa-regular fa-envelope mn-input-icon"></i>
                  <input 
                    type="email" 
                    className="mn-form-input" 
                    placeholder="name@example.com"
                    value={regEmail}
                    onChange={(e) => setRegEmail(e.target.value)}
                    required 
                  />
                </div>
              </div>

              <div className="mn-form-group">
                <label className="mn-form-label">Password</label>
                <div className="mn-input-wrapper">
                  <i className="fa-solid fa-lock mn-input-icon"></i>
                  <input 
                    type="password" 
                    className="mn-form-input" 
                    placeholder="••••••••"
                    value={regPassword}
                    onChange={(e) => setRegPassword(e.target.value)}
                    required 
                  />
                </div>
              </div>
            </>
          ) : (
            <>
              <div className="mn-form-group">
                <label className="mn-form-label" htmlFor="login">Username or Email</label>
                <div className="mn-input-wrapper">
                  <i className="fa-regular fa-user mn-input-icon"></i>
                  <input 
                    type="text" 
                    className="mn-form-input" 
                    id="login" 
                    placeholder="Enter your username or email"
                    value={loginInput}
                    onChange={(e) => setLoginInput(e.target.value)}
                    required 
                    autoFocus
                  />
                </div>
              </div>

              <div className="mn-form-group">
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' }}>
                  <label className="mn-form-label" htmlFor="password" style={{ marginBottom: 0 }}>Password</label>
                </div>
                <div className="mn-input-wrapper">
                  <i className="fa-solid fa-lock mn-input-icon"></i>
                  <input 
                    type="password" 
                    className="mn-form-input" 
                    id="password" 
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required 
                  />
                </div>
              </div>
            </>
          )}

          <button 
            type="submit" 
            className="mn-btn mn-btn-teal" 
            style={{ width: '100%', height: '46px', fontSize: '15px', marginTop: '6px' }}
            disabled={loading}
          >
            {loading ? (
              <span><i className="fa-solid fa-spinner fa-spin"></i> Authenticating...</span>
            ) : (
              <span>
                <i className="fa-solid fa-arrow-right-to-bracket"></i> {isRegistering ? 'Create Patient Account' : 'Sign In to Smart Healthcare Assistance System'}
              </span>
            )}
          </button>
        </form>

        <div style={{ textAlign: 'center', marginTop: '16px', fontSize: '13.5px', color: '#64748b' }}>
          {isRegistering ? (
            <span>Already have an account? <button onClick={() => setIsRegistering(false)} style={{ color: '#0d9488', fontWeight: 700, border: 'none', background: 'none', cursor: 'pointer' }}>Sign In</button></span>
          ) : (
            <span>New patient? <button onClick={() => setIsRegistering(true)} style={{ color: '#0d9488', fontWeight: 700, border: 'none', background: 'none', cursor: 'pointer' }}>Create Account</button></span>
          )}
        </div>

        {/* Demonstration Quick Reference */}
        <div className="mn-credential-hint">
          <div style={{ fontWeight: 700, color: '#0f172a', marginBottom: '6px', display: 'flex', alignItems: 'center', gap: '6px' }}>
            <i className="fa-solid fa-key" style={{ color: '#0d9488' }}></i> Demonstration Role Credentials
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '6px', fontSize: '12px', color: '#475569' }}>
            <div 
              style={{ cursor: 'pointer', padding: '4px 6px', background: '#f8fafc', borderRadius: '4px', border: '1px solid #e2e8f0' }}
              onClick={() => setDemoCredentials('admin', '123456')}
            >
              👑 <strong>admin</strong> (Operations)
            </div>
            <div 
              style={{ cursor: 'pointer', padding: '4px 6px', background: '#f8fafc', borderRadius: '4px', border: '1px solid #e2e8f0' }}
              onClick={() => setDemoCredentials('dr.sarah', '123456')}
            >
              🩺 <strong>dr.sarah</strong> (Doctor)
            </div>
            <div 
              style={{ cursor: 'pointer', padding: '4px 6px', background: '#f8fafc', borderRadius: '4px', border: '1px solid #e2e8f0' }}
              onClick={() => setDemoCredentials('receptionist', '123456')}
            >
              📋 <strong>receptionist</strong> (Front Desk)
            </div>
            <div 
              style={{ cursor: 'pointer', padding: '4px 6px', background: '#f8fafc', borderRadius: '4px', border: '1px solid #e2e8f0' }}
              onClick={() => setDemoCredentials('patient.john', '123456')}
            >
              🧑 <strong>patient.john</strong> (Patient)
            </div>
          </div>
          <div style={{ marginTop: '6px', fontSize: '11px', color: '#94a3b8', textAlign: 'center' }}>
            All passwords default to: <strong>123456</strong>. Click any role to auto-fill.
          </div>
        </div>

        <div style={{ textAlign: 'center', marginTop: '16px' }}>
          <button 
            onClick={() => setView('landing')} 
            style={{ background: 'none', border: 'none', color: '#64748b', fontSize: '13px', cursor: 'pointer' }}
          >
            <i className="fa-solid fa-arrow-left"></i> Return to Homepage
          </button>
        </div>
      </div>
    </div>
  );
}
