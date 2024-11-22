<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, defineProps, ref, computed } from 'vue';
import { initFlowbite } from 'flowbite';
import Swal from 'sweetalert2';

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
const selectedStatus = ref('Ongoing');
const isStatusClicked = ref(false);

const notifyCustomer = (transactionId) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to notify the customer that their car is ready?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, notify!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(`/transactions/${transactionId}/update-status`, { status: 'Completed' })
                .then((response) => {
                    Swal.fire(
                        'Notified!',
                        'The customer has been notified.',
                        'success'
                    );


                    router.reload();
                })
                .catch((error) => {
                    Swal.fire(
                        'Error!',
                        'There was an issue updating the status.',
                        'error'
                    );
                });
        }
    });
};

const filteredTransactions = computed(() => {
    if (!isStatusClicked.value) {
        return props.transactions;
    }
    return props.transactions.filter(transaction => {
        return transaction.status === selectedStatus.value;
    });
});

const handleStatusChange = (status) => {
    selectedStatus.value = status;
    isStatusClicked.value = true;
};

const search = () => {
    router.get(route('TransactionStatus.index'), { query: searchQuery.value }, { preserveState: true });
};


</script>



<template>

    <Head title="Transaction Status" />
    <AuthenticatedLayout>

        <section class=" p-3 sm:p-5 mt-20  antialiased">

            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col bg-indigo-100  md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <p class="text-2xl mb-3 ml-4  text-gray-900 dark:text-white">Transaction Status</p>
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
                                    <th scope="col" class="px-8 py-3"> Customer Name</th>
                                    <th scope="col" class="px-8 py-3">Invoice</th>
                                    <th scope="col" class="px-8 py-3">Car Plate</th>
                                    <th scope="col" class="px-8 py-3">Transaction Date</th>
                                    <th scope="col" class="px-8 py-3">Status</th>
                                    <th scope="col" class="px-8 py-3 ">Actions</th>

                                </tr>
                            </thead>
                            <tbody v-if="transactions && transactions.length">
                                <tr v-for="transaction in transactions" :key="transaction.id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            transaction.customer_name }}
                                    </th>
                                    <td class="px-8 py-3">{{ transaction.invoice }}</td>
                                    <td class="px-8 py-3"> {{
                                        transaction.vehicle_plate }} </td>
                                    <td class="px-8 py-3">{{ new
                                        Date(transaction.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}</td>
                                    <td class="px-8 py-3">
                                        <span
                                            :class="transaction.status === 'Completed'
                                                ? 'inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'
                                                : 'inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300'">
                                            <span :class="transaction.status === 'Completed'
                                                ? 'w-2 h-2 me-1 bg-green-500 rounded-full'
                                                : 'w-2 h-2 me-1 bg-yellow-500 rounded-full'"></span>
                                            {{ transaction.status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-3">
                                        <button :disabled="transaction.status === 'Completed'"
                                            @click="notifyCustomer(transaction.id)"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                                            :class="{ 'opacity-50 cursor-not-allowed': transaction.status === 'Completed' }">
                                            Notify Customer
                                        </button>
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
