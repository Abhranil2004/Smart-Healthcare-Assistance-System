<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Smart Healthcare Assistance System</title>
    <meta name="description" content="Sign in to your Smart Healthcare Assistance System account.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/medinexa-theme.css') }}">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 50%, #f0f9ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #0f172a;
        }
        .mn-login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 440px;
            padding: 38px 32px;
            position: relative;
            overflow: hidden;
        }
        .mn-login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0284c7, #0d9488);
        }
        .mn-login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .mn-form-group {
            margin-bottom: 20px;
        }
        .mn-form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .mn-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .mn-input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 15px;
        }
        .mn-form-input {
            width: 100%;
            height: 46px;
            padding: 10px 14px 10px 40px;
            font-size: 14.5px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }
        .mn-form-input:focus {
            outline: none;
            border-color: #0d9488;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }
        .mn-alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mn-alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .mn-alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        .mn-credential-hint {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            margin-top: 24px;
            font-size: 12.5px;
            color: #475569;
        }
    </style>
</head>
<body>

    <div class="mn-login-card">
        <!-- Logo and Brand -->
        <div class="mn-login-header">
            <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:14px;">
                <div class="mn-brand-logo-icon" style="width:44px;height:44px;">
                    <i class="fa-solid fa-hospital-user" style="font-size:20px;"></i>
                </div>
                <div style="text-align:left;">
                    <div style="font-size:18px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;">Smart <span style="color:#0d9488;">Healthcare</span></div>
                    <div style="font-size:10.5px;color:#0d9488;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;">Assistance System</div>
                </div>
            </a>
            <h2 style="font-size:20px;font-weight:700;color:#0f172a;margin-bottom:4px;">Sign in to your account</h2>
            <p style="font-size:13.5px;color:#64748b;">
                Welcome to {{ $institute->name ?? 'Smart Healthcare Assistance System' }}
            </p>
        </div>

        @if (Session::get('message'))
            <div class="mn-alert mn-alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ Session::get('message') }}</span>
            </div>
        @endif

        @if (isset($error) && $error)
            <div class="mn-alert mn-alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $error }}</span>
            </div>
        @endif

        <!-- Preserved Form Submission to users/login -->
        <form action="{{ url('users/login') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="mn-form-group">
                <label class="mn-form-label" for="login">Username or Email</label>
                <div class="mn-input-wrapper">
                    <i class="fa-regular fa-user mn-input-icon"></i>
                    <input 
                        type="text" 
                        class="mn-form-input" 
                        id="login" 
                        name="login" 
                        placeholder="Enter your username" 
                        value="{{ old('login') }}" 
                        required 
                        autofocus>
                </div>
            </div>

            <div class="mn-form-group">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <label class="mn-form-label" for="password" style="margin-bottom:0;">Password</label>
                </div>
                <div class="mn-input-wrapper">
                    <i class="fa-solid fa-lock mn-input-icon"></i>
                    <input 
                        type="password" 
                        class="mn-form-input" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required>
                </div>
            </div>

            <button type="submit" class="mn-btn mn-btn-teal" style="width:100%;height:46px;font-size:15px;margin-top:6px;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Smart Healthcare Assistance System
            </button>
        </form>

        <!-- Evaluation Quick Reference -->
        <div class="mn-credential-hint">
            <div style="font-weight:700;color:#0f172a;margin-bottom:4px;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-key" style="color:#0d9488;"></i> Demonstration Credentials
            </div>
            <div>Default Admin: <strong>admin</strong> / Password: <strong>123456</strong></div>
            <div style="font-size:11.5px;color:#64748b;margin-top:4px;">
                Roles supported: Administrator, Doctor, Receptionist, Patient.
            </div>
        </div>

        <div style="text-align:center;margin-top:22px;">
            <a href="{{ url('/') }}" style="font-size:13px;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-arrow-left"></i> Return to Homepage
            </a>
        </div>
    </div>

</body>
</html>
