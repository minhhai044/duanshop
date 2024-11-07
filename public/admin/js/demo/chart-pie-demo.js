// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = "Nunito, '-apple-system', system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
Chart.defaults.global.defaultFontColor = "#858796";

// Function to format number for Vietnamese currency


// Pie Chart configuration
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: "doughnut",
    data: {
        labels: ["Thanh toán khi nhận hàng", "Thanh toán Online"],
        datasets: [{
            data: [payment_deliver.total, payment_vnpay.total],
            backgroundColor: ["#4e73df", "#1cc88a"],
            hoverBackgroundColor: ["#2e59d9", "#17a673"],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: false,
        },
        cutoutPercentage: 80,
    },
});
