# =============================================================================
# Smart Healthcare Assistance System - Local Microservices Launcher
# =============================================================================

Write-Host "================================================================" -ForegroundColor Cyan
Write-Host " Starting Smart Healthcare Assistance System Microservices... " -ForegroundColor Cyan
Write-Host "================================================================" -ForegroundColor Cyan

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path

# 1. AI Service (Python FastAPI)
Write-Host "[1/4] Launching FastAPI AI Service on port 8000..." -ForegroundColor Green
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$scriptDir\backend\ai-service'; uvicorn main:app --host 0.0.0.0 --port 8000"

# 2. Hospital Backend (Java Spring Boot)
Write-Host "[2/4] Launching Spring Boot Hospital Backend on port 8081..." -ForegroundColor Green
$mvnCmd = "C:\Program Files\Apache NetBeans\java\maven\bin\mvn.cmd"
if (Test-Path $mvnCmd) {
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$scriptDir\backend\hospital-backend'; & '$mvnCmd' spring-boot:run"
} else {
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$scriptDir\backend\hospital-backend'; mvn spring-boot:run"
}

# 3. API Gateway (Node.js Express)
Write-Host "[3/4] Launching Express API Gateway on port 5000..." -ForegroundColor Green
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$scriptDir\backend\api-gateway'; npm start"

# 4. Frontend (React + Vite)
Write-Host "[4/4] Launching React Frontend on port 3000..." -ForegroundColor Green
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$scriptDir\frontend'; npm run dev -- --port 3000"

Write-Host ""
Write-Host "All 4 microservices launched successfully!" -ForegroundColor Cyan
Write-Host "Frontend URL:      http://localhost:3000" -ForegroundColor Yellow
Write-Host "API Gateway URL:   http://localhost:5000" -ForegroundColor Yellow
Write-Host "Hospital Service:  http://localhost:8081" -ForegroundColor Yellow
Write-Host "FastAPI Service:   http://localhost:8000" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
