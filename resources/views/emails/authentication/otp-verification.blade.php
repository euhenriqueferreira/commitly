<!DOCTYPE html>
<html lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Seu código de verificação — Commitly</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; background-color: #0D1117; }
 
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper { width: 100% !important; }
            .email-body { padding: 32px 20px !important; }
            .otp-cell { padding: 0 6px !important; }
            .otp-digit {
                width: 44px !important;
                height: 52px !important;
                font-size: 26px !important;
                min-width: 44px !important;
            }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#0D1117;">
 
    <!-- Preheader invisível -->
    <div style="display:none; max-height:0; overflow:hidden; font-size:1px; color:#0D1117;">
        Seu código de verificação do Commitly: {{ $code }} — Válido por {{ $expiresIn }} minutos.
        &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>
    
    <!-- Wrapper externo -->
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#0D1117; min-height:100vh;">
        <tr>
            <td align="center" style="padding: 48px 16px;">
    
                <!-- Container principal -->
                <table role="presentation" class="email-wrapper" cellpadding="0" cellspacing="0" border="0" width="560" style="max-width:560px; width:100%;">
    
                    <!-- Logo / Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 36px;">
                            {{-- <img src="{{ asset('assets/images/logotipo-commitly.svg') }}" style="width: fit-content; height: 64px; object-fit: scale-down;"> --}}
                            <img src="{{ asset('assets/images/logotipo-commitly-3x.png') }}" style="width: fit-content; height: 64px; object-fit: scale-down;">
                        </td>
                    </tr>
    
                    <!-- Card principal -->
                    <tr>
                        <td style="background-color:#17181c; border-radius:16px; border:1px solid #2a2b30; overflow:hidden;">
    
                            <!-- Barra de acento superior -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td height="3" style="background: linear-gradient(90deg, #1F6FEB 0%, #1F6FEB70 50%, #0D1117 100%); font-size:0; line-height:0;">&nbsp;</td>
                                </tr>
                            </table>
    
                            <!-- Corpo do card -->
                            <table role="presentation" class="email-body" cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 48px 48px 40px;">
                                <tr>
                                    <td>
    
                                        <!-- Tag de contexto -->
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 24px;">
                                            <tr>
                                                <td style="background-color:#1F6FEB30; border:1px solid #1F6FEB33; border-radius:20px; padding:4px 12px;">
                                                    <span style="font-family:'JetBrains Mono', Courier, monospace; font-size:11px; font-weight:600; color:#1F6FEB; letter-spacing:0.1em; text-transform:uppercase;">🔐 Autenticação</span>
                                                </td>
                                            </tr>
                                        </table>
    
                                        <!-- Título -->
                                        <p style="font-family: 'Inter', sans-serif; font-size:28px; font-weight:700; color:#C9D1D9; margin:0 0 12px; line-height:1.2;">
                                            Código de verificação
                                        </p>
    
                                        <!-- Subtítulo -->
                                        <p style="font-family: 'Inter', sans-serif; font-size:15px; color:#8B949E; margin-bottom: 36px; line-height:1.6;">
                                            Alguém solicitou acesso à sua conta no Commitly. Use o código abaixo para confirmar sua identidade.
                                        </p>
    
                                        <!-- Divisor sutil -->
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 36px;">
                                            <tr>
                                                <td height="1" style="background-color:#2a2b30; font-size:0; line-height:0;">&nbsp;</td>
                                            </tr>
                                        </table>
    
                                        <!-- Label do OTP -->
                                        <p style="font-family:'JetBrains Mono'; font-size:11px; font-weight:600; color:#8B949E; letter-spacing:0.15em; text-transform:uppercase; margin:0 0 16px;">
                                            // seu código
                                        </p>
    
                                        <!-- Dígitos OTP -->
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 36px;">
                                            <tr>
                                                <!-- Dígito 1 -->
                                                <td class="otp-cell" style="padding: 0 7px 0 0;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 0, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <!-- Dígito 2 -->
                                                <td class="otp-cell" style="padding: 0 7px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 1, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <!-- Dígito 3 -->
                                                <td class="otp-cell" style="padding: 0 7px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 2, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <!-- Separador -->
                                                <td style="padding: 0 10px; vertical-align:middle;">
                                                    <span style="font-family:'JetBrains Mono'; font-size:22px; color:#3a3b42; line-height:1; user-select: none; -webkit-user-select: none;">—</span>
                                                </td>
                                                <!-- Dígito 4 -->
                                                <td class="otp-cell" style="padding: 0 7px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 3, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <!-- Dígito 5 -->
                                                <td class="otp-cell" style="padding: 0 7px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 4, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <!-- Dígito 6 -->
                                                <td class="otp-cell" style="padding: 0 0 0 7px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="otp-digit" width="52" height="64" style="background-color:#1e1f25; border:1px solid #1F6FEB; border-radius:10px; text-align:center; vertical-align:middle; min-width:52px;">
                                                                <span style="font-family:'JetBrains Mono'; font-size:30px; font-weight:700; color:#1F6FEB; line-height:1; display:block;">{{ substr($code, 5, 1) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
    
                                        <!-- Info de expiração -->
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 36px;">
                                            <tr>
                                                <td style="background-color:#1c1a14; border:1px solid #9A670030; border-radius:8px; padding:10px 16px;">
                                                    <span style="font-family:'Inter', sans-serif; font-size:13px; color:#9A6700;">
                                                        ⏳&nbsp; Este código expira em <strong style="color:#9A6700;">10 minutos</strong>. Não compartilhe com ninguém.
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
    
                                        <!-- Divisor sutil -->
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 28px;">
                                            <tr>
                                                <td height="1" style="background-color:#2a2b30; font-size:0; line-height:0;">&nbsp;</td>
                                            </tr>
                                        </table>
    
                                        <!-- Aviso de segurança -->
                                        <p style="font-family:'Inter', sans-serif; font-size:13px; color:#C9D1D9; margin:0; line-height:1.7;">
                                            Não solicitou isso? Ignore este e-mail com segurança. Sua conta permanece protegida.
                                        </p>
    
                                    </td>
                                </tr>
                            </table>
    
                        </td>
                    </tr>
    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top: 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <p style="font-family:'JetBrains Mono'; font-size:11px; color:#C9D1D9; margin:0; letter-spacing:0.05em;">
                                            git commit -m "streak mantida ✓"
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="font-family:'Inter', sans-serif; font-size:12px; color:#C9D1D9; margin:0; line-height:1.8;">
                                            Commitly · Feito para devs que valorizam consistência<br>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
    
                </table>
            </td>
        </tr>
    </table>
 
</body>
</html>