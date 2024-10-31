<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, defineProps, } from 'vue';
import { initFlowbite } from 'flowbite';
import { DataTable } from 'simple-datatables';




// Initialize Flowbite and DataTable on mount
onMounted(() => {
    initFlowbite();
    const dataTable = new DataTable("#search-table");

});




const props = defineProps({
    products: Array,
    packages: Array,
    carts_total: Number,
    carts: Array,
    transactions: Array
});








</script>


<template>

    <Head title="Transaction" />
    <AuthenticatedLayout>

        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">


            <!-- Table Section -->

            <div class="space-y-4 mx-10 2xl:px-0 mt-8 px-5 py-5 bg-white dark:bg-gray-800">

                <div class="w-full lg:w-full px-5 py-5 shadow-lg rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                    <table id="search-table" class="min-w-full bg-white dark:bg-indigo-800">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Cashier
                                </th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Customer Name</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Phone</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Vehicle Type</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Plate</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Invoice</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Total</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Cash</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Change</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Transaction Date</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Status</th>
                                <th
                                    class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                    Actions</th>
                            </tr>

                        </thead>
                        <tbody v-if="transactions && transactions.length">
                            <tr v-for="transaction in transactions" :key="transaction.id">
                                <td>{{ transaction.cashier ? transaction.cashier.name : 'N/A' }}</td>
                                <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                    transaction.customer_name }}
                                </td>

                                <td>{{ transaction.customer_phone }}</td>
                                <td>{{ transaction.vehicle_type }}</td>
                                <td>{{ transaction.vehicle_plate }}</td>
                                <td>{{ transaction.invoice }}</td>
                                <td>{{ transaction.grand_total }}</td>
                                <td>{{ transaction.cash }}</td>
                                <td>{{ transaction.change }}</td>
                                <td>{{ new Date(transaction.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                }) }}</td>
                                <td><span
                                        :class="transaction.status === 'Paid'
                                            ? 'inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'
                                            : 'inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300'">
                                        <span :class="transaction.status === 'Paid'
                                            ? 'w-2 h-2 me-1 bg-green-500 rounded-full'
                                            : 'w-2 h-2 me-1 bg-red-500 rounded-full'"></span>
                                        {{ transaction.status }}
                                    </span></td>
                                <td>

                                <td class="flex justify-start items-center space-x-2">
                                    <button class="rounded-full bg-indigo-300 p-2"
                                        @click="$inertia.get(route('transactions.print'), { invoice: transaction.invoice })">
                                        <svg class="w-6 h-6 text-indigo-800 dark:indigo-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                                d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                        </svg>

                                    </button>

                                </td>
                                </td>
                            </tr>
                        </tbody>


                    </table>
                </div>
            </div>


        </section>





    </AuthenticatedLayout>
</template>
