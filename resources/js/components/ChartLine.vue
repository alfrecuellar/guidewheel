<template>
    <div>
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
                            hour: '%H:%M',
                        },
                        title: {
                            text: 'Datetime'
                        },
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
                                to: 1,
                                color: 'rgba(220, 53, 69, 0.3)',
                                label: {
                                    style: {
                                        color: '#606060'
                                    }
                                }
                            }, {
                                from: 1,
                                to: 20,
                                color: 'rgba(255, 193, 7, 0.2)',
                                label: {
                                    style: {
                                        color: '#606060'
                                    }
                                }
                            }, {
                                from: 20,
                                to: 125,
                                color: 'rgba(13, 110, 253, 0.15)',
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
                            lineWidth: 2,
                            states: {
                                hover: {
                                    lineWidth: 2
                                }
                            },
                            marker: {
                                enabled: false
                            },
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
                            serie.push([this.formatUTC(item[0]), parseFloat(item[1])]);
                        });
                        this.options.series[0].data = serie;
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