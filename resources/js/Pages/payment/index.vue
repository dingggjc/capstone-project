<script setup>
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { initFlowbite } from "flowbite";
import { ref, computed, onMounted, } from "vue";
import { ElNotification } from 'element-plus';
import Swal from 'sweetalert2';
onMounted(() => {
    initFlowbite();
});

const customerDetails = usePage().props.customerDetails || { name: '', phone: '', vehicle_plate: '' };


const customerform = useForm({
    name: customerDetails.name,
    phone: customerDetails.phone,
    vehicle_plate: customerDetails.vehicle_plate,
});

const submitCustomerForm = () => {
    customerform.post(route('customer.details.save'), {
        onSuccess: () => {
            window.location.reload();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};



const inputAmount = ref(0);
const carts = usePage().props.carts || [];
const cartsTotal = computed(() =>
    carts.reduce((total, item) => total + item.qty * (item.price / item.qty), 0)
);


const change = computed(() => {
    const total = cartsTotal.value;
    return inputAmount.value > total ? (inputAmount.value - total).toFixed(2) : 0;
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
    },
    category: {
        type: Array,
        required: true,
    },
    specials: {
        type: Array,
        required: true,
    },
    others: {
        type: Array,
        required: true,
    },

});

const submitTransaction = () => {
    if (carts.length === 0) {
        ElNotification({
            title: 'Error',
            message: 'Your cart is empty. Please add items before completing the transaction.',
            type: 'error',
        });
        return;
    }

    if (inputAmount.value < cartsTotal.value) {
        ElNotification({
            title: 'Error',
            message: `Insufficient payment. Please provide at least ₱${cartsTotal.value}.`,
            type: 'error',
        });
        return;
    }

    axios
        .post(route('transaction.store'), {
            cash: inputAmount.value,
            change: change.value,
            carts_total: cartsTotal.value,
            customer_name: customerform.name,
            customer_phone: customerform.phone,
            vehicle_plate: customerform.vehicle_plate,
        })
        .then((response) => {
            const invoice = response.data?.invoice;

            if (!invoice) {
                throw new Error('Invoice not available in response data.');
            }

            Swal.fire({
                icon: 'success',
                title: 'Transaction Complete!',
                text: response.data.message || 'The transaction was completed successfully. Would you like to print the receipt?',
                showCancelButton: true,
                confirmButtonText: 'Print Now',
                cancelButtonText: 'Close',
            }).then((result) => {
                if (result.isConfirmed) {
                    router.get(route('transactions.print'), { invoice });
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            });
            clearTransactionData();
        })
        .catch((error) => {
            console.error('Transaction Error:', error.response?.data || error);
            ElNotification({
                title: 'Error',
                message: error.response?.data?.error || 'Failed to complete transaction.',
                type: 'error',
            });
        });
};

const clearTransactionData = () => {
    carts.splice(0);
    inputAmount.value = 0;
    customerform.reset();
};

</script>


<template>

    <Head title="Payment" />

    <section class="bg-gray-200   py-8 antialiased dark:bg-gray-900 mt-20">
        <form action="#"
            class="  rounded-lg max-w-2xl mx-auto shadow-xl border border-gray-200 bg-white pt-8 my-20 pb-8 p-4 dark:border-gray-700 dark:bg-gray-800 sm:p-6">
            <div class="mx-auto max-w-3xl">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Summary</h2>

                <div class="mt-6 space-y-4 border-b border-t border-gray-200 py-8 dark:border-gray-700 sm:mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Information
                    </h4>

                    <dl>
                        <dt class="text-base font-medium text-gray-900 dark:text-white">Individual</dt>
                        <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400"> {{ customerDetails.name
                            }} {{ customerDetails.phone }}</dd>
                        <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400"> Car plate - {{
                            customerDetails.vehicle_plate }}</dd>


                    </dl>

                    <button type="button" data-modal-target="billingInformationModal"
                        data-modal-toggle="billingInformationModal"
                        class="text-base font-medium text-indigo-700 hover:underline dark:text-indigo-500">Edit</button>
                </div>

                <div class="mt-6 sm:mt-8">
                    <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                        <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr v-for="cartItem in carts" :key="cartItem.id">
                                    <td class="whitespace-nowrap py-4 md:w-[384px]">
                                        <div class="flex items-center gap-4">
                                            <a href="#" class="hover:underline"> {{ cartItem.product_inventory_id
                                                ? `${products.find(p => p.product_inventory_id ===
                                                    cartItem.product_inventory_id)?.product_name} `
                                                : '' }}
                                                {{ cartItem.package_id ? packages.find(pkg => pkg.package_id ===
                                                    cartItem.package_id)?.package_name : '' }}
                                                {{ cartItem.specials_id ? specials.find(special => special.specials_id
                                                    ===
                                                    cartItem.specials_id)?.name : '' }}</a>
                                        </div>
                                    </td>

                                    <td class="p-4 text-base font-normal   text-gray-900 dark:text-white"> <span
                                            v-if="cartItem.product_inventory_id">x{{ cartItem.qty || 0 }}</span></td>

                                    <td class="p-4 text-right text-base font-bold text-gray-900 dark:text-white">
                                        ₱ {{ cartItem.price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 space-y-6">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Sub Total</h4>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">₱ {{
                                        carts.reduce((total,
                                            item) => total + item.qty * (item.price /
                                                item.qty), 0) }}</dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Input Amount</dt>
                                    <div>
                                        <input type="text" id="small-input" v-model="inputAmount"
                                            class="block  font-medium text-gray-900 text-right  w-16  p-2 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                    </div>

                                </dl>
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Change</dt>

                                    <dd class="text-base font-medium text-green-600 dark:text-white">₱ {{ change }}</dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">₱ {{ change }} </dd>
                            </dl>
                        </div>
                        <div class="gap-4 sm:flex sm:items-center">
                            <button type="button" @click="$inertia.visit(route('transaction.index'))"
                                class="w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-indigo-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                Return
                            </button>

                            <button type="submit" @click.prevent="submitTransaction"
                                class="mt-4 flex w-full items-center justify-center rounded-lg bg-indigo-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 sm:mt-0">
                                Complete transaction
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>

    <div id="billingInformationModal" tabindex="-1" aria-hidden="true"
        class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-auto w-full max-h-full items-center justify-center overflow-y-auto overflow-x-hidden antialiased md:inset-0">
        <div class="relative max-h-auto w-full max-h-full max-w-lg p-4">
            <!-- Modal content -->
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h3>
                    <button type="button"
                        class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="billingInformationModal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" @submit.prevent="submitCustomerForm">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">
                        <div>
                            <label for="first_name_billing_modal"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> First Name*
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                    </svg>
                                </span>
                                <input type="text" id="name-1" v-model="customerform.name"
                                    class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                    placeholder="Charles Acierto">
                            </div>
                            <p v-if="customerform.errors.name" class="text-red-500 text-xs mt-1">{{
                                customerform.errors.name }}</p>
                        </div>

                        <div>
                            <label for="last_name_billing_modal"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Last Name*
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M7.978 4a2.553 2.553 0 0 0-1.926.877C4.233 6.7 3.699 8.751 4.153 10.814c.44 1.995 1.778 3.893 3.456 5.572 1.68 1.679 3.577 3.018 5.57 3.459 2.062.456 4.115-.073 5.94-1.885a2.556 2.556 0 0 0 .001-3.861l-1.21-1.21a2.689 2.689 0 0 0-3.802 0l-.617.618a.806.806 0 0 1-1.14 0l-1.854-1.855a.807.807 0 0 1 0-1.14l.618-.62a2.692 2.692 0 0 0 0-3.803l-1.21-1.211A2.555 2.555 0 0 0 7.978 4Z" />
                                    </svg>
                                </span>
                                <input type="text" id="phone" v-model="customerform.phone"
                                    class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                    placeholder="09264648501">
                            </div>
                            <p v-if="customerform.errors.phone" class="text-red-500 text-xs mt-1">{{
                                customerform.errors.phone }}</p>
                        </div>
                        <div class="col-span-2">
                            <label for="last_name_billing_modal"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Last Name*
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </span>
                                <input type="text" id="Vehicle-plate" v-model="customerform.vehicle_plate"
                                    class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                    placeholder="Vehicle-plate">
                            </div>
                            <p v-if="customerform.errors.vehicle_plate" class="text-red-500 text-xs mt-1">{{
                                customerform.errors.vehicle_plate }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
                        <button type="submit"
                            class="me-2 inline-flex items-center rounded-lg bg-indigo-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Update</button>
                        <button type="button" data-modal-toggle="billingInformationModal"
                            class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-indigo-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>