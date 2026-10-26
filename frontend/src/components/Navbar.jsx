import React from 'react';

export default function Navbar({ currentView, setView, user, onLogout }) {
  return (
    <header className="mn-public-nav">
      <div className="mn-container">
        <div className="mn-nav-inner">
          <div 
            onClick={() => setView('landing')} 
            className="mn-brand-wrapper" 
            style={{ cursor: 'pointer' }}
          >
            <div className="mn-brand-logo-icon">
              <i className="fa-solid fa-hospital-user"></i>
            </div>
            <div className="mn-brand-text">
              <div className="mn-brand-title">Smart <span style={{ color: '#0d9488' }}>Healthcare</span></div>
              <div className="mn-brand-subtitle">Assistance System</div>
            </div>
          </div>

          <ul className="mn-nav-links">
            <li><a href="#home" onClick={() => setView('landing')}>Home</a></li>
            <li><a href="#assistant" onClick={() => setView('landing')}>Healthcare Assistant</a></li>
            <li><a href="#services" onClick={() => setView('landing')}>Services</a></li>
            <li><a href="#how-it-works" onClick={() => setView('landing')}>How It Works</a></li>
            <li><a href="#specialties" onClick={() => setView('landing')}>Specialties</a></li>
            <li><a href="#roles" onClick={() => setView('landing')}>Roles</a></li>
            <li><a href="#faq" onClick={() => setView('landing')}>FAQ</a></li>
          </ul>

          <div className="mn-nav-actions">
            {user ? (
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <button 
                  onClick={() => {
                    const role = user.role;
                    if (role === 'ROLE_DOCTOR') setView('doctor');
                    else if (role === 'ROLE_RECEPTIONIST') setView('receptionist');
                    else if (role === 'ROLE_ADMIN') setView('admin');
                    else setView('patient');
                  }}
                  className="mn-btn mn-btn-outline mn-btn-sm"
                >
                  <i className="fa-solid fa-table-columns"></i> Dashboard
                </button>
                <button 
                  onClick={onLogout}
                  className="mn-btn mn-btn-outline mn-btn-sm" 
                  style={{ color: '#ef4444', borderColor: '#fee2e2' }}
                >
                  <i className="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </button>
              </div>
            ) : (
              <>
                <button 
                  onClick={() => setView('login')} 
                  className="mn-btn mn-btn-outline mn-btn-sm"
                >
                  <i className="fa-solid fa-right-to-bracket"></i> Sign In
                </button>
                <a 
                  href="#assistant" 
                  onClick={() => setView('landing')} 
                  className="mn-btn mn-btn-teal mn-btn-sm"
                >
                  <i className="fa-solid fa-wand-magic-sparkles"></i> AI Assistant
                </a>
              </>
            )}
          </div>
        </div>
      </div>
    </header>
  );
}
