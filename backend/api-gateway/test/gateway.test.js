import { test, describe } from 'node:test';
import assert from 'node:assert';
import http from 'node:http';
import app from '../server.js';

describe('API Gateway Tests', () => {
    let server;
    let baseUrl;

    test('setup server', (t, done) => {
        server = app.listen(0, () => {
            const port = server.address().port;
            baseUrl = `http://localhost:${port}`;
            done();
        });
    });

    test('GET /health returns gateway status', async () => {
        const res = await fetch(`${baseUrl}/health`);
        assert.strictEqual(res.status, 200);
        const data = await res.json();
        assert.strictEqual(data.status, 'UP');
        assert.ok(data.gateway.includes('Smart Healthcare Assistance'));
    });

    test('POST /api/ai/triage-and-recommend validates required symptoms', async () => {
        const res = await fetch(`${baseUrl}/api/ai/triage-and-recommend`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ symptoms: '   ' })
        });
        assert.strictEqual(res.status, 422);
    });

    test('teardown server', (t, done) => {
        server.close(done);
    });
});
