<template>
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"></h1>
        <div class="row mb-2">
            <div class="row justify-content-md-center">
                <div class="col-4">
                    <div class="row">
                        <div class="col-4 text-end pt-2 fw-bold">
                            <label class="form-label">Period:</label>
                        </div>
                        <div class="col-8">
                            <select class="form-select" v-model="period" disabled="disabled">
                                <option value="hour">Hour</option>
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-4 text-end pt-2 fw-bold">
                            <label v-if="period == 'hour'" class="form-label">Datetime:</label>
                            <label v-else class="form-label">Date:</label>
                        </div>
                        <div class="col-8">
                            <div class="input-group">
                                <input type="date" class="form-control" v-model="date">
                                <input v-if="period == 'hour'" type="time" class="form-control" v-model="time">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <chart-line ref="chartLine" :params="params"></chart-line>
    <chart-cols ref="chartCols" :params="params"></chart-cols>
</template>

<script>
    import ChartLine from './ChartLine.vue'
    import ChartCols from './ChartCols.vue'
    export default {
        name: 'home',
        components: {
            ChartLine,
            ChartCols,
        },
        data(){
            return {
                period : 'hour',
                date: '2018-08-20',
                time: '08:00',
                serie: null,
            }
        },
        computed : {
            params() {
                let date = this.date ? this.date : DateTime.now().toFormat('yyyy-MM-dd');
                let time = this.time ? this.time : DateTime.now().toLocaleString(DateTime.TIME_24_SIMPLE);
                return {
                    'period' : this.period,
                    'datetime' : date + 'T' + time,
                };
            }
        },
        watch : {
            period() {
                this.load();
            },
            date() {
                this.load();
            },
            time() {
                this.load();
            },
        },
        mounted() {
            this.load();
        },
        methods : {
            load() {
                setTimeout(() => {
                    this.$refs.chartLine.load();
                    this.$refs.chartCols.load();
                }, 100);
            }
        }
    }
</script>
