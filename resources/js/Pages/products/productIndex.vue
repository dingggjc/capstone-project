<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
import { initFlowbite } from "flowbite";
import { ElDialog } from 'element-plus';
import { useForm } from '@inertiajs/vue3'; // Import useForm for handling form submission
import Swal from 'sweetalert2'; // Import SweetAlert2

defineProps({
    products: Array
});

onMounted(() => {
    initFlowbite();
});

// Modal visibility states and product data
const showCreateProductModal = ref(false);
const showEditProductModal = ref(false);
const showPreviewProductModal = ref(false);

const editProduct = ref({
    product_inventory_id: null,
    product_name: '',
    product_description: '',
    product_price: 0,
    product_quantity: 0
});

const previewProduct = ref({
    product_inventory_id: null,
    product_name: '',
    product_description: '',
    product_price: 0,
    product_quantity: 0
});

// Modal control functions
const openCreateProductModal = () => {
    resetFormData(); // Reset the form before opening the modal
    showCreateProductModal.value = true;
};
const closeCreateProductModal = () => {
    showCreateProductModal.value = false;
    resetFormData(); // Reset the form when closing the modal
};

// Open Edit Modal and populate form
const openEditProductModal = (product) => {
    editProduct.value = { ...product };

    productForm.product_name = product.product_name;
    productForm.product_description = product.product_description;
    productForm.product_price = product.product_price;
    productForm.product_quantity = parseInt(product.product_quantity);
    showEditProductModal.value = true;
};
const closeEditProductModal = () => {
    showEditProductModal.value = false;
    resetFormData(); // Reset the form when closing the modal
};
const openPreviewProductModal = (product) => {
    previewProduct.value = { ...product }; // Set the preview data
    showPreviewProductModal.value = true;
};
const closePreviewProductModal = () => { showPreviewProductModal.value = false; };

// Initialize form only once
const productForm = useForm({
    product_name: '',
    product_price: 0,
    product_description: '',
    product_quantity: 0
});

// Handle form submission for adding a product
const handleAddProduct = () => {
    productForm.post(route('products.store'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Product Added',
                text: 'The product was added successfully!',
                confirmButtonText: 'OK',
            });
            closeCreateProductModal();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error adding the product.',
                confirmButtonText: 'OK',
            });
        }
    });
};

// Handle form submission for updating a product

const handleEditProduct = () => {
    productForm.put(route('products.update', editProduct.value.product_inventory_id), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Product Updated',
                text: 'The product was updated successfully!',
                confirmButtonText: 'OK',
            });
            closeEditProductModal(); // Close the modal and reset the form data
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error updating the product.',
                confirmButtonText: 'OK',
            });
        }
    });
};

// Handle product deletion
const deleteProduct = (product) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            productForm.delete(route('products.destroy', product.product_inventory_id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'Your product has been deleted.', 'success');
                },
                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error deleting the product.',
                    });
                }
            });
        }
    });
};

const getColorClass = (quantity) => {
    if (quantity >= 26) return 'bg-green-500';
    if (quantity >= 11 && quantity <= 25) return 'bg-yellow-500';
    if (quantity <= 10) return 'bg-red-500';
    return 'bg-gray-500';
};

const getStockStatus = (quantity) => {
    if (quantity >= 26) return 'Safe (good)';
    if (quantity >= 11 && quantity <= 25) return 'Low Stock';
    if (quantity <= 10) return 'Very Low Stock';
    return 'Unknown';
};


// Corrected resetFormData function
const resetFormData = () => {
    productForm.reset();
};

</script>







