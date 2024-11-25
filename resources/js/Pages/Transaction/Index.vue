<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { initFlowbite } from 'flowbite';
import { Inertia } from '@inertiajs/inertia';
import { ElButton, ElNotification } from 'element-plus';
import NavLink from '@/Components/NavLink.vue';
import drawer from '@/Pages/Transaction/drawer.vue';
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";



onMounted(() => {
    initFlowbite();
    const savedTab = localStorage.getItem('activeTab') || 'profile-tab';
    document.getElementById(savedTab).click();
});

const drawerVisible = ref(false);


const searchQuery = ref('');
const filteredCategories = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.category;
    }
    const query = searchQuery.value.toLowerCase();

    return props.category.map(cat => {
        const filteredExamples = cat.examples.filter(example =>
            example.example_name.toLowerCase().includes(query)
        );
        const matchesCategoryName = cat.category_name.toLowerCase().includes(query);
        return {
            ...cat,
            examples: matchesCategoryName ? cat.examples : filteredExamples,
        };
    }).filter(cat => cat.examples.length > 0 || cat.category_name.toLowerCase().includes(query));
});

const selectedCategoryId = ref(null)

const filteredPackages = computed(() => {
    if (!selectedVehicleType.value) {
        return [];
    }
    return props.packages.filter(pkg => pkg.category_id === selectedVehicleType.value.id);
});


const filteredSpecials = computed(() => {
    if (!selectedVehicleType.value) {
        return [];
    }
    return props.specials.filter(special => special.category_id === selectedVehicleType.value.id);
});






const customerDetails = usePage().props.customerDetails || { name: '', phone: '', vehicle_plate: '' };
const customerform = useForm({
    name: customerDetails.name,
    phone: customerDetails.phone,
    vehicle_plate: customerDetails.vehicle_plate,
});


const customerSaved = ref(false);

const submitCustomerForm = () => {
    customerform.post(route('customer.details.save'), {
        onSuccess: () => {
            ElNotification({
                title: 'Success',
                message: 'Customer saved successfully!',
                type: 'success',
            });
            customerSaved.value = true;
        },
        onError: (errors) => {
            ElNotification({
                title: 'Error',
                message: 'Failed to update customer details. Please check the form.',
                type: 'error',
            });
            console.error(errors);
        },
    });
};
const addToCartForm = useForm({
    product_inventory_id: null,
    package_id: null,
    specials_id: null,
    staff_id: null,
    qty: 1,
});

const incrementQty = (product) => {
    if (product.product_qty == null || isNaN(product.product_qty)) {
        product.product_qty = 0;
    }
    product.product_qty = parseInt(product.product_qty) + 1;
};

const decrementQty = (product) => {
    if (product.product_qty > 0) {
        product.product_qty = parseInt(product.product_qty) - 1;
    }
};

const addToCart = (type, id, qty = 1) => {
    if (type === 'staff') {

        const selectedStaff = props.staff.find(staff => staff.staff_id === id);

        if (selectedStaff?.staff_status !== 'Active') {
            ElNotification({
                title: 'Error',
                message: 'Staff not available. Please choose another staff.',
                type: 'error',
            });
            return;
        }
        Inertia.post(route('staff.updateStatus'), { staff_id: id, staff_status: 'Inactive' }, {
            onSuccess: () => {
                ElNotification({
                    title: 'Success',
                    message: 'Staff status updated to Inactive.',
                    type: 'success',
                });
            },
            onError: (errors) => {
                ElNotification({
                    title: 'Error',
                    message: 'Failed to update staff status.',
                    type: 'error',
                });
            },
        });
    } else if (type === 'staff') {
        addToCartForm.staff_id = id;
    }


    const existingPackage = addToCartForm.package_id;
    const existingSpecial = addToCartForm.specials_id;

    if (type === 'package') {
        if (existingPackage) {
            ElNotification({
                title: 'Warning',
                message: 'You can only add one package.',
                type: 'warning',
            });
            return;
        }
        addToCartForm.product_inventory_id = null;
        addToCartForm.package_id = id;
        addToCartForm.specials_id = null;
    } else if (type === 'special') {
        if (existingSpecial) {
            ElNotification({
                title: 'Warning',
                message: 'You can only add one special.',
                type: 'warning',
            });
            return;
        }
        addToCartForm.product_inventory_id = null;
        addToCartForm.package_id = null;
        addToCartForm.specials_id = id;
    } else if (type === 'product') {
        addToCartForm.product_inventory_id = id;
        addToCartForm.package_id = null;
        addToCartForm.specials_id = null;
    } else if (type === 'staff') {
        addToCartForm.staff_id = id;
    } else {
        console.error("Invalid type provided to addToCart:", type);
        return;
    }

    addToCartForm.post(route('cart.add'), {
        onSuccess: () => {
            ElNotification({
                title: 'Success',
                message: 'Added',
                type: 'success',
            });
        },
        onError: (errors) => {
            console.error("Error Adding to Cart:", errors);
            ElNotification({
                title: 'Error',
                message: errors?.error || 'Failed to add',
                type: 'error',
            });
        },
    });
};

