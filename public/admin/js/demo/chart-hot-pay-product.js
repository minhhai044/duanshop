Highcharts.chart('tablecontainer', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Top 5 sản phẩm bán chạy'
    },
    subtitle: {
        text:
            // 'Source: <a href="https://www.ssb.no/en/statbank/table/04231" target="_blank">SSB</a>'
            ''
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Amount'
        }
    }
});