<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, watch, defineProps } from 'vue';
import { initFlowbite } from 'flowbite';
import { DataTable } from 'simple-datatables';
import { ElDialog } from 'element-plus';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';




onMounted(() => {
    initFlowbite();
    const dataTable = new DataTable("#search-table");

});

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
    packages: {
        type: Array,
        required: true,
    },
    carts_total: {
        type: Number,
        required: true,
    },
    carts: {
        type: Array,
        required: true,
    },
    transactions: {
        type: Array,
        required: true,
    }
});

const payAmount = ref(0);
const changeAmount = ref(0);

watch(payAmount, (newPayAmount) => {
    changeAmount.value = newPayAmount - props.carts_total;
});


// Payment Modal state
const showPaymentModal = ref(false);

const openPaymentModal = () => {
    showPaymentModal.value = true;
};


const form = ref({

    type: 'product',
    product_inventory_id: null,
    package_id: null,
    qty: 1,
});

const addToCart = () => {
    const data = {
        qty: form.value.qty,
        product_inventory_id: form.value.product_inventory_id,
        package_id: form.value.package_id
    };
    Inertia.post(route('transaction.addToCart'), data, {
        onSuccess: () => {

            form.value = {

                type: 'product',
                product_inventory_id: null,
                package_id: null,
                qty: 1,
            };
        }
    });
};



const destroyCart = (cartId) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this item from the cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route('transaction.destroyCart'), {
                data: { cart_id: cartId },
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The item has been removed from the cart.', 'success');
                },
                onError: () => {
                    Swal.fire('Error!', 'The item could not be removed.', 'error');
                },
            });
        }
    });
};


const submitTransaction = (transactionStatus) => {
    Inertia.post(route('transaction.store'), {
        cash: payAmount.value,
        change: changeAmount.value,
        carts_total: props.carts_total,
        customer_name: form.value.customer_name,
        customer_phone: form.value.customer_phone,
        vehicle_type: form.value.vehicle_type,
        vehicle_plate: form.value.vehicle_plate,
        status: transactionStatus // use transactionStatus passed as argument
    }, {
        onSuccess: () => {
            Swal.fire('Success', 'Transaction completed successfully', 'success');

            payAmount.value = 0;
            changeAmount.value = 0;
            form.value = {
                customer_name: '',
                customer_phone: '',
                vehicle_type: '',
                vehicle_plate: ''
            };
        },
        onError: () => {
            Swal.fire('Error', 'Failed to process transaction', 'error');
        }
    });
};


const updateTransactionStatus = (transactionId, newStatus) => {
    Swal.fire({
        title: 'Mark as Paid?',
        text: 'Are you sure you want to change the status to Paid?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.put(route('transaction.updateStatus', { id: transactionId }),
                { status: newStatus },
                {
                    onSuccess: () => {
                        Swal.fire('Updated!', 'The transaction status has been updated to Paid.', 'success');
                    },
                    onError: () => {
                        Swal.fire('Error!', 'Failed to update transaction status.', 'error');
                    }
                }
            );
        }
    });
};

const clearTransaction = () => {
    form.value = {
        type: 'product',
        product_inventory_id: null,
        package_id: null,
        qty: 1,
    };
};




</script>