const removeFromCart = (id) => {
    Inertia.post(route('cart.remove'), { id }, {
        onSuccess: () => {
            ElNotification({
                title: 'Success',
                message: 'Item removed from cart!',
                type: 'success',
            });
        },
        onError: (errors) => {
            ElNotification({
                title: 'Error',
                message: 'Failed to remove item.',
                type: 'error',
            });
        },
    });
};

const removeStaffFromCart = async (staffId) => {
    const cartItem = props.carts.find(item => item.staff_id === staffId);
    if (!cartItem) {
        ElNotification({
            title: 'Error',
            message: 'Staff not found in cart!',
            type: 'error',
        });
        return;
    }

    try {

        await Inertia.post(route('cart.remove'), { id: cartItem.id }, {
            preserveState: true,
            onSuccess: () => {
                ElNotification({
                    title: 'Success',
                    message: 'Staff removed from cart!',
                    type: 'success',
                });
            },
            onError: (errors) => {
                throw new Error('Failed to remove staff from cart.');
            },
        });


        await Inertia.post(route('staff.updateStatus'), { staff_id: staffId, staff_status: 'Active' }, {
            preserveState: true,
            onSuccess: () => {
                ElNotification({
                    title: 'Success',
                    message: 'Staff status updated to Active.',
                    type: 'success',
                });
            },
            onError: (errors) => {
                throw new Error('Failed to update staff status.');
            },
        });
    } catch (error) {
        ElNotification({
            title: 'Error',
            message: error.message || 'An error occurred while processing your request.',
            type: 'error',
        });
    }
};



const handleMouseOver = (event) => {
    event.target.style.backgroundColor = '#4338ca';
};

const handleMouseOut = (event) => {
    event.target.style.backgroundColor = '#4f46e5';
};




const setActiveTab = (tabId) => {
    localStorage.setItem('activeTab', tabId);
};




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
    staff: {
        type: Array,
        required: true,
    },

});

const isFormValid = computed(() => {
    const hasCustomerDetails =
        customerform.name.trim() !== "" &&
        customerform.phone.trim() !== "" &&
        customerform.vehicle_plate.trim() !== "";
    const hasCartItems = props.carts.length > 0;

    return customerSaved.value && hasCustomerDetails && hasCartItems;
});

const proceedToPayment = () => {
    if (!isFormValid.value) {
        ElNotification({
            title: "Error",
            message: "Please ensure customer details are filled out and the cart is not empty.",
            type: "error",
        });
        return;
    }

    Inertia.get(route("payment.index"));
};


const isDropdownVisible = ref(false);

const selectedVehicleType = ref(null);

const vehicleTypeOptions = computed(() =>
    props.category.flatMap(cat =>
        cat.examples.map(example => ({
            id: cat.category_id,
            example_name: `${example.example_name} (${cat.category_name})`,
        }))
    )
);

</script>

