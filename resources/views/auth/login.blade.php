<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Authentication</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background:url({{ asset('lines.png') }}); background-size:cover;">
    <div class="bg-white shadow-md rounded-lg overflow-hidden flex items-center " id="auth-container">
        <!-- Left Side Image/Illustration -->
        <div class="md:block flex-1">
            <img src="{{ asset('bg.png') }}" width="80%" alt="Illustration" class="w-full h-full object-cover">
        </div>

        <!-- Right Side Form -->
        <div class="w-full p-10  flex-1 flex-col justify-center" id="auth-forms">
            <!-- Login Form -->
            <div id="login-form" >
                <img src="{{ asset('logo.png') }}" alt="Illustration" class=" object-cover">
                <h2 class="mt-4 text-xl font-semibold text-gray-700 font-playfair">Anmeldung bei Ihrem Konto</h2>
                <small class="font-rajdhani">Bitte geben Sie Ihre genauen Daten ein, um sich anzumelden</small>
                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 font-rajdhani">E-Mail</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 font-rajdhani">Passwort</label>
                        <input type="password" id="password" name="password" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="text-right">
                        <a href="javascript:void(0);" onclick="showForgotPassword()" class="text-sm text-gray-600 hover:text-gray-900">Passwort vergessen?</a>
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">Anmelden</button>
                </form>
            </div>

            <!-- Forgot Password Form -->
            <div id="forgot-password-form" style="display: none;">
                <img src="{{ asset('logo.png') }}" alt="Illustration" class=" object-cover">
                <h2 class="mt-4 text-xl font-semibold text-gray-700">Passwort vergessen</h2>
                <form id="forgot-password" class="mt-6 space-y-4" onsubmit="sendResetLink(event)">
                    <div>
                        <label for="reset-email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                        <input type="email" id="reset-email" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">Senden</button>
                </form>
                <p class="mt-4 text-center">
                    Sie haben bereits ein Konto? <a href="javascript:void(0);" onclick="showLogin()" class="font-semibold text-gray-800">Anmeldung</a>
                </p>
            </div>

            <!-- Reset Password Form -->
            <div id="reset-password-form" style="display: none;">
                <img src="{{ asset('logo.png') }}" alt="Illustration" class=" object-cover">
                <h2 class="mt-4 text-xl font-semibold text-gray-700">Ihr Passwort zurücksetzen</h2>
                <form id="reset-password" class="mt-6 space-y-4" onsubmit="updatePassword(event)">
                    <div>
                        <label for="new-password" class="block text-sm font-medium text-gray-700">Neues Passwort</label>
                        <input type="password" id="new-password" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="confirm-password" class="block text-sm font-medium text-gray-700">Neues Passwort bestätigen</label>
                        <input type="password" id="confirm-password" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">Passwort aktualisieren</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showForgotPassword() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('forgot-password-form').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('forgot-password-form').style.display = 'none';
            document.getElementById('reset-password-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }

        function showResetPassword() {
            document.getElementById('forgot-password-form').style.display = 'none';
            document.getElementById('reset-password-form').style.display = 'block';
        }

        async function sendResetLink(event) {
            event.preventDefault();
            const email = document.getElementById('reset-email').value;
            try {
                await axios.post('/api/password/email', { email });
                alert('Reset link sent to your email.');
                showResetPassword();
            } catch (error) {
                alert('Error sending reset link.');
            }
        }

        async function updatePassword(event) {
            event.preventDefault();
            const password = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }
            try {
                await axios.post('/api/password/reset', { password });
                alert('Password updated successfully.');
                showLogin();
            } catch (error) {
                alert('Error updating password.');
            }
        }
    </script>
</body>
</html>
