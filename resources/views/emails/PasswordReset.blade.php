<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password</title>
</head>
<body>
  <a href="{{ env('FRONTEND_URL') }}/reset/{{ $token }}">Reset Password</a>
</body>
</html>