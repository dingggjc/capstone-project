<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { onMounted, defineProps, computed, ref, watch } from 'vue';
import { initFlowbite } from 'flowbite';
import pdfMake from 'pdfmake/build/pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';

pdfMake.vfs = pdfFonts.pdfMake.vfs;

onMounted(() => {
    initFlowbite();
});

const props = defineProps({
    transactions: {
        type: Array,
        required: true,
    }
});
const searchQuery = ref(usePage().props.searchQuery || '');

const search = () => {
    router.get(route('sales.report'), { query: searchQuery.value });
};

const startDate = ref(null);
const endDate = ref(null);
const filteredTransactions = ref([...props.transactions]);

watch([startDate, endDate], () => {
    filterTransactions();
});

function filterTransactions() {
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);


        start.setHours(0, 0, 0, 0);
        end.setHours(23, 59, 59, 999);

        filteredTransactions.value = props.transactions.filter(transaction => {
            const transactionDate = new Date(transaction.created_at);
            return transactionDate >= start && transactionDate <= end;
        });
    } else {
        filteredTransactions.value = [...props.transactions];
    }
}


const totalAmount = computed(() => {
    return filteredTransactions.value.reduce((sum, transaction) => sum + (transaction.grand_total || 0), 0);
});

function generatePDF() {
    const headers = [
        { text: 'Date', style: 'tableHeader' },
        { text: 'Invoice', style: 'tableHeader' },
        { text: 'Cashier', style: 'tableHeader' },
        { text: 'Customer', style: 'tableHeader' },
        { text: 'Total', style: 'tableHeader' }
    ];

    const body = filteredTransactions.value.map(transaction => [
        new Date(transaction.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }),
        transaction.invoice,
        transaction.cashier ? transaction.cashier.name : 'N/A',
        transaction.customer_name || 'N/A',
        transaction.grand_total.toString()
    ]);

    body.unshift(headers);
    body.push([
        { text: 'Grand Total', colSpan: 4, alignment: 'right', style: 'tableHeader' },
        {}, {}, {},
        { text: totalAmount.value.toString(), alignment: 'right', style: 'tableHeader' }
    ]);

    const docDefinition = {
        pageSize: 'A4',
        pageMargins: [40, 60, 40, 60],
        content: [
            { text: 'Transaction Report', style: 'header', alignment: 'center' },
            {
                style: 'tableStyle',
                table: {
                    headerRows: 1,
                    widths: ['auto', '*', '*', '*', 'auto'],
                    body: body
                },
                layout: {
                    fillColor: (rowIndex) => rowIndex === 0 || rowIndex === body.length - 1 ? '#F2F6FD' : null,
                    hLineWidth: () => 0.5,
                    vLineWidth: () => 0.5,
                    hLineColor: () => '#E0E0E0',
                    vLineColor: () => '#E0E0E0'
                }
            }
        ],
        styles: {
            header: {
                fontSize: 18,
                bold: true,
                marginBottom: 20
            },
            tableHeader: {
                bold: true,
                fontSize: 12,
                color: '#000000',
                alignment: 'center',
                margin: [0, 5]
            },
            tableStyle: {
                margin: [0, 5, 0, 15]
            },
            footer: {
                bold: true,
                alignment: 'right'
            }
        }
    };

    pdfMake.createPdf(docDefinition).download('transaction-report.pdf');
}
</script>

<template>

    <Head title="Sales Report" />
    <AuthenticatedLayout>
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div id="pdf-content">

                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <div class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <p class="text-2xl mb-3 ml-4  text-gray-900 dark:text-white">Sales Report</p>
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                                <!-- <label for="table-search" class="sr-only">Search</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="table-search" v-model="searchQuery" @input="search"
                                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search for items">
                                </div> -->

                                <div id="date-range-picker" class="flex items-center ml-16 mt-5">
                                    <div class="relative">
                                        <input type="date" v-model="startDate" placeholder="Select date start"
                                            class="input-class bg-gray-50 border text-sm text-gray-600 rounded-lg focus:ring-blue-500 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-500" />
                                    </div>
                                    <span class="mx-4 text-gray-500">to</span>
                                    <div class="relative">
                                        <input type="date" v-model="endDate" placeholder="Select date end"
                                            class="input-class text-sm bg-gray-50 border text-gray-600 rounded-lg focus:ring-blue-500 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-500" />
                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="overflow-x-auto max-h-135 overflow-y-auto">
                            <table class="w-full text-md text-left text-gray-500 dark:text-gray-400 ">
                                <thead
                                    class="text-xs text-gray-700 uppercase w-full bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-8 py-4">Date</th>
                                        <th scope="col" class="px-8 py-3">Invoice</th>
                                        <th scope="col" class="px-8 py-3">Cashier</th>
                                        <th scope="col" class="px-8 py-3">Customer</th>
                                        <th scope="col" class="px-8 py-3">Total</th>



                                    </tr>
                                </thead>
                                <tbody v-if="filteredTransactions && filteredTransactions.length">

                                    <tr v-for="transaction in filteredTransactions" :key="transaction.id"
                                        class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ new Date(transaction.created_at).toLocaleDateString('en-US', {
                                                year:
                                                    'numeric', month: 'long', day: 'numeric'
                                            }) }}
                                        </th>
                                        <td class="px-8 py-3 max-w-[12rem] truncate">
                                            {{ transaction.invoice }}
                                        </td>
                                        <td class="px-8 py-3">{{ transaction.cashier ? transaction.cashier.name : 'N/A'
                                            }}
                                        </td>
                                        <td class="px-8 py-3">{{ transaction.customer_name }}</td>
                                        <td class="px-8 py-3"> {{ transaction.grand_total }}</td>
                                        <td class=" py-3 flex items-center justify-center space-x-2">


                                        </td>

                                    </tr>
                                </tbody>
                                <tr
                                    class="text-gray-700 text-xs w-auto uppercase bg-indigo-50 dark:bg-indigo-700 dark:text-gray-400">
                                    <td colspan="4" class="px-6 py-3 text-right font-bold">Total</td>
                                    <td colspan="1" class="px-6 py-3 font-bold text-left">{{ totalAmount }}</td>

                                </tr>
                                <td colspan="5" class="text-right pt-4 font-bold">
                                    <button type="button" @click="generatePDF"
                                        class="text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Print</button>
                                </td>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        </section>
    </AuthenticatedLayout>
</template>
