# Smart Healthcare Assistance System - Frontend

This directory contains the entire React.js frontend application, preserving the MediNexa clinical design system, styling, tokens, and multi-role dashboards.

## Directory Structure

```
frontend/
├── public/                 # Static assets, MediNexa brand logos
│   └── img/medinexa-logo.svg
├── src/
│   ├── components/         # Reusable UI widgets
│   │   ├── Navbar.jsx
│   │   └── SymptomTriageWidget.jsx
│   ├── views/              # Role-based dashboard and page views
│   │   ├── LandingPage.jsx
│   │   ├── LoginPage.jsx
│   │   ├── PatientDashboard.jsx
│   │   ├── DoctorDashboard.jsx
│   │   ├── ReceptionistDashboard.jsx
│   │   └── AdminDashboard.jsx
│   ├── services/           # Axios API client connecting to API Gateway (port 5000)
│   │   └── api.js
│   ├── medinexa-theme.css  # MediNexa design tokens, slate/teal palette & animations
│   ├── App.jsx             # Main router and state coordinator
│   └── main.jsx
├── index.html
├── package.json
└── vite.config.js
```

---

## Getting Started

1. **Install dependencies**:
   ```bash
   npm install
   ```

2. **Run Development Server**:
   ```bash
   npm run dev
   ```
   Open [http://localhost:3000](http://localhost:3000) in your browser.

3. **Build for Production**:
   ```bash
   npm run build
   ```
