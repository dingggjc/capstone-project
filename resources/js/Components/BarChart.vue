<template>
    <div>
        <apexchart type="bar" :options="chartOptions" :series="series" />
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    name: "BarChart",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        series: {
            type: Array,
            required: true,
        },
        xCategories: {
            type: Array,
            required: true,
        },
        chartTitle: {
            type: String,
            default: "Bar Chart",
        },
    },
    computed: {
        chartOptions() {
            return {
                chart: {
                    type: "bar",
                    height: 200, // Adjust to a smaller height
                    width: '100%',
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        borderRadiusApplication: "end",
                        horizontal: true,
                        barHeight: '50%', // Make the bars thinner (adjust percentage as needed)
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                xaxis: {
                    categories: this.xCategories,
                },
                title: {
                    text: this.chartTitle,
                    align: "left",
                },
            };
        },
    },

};
</script>

<style scoped>
/* Add any custom styles for your chart component here */
</style>