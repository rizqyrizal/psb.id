<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akun Guru</title>
</head>
<body>
    <h2>Halo {{ $name ?? '-' }}</h2>
    <p>Akun Anda sudah aktif. Berikut detail login Anda:</p>

    <ul>
        <li>Email: <strong>{{ $email }}</strong></li>
        <li>Password: <strong>{{ $password }}</strong></li>
    </ul>

    <p>Silakan login dan ganti password Anda setelah masuk.</p>

    <p>Terima kasih,<br>Tim Admin</p>
</body>
</html>
