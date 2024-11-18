import "./bootstrap";
import toastr from "toastr";

toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right", // Vị trí thông báo
    timeOut: "5000", // Thời gian hiển thị (5s)
    extendedTimeOut: "1000", // Thời gian hiển thị thêm
};

window.Echo.channel("broadcast_coupon").listen("CouponEvent", function (event) {
    console.log(event);

    toastr.info(`
        <h4>🎉 Coupon mới: <span>${event.code}</span></h4>
         <p>${event.description} . Hạn sử dụng: Từ ${event.start} đến ${event.end}.</p>
      <p>Chúc bạn mua sắm vui vẻ .</p>
       `);
});
