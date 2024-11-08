// console.log(total_day);
const day = total_day.map((item, index) => index + 1);
const dayTotal = total_day.map((item, index) => Number(item));

const chart = Highcharts.chart("containerday", {
    title: {
        text: "Doanh thu các ngày trong tháng",
        align: "left",
    },
    // subtitle: {
    //     text: 'Chart option: Plain | Source: ' +
    //         '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
    //         'target="_blank">NAV</a>',
    //     align: 'left'
    // },
    colors: [],
    xAxis: {
        categories: day,
    },
    series: [
        {
            type: "column",
            name: "Giá",
            borderRadius: 5,
            colorByPoint: true,
            data: dayTotal,
            showInLegend: false,
        },
    ],
});

document.getElementById("plain").addEventListener("click", () => {
    chart.update({
        chart: {
            inverted: false,
            polar: false,
        },
    });
});

document.getElementById("inverted").addEventListener("click", () => {
    chart.update({
        chart: {
            inverted: true,
            polar: false,
        },
    });
});

document.getElementById("polar").addEventListener("click", () => {
    chart.update({
        chart: {
            inverted: false,
            polar: true,
        },
    });
});
