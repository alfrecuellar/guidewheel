<template>
    <div>
        <div>Line</div>
        <Highcharts :options="options"></Highcharts>
    </div>
</template>

<script>
    import Highcharts from 'highcharts';
    import { createHighcharts } from 'vue-highcharts';

    export default {
        name: 'chart-line',
        props: ['params'],
        components: {
            Highcharts: createHighcharts('Highcharts', Highcharts),
        },
        data() {
            return {
                request : null,
                options: {
                    chart: {
                        type: 'spline',
                        scrollablePlotArea: {
                            minWidth: 600,
                            scrollPositionX: 1
                        }
                    },
                    title: {
                        text: 'Compressor Status',
                        align: 'left'
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: {
                            minute: '%H:%M',
                            month: '%e. %b',
                            year: '%b'
                        },
                        title: {
                            text: 'Date'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Psum_kW'
                        },
                        min: 0,
                        max: 125,
                        minorGridLineWidth: 0,
                        gridLineWidth: 0,
                        alternateGridColor: null,
                        plotBands: [
                            {
                                from: 0,
                                to: 10,
                                color: 'rgba(220, 53, 69, 0.1)',
                                label: {
                                    style: {
                                        color: '#606060'
                                    }
                                }
                            }, {
                                from: 10,
                                to: 20,
                                color: 'rgba(255, 193, 7, 0.1)',
                                label: {
                                    style: {
                                        color: '#606060'
                                    }
                                }
                            }, {
                                from: 20,
                                to: 100,
                                color: 'rgba(13, 110, 253, 0.1)',
                                label: {
                                    style: {
                                        color: '#606060'
                                    }
                                }
                            }
                        ]
                    },
                    tooltip: {
                        valueSuffix: ' kW'
                    },
                    plotOptions: {
                        series: {
                            marker: {
                                enabled: undefined
                            }
                        },
                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: false
                            },
                            pointInterval: 3600000 / 120,
                        }
                    },
                    series: [{
                        name: 'Psum_kW',
                        data: []

                    }],
                    navigation: {
                        menuItemStyle: {
                            fontSize: '10px'
                        }
                    }
                },
            }
        },
        methods : {
            load() {
                if (this.request) {
                    this.request.cancel();
                }
                this.options.series[0].data = [];
                this.loading = true;
                this.request = axios.CancelToken.source();
                axios.get('/chart/line', {
                        cancelToken: this.request.token,
                        params: this.params
                    })
                    .then((response) => {
                        let serie = [];
                        _.each(response.data, (item) => {
                            serie.push([DateTime.fromSQL(item[0]).toJSDate(), parseFloat(item[1])]);
                        });
                        console.log(serie);
                        this.options.series[0].data = serie;
                        this.loading = false;
                    }).catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            }
        }
    }
</script>