<template>

    <Head title="Transaction" />
    <AuthenticatedLayout>
        <section class="bg-white antialiased dark:bg-gray-900">


            <div class="px-4 mt-6 mx-10 2xl:px-0 sm:mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-[2fr,1fr] gap-12">
                    <!-- Package Selection Column -->
                    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                        <div class="space-y-6">
                            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                                    data-tabs-toggle="#default-tab-content" role="tablist">
                                    <li class="me-2" role="presentation">
                                        <button @click="setActiveTab('profile-tab')"
                                            class="inline-block p-4  focus:text-indigo-600 focus:border-indigo-600 border-b-2 rounded-t-lg"
                                            id="profile-tab" data-tabs-target="#profile" type="button" role="tab"
                                            aria-controls="profile" aria-selected="false">Customer details</button>
                                    </li>
                                    <li class="me-2" role="presentation">
                                        <button @click="setActiveTab('dashboard-tab')"
                                            class="inline-block p-4 border-b-2 rounded-t-lg focus:text-indigo-600 focus:border-indigo-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                            id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                                            aria-controls="dashboard" aria-selected="false">Package Selection</button>
                                    </li>
                                    <li class="me-2" role="presentation">
                                        <button @click="setActiveTab('settings-tab')"
                                            class="inline-block p-4 border-b-2 rounded-t-lg focus:text-indigo-600 focus:border-indigo-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                            id="settings-tab" data-tabs-target="#settings" type="button" role="tab"
                                            aria-controls="settings" aria-selected="false">Special Services</button>
                                    </li>

                                    <li role="presentation">
                                        <button @click="setActiveTab('contacts-tab')"
                                            class="inline-block p-4 border-b-2 rounded-t-lg focus:text-indigo-600 focus:border-indigo-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                            id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab"
                                            aria-controls="contacts" aria-selected="false">Add ons</button>
                                    </li>
                                    <li role="presentation">
                                        <button @click="setActiveTab('staff-tab')"
                                            class="inline-block p-4 border-b-2 rounded-t-lg focus:text-indigo-600 focus:border-indigo-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                            id="staff-tab" data-tabs-target="#staff" type="button" role="tab"
                                            aria-controls="staff" aria-selected="false">Assign Wash Staff</button>
                                    </li>


                                </ul>
                            </div>
                            <div id="default-tab-content">
                                <div class="hidden rounded-lg  dark:bg-gray-800" id="profile" role="tabpanel"
                                    aria-labelledby="package-selection-tab">

                                    <div
                                        class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:p-6">



                                        <div class="mb-5">
                                            <el-button type="primary"
                                                style="margin: 0; background-color: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 8px; transition: background-color 0.3s ease;"
                                                @click="drawerVisible = true" @mouseover="handleMouseOver"
                                                @mouseout="handleMouseOut">
                                                Click for repeated customer
                                            </el-button>

                                        </div>
                                        <drawer v-model="drawerVisible" :transactions="transactions" />
                                        <form @submit.prevent="submitCustomerForm"
                                            class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- First Input Field -->
                                            <div>
                                                <label for="name-1"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                                <div class="flex">
                                                    <span
                                                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" id="name-1" v-model="customerform.name"
                                                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                                        placeholder="Input name">
                                                </div>
                                                <p v-if="customerform.errors.name" class="text-red-500 text-xs mt-1">{{
                                                    customerform.errors.name }}</p>
                                            </div>

                                            <!-- Second Input Field -->
                                            <div>
                                                <label for="phone"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                                                <div class="flex">
                                                    <span
                                                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-white"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M7.978 4a2.553 2.553 0 0 0-1.926.877C4.233 6.7 3.699 8.751 4.153 10.814c.44 1.995 1.778 3.893 3.456 5.572 1.68 1.679 3.577 3.018 5.57 3.459 2.062.456 4.115-.073 5.94-1.885a2.556 2.556 0 0 0 .001-3.861l-1.21-1.21a2.689 2.689 0 0 0-3.802 0l-.617.618a.806.806 0 0 1-1.14 0l-1.854-1.855a.807.807 0 0 1 0-1.14l.618-.62a2.692 2.692 0 0 0 0-3.803l-1.21-1.211A2.555 2.555 0 0 0 7.978 4Z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" id="phone" v-model="customerform.phone"
                                                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                                        placeholder="Input number">
                                                </div>
                                                <p v-if="customerform.errors.phone" class="text-red-500 text-xs mt-1">{{
                                                    customerform.errors.phone }}</p>
                                            </div>
                                            <!-- Fourth Input Field -->
                                            <div class="col-span-1">
                                                <label for="Vehicle-plate"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Vehicle
                                                    plate</label>
                                                <div class="flex">
                                                    <span
                                                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-white"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd"
                                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                                                clip-rule="evenodd" />
                                                        </svg>

                                                    </span>
                                                    <input type="text" id="Vehicle-plate"
                                                        v-model="customerform.vehicle_plate"
                                                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                                        placeholder="Vehicle-plate">
                                                </div>
                                                <p v-if="customerform.errors.vehicle_plate"
                                                    class="text-red-500 text-xs mt-1">{{
                                                        customerform.errors.vehicle_plate }}</p>
                                            </div>

                                            <div class="col-span-1">
                                                <label for="Vehicle-plate"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Vehicle
                                                    Type</label>

                                                <label for="default-search"
                                                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                                                <v-select type="search" id="search-plate" :options="vehicleTypeOptions"
                                                    :getOptionKey="option => option.id" label="example_name"
                                                    v-model="selectedVehicleType" placeholder="Select a vehicle type"
                                                    @input="saveCategorySelection"
                                                    class="  w-auto h-10  p-2.5 ps-10 text-sm mb-5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                                    required />
                                                <!-- Dropdown menu -->
                                                <div id="dropdownSearch" v-show="isDropdownVisible"
                                                    class="z-10 hidden bg-white rounded-lg shadow w-80 dark:bg-gray-700 ">
                                                    <div class="p-3">
                                                        <label for="input-group-search" class="sr-only">Search</label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 20 20">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                                </svg>
                                                            </div>
                                                            <input type="text" id="input-group-search"
                                                                v-model="searchQuery" @input="search"
                                                                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                                                                placeholder="Search for vehicle type">
                                                        </div>
                                                    </div>
                                                    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                                        aria-labelledby="dropdownSearchButton">
                                                        <li v-for="cat in filteredCategories" :key="cat.category_id">
                                                            <div class="font-bold text-gray-900 dark:text-white">{{
                                                                cat.category_name }}</div>
                                                            <ul>
                                                                <li v-for="example in cat.examples" :key="example.id">
                                                                    <div
                                                                        class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                                                        <input :id="'example-' + example.id"
                                                                            type="radio" :value="example.example_name"
                                                                            name="example-radio" @change="() => {
                                                                                saveCategorySelection(cat.category_id);
                                                                                isDropdownVisible = false;
                                                                            }"
                                                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                                        <label :for="'example-' + example.id"
                                                                            class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">
                                                                            {{ example.example_name }}
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>



                                                </div>
                                            </div>
                                            <div class="col-span-2 flex justify-end">
                                                <button type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div class="hidden" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab"
                                    style="max-height: 400px; overflow-y: auto;">
                                    <div
                                        class="rounded-lg border  border-gray-200 bg-white p-2  dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                        <div v-for="pkg in filteredPackages" :key="pkg.package_id"
                                            class="rounded-lg border border-gray-200 bg-white p-4 mb-4 dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h1
                                                        class="text-sm font-medium text-gray-600 hover:underline dark:text-white">
                                                        {{ pkg.package_name }}</h1>
                                                    <h1
                                                        class="text-sm font-medium text-gray-600 pb-0 hover:underline dark:text-white">
                                                        {{ category.find(cat => cat.category_id ===
                                                            pkg.category_id)?.category_name || 'No category assigned' }}
                                                    </h1>

                                                    <a href="#"
                                                        class="text-sm font-medium text-gray-600 hover:underline dark:text-white">
                                                        {{ pkg.package_description }}
                                                    </a>
                                                </div>
                                                <div class="text-end md:w-32">
                                                    <p class="text-sm font-bold text-gray-600 dark:text-white">₱ {{
                                                        pkg.package_price }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4 mt-4">
                                                <button type="button" @click="addToCart('package', pkg.package_id)"
                                                    class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                                    <svg class="w-5 h-5 text-white me-2" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a 1 1 0 0 0 1.414 0l4-4Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Choose Package
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- specials -->
                                <div class="hidden dark:bg-gray-800" id="settings" role="tabpanel"
                                    aria-labelledby="settings-tab" style="max-height: 400px; overflow-y: auto;">
                                    <div v-for="special in filteredSpecials" :key="special.specials_id"
                                        class="rounded-lg border border-gray-200 bg-white p-4 mb-4 dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h1
                                                    class="text-sm font-medium text-gray-600 hover:underline dark:text-white">
                                                    {{ special.name }}</h1>
                                                <h1
                                                    class="text-sm font-medium text-gray-600 pb-0 hover:underline dark:text-white">
                                                    {{ category.find(cat => cat.category_id ===
                                                        special.category_id)?.category_name || 'No category assigned' }}
                                                </h1>
                                                <h1
                                                    class="text-sm font-medium text-gray-600 pb-0 hover:underline dark:text-white">
                                                    {{ category.find(cat => cat.category_id ===
                                                        special.category_id)?.category_example || 'No example available' }}
                                                </h1>

                                                <a href="#"
                                                    class="text-sm font-medium text-gray-600 hover:underline dark:text-white">
                                                    {{ special.description }}
                                                </a>
                                            </div>
                                            <div class="text-end md:w-32">
                                                <p class="text-sm font-bold text-gray-600 dark:text-white">₱ {{
                                                    special.price }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <button type="button" @click="addToCart('special', special.specials_id)"
                                                class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                                <svg class="w-5 h-5 text-white me-2" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd"
                                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a 1 1 0 0 0 1.414 0l4-4Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Choose Package
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- add ons -->
                                <div class="hidden dark:bg-gray-800" id="contacts" role="tabpanel"
                                    aria-labelledby="contacts-tab" style="max-height: 400px; overflow-y: auto;">
                                    <div v-for="product in products" :key="product.product_inventory_id" class="  ">
                                        <div
                                            class="rounded-lg border mb-4 border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                            <div
                                                class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                                <div
                                                    class="flex items-center justify-between md:order-3 md:justify-end">
                                                    <div class="text-end md:order-4 md:w-32">
                                                        <p class="text-sm text-red-900 dark:text-white">stock: {{
                                                            parseInt(product.product_quantity) }}</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-full min-w-0 flex-1 pb-4 space-y-4 md:order-2 md:max-w-md">
                                                    <h1
                                                        class="text-sm font-medium text-gray-900 hover:underline dark:text-white">
                                                        {{ product.product_name }}
                                                    </h1>
                                                    <a href="#"
                                                        class="text-sm font-medium text-gray-900 hover:underline dark:text-white">
                                                        {{ product.product_description }}
                                                    </a>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between md:order-3 md:justify-end">
                                                    <div class="flex items-center">
                                                        <!-- Decrement Button -->
                                                        <button type="button" @click="decrementQty(product)"
                                                            class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 18 2">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M1 1h16" />
                                                            </svg>
                                                        </button>

                                                        <!-- Quantity Input -->
                                                        <input type="text" value="1" v-model="product.product_qty"
                                                            class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                                                            placeholder="1" required />

                                                        <!-- Increment Button -->
                                                        <button type="button" @click="incrementQty(product)"
                                                            class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 18 18">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 1v16M1 9h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="text-end md:order-4 md:w-32">
                                                        <p class="text-base font-bold text-gray-900 dark:text-white">
                                                            ₱{{ product.product_price }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <button type="button"
                                                    @click="addToCart('product', product.product_inventory_id, product.product_qty)"
                                                    class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                                    <svg class="w-5 h-5 text-white me-2" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Choose Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- staff -->
                                <div class="hidden dark:bg-gray-800" id="staff" role="tabpanel"
                                    aria-labelledby="staff-tab" style="max-height: 400px; overflow-y: auto;">
                                    <div
                                        class="rounded-lg border  border-gray-200 bg-white p-2  dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                        <button id="dropdownRadioButton" data-dropdown-toggle="dropdownDefaultRadio"
                                            class="text-white mb-5 bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-xs px-5 py-2 text-center inline-flex items-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800"
                                            type="button">Filter <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="dropdownDefaultRadio"
                                            class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="p-3 space-y-3 text-xs text-gray-700 dark:text-gray-200"
                                                aria-labelledby="dropdownRadioButton">
                                                <li>
                                                    <div class="flex items-center">
                                                        <input id="default-radio-1" type="radio" value=""
                                                            name="default-radio"
                                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label for="default-radio-1"
                                                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Available</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="flex items-center">
                                                        <input checked id="default-radio-2" type="radio" value=""
                                                            name="default-radio"
                                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label for="default-radio-2"
                                                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Unavailable</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-for="staff in staff" :key="staff.staff_id"
                                            class="rounded-lg border border-gray-200 bg-white p-4 mb-4 dark:border-gray-700 dark:bg-gray-800 md:p-6">



                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h1
                                                        class="text-sm font-medium text-gray-600 hover:underline dark:text-white">
                                                        {{ staff.staff_name }}</h1>
                                                    <h1
                                                        class="text-sm font-medium text-gray-600 pb-0 hover:underline dark:text-white">
                                                        {{ staff.staff_phone }}
                                                    </h1>
                                                    <h1
                                                        class="text-sm font-medium text-gray-600 pb-0 hover:underline dark:text-white">

                                                    </h1>

                                                    <a href="#"
                                                        class="text-sm font-medium text-gray-600 hover:underline dark:text-white">

                                                    </a>
                                                </div>
                                                <div class="text-end md:w-32">
                                                    <p class="text-sm font-bold text-gray-600 dark:text-white">
                                                        <span
                                                            :class="staff.staff_status === 'Active'
                                                                ? 'inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'
                                                                : 'inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300'">
                                                            <span :class="staff.staff_status === 'Active'
                                                                ? 'w-2 h-2 me-1 bg-green-500 rounded-full'
                                                                : 'w-2 h-2 me-1 bg-red-500 rounded-full'"></span>
                                                            {{ staff.staff_status }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4 mt-4">
                                                <button type="button" @click="addToCart('staff', staff.staff_id)"
                                                    class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                                    <svg class="w-5 h-5 text-white me-2" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a 1 1 0 0 0 1.414 0l4-4Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Assign Staff
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>


                    <!-- Order Summary Column -->
                    <section class="  bg-white py-8 antialiased dark:bg-gray-900 md:py-16">

                        <div class="space-y-6">

                            <div
                                class="rounded-lg border border-gray-200 bg-white pt-8 my-20 pb-8 p-4 dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                                <p class="text-xl font-semibold text-gray-900 mb-2 dark:text-white">Assigned Staff</p>
                                <div v-for="(staffId, index) in [...new Set(carts.map(item => item.staff_id).filter(id => id))]"
                                    :key="index" class="flex items-center text-gray-500 justify-between mb-2 gap-4">

                                    {{ staff.find(s => s.staff_id === staffId)?.staff_name || 'No staff found'
                                    }}

                                    <dt class="text-sm font-normal text-gray-500 dark:text-gray-400">

                                    </dt>


                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                        <button @click="removeStaffFromCart(staffId)" type="button"
                                            class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                            Remove
                                        </button>
                                    </dd>
                                </div>
                                <p class="text-xl font-semibold text-gray-900 mt-5 dark:text-white">Summary</p>

                                <div class="space-y-4 pt-4 pb-4">
                                    <!-- Loop through cart items -->
                                    <div v-for="cartItem in carts.filter(item => !item.staff_id)" :key="cartItem.id"
                                        class="flex items-center justify-between gap-4">
                                        <!-- Remove button -->
                                        <button @click="removeFromCart(cartItem.id)" type="button"
                                            class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                            Remove
                                        </button>

                                        <!-- Item name and type -->
                                        <dt class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ cartItem.product_inventory_id
                                                ? `${products.find(p => p.product_inventory_id ===
                                                    cartItem.product_inventory_id)?.product_name} (x${cartItem.qty})`
                                                : '' }}
                                            {{ cartItem.package_id ? packages.find(pkg => pkg.package_id ===
                                                cartItem.package_id)?.package_name : '' }}
                                            {{ cartItem.specials_id ? specials.find(special => special.specials_id ===
                                                cartItem.specials_id)?.name : '' }}
                                        </dt>


                                        <!-- Item price -->
                                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                            ₱ {{ cartItem.price }}
                                        </dd>
                                    </div>

                                    <!-- Subtotal -->
                                    <dl
                                        class="flex items-center justify-between gap-4 border-t border-gray-200 pt-4 pb-4 dark:border-gray-700">
                                        <dt class="text-sm font-bold text-gray-900 dark:text-white">Subtotal</dt>
                                        <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                            ₱ {{ carts.reduce((total, item) => total + item.qty * (item.price /
                                                item.qty), 0) }}
                                        </dd>
                                    </dl>
                                </div>

                                <!-- Proceed to Payment Button -->
                                <NavLink v-if="isFormValid" :href="route('payment.index')"
                                    class="flex w-full items-center justify-center rounded-lg bg-indigo-700 py-2.5 text-sm font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                    Proceed to Payment
                                </NavLink>
                                <button v-else :disabled="true" @click="proceedToPayment"
                                    class="flex w-full items-center justify-center rounded-lg bg-gray-500 py-2.5 text-sm font-medium text-white cursor-not-allowed">
                                    Proceed to Payment
                                </button>
                            </div>
                        </div>
                    </section>


                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>