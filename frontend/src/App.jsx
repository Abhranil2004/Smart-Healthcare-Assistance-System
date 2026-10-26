import React, { useState, useEffect } from 'react';
import Navbar from './components/Navbar';
import LandingPage from './views/LandingPage';
import LoginPage from './views/LoginPage';
import PatientDashboard from './views/PatientDashboard';
import DoctorDashboard from './views/DoctorDashboard';
import ReceptionistDashboard from './views/ReceptionistDashboard';
import AdminDashboard from './views/AdminDashboard';
import './medinexa-theme.css';

export default function App() {
  const [currentView, setCurrentView] = useState('landing');
  const [user, setUser] = useState(null);
  const [initialBookingData, setInitialBookingData] = useState(null);

  useEffect(() => {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        const parsed = JSON.parse(savedUser);
        setUser(parsed);
      } catch (e) {
        localStorage.removeItem('user');
      }
    }
  }, []);

  const handleLoginSuccess = (userData) => {
    setUser(userData);
    const role = userData.role;
    if (role === 'ROLE_DOCTOR') {
      setCurrentView('doctor');
    } else if (role === 'ROLE_RECEPTIONIST') {
      setCurrentView('receptionist');
    } else if (role === 'ROLE_ADMIN') {
      setCurrentView('admin');
    } else {
      setCurrentView('patient');
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    setUser(null);
    setCurrentView('landing');
  };

  const handleSelectDoctorForBooking = (doctor, triageResult) => {
    setInitialBookingData({ doctor, triage: triageResult });
    if (!user) {
      setCurrentView('login');
    } else {
      setCurrentView('patient');
    }
  };

  return (
    <div className="medinexa-theme" style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
      {currentView !== 'login' && (
        <Navbar 
          currentView={currentView} 
          setView={setCurrentView} 
          user={user} 
          onLogout={handleLogout} 
        />
      )}

      <main style={{ flex: 1 }}>
        {currentView === 'landing' && (
          <LandingPage 
            setView={setCurrentView} 
            onSelectDoctorForBooking={handleSelectDoctorForBooking} 
          />
        )}

        {currentView === 'login' && (
          <LoginPage 
            onLoginSuccess={handleLoginSuccess} 
            setView={setCurrentView} 
          />
        )}

        {currentView === 'patient' && (
          <PatientDashboard 
            user={user} 
            setView={setCurrentView} 
            initialBookingData={initialBookingData} 
          />
        )}

        {currentView === 'doctor' && (
          <DoctorDashboard 
            user={user} 
            setView={setCurrentView} 
          />
        )}

        {currentView === 'receptionist' && (
          <ReceptionistDashboard 
            user={user} 
            setView={setCurrentView} 
          />
        )}

        {currentView === 'admin' && (
          <AdminDashboard 
            user={user} 
            setView={setCurrentView} 
          />
        )}
      </main>
    </div>
  );
}
