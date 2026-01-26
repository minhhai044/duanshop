# Introduction

API Documentation cho ứng dụng E-commerce DuanShop

<aside>
    <strong>Base URL</strong>: <code>https://duanshop.iongeyser.com/</code>
</aside>

    Chào mừng bạn đến với API Documentation của DuanShop! 

    Tài liệu này cung cấp tất cả thông tin bạn cần để làm việc với API của chúng tôi.

    <aside>Khi bạn cuộn xuống, bạn sẽ thấy các ví dụ code để làm việc với API bằng các ngôn ngữ lập trình khác nhau ở vùng tối bên phải (hoặc như một phần của nội dung trên mobile).
    Bạn có thể chuyển đổi ngôn ngữ được sử dụng bằng các tab ở góc trên bên phải (hoặc từ menu nav ở góc trên bên trái trên mobile).</aside>

    ## Xác thực
    API sử dụng Laravel Sanctum để xác thực. Để truy cập các endpoint được bảo vệ, bạn cần:
    1. Đăng nhập qua endpoint `/api/auths/login` 
    2. Sử dụng token nhận được trong header `Authorization: Bearer {token}`

