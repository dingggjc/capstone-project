<template>
    <div>
        <!-- Dropdown Filter -->
        <div class="mb-4">
            <label for="date-range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Date
                Range</label>
            <select id="date-range" v-model="selectedRange" @change="filterData"
                class="block w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white">
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="last7days">Last 7 Days</option>
            </select>
        </div>

        <!-- Bar Chart -->
        <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>



<script>
import VueApexCharts from 'vue3-apexcharts';

import { watch } from 'vue';
export default {
    name: 'TopPackagesChart',
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        topPackages: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            series: [],
            chartOptions: {
                chart: {
                    type: 'bar',
                    height: 350,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '20%',
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => `${val}`,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#304758'],
                    },
                },
                xaxis: {
                    categories: [],
                },
                yaxis: {
                    title: {
                        text: 'Sales (Qty)',
                    },
                },
                title: {
                    text: 'Top-Selling Packages',
                    align: 'center',
                },
            },
            selectedRange: 'today',
        };
    },
    mounted() {
        console.log('Top Packages:', JSON.stringify(this.topPackages, null, 2));
        this.filterData();
    },
    watch: {
        topPackages: {
            handler(newVal) {
                console.log('topPackages updated:', newVal);
                this.filterData();
            },
            immediate: true,
            deep: true,
        },
    },

    methods: {
        filterData() {
            console.log('Filtering Data for Range:', this.selectedRange);

            if (!this.topPackages || this.topPackages.length === 0) {
                console.warn('No packages available to filter.');
                this.series = [];
                this.chartOptions.xaxis.categories = [];
                return;
            }

            const currentDate = new Date();
            let filteredPackages = [];

            try {
                const packages = [...this.topPackages];

                switch (this.selectedRange) {
                    case 'today':
                        filteredPackages = packages.filter((pkg) => {
                            const date = pkg.package?.created_at ? new Date(pkg.package.created_at) : null;
                            if (!date || isNaN(date)) return false;
                            return this.isSameDay(date, currentDate);
                        });

                        break;
                    case 'yesterday':
                        filteredPackages = packages.filter((pkg) => {
                            const date = new Date(pkg.package?.created_at);
                            return this.isSameDay(date, this.subtractDays(currentDate, 1));
                        });
                        break;
                    case 'last7days':
                        filteredPackages = packages.filter((pkg) => {
                            const date = new Date(pkg.package?.created_at);
                            return this.isWithinLastDays(date, 7);
                        });
                        break;
                    default:
                        console.warn('Unexpected date range selected.');
                }
            } catch (error) {
                console.error('Error filtering packages:', error);
            }

            const packageNames = filteredPackages.map((pkg) => pkg.package?.package_name || 'Unnamed Package');
            const packageQuantities = filteredPackages.map((pkg) => parseInt(pkg.total_qty, 10) || 0);

            if (packageNames.length === 0 || packageQuantities.length === 0) {
                console.warn('No data matches the selected range.');
                this.series = [
                    {
                        name: 'Quantity Sold',
                        data: [0],
                    },
                ];
                this.chartOptions.xaxis.categories = ['No Data'];
                return;
            }


            this.series = [
                {
                    name: 'Quantity Sold',
                    data: packageQuantities,
                },
            ];
            this.chartOptions = {
                ...this.chartOptions,
                xaxis: {
                    ...this.chartOptions.xaxis,
                    categories: packageNames,
                },
            };

            console.log('Updated Series:', this.series);
            console.log('Updated Categories:', this.chartOptions.xaxis.categories);
        },



        isSameDay(date1, date2) {
            return (
                date1.getFullYear() === date2.getFullYear() &&
                date1.getMonth() === date2.getMonth() &&
                date1.getDate() === date2.getDate()
            );
        },
        subtractDays(date, days) {
            const result = new Date(date);
            result.setDate(date.getDate() - days);
            return result;
        },
        isWithinLastDays(date, days) {
            const currentDate = new Date();
            const pastDate = this.subtractDays(currentDate, days);
            return date >= pastDate && date <= currentDate;
        },
    },
};
</script>