<template>

    <Head title="Transaction" />
    <AuthenticatedLayout>

        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <form action="#" class=" mx-10 px-4 2xl:px-0" @submit.prevent="addToCart">
                <div class=" px mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">

                    <div class="min-w-0 flex-1 lg:w-3/4 space-y-8 shadow-lg rounded-lg p-6 bg-white dark:bg-gray-800">
                        <div class="space-y-5">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Transaction</h2>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:grid-rows-1">


                                <div class="sm:col-span-2">
                                    <label for="select-product-input"
                                        class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Product*</label>
                                    <select id="select-product-input" v-model="form.product_inventory_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                        <option disabled selected>Select a Product</option>
                                        <option v-for="product in products" :key="product.product_inventory_id"
                                            :value="product.product_inventory_id">
                                            {{ product.product_name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="company-name"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                    <input type="text" id="company-name" v-model="form.qty"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                        placeholder="quantity" required />
                                </div>


                                <div class="sm:col-span-3">
                                    <label for="select-package-input"
                                        class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Package*</label>
                                    <select id="select-package-input" v-model="form.package_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                        <option disabled selected>Select a Package</option>
                                        <option v-for="pkg in packages" :key="pkg.package_id" :value="pkg.package_id">
                                            {{ pkg.package_name }}
                                        </option>
                                    </select>
                                </div>



                                <div class="sm:col-span-3">
                                    <button type="button" @click.prevent="addToCart"
                                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-indigo-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-indigo-100 dark:border-indigo-600 dark:bg-indigo-800 dark:text-indigo-400 dark:hover:bg-indigo-700 dark:hover:text-white dark:focus:ring-indigo-700">Add
                                        item</button>
                                </div>
                                <div class="sm:col-span-3">
                                    <button type="button" @click="clearTransaction"
                                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-5 py-2.5 text-sm font-medium text-red-900 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-red-100 dark:border-red-600 dark:bg-red-800 dark:text-indigo-400 dark:hover:bg-red-700 dark:hover:text-white dark:focus:ring-red-700">Clear</button>
                                </div>
                            </div>
                        </div>

                    </div>



                    <div class="mt-6 sm:mt-8 lg:mt-0 sm:w-1/2">
                        <div class="space-y-4 shadow-lg rounded-lg px-5 py-5 bg-white dark:bg-gray-800">
                            <div class="relative overflow-x-auto">
                                <h2 class="text-xl font-semibold pb-5 text-gray-900 dark:text-white">Items
                                </h2>
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-indigo-50 dark:bg-indigo-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">#</th>
                                            <th scope="col" class="px-6 py-3">Package</th>

                                            <th scope="col" class="px-6 py-3">Products</th>
                                            <th scope="col" class="px-6 py-3">Quantity</th>
                                            <th scope="col" class="px-6 py-3"> Total</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cart in carts" :key="cart.id"
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                                <button @click.prevent="destroyCart(cart.id)"
                                                    class="rounded-full bg-red-300 p-2">
                                                    <svg class="w-5 h-5 text-red-800 dark:red-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                    </svg>

                                                </button>
                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                                {{ cart.package?.package_name || 'No Package Selected' }}
                                            </td>

                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ cart.products?.product_name || 'No Product' }}

                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                                {{ cart.qty }}
                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                ₱ {{ cart.price }}
                                            </td>



                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr
                                            class="text-gray-700 text-xs uppercase bg-indigo-50 dark:bg-indigo-700 dark:text-gray-400">
                                            <td colspan="4" class="px-6 py-3 text-right font-bold">Total</td>
                                            <td class="px-6 py-3 font-bold">₱ {{ props.carts_total }}</td>
                                        </tr>
                                    </tfoot>


                                </table>





                            </div>

                            <button type="button" @click="openPaymentModal"
                                class="flex w-full items-center justify-center gap-2 mt-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-indigo-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-indigo-100 dark:border-indigo-600 dark:bg-indigo-800 dark:text-indigo-400 dark:hover:bg-indigo-700 dark:hover:text-white dark:focus:ring-indigo-700">
                                Payment
                            </button>
                        </div>
                    </div>

                </div>
            </form>

            <!-- Table Section -->
            <div>
                <div class="space-y-4 mx-10 2xl:px-0 mt-8 px-5 py-5 bg-white
                    dark:bg-gray-800">
                    <div
                        class="w-full lg:w-full px-5 py-5 shadow-lg rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                        <table id="search-table" class="min-w-full bg-white dark:bg-indigo-800">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Customer Name
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Package
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Product
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Quantity
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Total
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Transaction Date
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-indigo-300 dark:border-indigo-700 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="transactions && transactions.length">
                                <tr v-for="transaction in transactions" :key="transaction.id">
                                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ transaction.customer_name }}
                                    </td>
                                    <td v-for="detail in transaction.details" :key="detail.id"> {{
                                        detail.package?.package_name || 'No Package' }}</td>
                                    <td v-for="detail in transaction.details" :key="detail.id"> {{
                                        detail.product?.product_name || 'No Product' }}</td>
                                    <td v-for="detail in transaction.details" :key="detail.id"> {{ detail.qty }}</td>
                                    <td>{{ transaction.grand_total }}</td>
                                    <td>{{ new Date(transaction.created_at).toLocaleDateString('en-US', {
                                        year:
                                            'numeric', month: 'long', day: 'numeric'
                                    }) }}</td>
                                    <td>
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
                                    <td>
                                        <button @click="updateTransactionStatus(transaction.id, 'Paid')"
                                            class="rounded-full bg-green-300 p-2 text-green-800 hover:bg-green-400"
                                            v-if="transaction.status === 'Pending'">
                                            Mark as Paid
                                        </button>
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
                                </tr>
                            </tbody>

                        </table>
                    </div>

                </div>

            </div>
        </section>




        <el-dialog v-model="showPaymentModal" width="70%">
            <template #header>
                <section class="bg-white pb-10 antialiased dark:bg-gray-900 md:py-">
                    <div class="mx-auto w-auto px-10 2xl:px-0">
                        <section class="bg-white antialiased dark:bg-gray-900">

                            <form @submit.prevent="submitTransaction" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                                <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
                                    <div class="min-w-0 flex-1 space-y-8">
                                        <div class="space-y-4">
                                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Customer
                                                Details
                                            </h2>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="your_name"
                                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                                    <input type="text" v-model="form.customer_name"
                                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                                        placeholder="Name" required />
                                                </div>
                                                <div>
                                                    <label for="phone-input-3"
                                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone
                                                        Number*</label>
                                                    <div class="flex items-center">
                                                        <button id="dropdown-phone-button-3"
                                                            data-dropdown-toggle="dropdown-phone-3"
                                                            class="z-10 inline-flex shrink-0 items-center rounded-s-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                                                            type="button">+63</button>
                                                        <div class="relative w-full">
                                                            <input type="text" v-model="form.customer_phone"
                                                                class="z-20 block w-full rounded-e-lg border border-s-0 border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:border-s-gray-700 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500"
                                                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                                placeholder="123-456-7890" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="Vehicle_type"
                                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Vehicle
                                                        Type</label>
                                                    <input type="text" v-model="form.vehicle_type"
                                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                                        placeholder="Vehicle Type" required />
                                                </div>
                                                <div>
                                                    <label for="vehicle_plate"
                                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Vehicle
                                                        Plate</label>
                                                    <input type="text" v-model="form.vehicle_plate"
                                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                                        placeholder="Vehicle Plate" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-10 lg:max-w-xs xl:max-w-md">
                                        <div class="flow-root">
                                            <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                                                <dl class="flex items-center justify-between gap-4 py-3">
                                                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                                        Subtotal</dt>
                                                    <dd class="text-base font-medium text-gray-900 dark:text-white">₱ {{
                                                        props.carts_total }}</dd>
                                                </dl>
                                                <dl class="flex items-center justify-between gap-4 py-3">
                                                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                                        Payment Amount</dt>
                                                    <dd class="text-base font-medium text-gray-900 dark:text-white">₱ {{
                                                        payAmount }}</dd>
                                                </dl>
                                                <dl class="flex items-center justify-between gap-4 py-3">
                                                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                                        Change</dt>
                                                    <dd class="text-base font-medium text-green-500">₱ {{ changeAmount
                                                        }}
                                                    </dd>
                                                </dl>

                                                <div class="">
                                                    <label for="Pay"
                                                        class="block mb-2 mt-4 text-sm font-medium text-gray-500 dark:text-white">
                                                        Pay</label>
                                                    <input type="number" id="Enter Amount" v-model="payAmount"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Enter Amount" />
                                                    <label for="Pay"
                                                        class="block mb-2 mt-4 text-sm font-medium text-gray-500 dark:text-white">
                                                        Change</label>
                                                    <input type="text" id="Change" v-model="changeAmount"
                                                        class="bg-gray-50 border  mb-5 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Change" disabled />

                                                </div>

                                            </div>
                                        </div>

                                        <div class="space-y-3">
                                            <button type="button" @click="submitTransaction('Paid')"
                                                class="flex w-full items-center justify-center rounded-lg bg-indigo-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Pay
                                                Now</button>
                                        </div>
                                        <div class="space-y-3">
                                            <button type="button" @click="submitTransaction('Pending')"
                                                class="flex w-full items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Pay
                                                Later</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </section>
            </template>
        </el-dialog>
    </AuthenticatedLayout>
</template>
