<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Alat Pembelajaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 1100px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .illustration-section {
            flex: 1;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .illustration {
            width: 100%;
            max-width: 350px;
            position: relative;
            z-index: 1;
        }

        .form-section {
            flex: 1;
            padding: 60px 50px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #1e293b;
            font-size: 28px;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 18px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            background: white;
            outline: none;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        input::placeholder {
            color: #94a3b8;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748b;
            background: none;
            border: none;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #334155;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #1e293b;
        }

        .remember-me label {
            font-size: 14px;
            color: #1e293b;
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            font-size: 14px;
            color: #1e293b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #0f172a;
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3);
        }

        .login-button:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 41, 59, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            background: #64748b;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            border-left: 4px solid #dc2626;
            animation: slideIn 0.3s ease;
        }

        .error-message.show {
            display: block;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            border-left: 4px solid #10b981;
            animation: slideIn 0.3s ease;
        }

        .success-message.show {
            display: block;
        }

        .icon {
            width: 20px;
            height: 20px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .illustration-section {
                padding: 40px 20px;
                min-height: 250px;
            }

            .form-section {
                padding: 40px 30px;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 30px;
            }

            .remember-forgot {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Illustration Section -->
        <div class="illustration-section">
            <div class="illustration">
                <svg viewBox="0 0 320 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Shadow/Base -->
                    <ellipse cx="160" cy="250" rx="90" ry="15" fill="rgba(0,0,0,0.1)"/>
                    
                    <!-- Laptop Base/Stand -->
                    <path d="M 60 200 L 50 240 Q 50 245 55 245 L 265 245 Q 270 245 270 240 L 260 200 Z" fill="rgba(255,255,255,0.15)" stroke="white" stroke-width="1.5"/>
                    
                    <!-- Laptop Body Bottom -->
                    <rect x="60" y="200" width="200" height="8" rx="2" fill="rgba(200,200,200,0.3)" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
                    
                    <!-- Screen Bezel -->
                    <rect x="50" y="50" width="220" height="150" rx="8" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                    
                    <!-- Screen Glass -->
                    <rect x="58" y="58" width="204" height="134" rx="6" fill="rgba(255,255,255,0.95)" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                    
                    <!-- Screen Content - Header Bar -->
                    <rect x="65" y="68" width="190" height="8" rx="2" fill="rgba(102, 126, 234, 0.3)"/>
                    
                    <!-- Screen Content - Text Lines -->
                    <g opacity="0.6">
                        <rect x="65" y="85" width="170" height="4" rx="2" fill="rgba(102, 126, 234, 0.2)"/>
                        <rect x="65" y="95" width="180" height="4" rx="2" fill="rgba(102, 126, 234, 0.15)"/>
                        <rect x="65" y="105" width="160" height="4" rx="2" fill="rgba(102, 126, 234, 0.15)"/>
                        <rect x="65" y="115" width="175" height="4" rx="2" fill="rgba(102, 126, 234, 0.2)"/>
                        <rect x="65" y="125" width="155" height="4" rx="2" fill="rgba(102, 126, 234, 0.15)"/>
                        <rect x="65" y="135" width="165" height="4" rx="2" fill="rgba(102, 126, 234, 0.15)"/>
                        <rect x="65" y="145" width="140" height="4" rx="2" fill="rgba(102, 126, 234, 0.1)"/>
                        <rect x="65" y="155" width="150" height="4" rx="2" fill="rgba(102, 126, 234, 0.1)"/>
                        <rect x="65" y="165" width="130" height="4" rx="2" fill="rgba(102, 126, 234, 0.1)"/>
                    </g>
                    
                    <!-- Screen Reflection/Shine -->
                    <ellipse cx="100" cy="85" rx="30" ry="20" fill="rgba(255,255,255,0.3)"/>
                    <path d="M 220 75 Q 230 80 220 95" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                    
                    <!-- Keyboard Area -->
                    <rect x="60" y="200" width="200" height="6" rx="2" fill="rgba(100,100,100,0.2)" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                    
                    <!-- Keyboard Keys Pattern -->
                    <g opacity="0.4">
                        <rect x="68" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="76" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="84" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="92" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="100" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="108" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="116" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="124" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="132" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="140" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="148" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="156" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="164" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="172" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="180" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="188" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="196" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="204" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="212" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="220" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="228" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="236" y="204" width="3" height="2" rx="0.5" fill="rgba(255,255,255,0.6)"/>
                    </g>
                    
                    <!-- Touchpad -->
                    <rect x="110" y="211" width="100" height="22" rx="3" fill="rgba(100,100,100,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="1"/>
                    
                    <!-- Touchpad Border -->
                    <rect x="112" y="213" width="96" height="18" rx="2" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                    
                    <!-- Decorative Elements - Floating Lights -->
                    <circle cx="280" cy="90" r="3" fill="rgba(255,255,255,0.4)"/>
                    <circle cx="40" cy="130" r="2" fill="rgba(255,255,255,0.3)"/>
                    <circle cx="290" cy="160" r="2.5" fill="rgba(255,255,255,0.35)"/>
                    
                    <!-- Power Indicator -->
                    <circle cx="270" cy="65" r="2.5" fill="#22c55e" opacity="0.8"/>
                    <circle cx="270" cy="65" r="1.5" fill="#86efac"/>
                    
                    <!-- Glow under screen -->
                    <ellipse cx="160" cy="195" rx="100" ry="20" fill="rgba(255,255,255,0.05)"/>
                </svg>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-wrapper">
                <h2>Masuk Untuk Lanjut</h2>
                
                <div id="errorMessage" class="error-message"></div>
                <div id="successMessage" class="success-message"></div>

                <!-- Tampilkan error dari Laravel -->
                @if ($errors->any())
                    <div class="error-message show">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                placeholder="Username" 
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                            >
                        </div>
                        @error('username')
                            <span style="color: #991b1b; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Password" 
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg id="eyeIcon" class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span style="color: #991b1b; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="login-button">Login</button>

                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>
</html>