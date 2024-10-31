<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, defineProps, computed } from 'vue';
import { initFlowbite } from 'flowbite';
import { DataTable } from 'simple-datatables';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

pdfMake.vfs = pdfFonts.pdfMake.vfs;

// Initialize Flowbite and DataTable on mount
onMounted(() => {
    initFlowbite();
    const dataTable = new DataTable("#search-table");
});

const props = defineProps({



    transactions: {
        type: Array,
        required: true,
    }
});
const totalAmount = computed(() => {
    return props.transactions.reduce((sum, transaction) => sum + (transaction.grand_total || 0), 0);
});

function generatePDF() {

    const headers = [
        { text: 'Date', style: 'tableHeader' },
        { text: 'Invoice', style: 'tableHeader' },
        { text: 'Cashier', style: 'tableHeader' },
        { text: 'Customer', style: 'tableHeader' },
        { text: 'Total', style: 'tableHeader' }
    ];

    const body = props.transactions.map(transaction => [
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
        {}, {}, {}, // Empty cells to maintain column count
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
                    widths: ['auto', '*', '*', '*', 'auto'], // Adjust widths to fit content
                    body: body
                },
                layout: {
                    fillColor: (rowIndex, node, columnIndex) => {
                        // Light gray background for header and footer
                        return rowIndex === 0 || rowIndex === body.length - 1 ? '#F2F6FD' : null;
                    },
                    hLineWidth: (i, node) => (i === 0 || i === node.table.body.length ? 0 : 0.5), // Horizontal lines
                    vLineWidth: (i) => (i === 0 ? 0 : 0.5), // Vertical lines
                    hLineColor: (i) => '#E0E0E0',
                    vLineColor: (i) => '#E0E0E0'
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

    // Generate the PDF and download it
    pdfMake.createPdf(docDefinition).download('transaction-report.pdf');
}




</script>


<template>

    <Head title="Transaction" />
    <AuthenticatedLayout>

        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">


            <!-- Table Section -->
            <div id="pdf-content">


                <div class="space-y-4 mx-10 2xl:px-0 mt-8 px-5 py-5 bg-white
                    dark:bg-indigo-800">

                    <div
                        class="w-full lg:w-full px-5 py-5 shadow-lg rounded-lg overflow-hidden bg-white dark:bg-gray-800">

                        <table id="search-table" class="min-w-full bg-white dark:bg-indigo-800">

                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Date
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Invoice
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Cashier
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Customer
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="transactions && transactions.length">
                                <tr v-for="transaction in transactions" :key="transaction.id">
                                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ new Date(transaction.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </td>


                                    <td>{{ transaction.invoice }}</td>


                                    <td>{{ transaction.cashier ? transaction.cashier.name : 'N/A' }}</td>


                                    <td> {{ transaction.customer_name }}</td>

                                    <td class="flex justify-start items-center space-x-2">{{ transaction.grand_total }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr
                                    class="text-gray-700 text-xs uppercase bg-indigo-50 dark:bg-indigo-700 dark:text-gray-400">
                                    <td colspan="4" class="px-6 py-3 text-right font-bold">Total</td>
                                    <td colspan="1" class="px-6 py-3 font-bold text-left">{{ totalAmount }}</td>

                                </tr>
                                <td colspan="5" class="px-6 py-3 text-right font-bold">
                                    <button type="button" @click="generatePDF"
                                        class="text-white  bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Print</button>
                                </td>
                            </tfoot>
                        </table>
                    </div>

                </div>

            </div>
        </section>




    </AuthenticatedLayout>
</template>
