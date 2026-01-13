<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Mã xác thực OTP</title>
</head>

<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">
    <!-- Preheader (ẩn) -->
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        Mã OTP của bạn là {{ $otp }}. Mã có hiệu lực 1 phút.
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background:#f5f7fb;padding:24px 0;">
        <tr>
            <td align="center">

                <!-- Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
                    style="width:600px;max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(16,24,40,.08);">

                    <!-- Header -->
                    <tr>
                        <td style="padding:22px 24px;background:linear-gradient(135deg,#0ea5e9,#22c55e);">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="left" style="color:#fff;">
                                        <div style="font-size:14px;opacity:.95;">Trần Minh Hải</div>
                                        <div style="font-size:20px;font-weight:700;line-height:1.2;margin-top:2px;">Xác
                                            thực đăng nhập</div>
                                    </td>
                                    <td align="right">
                                        <!-- Logo (tùy chọn) -->
                                        <img src="{{ asset('images/logo.png') }}" width="44" height="44" alt="Logo"
                                            style="display:block;border-radius:10px;background:#ffffff1f;padding:6px;object-fit:contain;" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px;">
                            <div style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:10px;">
                                Đây là mã OTP của bạn
                            </div>

                            <div style="font-size:14px;color:#334155;line-height:1.7;margin-bottom:16px;">
                                Bạn đang thực hiện yêu cầu xác thực cho tài khoản:
                                <span style="font-weight:700;color:#0f172a;">{{ $email }}</span>.
                                Vui lòng nhập mã dưới đây để tiếp tục.
                            </div>

                            <!-- OTP box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="margin:10px 0 18px;">
                                <tr>
                                    <td align="center"
                                        style="padding:16px;border:1px dashed #cbd5e1;border-radius:14px;background:#f8fafc;">
                                        <div style="font-size:34px;letter-spacing:10px;font-weight:800;color:#0f172a;">
                                            {{ $otp }}
                                        </div>
                                        <div style="font-size:12px;color:#64748b;margin-top:8px;">
                                            Mã có hiệu lực trong <b>1 phút</b>.
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA (tuỳ chọn) -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center"
                                style="margin-bottom:16px;">
                                <tr>
                                    <td align="center" style="border-radius:12px;background:#0ea5e9;">
                                        <a href="#"
                                            style="display:inline-block;padding:12px 18px;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;">
                                            Xác thực ngay
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="font-size:13px;color:#64748b;line-height:1.7;">
                                Nếu bạn không yêu cầu mã này, hãy bỏ qua email. Vì vũ trụ hay troll nhưng hệ thống thì
                                không 🫠
                            </div>

                            <div style="height:14px;"></div>

                            <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                                Lưu ý: Không chia sẻ OTP cho bất kỳ ai, kể cả “admin siêu cấp vũ trụ”.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:18px 24px;background:#0b1220;color:#cbd5e1;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size:12px;line-height:1.6;">
                                        © 2026 Trần Minh Hải. All rights reserved.
                                        <br>
                                        Hỗ trợ: <span style="color:#ffffff;">0338997846</span> • <span
                                            style="color:#ffffff;">tmhai2004@gmail.com</span>
                                    </td>
                                    <td align="right" style="font-size:12px;color:#94a3b8;">
                                        123 Đường ABC, Quận XYZ, TP. HN
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

                <!-- Small note -->
                <div style="width:600px;max-width:600px;margin-top:10px;font-size:11px;color:#94a3b8;line-height:1.5;">
                    Email này được gửi tự động. Vui lòng không trả lời trực tiếp.
                </div>

            </td>
        </tr>
    </table>
</body>

</html>
