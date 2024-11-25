<script setup>
import { ElDrawer } from 'element-plus';
import { ref, computed } from 'vue';
import { onMounted } from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
    transactions: {
        type: Array,
        required: true,
    },
});

onMounted(() => {
    console.log('Received transactions:', props.transactions);
});

const drawer = ref(false);

const searchQuery = ref('');
const selectedPlate = ref(null);


const uniquePlates = computed(() => {
    const seen = new Set();
    return props.transactions.filter((transaction) => {
        if (seen.has(transaction.vehicle_plate)) return false;
        seen.add(transaction.vehicle_plate);
        return true;
    });
});


const transactionsForSelectedPlate = computed(() => {
    if (!selectedPlate.value) return [];
    return props.transactions.filter(
        (transaction) => transaction.vehicle_plate === selectedPlate.value.vehicle_plate
    );
});

</script>

<style>
.v-select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0;
}

.v-select .vs__dropdown-toggle {
    border: none;
    box-shadow: none;
    background-color: transparent;
}
</style>

<template>
    <el-drawer v-model="drawer" title="Recent Customers" direction="ltr" size="35%">
        <div class="sticky top-0 z-10 block">
            <!-- Dropdown -->
            <label for="search-plate" class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Search</label>
            <v-select id="search-plate" :options="uniquePlates" v-model="selectedPlate" label="vehicle_plate"
                class="w-auto p-2.5 ps-10 text-sm mb-5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                placeholder="Search for plates for recent customer" required />
        </div>

        <!-- Display Transactions -->

        <div v-if="selectedPlate" class="grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto">

            <div>
                <label for="name-1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <div class="flex">
                    <span
                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                        </svg>
                    </span>
                    <input type="text" id="name-1" disabled :value="selectedPlate.customer_name"
                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                        placeholder="Input name">
                </div>
                <p vclass="text-red-500 text-xs mt-1"></p>
            </div>

            <!-- Second Input Field -->
            <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
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
                    <input type="text" id="phone" disabled :value="selectedPlate.customer_phone"
                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                        placeholder="Input number">
                </div>
                <p vclass="text-red-500 text-xs mt-1"></p>
            </div>
            <!-- Fourth Input Field -->
            <div class="col-span-2">
                <label for="Vehicle-plate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Vehicle
                    plate</label>
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
                    <input type="text" id="Vehicle-plate" :value="selectedPlate.vehicle_plate"
                        class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                        placeholder="Vehicle-plate">
                </div>
            </div>


            <!-- Transactions List -->
            <div class="col-span-2">
                <div v-for="transaction in transactionsForSelectedPlate" :key="transaction.id"
                    class="rounded-lg border border-gray-200 bg-white p-4 mb-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-white">
                        <strong>Date:</strong> {{ transaction.date }}
                    </h3>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-white">
                        <strong>Package:</strong> {{ transaction.package?.package_name || 'N/A' }}
                    </h3>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-white">
                        <strong>Specials:</strong> {{ transaction.specials?.name || 'N/A' }}
                    </h3>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-white">
                        <strong>Quantity:</strong> {{ transaction.qty }}
                    </h3>
                    <div class="flex justify-end mt-2">
                        <button type="button" @click="$emit('use-transaction', transaction)"
                            class="text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-medium rounded-full text-sm px-5 py-2 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </el-drawer>
</template>
