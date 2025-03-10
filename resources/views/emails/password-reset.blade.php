<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Container styles */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1a1c2e 0%, #2a2c3e 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
        }

        /* Header styles */
        .email-header {
            background: linear-gradient(45deg, #2d1b0c, #3d2b1c);
            padding: 40px 20px;
            text-align: center;
            border-bottom: 3px solid #ffd700;
            position: relative;
        }

        .logo-container {
            margin-bottom: 20px;
            position: relative;
        }

        .logo {
            width: 150px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .header-title {
            color: #ffd700;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Alert icon styles */
        .alert-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: rgba(255, 215, 0, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-icon::before {
            content: "!";
            color: #ffd700;
            font-size: 24px;
            font-weight: bold;
        }

        /* Content styles */
        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .message {
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        /* Verification code styles */
        .code-container {
            background: linear-gradient(135deg, #2d1b0c 0%, #3d2b1c 100%);
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #ffd700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
            position: relative;
        }

        .verification-code {
            font-family: 'Courier New', monospace;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #ffd700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            animation: glow 2s infinite;
        }

        /* Warning box styles */
        .warning-box {
            background: #fff3cd;
            border-left: 5px solid #ff9800;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .warning-title {
            color: #ff9800;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .warning-title::before {
            content: "⚠️";
            margin-right: 10px;
        }

        .warning-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .warning-list li {
            color: #856404;
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .warning-list li::before {
            content: "•";
            color: #ff9800;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Security tips styles */
        .security-tips {
            background: #e8f5e9;
            border-left: 5px solid #4caf50;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .tips-title {
            color: #4caf50;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .tips-title::before {
            content: "🔒";
            margin-right: 10px;
        }

        .tips-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .tips-list li {
            color: #2e7d32;
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .tips-list li::before {
            content: "✓";
            color: #4caf50;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Footer styles */
        .email-footer {
            background: linear-gradient(45deg, #2d1b0c, #3d2b1c);
            padding: 30px;
            text-align: center;
            border-top: 3px solid #ffd700;
        }

        .footer-text {
            color: #cbd5e0;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #ffd700;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-link:hover {
            color: #fff;
        }

        .support-text {
            color: #cbd5e0;
            font-size: 14px;
            margin-top: 20px;
        }

        .support-link {
            color: #ffd700;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .support-link:hover {
            color: #fff;
        }

        /* Animations */
        @keyframes glow {
            0%, 100% {
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            }
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header-title {
                font-size: 24px;
            }

            .email-content {
                padding: 30px 20px;
            }

            .verification-code {
                font-size: 28px;
                letter-spacing: 6px;
            }

            .alert-icon {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="logo-container">
                <img src="{{ asset('frontend/images/z9dlogo.png') }}" alt="{{ config('app.name') }} Logo" class="logo">
            </div>
            <h1 class="header-title">Đặt Lại Mật Khẩu</h1>
            <div class="alert-icon"></div>
        </div>

        <div class="email-content">
            <p class="greeting">Xin chào, {{ $user->userid }}!</p>
            
            <p class="message">
                Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại {{ config('app.name') }}. 
                Để tiếp tục quá trình này, vui lòng sử dụng mã xác nhận dưới đây:
            </p>

            <div class="code-container">
                <div class="verification-code">{{ $code }}</div>
            </div>

            <div class="warning-box">
                <h3 class="warning-title">Thông tin quan trọng</h3>
                <ul class="warning-list">
                    <li>Mã xác nhận chỉ có hiệu lực trong vòng 12 giờ</li>
                    <li>Tuyệt đối không chia sẻ mã này với bất kỳ ai</li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                    <li>Mọi phiên đăng nhập hiện tại sẽ bị đăng xuất sau khi đổi mật khẩu</li>
                </ul>
            </div>

            <div class="security-tips">
                <h3 class="tips-title">Lời khuyên về bảo mật</h3>
                <ul class="tips-list">
                    <li>Sử dụng mật khẩu có ít nhất 8 ký tự</li>
                    <li>Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                    <li>Tránh sử dụng thông tin cá nhân trong mật khẩu</li>
                    <li>Không sử dụng lại mật khẩu từ các tài khoản khác</li>
                    <li>Định kỳ thay đổi mật khẩu để tăng cường bảo mật</li>
                </ul>
            </div>

            <p class="message">
                Nếu bạn gặp bất kỳ khó khăn nào trong quá trình đặt lại mật khẩu, đừng ngần ngại liên hệ với đội ngũ hỗ trợ của chúng tôi.
            </p>
        </div>

        <div class="email-footer">
            <p class="footer-text">&copy; {{ date('Y') }} {{ config('app.name') }}. Mọi quyền được bảo lưu.</p>
            
            <div class="social-links">
                <a href="#" class="social-link">Facebook</a>
                <a href="#" class="social-link">Discord</a>
                <a href="#" class="social-link">YouTube</a>
            </div>

            <p class="support-text">
                Cần hỗ trợ? 
                <a href="mailto:support@{{ config('app.domain') }}" class="support-link">Liên hệ với chúng tôi</a>
            </p>
        </div>
    </div>
</body>
</html>