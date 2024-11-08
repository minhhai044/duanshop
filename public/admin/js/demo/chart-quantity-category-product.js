Highcharts.setOptions({
    chart: {
        styledMode: true,
    },
});
Dashboards.board("container", {
    dataPool: {
        connectors: [
            {
                id: "VegeTable",
                type: "CSV",
                options: {
                    csv: document.querySelector("#csv").innerHTML,
                },
            },
        ],
    },
    gui: {
        layouts: [
            {
                rows: [
                    {
                        cells: [
                            {
                                id: "top-left",
                            },
                        ],
                    },
                ],
            },
        ],
    },
    components: [
        {
            renderTo: "top-left",
            type: "Highcharts",
            sync: {
                highlight: true,
            },
            connector: {
                id: "VegeTable",
            },
            chartOptions: {
                chart: {
                    type: "bar",
                },
                credits: {
                    enabled: false,
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        colorByPoint: true,
                    },
                },
                title: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                },
                yAxis: {
                    title: {
                        enabled: false,
                    },
                },
            },
        },
    ],
});
