<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin mật khẩu</title>
</head>

<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;color:#111;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:12px;padding:24px;border:1px solid #e9ecf3;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div
                    style="width:40px;height:40px;border-radius:10px;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                    NG
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;line-height:1.2;">Trần Minh Hải</div>
                    <div style="font-size:12px;color:#667085;">Thông báo mật khẩu tài khoản</div>
                </div>
            </div>

            <h2 style="margin:0 0 8px;font-size:18px;">Mật khẩu mới của bạn</h2>
            <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
                Xin chào, tài khoản <b>{{ $email ?? '---' }}</b> đã được tạo/cập nhật mật khẩu.
            </p>

            <div style="background:#f2f4f7;border:1px dashed #cfd5e3;border-radius:12px;padding:16px;margin:16px 0;">
                <div style="font-size:12px;color:#667085;margin-bottom:6px;">Mật khẩu:</div>
                <div style="font-size:20px;font-weight:800;letter-spacing:1px;">
                    {{ $password ?? '---' }}
                </div>
            </div>

            <p style="margin:0 0 12px;color:#475467;font-size:14px;line-height:1.6;">
                Vì lý do bảo mật: vui lòng đăng nhập và <b>đổi mật khẩu</b> ngay sau khi vào được hệ thống.
            </p>

            <p style="margin:0;color:#98a2b3;font-size:12px;line-height:1.6;">
                Nếu bạn không yêu cầu thao tác này, hãy bỏ qua email hoặc liên hệ hỗ trợ.
            </p>
        </div>

        <div style="text-align:center;color:#98a2b3;font-size:12px;margin-top:12px;">
            © {{ date('Y') }} Trần Minh Hải. All rights reserved.
        </div>
    </div>
</body>

</html>
