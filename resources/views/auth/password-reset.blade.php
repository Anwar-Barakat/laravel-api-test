<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>
    <form action="{{ route('password.update') }}" method="post">
        <input type="email" name="email" id="">
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password_confirmation" placeholder="Password Confirmation">
    </form>
</body>
</html>
