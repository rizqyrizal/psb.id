<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Set Password</title>
</head>
<body>
    <h2>Atur Password</h2>
    <form method="POST" action="{{ route('set-password.post') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label>Password Baru</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Simpan Password</button>
    </form>
</body>
</html>
