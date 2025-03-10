<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích Hoạt Tài Khoản</title>
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
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .email-header h1 {
            color: #ffd700;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Content styles */
        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .welcome-text {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .instruction-text {
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        /* Activation code styles */
        .activation-code-container {
            background: linear-gradient(135deg, #2d1b0c 0%, #3d2b1c 100%);
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #ffd700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
        }

        .activation-code {
            font-family: 'Courier New', monospace;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #ffd700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            animation: glow 2s infinite;
        }

        /* Notice box styles */
        .notice-box {
            background-color: #fff3cd;
            border-left: 5px solid #ffd700;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .notice-title {
            color: #856404;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .notice-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .notice-list li {
            color: #856404;
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .notice-list li:before {
            content: "•";
            color: #ffd700;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        /* Features list styles */
        .features-list {
            margin: 25px 0;
            padding-left: 20px;
        }

        .features-list li {
            color: #4a5568;
            margin-bottom: 15px;
            position: relative;
            padding-left: 25px;
        }

        .features-list li:before {
            content: "✓";
            color: #48bb78;
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
        }

        .social-link:hover {
            text-decoration: underline;
        }

        .support-text {
            color: #cbd5e0;
            font-size: 14px;
            margin-top: 20px;
        }

        .support-link {
            color: #ffd700;
            text-decoration: none;
        }

        .support-link:hover {
            text-decoration: underline;
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

            .email-header h1 {
                font-size: 24px;
            }

            .email-content {
                padding: 30px 20px;
            }

            .activation-code {
                font-size: 28px;
                letter-spacing: 6px;
            }

            .features-list li {
                margin-bottom: 12px;
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
            <h1>Kích Hoạt Tài Khoản</h1>
        </div>

        <div class="email-content">
            <p class="welcome-text">Xin chào, {{ $user->userid }}!</p>
            
            <p class="instruction-text">
                Cảm ơn bạn đã đăng ký tài khoản tại {{ config('app.name') }}. Để hoàn tất việc đăng ký và bảo mật tài khoản của bạn,
                vui lòng sử dụng mã kích hoạt dưới đây:
            </p>

            <div class="activation-code-container">
                <div class="activation-code">{{ $activation_code }}</div>
            </div>

            <div class="notice-box">
                <h3 class="notice-title">Lưu ý quan trọng:</h3>
                <ul class="notice-list">
                    <li>Mã kích hoạt có hiệu lực trong vòng 24 giờ</li>
                    <li>Không chia sẻ mã này với bất kỳ ai</li>
                    <li>Đảm bảo bạn đang truy cập trang web chính thức của chúng tôi</li>
                    <li>Mỗi mã chỉ có thể sử dụng một lần duy nhất</li>
                </ul>
            </div>

            <p class="instruction-text">Sau khi kích hoạt thành công, bạn sẽ có thể:</p>
            <ul class="features-list">
                <li>Truy cập và tham gia game ngay lập tức</li>
                <li>Tham gia các sự kiện đặc biệt và nhận phần thưởng hấp dẫn</li>
                <li>Sử dụng đầy đủ tất cả tính năng của tài khoản</li>
                <li>Tham gia cộng đồng người chơi</li>
                <li>Bảo vệ tài khoản khỏi các hành vi gian lận</li>
            </ul>

            <div class="notice-box">
                <h3 class="notice-title">Bảo mật tài khoản:</h3>
                <ul class="notice-list">
                    <li>Sử dụng mật khẩu mạnh và không dễ đoán</li>
                    <li>Không chia sẻ thông tin đăng nhập với người khác</li>
                    <li>Thường xuyên thay đổi mật khẩu để bảo vệ tài khoản</li>
                </ul>
            </div>
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