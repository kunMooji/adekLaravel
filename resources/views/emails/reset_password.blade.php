<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .token {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #00880C;
            padding: 15px;
            margin: 20px 0;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reset Password</h2>
        </div>

        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>

        <p>Berikut adalah kode verifikasi untuk reset password Anda:</p>

        <div class="token">{{ $token }}</div>

        <p>Masukkan kode di atas pada aplikasi untuk melanjutkan proses reset password.</p>

        <p>Kode verifikasi ini akan kadaluarsa dalam 60 menit.</p>

        <p>Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.</p>

        <div class="footer">
            &copy; {{ date('Y') }} ADEK. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>
