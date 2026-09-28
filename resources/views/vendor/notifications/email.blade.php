<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $greeting ?? 'Notifikasi' }} - SIPENA GenBI</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #F0F4FA;
            color: #0F172A;
        }
        .wrapper {
            max-width: 580px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        .header {
            background: linear-gradient(135deg, #0A192F 0%, #002B66 100%);
            border-bottom: 4px solid #D90429;
            padding: 28px 36px;
            text-align: center;
        }
        .header-logo-circle {
            display: inline-block;
            background: #ffffff;
            border-radius: 50%;
            padding: 6px;
            width: 64px;
            height: 64px;
            margin-bottom: 12px;
        }
        .header-logo-circle img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: contain;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        .header h1 span { color: #FF6B6B; }
        .header p {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            margin-top: 4px;
        }
        .body {
            padding: 36px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 12px;
        }
        .content {
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .action-btn {
            display: inline-block;
            background: linear-gradient(135deg, #002B66 0%, #001A4D 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            padding: 14px 32px;
            border-radius: 12px;
            margin: 8px 0 24px;
            letter-spacing: 0.4px;
        }
        .action-btn:hover { background: #D90429; }
        .divider {
            height: 1px;
            background: #E2E8F0;
            margin: 24px 0;
        }
        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 12px;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .url-text {
            word-break: break-all;
            color: #002B66;
            font-size: 12px;
        }
        .footer {
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 20px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #94A3B8;
            line-height: 1.6;
        }
        .footer strong { color: #64748B; }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-logo-circle">
            <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo">
        </div>
        <h1>SIPENA <span>GenBI</span></h1>
        <p>Sistem Pelaporan Engagement Instagram &bull; Komisariat Politeknik Negeri Manado</p>
    </div>

    <!-- Body -->
    <div class="body">
        <!-- Greeting -->
        <div class="greeting">{{ $greeting ?? 'Halo!' }}</div>

        <!-- Intro Lines -->
        @foreach ($introLines as $line)
            <div class="content">{{ $line }}</div>
        @endforeach

        <!-- Action Button -->
        @isset($actionText)
            <div style="text-align: center; margin: 28px 0;">
                <a href="{{ $actionUrl }}" class="action-btn">{{ $actionText }}</a>
            </div>

            <div class="divider"></div>

            <!-- Fallback URL -->
            <div class="info-box">
                <strong>Jika tombol di atas tidak berfungsi</strong>, salin dan tempel tautan berikut ke browser Anda:<br><br>
                <span class="url-text">{{ $actionUrl }}</span>
            </div>
        @endisset

        <!-- Outro Lines -->
        @foreach ($outroLines as $line)
            <div class="content" style="margin-bottom: 8px;">{{ $line }}</div>
        @endforeach

        <!-- Salutation -->
        <div style="margin-top: 24px; font-size: 14px; color: #475569;">
            Salam,<br>
            <strong style="color: #002B66;">Tim SIPENA GenBI</strong>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            &copy; {{ date('Y') }} <strong>GenBI Komisariat Politeknik Negeri Manado</strong><br>
            Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.
        </p>
        @isset($actionText)
            <p style="margin-top: 8px;">
                Link reset password hanya berlaku selama <strong>60 menit</strong>.
            </p>
        @endisset
    </div>
</div>
</body>
</html>
