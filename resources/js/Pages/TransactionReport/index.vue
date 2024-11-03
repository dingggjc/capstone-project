<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, defineProps, ref, computed } from 'vue';
import { initFlowbite } from 'flowbite';


onMounted(() => {
    initFlowbite();

});


const props = defineProps({
    products: Array,
    packages: Array,
    carts_total: Number,
    carts: Array,
    transactions: Array,
    searchQuery: String
});
const searchQuery = ref(props.searchQuery || '');
const selectedStatus = ref('Pending');


const filteredTransactions = computed(() => {
    return props.transactions.filter(transaction => {
        return transaction.status === selectedStatus.value;
    });
});
const handleStatusChange = (status) => {
    selectedStatus.value = status;
};

const search = () => {
    router.get(route('transaction.report'), { query: searchQuery.value }, { preserveState: true });
};


</script>



<template>

    <Head title="Transaction Report" />
    <AuthenticatedLayout>

        <section class=" p-3 sm:p-5 mt-20  antialiased">

            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col bg-indigo-100  md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <p class="text-2xl mb-3 ml-4  text-gray-900 dark:text-white">Transaction Report</p>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <label for="table-search" class="sr-only">Search</label>
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
                            </div>




                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-135 overflow-y-auto">
                        <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400 ">
                            <thead
                                class="text-xs text-gray-700 uppercase w-full bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Cashier</th>
                                    <th scope="col" class="px-8 py-3"> Customer Name</th>
                                    <th scope="col" class="px-8 py-3">Invoice</th>
                                    <th scope="col" class="px-8 py-3">Product</th>
                                    <th scope="col" class="px-8 py-3">Package</th>
                                    <th scope="col" class="px-8 py-3">Transaction Date</th>
                                    <th scope="col" class="px-8 py-3">Total</th>
                                    <th scope="col" class="px-8 py-3">Change</th>
                                    <th scope="col" class="text-center">Actions</th>


                                </tr>
                            </thead>
                            <tbody v-if="transactions && transactions.length">
                                <tr v-for="transaction in transactions" :key="transaction.id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ transaction.cashier ? transaction.cashier.name : 'N/A' }}
                                    </th>
                                    <td class="px-8 py-3">{{
                                        transaction.customer_name }}</td>
                                    <td class="px-8 py-3">{{ transaction.invoice }}</td>
                                    <td class="px-8 py-3">
                                        <ul>
                                            <li v-for="detail in transaction.details || []" :key="detail.id">
                                                <span v-if="detail.product">
                                                    {{ detail.product.product_name }}
                                                </span>
                                            </li>
                                        </ul>
                                    </td>

                                    <td class="px-8 py-3">
                                        <ul>
                                            <li v-for="detail in transaction.details || []" :key="detail.id">
                                                <span v-if="detail.package">
                                                    {{ detail.package.package_name }}
                                                </span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="px-8 py-3">{{ new
                                        Date(transaction.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}</td>
                                    <td class="px-8 py-3">{{ transaction.grand_total }}</td>
                                    <td class="px-8 py-3">{{ transaction.change }}</td>
                                    <td class="px-8 py-3"> <button class="rounded-full bg-indigo-300 p-2"
                                            @click="$inertia.get(route('transactions.print'), { invoice: transaction.invoice })">
                                            <svg class="w-6 h-6 text-indigo-800 dark:indigo-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                            </svg>

                                        </button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-20 max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col  md:flex-row bg-indigo-100 items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div
                            class="w-full md:w-auto  flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">



                            <button id="dropdownRadioButton" data-dropdown-toggle="dropdownDefaultRadio"
                                class="text-white bg-indigo-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800"
                                type="button">Payment Status <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownDefaultRadio"
                                class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownRadioButton">
                                    <li>
                                        <div class="flex items-center">
                                            <input id="default-radio-1" type="radio" value="" name="default-radio"
                                                @change="handleStatusChange('Paid')"
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="default-radio-1"
                                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paid</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <input checked id="default-radio-2" type="radio" value=""
                                                @change="handleStatusChange('Pending')" name="default-radio"
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="default-radio-2"
                                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pending</label>
                                        </div>
                                    </li>

                                </ul>
                            </div>






                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-135 overflow-y-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                            <thead
                                class="text-xs text-gray-700 uppercase w-full bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Cashier</th>
                                    <th scope="col" class="px-8 py-3"> Customer Name</th>
                                    <th scope="col" class="px-8 py-3">Invoice</th>
                                    <th scope="col" class="px-8 py-3">Transaction Date</th>
                                    <th scope="col" class="px-8 py-3">Status</th>



                                </tr>
                            </thead>
                            <tbody v-if="transactions && transactions.length">
                                <tr v-for="transaction in filteredTransactions" :key="transaction.id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ transaction.cashier ? transaction.cashier.name : 'N/A' }}
                                    </th>
                                    <td class="px-8 py-3">{{
                                        transaction.customer_name }}</td>
                                    <td class="px-8 py-3">{{ transaction.invoice }}</td>


                                    <td class="px-8 py-3">{{ new
                                        Date(transaction.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}</td>
                                    <td class="px-8 py-3">
                                        <span
                                            :class="transaction.status === 'Paid'
                                                ? 'inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'
                                                : 'inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300'">
                                            <span :class="transaction.status === 'Paid'
                                                ? 'w-2 h-2 me-1 bg-green-500 rounded-full'
                                                : 'w-2 h-2 me-1 bg-red-500 rounded-full'"></span>
                                            {{ transaction.status }}
                                        </span>
                                    </td>


                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
