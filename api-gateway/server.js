/**
 * Smart Healthcare Assistance System - Node.js Express API Gateway
 * Coordinates communication between React Frontend, Spring Boot Backend, and FastAPI AI Service.
 */

import express from 'express';
import cors from 'cors';
import morgan from 'morgan';
import axios from 'axios';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 5000;
const HOSPITAL_BACKEND_URL = process.env.HOSPITAL_BACKEND_URL || 'http://localhost:8081';
const AI_SERVICE_URL = process.env.AI_SERVICE_URL || 'http://localhost:8000';

app.use(cors());
app.use(express.json());
app.use(morgan('dev'));

// -----------------------------------------------------------------------------
// Health Check
// -----------------------------------------------------------------------------
app.get('/health', (req, res) => {
    res.json({
        status: 'UP',
        gateway: 'Smart Healthcare Assistance API Gateway',
        targets: {
            hospitalBackend: HOSPITAL_BACKEND_URL,
            aiService: AI_SERVICE_URL
        },
        timestamp: new Date().toISOString()
    });
});

// -----------------------------------------------------------------------------
// AI Assistant Composite Orchestration: /api/ai/triage-and-recommend
// Combines:
// 1. FastAPI: NLP symptom triage & rule engine check
// 2. Spring Boot: Real doctor database lookup matching recommended specialty
// -----------------------------------------------------------------------------
app.post('/api/ai/triage-and-recommend', async (req, res) => {
    try {
        const { symptoms, duration } = req.body;
        if (!symptoms || !symptoms.trim()) {
            return res.status(422).json({ error: 'Symptoms text is required.' });
        }

        // 1. Query FastAPI AI service for triage
        let aiResult;
        try {
            const aiRes = await axios.post(`${AI_SERVICE_URL}/analyze-symptoms`, {
                symptoms,
                duration: duration || 'Not specified'
            });
            aiResult = aiRes.data;
        } catch (aiErr) {
            console.error('[Gateway] AI Service error, utilizing safety fallback:', aiErr.message);
            aiResult = {
                success: true,
                is_emergency: false,
                urgency_level: 'Routine',
                extracted_symptoms: ['symptoms reported'],
                duration: duration || 'Not specified',
                recommended_specialty: 'General Medicine',
                clinical_rationale: 'Consultation advised via General Medicine triage baseline.',
                actionable_advice: 'Consult with an on-duty hospital physician for comprehensive evaluation.',
                disclaimer: 'Preliminary AI triage guidance only.'
            };
        }

        // 2. Query Spring Boot for specialist doctors in that recommended specialty
        let recommendedDoctors = [];
        try {
            const docRes = await axios.get(`${HOSPITAL_BACKEND_URL}/api/hospital/doctors/by-specialty`, {
                params: { specialty: aiResult.recommended_specialty }
            });
            recommendedDoctors = docRes.data || [];
            
            // If none found for specialty, query general doctors
            if (recommendedDoctors.length === 0) {
                const allDocs = await axios.get(`${HOSPITAL_BACKEND_URL}/api/hospital/doctors`);
                recommendedDoctors = allDocs.data || [];
            }
        } catch (backendErr) {
            console.warn('[Gateway] Could not fetch doctors from backend:', backendErr.message);
        }

        // Format combined response matching frontend expectations
        const formattedDoctors = recommendedDoctors.slice(0, 4).map(doc => ({
            id: doc.id,
            name: doc.user ? `Dr. ${doc.user.firstName} ${doc.user.lastName}` : (doc.name || 'Staff Doctor'),
            specialization: doc.specialization || doc.department?.name || 'General Medicine',
            department: doc.department?.name || 'General Medicine',
            qualification: doc.qualification || 'MBBS, MD',
            roomNumber: doc.roomNumber || 'Room 101',
            consultationFee: doc.consultationFee || 50.00,
            availableDays: doc.availableDays || 'Mon-Fri'
        }));

        res.json({
            ...aiResult,
            recommended_doctors: formattedDoctors
        });
    } catch (err) {
        console.error('[Gateway] /api/ai/triage-and-recommend failed:', err.message);
        res.status(500).json({ error: 'Failed to process triage recommendation.' });
    }
});

// -----------------------------------------------------------------------------
// Direct AI Endpoints
// -----------------------------------------------------------------------------
app.post('/api/ai/analyze-symptoms', async (req, res) => {
    try {
        const response = await axios.post(`${AI_SERVICE_URL}/analyze-symptoms`, req.body);
        res.json(response.data);
    } catch (err) {
        const status = err.response?.status || 500;
        res.status(status).json(err.response?.data || { error: 'AI Service unreachable' });
    }
});

app.post('/api/ai/chat-assistant', async (req, res) => {
    try {
        const response = await axios.post(`${AI_SERVICE_URL}/chat-assistant`, req.body);
        res.json(response.data);
    } catch (err) {
        const status = err.response?.status || 500;
        res.status(status).json(err.response?.data || { error: 'AI Service unreachable' });
    }
});

// -----------------------------------------------------------------------------
// Forwarding: Spring Boot Hospital Endpoints & Auth
// -----------------------------------------------------------------------------
app.all(['/api/auth/*', '/api/hospital/*'], async (req, res) => {
    try {
        const targetUrl = `${HOSPITAL_BACKEND_URL}${req.originalUrl}`;
        const headers = { ...req.headers };
        delete headers.host;

        const response = await axios({
            method: req.method,
            url: targetUrl,
            data: req.body,
            params: req.query,
            headers: headers,
            validateStatus: () => true // Forward all status codes cleanly
        });

        res.status(response.status).json(response.data);
    } catch (err) {
        console.error('[Gateway Forwarding Error]:', err.message);
        res.status(502).json({
            error: 'Hospital Management Backend is currently unreachable',
            details: err.message
        });
    }
});

// Export app for testing and start server if run directly
export default app;

import { fileURLToPath } from 'url';
const isMain = process.argv[1] === fileURLToPath(import.meta.url);

if (isMain) {
    app.listen(PORT, () => {
        console.log(`[API Gateway] Running on port ${PORT}`);
        console.log(`[API Gateway] Forwarding hospital requests to: ${HOSPITAL_BACKEND_URL}`);
        console.log(`[API Gateway] Forwarding AI requests to: ${AI_SERVICE_URL}`);
    });
}
