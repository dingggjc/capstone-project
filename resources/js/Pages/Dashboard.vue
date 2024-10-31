<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
import { initFlowbite } from "flowbite";
import NavLink from '@/Components/NavLink.vue';

// Define props
const props = defineProps({
    products: {
        type: Array,
        required: true
    },
    today_sales: {
        type: Number,
        required: true
    },
    transactions_count: {
        type: Number,
        required: true
    },
    total_product_stock: {
        type: Number,
        required: true
    },
    top_selling_products: {
        type: Array,
        required: true
    },
    top_selling_packages: {
        type: Array,
        required: true
    }
});


onMounted(() => {
    initFlowbite();
});




</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <main class="p-4 h-auto pt-20">

            <!-- Dashboard Header -->
            <div class="flex justify-between mx-5 items-center mb-8">
                <div>
                    <h1 class="text-3xl mb-3 font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-sm  text-indigo-700 dark:text-gray-400">Daily Reports</p>
                </div>
                <div class="flex space-x-2">
                    <!-- Add New Transaction Button -->
                    <NavLink :href="route('transaction.index')"><button type="button"
                            class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-700">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            New Transaction
                        </button>
                    </NavLink>
                </div>
            </div>

            <!-- Cards Section: Two Columns Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Card 1 -->

                <div class="rounded-lg border-gray-300 dark:border-gray-600 p-4 h-full">
                    <div
                        class="block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 h-full p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-2xl  tracking-tight font-semibold text-gray-700 dark:text-white">
                                Sales Today
                            </h4>
                            <div class="rounded-full bg-indigo-200 p-3 inline-flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                        d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-4xl text-indigo-900 dark:text-white">₱ {{ today_sales }}</p>
                        <div class="mt-auto text-right">
                            <hr class="my-2 border-gray-300 dark:border-gray-600" />
                            <NavLink :href="route('sales.report')">
                                <a class="cursor-pointer underline text-sm text-indigo-700 dark:text-gray-400">See
                                    details</a>
                            </NavLink>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="rounded-lg border-gray-300 dark:border-gray-600 p-4 h-full">
                    <div
                        class="block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 h-full p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-2xl  font-semibold tracking-tight text-gray-700 dark:text-white">
                                Transaction
                            </h4>
                            <div class="rounded-full bg-indigo-200 p-3 inline-flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M5 18h14M5 18v3h14v-3M5 18l1-9h12l1 9M16 6v3m-4-3v3m-2-6h8v3h-8V3Zm-1 9h.01v.01H9V12Zm3 0h.01v.01H12V12Zm3 0h.01v.01H15V12Zm-6 3h.01v.01H9V15Zm3 0h.01v.01H12V15Zm3 0h.01v.01H15V15Z" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-4xl text-indigo-900 dark:text-white">{{ transactions_count }}</p>
                        <div class="mt-auto text-right">
                            <hr class="my-2 border-gray-300 dark:border-gray-600" />
                            <NavLink :href="route('transaction.report')">
                                <a class="cursor-pointer underline text-sm text-indigo-700 dark:text-gray-400">See
                                    details</a>
                            </NavLink>
                        </div>
                    </div>
                </div>



                <!-- Card 3 -->
                <div class="rounded-lg border-gray-300 dark:border-gray-600 p-4 h-full">
                    <div
                        class="block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 h-full p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-2xl  font-semibold tracking-tight text-gray-700 dark:text-white">
                                Product Stock
                            </h4>
                            <div class="rounded-full bg-indigo-200 p-3 inline-flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M10 21v-9m3-4H7.5a2.5 2.5 0 1 1 0-5c1.5 0 2.875 1.25 3.875 2.5M14 21v-9m-9 0h14v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8ZM4 8h16a1 1 0 0 1 1 1v3H3V9a1 1 0 0 1 1-1Zm12.155-5c-3 0-5.5 5-5.5 5h5.5a2.5 2.5 0 0 0 0-5Z" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-4xl text-indigo-900 dark:text-white">{{ total_product_stock }}</p>

                        <div class="mt-auto text-right">
                            <hr class="my-2 border-gray-300 dark:border-gray-600" />
                            <NavLink :href="route('productIndex')">
                                <a class="cursor-pointer underline text-sm text-indigo-700 dark:text-gray-400">See
                                    details</a>
                            </NavLink>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="rounded-lg border-gray-300 dark:border-gray-600 p-4 h-full">
                    <div
                        class="block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 h-full p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-2xl  font-semibold tracking-tight text-gray-700 dark:text-white">
                                Customers
                            </h4>
                            <div class="rounded-full bg-indigo-200 p-3 inline-flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                        d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-4xl text-indigo-900 dark:text-white">{{ transactions_count }}</p>
                        <hr class="my-2 border-gray-300 dark:border-gray-600" />
                        <div class="mt-auto text-right">

                        </div>
                    </div>
                </div>
            </div>




            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-4">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-5 ">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500  dark:text-gray-400">
                        <caption
                            class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                            Top selling products weekly

                        </caption>
                        <thead
                            class="text-xs text-gray-700 uppercase bg-indigo-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Product name
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Unit Sold Per Day
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total Sales
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in top_selling_products" :key="product.product_inventory_id"
                                class="bg-white border-b hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product.product.product_name }}
                                </th>

                                <td class="px-6 py-4">
                                    {{ product.total_qty }}
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ product.total_sales }}
                                </td>

                            </tr>


                        </tbody>
                    </table>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-5 ">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <caption
                            class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                            Top selling packages weekly

                        </caption>
                        <thead
                            class="text-xs text-gray-700 uppercase bg-indigo-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Package name
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Package Sold Per Day
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total Sales
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pkg in top_selling_packages" :key="pkg.package_id"
                                class="bg-white border-b hover:bg-gray-100 cursor-pointer dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ pkg.package.package_name }}
                                </th>

                                <td class="px-6 py-4">
                                    {{ pkg.total_qty }}
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ pkg.total_sales }}
                                </td>

                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>


        </main>

    </AuthenticatedLayout>
</template>
