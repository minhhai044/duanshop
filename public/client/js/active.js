document.addEventListener('DOMContentLoaded', function() {
    // Lấy URL hiện tại
    const currentUrl = window.location.href;

    // Lấy tất cả các thẻ <a> trong menu
    const navLinks = document.querySelectorAll('#navbar .nav-link');

    navLinks.forEach(link => {
        // Nếu href của thẻ <a> trùng với URL hiện tại
        if (link.href === currentUrl) {
            // Thêm class "active" vào thẻ <li> chứa thẻ <a>
            link.parentElement.classList.add('active');
        }
    });
});