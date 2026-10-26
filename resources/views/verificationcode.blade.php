<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Code | Smart Healthcare Assistance System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/medinexa-theme.css') }}">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 50%, #f0f9ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #0f172a;
        }
        .mn-auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 420px;
            padding: 38px 32px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .mn-auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0284c7, #0d9488);
        }
    </style>
</head>
<body>

    <div class="mn-auth-card">
        <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:18px;">
            <div class="mn-brand-logo-icon" style="width:44px;height:44px;">
                <i class="fa-solid fa-hospital-user" style="font-size:20px;"></i>
            </div>
            <div style="text-align:left;">
                <div style="font-size:18px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;">Smart <span style="color:#0d9488;">Healthcare</span></div>
                <div style="font-size:10px;color:#0d9488;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;">Assistance System</div>
            </div>
        </a>

        <h2 style="font-size:20px;font-weight:700;color:#0f172a;margin-bottom:6px;">Verify Identity</h2>
        <p style="font-size:13.5px;color:#64748b;margin-bottom:24px;">
            Please enter the authentication code sent to your registered device for {{ $institute->name ?? 'Smart Healthcare Assistance System' }}.
        </p>

        @if (Session::get('message'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:18px;">
                {{ Session::get('message') }}
            </div>
        @endif

        @if (isset($error) && $error)
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:18px;">
                {{ $error }}
            </div>
        @endif

        <form action="{{ url('users/code_check') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $id }}">

            <div style="margin-bottom:20px;text-align:left;">
                <label for="code" style="display:block;font-size:13px;font-weight:600;color:#1e293b;margin-bottom:6px;">
                    Verification Code
                </label>
                <div style="position:relative;display:flex;align-items:center;">
                    <i class="fa-solid fa-shield-halved" style="position:absolute;left:14px;color:#94a3b8;"></i>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        placeholder="Enter 4-digit code" 
                        style="width:100%;height:46px;padding:10px 14px 10px 40px;font-size:15px;border:1px solid #cbd5e1;border-radius:8px;letter-spacing:2px;font-weight:600;" 
                        required 
                        autofocus>
                </div>
            </div>

            <button type="submit" class="mn-btn mn-btn-teal" style="width:100%;height:46px;font-size:15px;">
                <i class="fa-solid fa-check"></i> Verify & Sign In
            </button>
        </form>

        <div style="margin-top:22px;">
            <a href="{{ url('/login') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>

</body>
</html>