<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>

        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 mt-20  antialiased">

            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <p class="text-2xl mb-3 ml-4  text-gray-900 dark:text-white">Manage Products</p>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <!-- Add Product Button -->
                            <button type="button" @click="openCreateProductModal"
                                class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-400 font-medium rounded-lg text-sm px-4 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none dark:focus:ring-indigo-700">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Add product
                            </button>



                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-135 overflow-y-auto">
                        <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase w-full bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Product name</th>
                                    <th scope="col" class="px-8 py-3">Description</th>
                                    <th scope="col" class="px-8 py-3">Price</th>
                                    <th scope="col" class="px-8 py-3">Stocks</th>
                                    <th scope="col" class="px-8 py-3">Stock Status</th>
                                    <th scope="col" class="px-8 py-3">Date Added</th>
                                    <th scope="col" class="text-center">Actions</th>


                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through products -->
                                <tr v-for="product in products" :key="product.product_inventory_id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.product_name }}
                                    </th>
                                    <td class="px-8 py-3 max-w-[12rem] truncate">
                                        {{ product.product_description }}
                                    </td>
                                    <td class="px-8 py-3">₱{{ product.product_price }}</td>
                                    <td class="px-8 py-3">{{ parseInt(product.product_quantity) }}</td>
                                    <td class="px-8 py-3 flex items-center space-x-2">
                                        <!-- Updated UI Structure -->
                                        <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dd :class="[getColorClass(product.product_quantity)]"
                                                class="me-2 mt-1.5 inline-flex items-center rounded px-2.5 py-0.5 text-xs font-medium">

                                                <span class="text-white">{{ getStockStatus(product.product_quantity)
                                                    }}</span>
                                            </dd>
                                        </dl>
                                    </td>




                                    <td class="px-8 py-3">
                                        {{ new Date(product.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long', day: 'numeric'
                                        }) }}
                                    </td>

                                    <td class="pr-2 py-3 flex items-center justify-center space-x-2">
                                        <!-- Edit Button -->
                                        <button type="button" @click="openEditProductModal(product)"
                                            class="inline-flex items-center px-4 py-2 text-xs font-medium text-indigo-700 bg-white border rounded-lg border-indigo-700 hover:bg-indigo-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <!-- Preview Button -->
                                        <button type="button" @click="openPreviewProductModal(product)"
                                            class="inline-flex items-center px-4 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-indigo-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5c5.523 0 10 6 10 6s-4.477 6-10 6-10-6-10-6 4.477-6 10-6z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8.25a2.25 2.25 0 110 4.5 2.25 2.25 0 010-4.5z" />
                                            </svg>
                                            Preview
                                        </button>
                                        <!-- Delete Button -->
                                        <button type="button" @click="deleteProduct(product)"
                                            class="inline-flex items-center px-4 py-2 text-xs font-medium text-red-600 bg-white border border-red-600 rounded-lg hover:bg-red-600 hover:text-white focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 dark:bg-gray-800 dark:text-red-500 dark:border-red-500 dark:hover:bg-red-600 dark:hover:text-white">
                                            <svg class="w-4 h-4 mr-2" viewBox="0 0 14 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                    d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.09922 0.300781H6.09922ZM4.29922 5.70078C4.29922 5.46209 4.39404 5.23317 4.56282 5.06439C4.73161 4.8956 4.96052 4.80078 5.19922 4.80078C5.43791 4.80078 5.66683 4.8956 5.83561 5.06439C6.0044 5.23317 6.09922 5.46209 6.09922 5.70078V11.1008C6.09922 11.3395 6.0044 11.5684 5.83561 11.7372C5.66683 11.906 5.43791 12.0008 5.19922 12.0008C4.96052 12.0008 4.73161 11.906 4.56282 11.7372C4.39404 11.5684 4.29922 11.3395 4.29922 11.1008V5.70078ZM8.79922 4.80078C8.56052 4.80078 8.33161 4.8956 8.16282 5.06439C7.99404 5.23317 7.89922 5.46209 7.89922 5.70078V11.1008C7.89922 11.3395 7.99404 11.5684 8.16282 11.7372C8.33161 11.906 8.56052 12.0008 8.79922 12.0008C9.03791 12.0008 9.26683 11.906 9.43561 11.7372C9.6044 11.5684 9.69922 11.3395 9.69922 11.1008V5.70078C9.69922 5.46209 9.6044 5.23317 9.43561 5.06439C9.26683 4.8956 9.03791 4.80078 8.79922 4.80078Z" />
                                            </svg>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Product Modal -->
            <el-dialog v-model="showCreateProductModal" title="Add Product" width="30%">
                <form @submit.prevent="handleAddProduct">
                    <div class="mb-4">
                        <label for="productName" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input v-model="productForm.product_name" type="text" id="productName" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product name" />
                    </div>
                    <div class="mb-4">
                        <label for="productDescription"
                            class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="productForm.product_description" id="productDescription" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product description" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="productPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input v-model="productForm.product_price" type="number" id="productPrice" min="0" step="0.01"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product price" />
                    </div>
                    <div class="mb-4">
                        <label for="productPrice" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input v-model="productForm.product_quantity" type="number" id="productQuantity" min="0"
                            step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Quantity" />
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeCreateProductModal"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Add Product
                        </button>
                    </div>
                </form>
            </el-dialog>
            <!-- End Add Product Modal -->

            <!-- Edit Product Modal -->
            <el-dialog v-model="showEditProductModal" title="Edit Product" width="30%">
                <form @submit.prevent="handleEditProduct">
                    <div class="mb-4">
                        <label for="editProductName" class="block text-sm font-medium text-gray-700">Product
                            Name</label>
                        <input v-model="productForm.product_name" type="text" id="editProductName" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product name" />
                    </div>
                    <div class="mb-4">
                        <label for="editProductDescription"
                            class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="productForm.product_description" id="editProductDescription" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product description" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="editProductPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input v-model="productForm.product_price" type="number" id="editProductPrice" min="0"
                            step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter product price" />
                    </div>
                    <div class="mb-4">
                        <label for="editProductQuantity"
                            class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input v-model="productForm.product_quantity" type="number" id="editProductQuantity" min="0"
                            step="1" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Quantity" />
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="showEditProductModal = false"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Update Product
                        </button>
                    </div>
                </form>
            </el-dialog>



            <!-- End Edit Product Modal -->

            <!-- Preview Product Modal -->
            <el-dialog v-model="showPreviewProductModal" title="Product Preview"
                class="relative p-4 w-full max-w-xl h-full md:h-auto">
                <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                    <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                        <h3 class="font-semibold">
                            {{ previewProduct.product_name }}
                        </h3>
                        <p class="font-bold">
                            ₱ {{ previewProduct.product_price }}
                        </p>
                    </div>
                    <div>

                    </div>
                </div>
                <dl>
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Description</dt>
                    <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">{{
                        previewProduct.product_description }}</dd>
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Quantity</dt>
                    <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">
                        {{ parseInt(previewProduct.product_quantity) }}
                    </dd>


                </dl>
                <div class="flex justify-end mt-4">
                    <button type="button" @click="showPreviewProductModal = false"
                        class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                        Close
                    </button>
                </div>


            </el-dialog>
            <!-- End Preview Product Modal -->
        </section>
    </AuthenticatedLayout>
</template>
