$(function () {
    $(function () {
        let timeout;

        $("#listmenu").hide();
        $("#keysearch").keyup(function () {
                clearTimeout(timeout);
                let keysearch = $(this).val().trim();
                timeout = setTimeout(() => {
                    if (keysearch.length > 3) {
                        $("#listmenu").show();
                        $.ajax({
                            type: "GET",
                            url: `https://duanshop.test/api/search?search=${keysearch}`,
                            success: function (response) {
                                $("#listmenu").empty();
                                if (response.data.length > 0) {
                                    response.data.forEach((item) => {
                                        $("#listmenu").append(`
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="http://duanshop.test/${item.id}/detail">
                                    <img src="${window.location.origin}/storage/${item.pro_img_thumbnail}" width="40px" height="40px" class="rounded me-3" alt="">
                                    <div>
                                        <div class="fw-bold">${item.pro_name}</div>
                                        <div>
                                            <small class="text-muted fs-6 text-decoration-line-through">${new Intl.NumberFormat("en-US").format(item.pro_price_regular)} đ</small>
                                            <span class="text-danger fw-bold ms-2">${new Intl.NumberFormat("en-US").format(item.pro_price_sale)} đ</span>
                                        </div>
                                    </div>
                                </a>
                            </li>`);
                                    });
                                } else {
                                    $("#listmenu").empty();
                                    $("#listmenu").append(`
                                 <div class="text-center">
                                    <img src="/client/images/Search-Empty.webp" width="200px" height="200px" class="rounded me-3" alt="">
                                    <p>Không tìm thấy kết quả cần tìm</p>
                                 </div>
                            `);
                                }
                            },
                            error: function (error) {
                                $("#listmenu").empty();
                                $("#listmenu").append(
                                    `<p class="text-center">Lỗi hệ thống !!!</p>`
                                );
                            },
                        });
                    } else {
                        $("#listmenu").hide();
                    }
                }, 800);
            });
    });
});
