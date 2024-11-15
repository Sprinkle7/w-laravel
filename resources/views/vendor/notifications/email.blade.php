<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen E-Mail</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body style="background-color: #f3f4f6; font-family: 'Rajdhani', sans-serif; color: #333; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f3f4f6; width: 100%; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                    <!-- Header Section -->
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <img src="{{ asset('logo.png') }}" alt="Illustration" style="width: 100%; max-width: 180px; display: block; margin: auto;">
                        </td>
                    </tr>
                    <!-- Illustration Section -->
                    <tr>
                        <td align="left" style="padding: 20px;">
                            <div style="background: linear-gradient(90deg, #fef3c7, #fecdd3); border-radius: 8px; padding: 20px;">
                                <img src="{{ asset('heading.png') }}" alt="Illustration" style="width: 100%; max-width: 800px; display: block; margin: auto;">
                            </div>
                        </td>
                    </tr>
                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <p style="font-size: 14px; color: #333; line-height: 1.5; font-family: 'Rajdhani', sans-serif;font-weight: 500;font-style: normal;">
                                {{ $greeting }}
                            </p>
                            <br>
                            <p style="font-size: 14px; color: #333; line-height: 1.5;font-family: 'Rajdhani', sans-serif; font-weight: 400;font-style: normal;">
                                Wir haben eine Anfrage zum Zurücksetzen Ihres Passworts für Ihr Konto bei Wirtschaft Magazin erhalten. <br>
                                Klicken Sie auf die Schaltfläche unten, um ein neues Passwort festzulegen.
                            </p>
                            <div style="text-align: center; margin: 20px 0;">
                                <x-mail::button :url="$actionUrl">
                                    {{ $actionText }} 
                                </x-mail::button>
                            </div>
                            <p style="font-size: 14px; color: #666; line-height: 1.5;font-family: 'Rajdhani', sans-serif; font-weight: 400;font-style: normal;">
                                Wenn Sie diese Anfrage nicht gestellt haben, ignorieren Sie bitte diese E-Mail. Ihr Passwort bleibt sicher.
                                <br>
                                Für weitere Unterstützung können Sie uns gerne unter <a href="mailto:help@wirtschaftmagazin.de" style="color: #3b82f6; text-decoration: none;">help@Wirtschaft Magazin</a> kontaktieren.
                            </p>
                            <p style="font-size: 14px; color: #333;line-height: 1.5; margin-top: 20px;font-family: 'Rajdhani', sans-serif; font-weight: 400;font-style: normal;">
                            Mit freundlichen Grüßen,,<br>Das Team von Wirtschaft Magazin
                            </p>
                        </td>
                    </tr>
                    <!-- Footer Section -->
                    <tr>
                        <td style="padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center;font-family: 'Rajdhani', sans-serif; font-weight: 400;font-style: normal;">
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <img src="{{ asset('ticktok.png') }}" alt="Wirtschaft Logo" style="width: 32px; height: 32px; margin-right: 8px;">
                                <img src="{{ asset('x.png') }}" alt="Wirtschaft Logo" style="width: 32px; height: 32px; margin-right: 8px;">
                                <img src="{{ asset('instagram.png') }}" alt="Wirtschaft Logo" style="width: 32px; height: 32px; margin-right: 8px;">
                                <img src="{{ asset('youtube.png') }}" alt="Wirtschaft Logo" style="width: 32px; height: 32px; margin-right: 8px;">
                                <br>
                                <br>
                                <p style="font-size: 14px; color: #333;font-family: 'Rajdhani', sans-serif; font-weight: 700;font-style: normal;">Wirtschaft Magazin</p>
                            </div>
                            <!-- <p style="font-size: 12px; color: #666; margin: 4px 0;">&copy; Wirtschaft Magazin Inc</p> -->
                            <!-- <p style="font-size: 12px; color: #666; margin: 4px 0;">2261 Market Street #4650 | San Francisco, CA 94114</p> -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
