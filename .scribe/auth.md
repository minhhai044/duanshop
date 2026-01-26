# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Bạn có thể lấy token bằng cách đăng nhập qua endpoint <code>POST /api/auths/login</code>.
