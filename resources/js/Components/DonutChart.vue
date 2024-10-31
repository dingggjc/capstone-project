<template>
    <div>
        <apexchart type="donut" :options="chartOptions" :series="series" />
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    name: "DonutChart",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        series: {
            type: Array,
            required: true,
        },
        chartOptions: {
            type: Object,
            default: () => ({
                chart: {
                    type: 'donut',
                    width: '100%',
                    height: '100%',
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%', // Adjust donut thickness as needed
                        },
                    },
                },
                tooltip: {
                    y: {
                        formatter: (val, opts) => {
                            const product = opts.w.config.labels[opts.seriesIndex];
                            return `${val} units sold for ${product}`;
                        },
                    },
                },
                legend: {
                    position: 'bottom',
                },
                dataLabels: {
                    formatter: (val, opts) => `${opts.w.config.series[opts.seriesIndex]} units`, // Show units inside the donut segments
                },
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                                height: 200,
                            },
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                ],
            }),

        },


    },
};
</script>

<style scoped>
/* Add any custom styles for your donut chart component here */
</style>