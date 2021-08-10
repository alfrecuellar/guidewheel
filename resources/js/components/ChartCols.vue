<template>
    <div></div>
</template>

<script>
    export default {
        name: 'chart-cols',
        props: ['params'],
        data() {
            return {
                serie : [],
                request : null,
            }
        },
        methods : {
            load() {
                if (this.request) {
                    this.request.cancel();
                }
                this.serie = [];
                this.loading = true;
                this.request = axios.CancelToken.source();
                axios.get('/chart/cols', {
                        cancelToken: this.request.token,
                        params: this.params
                    })
                    .then((response) => {
                        this.serie = response.data;
                        this.loading = false;
                    }).catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            }
        }
    }
</script>