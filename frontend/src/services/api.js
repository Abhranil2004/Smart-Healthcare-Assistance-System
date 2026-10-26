import axios from 'axios';

const API_BASE = import.meta.env.VITE_API_BASE_URL || '';

const client = axios.create({
  baseURL: API_BASE,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Intercept requests to add JWT token if available
client.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const api = {
  // Authentication
  login: async (login, password) => {
    const res = await client.post('/api/auth/login', { login, password });
    return res.data;
  },

  register: async (userData) => {
    const res = await client.post('/api/auth/register', userData);
    return res.data;
  },

  getMe: async () => {
    const res = await client.get('/api/auth/me');
    return res.data;
  },

  // Hospital Clinical Data
  getDepartments: async () => {
    const res = await client.get('/api/hospital/departments');
    return res.data;
  },

  getDoctors: async (specialty = '') => {
    const url = specialty 
      ? `/api/hospital/doctors/by-specialty?specialty=${encodeURIComponent(specialty)}`
      : '/api/hospital/doctors';
    const res = await client.get(url);
    return res.data;
  },

  getAppointments: async (params = {}) => {
    const res = await client.get('/api/hospital/appointments', { params });
    return res.data;
  },

  bookAppointment: async (appointmentData) => {
    const res = await client.post('/api/hospital/appointments', appointmentData);
    return res.data;
  },

  updateAppointmentStatus: async (id, status, cancellationReason = null) => {
    const res = await client.patch(`/api/hospital/appointments/${id}/status`, {
      status,
      cancellationReason
    });
    return res.data;
  },

  getMedicalRecords: async (patientId = null) => {
    const params = patientId ? { patientId } : {};
    const res = await client.get('/api/hospital/medical-records', { params });
    return res.data;
  },

  createMedicalRecord: async (recordData) => {
    const res = await client.post('/api/hospital/medical-records', recordData);
    return res.data;
  },

  getAnalyticsOverview: async () => {
    const res = await client.get('/api/hospital/analytics/overview');
    return res.data;
  },

  // AI & Triage Engine
  triageAndRecommend: async (symptoms, duration) => {
    const res = await client.post('/api/ai/triage-and-recommend', { symptoms, duration });
    return res.data;
  },

  chatAssistant: async (message) => {
    const res = await client.post('/api/ai/chat-assistant', { message });
    return res.data;
  }
};

export default api;
