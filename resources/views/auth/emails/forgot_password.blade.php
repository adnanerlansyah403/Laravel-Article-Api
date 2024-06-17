<h1>Forget Password Email</h1>

You can reset password from bellow link:
<a href="{{ route('auth.reset-password', ['email' => $email, 'token' => $token]) }}">Reset Password</a>
