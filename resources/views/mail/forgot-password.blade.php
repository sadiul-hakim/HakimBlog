<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:40px 0;">
        <tr>
            <td align="center">

                <table width="500" cellpadding="0" cellspacing="0"
                    style="background-color:#ffffff; padding:30px; border-radius:8px;">

                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; color:#333333;">Password Reset Request</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#555555; font-size:14px; line-height:1.6;">
                            <p>
                                We received a request to reset your password.
                            </p>

                            <p>
                                Click the button below to set a new password:
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:25px 0;">
                            <a href="{{ $link }}"
                                style="background-color:#2563eb;
                                  color:#ffffff;
                                  text-decoration:none;
                                  padding:12px 25px;
                                  border-radius:4px;
                                  display:inline-block;
                                  font-weight:bold;">
                                Reset Password
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#888888; font-size:12px; line-height:1.5;">
                            <p>
                                If you did not request this, you can safely ignore this email.
                            </p>

                            <p>
                                This link would expire in 15 minutes for security reasons.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px; border-top:1px solid #eeeeee; font-size:12px; color:#aaaaaa;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
