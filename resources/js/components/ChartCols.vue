<template>
    <div>
        <Highcharts :options="options"></Highcharts>
    </div>
</template>

<script>
    import Highcharts from 'highcharts';
    import { createHighcharts } from 'vue-highcharts';

    export default {
        name: 'chart-cols',
        props: ['params'],
        components: {
            Highcharts: createHighcharts('Highcharts', Highcharts),
        },
        data() {
            return {
                request : null,
                options: {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Status in Hours Range',
                        align: 'left'
                    },
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Percentage Time'
                        }
                    },
                    tooltip: {
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                        shared: true
                    },
                    plotOptions: {
                        column: {
                            stacking: 'percent'
                        }
                    },
                    series: []
                },
            }
        },
        methods : {
            load() {
                if (this.request) {
                    this.request.cancel();
                }

                this.options.series = [];
                this.options.xAxis.categories = [];

                this.loading = true;
                this.request = axios.CancelToken.source();
                axios.get('/chart/cols', {
                        cancelToken: this.request.token,
                        params: this.params
                    })
                    .then((response) => {
                        this.options.series = response.data.series;
                        this.options.xAxis.categories = response.data.categories;
                        this.loading = false;
                    }).catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            formatUTC(string) {
                let datetime = DateTime.fromSQL(string);
                return Date.UTC(datetime.get('year'), datetime.get('month'), datetime.get('day'), datetime.get('hour'), datetime.get('minute'), datetime.get('second'));
            }
        }
    }
</script>