<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $greeting ?? 'Notifikasi' }} - SIPENA GenBI</title>
</head>
<body style="margin:0;padding:0;background-color:#EEF2F7;font-family:'Segoe UI',Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

    {{-- Preheader hidden text --}}
    <div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
        {{ $greeting ?? 'Notifikasi dari SIPENA GenBI' }} &mdash; {{ collect($introLines)->first() ?? '' }}
    </div>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#EEF2F7;padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="560" style="max-width:560px;width:100%;">

                    {{-- Brand Badge --}}
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="background:#002B66;border-radius:50px;padding:8px 22px;">
                                        <span style="color:#ffffff;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">SIPENA</span>
                                        <span style="color:#FF8A8A;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">GenBI</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Main Card --}}
                    <tr>
                        <td style="background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,43,102,0.12);">

                            {{-- Header --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="background:linear-gradient(135deg,#001A4D 0%,#002B66 60%,#0A3A7A 100%);padding:36px 40px 30px;text-align:center;border-bottom:3px solid #D90429;">
                                        <div style="display:inline-block;background:rgba(255,255,255,0.10);border:2px solid rgba(255,255,255,0.18);border-radius:50%;width:68px;height:68px;text-align:center;line-height:64px;margin-bottom:16px;">
                                            @isset($actionText)
                                                <span style="font-size:30px;line-height:64px;">&#9993;</span>
                                            @else
                                                <span style="font-size:30px;line-height:64px;">&#128276;</span>
                                            @endisset
                                        </div>
                                        <br>
                                        <span style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.5px;display:block;margin-bottom:4px;">
                                            SIPENA <span style="color:#FF8A8A;">GenBI</span>
                                        </span>
                                        <span style="color:rgba(255,255,255,0.50);font-size:11px;letter-spacing:0.5px;display:block;">
                                            Komisariat Politeknik Negeri Manado
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            {{-- Body --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:36px 40px 32px;">

                                        {{-- Greeting + accent bar --}}
                                        <p style="margin:0 0 8px;font-size:24px;font-weight:800;color:#0F172A;letter-spacing:-0.5px;">
                                            {{ $greeting ?? 'Halo!' }}
                                        </p>
                                        <div style="width:44px;height:3px;background:linear-gradient(90deg,#002B66,#D90429);border-radius:4px;margin-bottom:20px;"></div>

                                        {{-- Intro Lines --}}
                                        @foreach ($introLines as $line)
                                            <p style="margin:0 0 14px;font-size:15px;color:#475569;line-height:1.8;">{{ $line }}</p>
                                        @endforeach

                                        {{-- Action Button --}}
                                        @isset($actionText)
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:28px 0 20px;">
                                                <tr>
                                                    <td align="center">
                                                        <div style="display:inline-block;border-radius:14px;background:linear-gradient(135deg,#002B66,#D90429);padding:2px;">
                                                            <a href="{{ $actionUrl }}"
                                                               style="display:inline-block;background:linear-gradient(135deg,#002B66 0%,#001A4D 100%);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:15px 38px;border-radius:12px;letter-spacing:0.5px;">
                                                                &#10003;&nbsp; {{ $actionText }} &nbsp;&#8594;
                                                            </a>
                                                        </div>
                                                        <p style="margin:10px 0 0;font-size:11px;color:#94A3B8;">
                                                            Tautan berlaku selama <strong style="color:#64748B;">60 menit</strong>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>

                                            {{-- Dashed Divider --}}
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:20px 0;">
                                                <tr><td style="border-top:1px dashed #E2E8F0;height:1px;"></td></tr>
                                            </table>

                                            {{-- Fallback URL --}}
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="background:#F8FAFC;border:1px solid #E2E8F0;border-left:4px solid #002B66;border-radius:0 10px 10px 0;padding:14px 18px;">
                                                        <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#334155;">
                                                            Tombol tidak berfungsi?
                                                        </p>
                                                        <p style="margin:0 0 8px;font-size:12px;color:#64748B;line-height:1.6;">
                                                            Salin dan tempel tautan berikut ke browser Anda:
                                                        </p>
                                                        <p style="margin:0;font-size:11px;color:#002B66;word-break:break-all;line-height:1.7;">
                                                            {{ $actionUrl }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endisset

                                        {{-- Outro Lines --}}
                                        @if(count($outroLines) > 0)
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:20px;">
                                                <tr>
                                                    <td style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:14px 18px;">
                                                        @foreach ($outroLines as $line)
                                                            <p style="margin:0 0 4px;font-size:13px;color:#92400E;line-height:1.7;">
                                                                &#9888; {{ $line }}
                                                            </p>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                        {{-- Salutation --}}
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:30px;">
                                            <tr>
                                                <td style="border-top:1px solid #F1F5F9;padding-top:20px;">
                                                    <p style="margin:0;font-size:14px;color:#64748B;">Salam hangat,</p>
                                                    <p style="margin:4px 0 0;font-size:15px;font-weight:800;color:#002B66;">Tim SIPENA GenBI</p>
                                                    <p style="margin:2px 0 0;font-size:11px;color:#94A3B8;">Komisariat Politeknik Negeri Manado</p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 0 8px;text-align:center;">
                            <p style="margin:0 0 4px;font-size:11px;color:#94A3B8;">
                                &copy; {{ date('Y') }} <strong style="color:#64748B;">GenBI Komisariat Politeknik Negeri Manado</strong>
                            </p>
                            <p style="margin:0;font-size:11px;color:#CBD5E1;">
                                Email ini dikirim otomatis oleh sistem &middot; Harap tidak membalas email ini
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
