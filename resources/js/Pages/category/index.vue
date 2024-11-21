<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ElDialog } from 'element-plus';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

defineProps({
    category: Array
});

const showAddCategoryModal = ref(false);
const showEditCategoryModal = ref(false);

const categoryForm = useForm({
    category_id: '',
    category_name: '',
    category_description: '',
    examples: []
});

const newExample = ref('');

const addExample = () => {
    if (newExample.value.trim()) {
        categoryForm.examples.push(newExample.value.trim());
        newExample.value = '';
    }
};

const removeExample = (index) => {
    categoryForm.examples.splice(index, 1);
};



const openAddCategoryModal = () => {
    resetFormData();
    showAddCategoryModal.value = true;
};
const closeAddCategoryModal = () => {
    showAddCategoryModal.value = false;
    resetFormData();
};


const openEditCategoryModal = (category) => {
    categoryForm.category_id = category.category_id;
    categoryForm.category_name = category.category_name;
    categoryForm.category_description = category.category_description;
    categoryForm.examples = category.examples.map(example => example.example_name);
    showEditCategoryModal.value = true;
};

const closeEditCategoryModal = () => {
    showEditCategoryModal.value = false;
    resetFormData();
};


const handleEditCategory = () => {
    if (!categoryForm.category_id) {
        console.error('Category ID is missing');
        return;
    }
    categoryForm.put(route('category.update', categoryForm.category_id), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Category Updated',
                text: 'Category was updated successfully!',
                confirmButtonText: 'OK',
            });
            closeEditCategoryModal();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error updating the Category.',
                confirmButtonText: 'OK',
            });
        }
    });
};




const handleAddCategory = () => {
    categoryForm.post(route('category.store'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Category added successfully',
                text: 'Category was added successfully!',
                confirmButtonText: 'OK',
            });
            closeAddCategoryModal();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error adding the Category.',
                confirmButtonText: 'OK',
            });
        },
    });
};


const deleteCategory = (category) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not recover any of this',
        icon: 'warning',
        showCancelButton: true,
        confrimButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
    }).then((result) => {
        if (result.isConfirmed) {
            categoryForm.delete(route('category.destroy', category.category_id), {
                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Category deleted',
                        text: 'Category has been deleted succesfully!',
                        confirmButtonText: 'OK',
                    });
                },
                onError: () => {
                    Swal.Fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error deleting the category',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }
    });
};
const resetFormData = () => {
    categoryForm.reset();
};
</script>


<template>

    <Head title="Categories" />
    <AuthenticatedLayout>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 mt-20 antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 bg-indigo-100  md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <p class="text-2xl mb-3 ml-4  text-gray-900 dark:text-white">Manage Categories</p>
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
                                <input type="text" id="table-search"
                                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search for category">
                            </div>

                            <button type="button" @click="openAddCategoryModal"
                                class="flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none dark:focus:ring-indigo  -700">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Add Category
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x max-h-135 overflow-y-auto">
                        <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400 ">
                            <thead
                                class="text-xs text-gray-700 uppercase w-full bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Category Name</th>
                                    <th scope="col" class="px-8 py-3">Description</th>
                                    <th scope="col" class="px-8 py-3">Car Types Included</th>
                                    <th scope="col" class="text-center">Actions</th>


                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="category in category" :key="category.id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ category.category_name }}
                                    </th>
                                    <td class="px-8 py-3">{{ category.category_description }} </td>
                                    <td class="px-8 py-3">
                                    <td class="px-8 py-3">
                                        <span v-if="category.examples && category.examples.length > 0">
                                            <span v-for="example in category.examples" :key="example.id"
                                                class="inline-block mr-2 px-2 py-1 bg-indigo-100 text-indigo-700 rounded">
                                                {{ example.example_name }}
                                            </span>
                                        </span>
                                        <span v-else class="text-gray-500">No examples available</span>
                                    </td>
                                    </td>
                                    <td class=" py-3 flex items-center justify-center space-x-2">

                                        <button type="button" @click="openEditCategoryModal(category)"
                                            class="inline-flex items-center px-4 py-2 text-xs font-medium text-indigo-700 bg-white border rounded-lg border-indigo-700 hover:bg-indigo-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-indigo-800 dark:bg-indigo-600 dark:hover:bg-indigo-700">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button type="button" @click="deleteCategory(category)"
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






            <el-dialog v-model="showAddCategoryModal" title="Add Product" width="30%">
                <form @submit.prevent="handleAddCategory">
                    <div class="mb-4">
                        <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input v-model="categoryForm.category_name" type="text" id="categoryName" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter Category name" />
                    </div>
                    <div class="mb-4">
                        <label for="categoryDescription"
                            class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="categoryForm.category_description" id="categoryDescription" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter description" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="categoryExamples" class="block text-sm font-medium text-gray-700">Car Types</label>
                        <div class="flex items-center space-x-2">
                            <input v-model="newExample" type="text" id="categoryExamples" placeholder="Enter car type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            <button type="button" @click="addExample"
                                class="bg-indigo-600 text-white px-2 py-1 rounded">
                                Add
                            </button>
                        </div>
                        <ul class="mt-2">
                            <li v-for="(example, index) in categoryForm.examples" :key="index"
                                class="flex items-center space-x-2">
                                {{ example }}
                                <button @click="removeExample(index)" class="text-red-600">Remove</button>
                            </li>
                        </ul>
                    </div>


                    <div class="flex justify-end">
                        <button type="button" @click="closeAddCategoryModal"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Add
                        </button>
                    </div>
                </form>
            </el-dialog>


            <el-dialog v-model="showEditCategoryModal" title="Edit Product" width="30%">
                <form @submit.prevent="handleEditCategory">
                    <div class="mb-4">
                        <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input v-model="categoryForm.category_name" type="text" id="categoryName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter Category name" />
                    </div>
                    <div class="mb-4">
                        <label for="categoryDescription"
                            class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="categoryForm.category_description" id="categoryDescription"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter description" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="categoryExamples" class="block text-sm font-medium text-gray-700">Car Types</label>
                        <div class="flex items-center space-x-2">
                            <input v-model="newExample" type="text" id="categoryExamples" placeholder="Enter car type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            <button type="button" @click="addExample"
                                class="bg-indigo-600 text-white px-2 py-1 rounded">
                                Add
                            </button>
                        </div>
                        <ul class="mt-2">
                            <li v-for="(example, index) in categoryForm.examples" :key="index"
                                class="flex items-center space-x-2">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                    {{ example }}
                                </span>
                                <button @click="removeExample(index)" class="text-red-600">Remove</button>
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeAddCategoryModal"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Update
                        </button>
                    </div>
                </form>
            </el-dialog>

        </section>
    </AuthenticatedLayout>
</template>