<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
import Swal from 'sweetalert2';
import { ElDialog } from 'element-plus';


defineProps({
    users: Array,
    roles: Array,
});

const showEditUserModal = ref(false);
const showCreateUserModal = ref(false);

const editUser = ref({
    user_id: null,
    name: '',
    email: '',
    password: '',
    role: ''
});

const userForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: ''
});

const openCreateUserModal = () => {
    resetFormData();
    showCreateUserModal.value = true;
};

const closeCreateUserModal = () => {
    showCreateUserModal.value = false;
    resetFormData();
};

const handleAddUser = () => {
    userForm.post(route('users.store'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'User Created',
                text: 'The user was created successfully!',
                confirmButtonText: 'OK',
            });
            closeCreateUserModal();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error creating the user.',
                confirmButtonText: 'OK',
            });
        }
    });
};


const openEditUserModal = (user) => {
    editUser.value = { ...user };
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.password = '';
    userForm.password_confirmation = '';
    userForm.role = user.roles.length ? user.roles[0].name : '';
    editUser.value.user_id = user.id;
    showEditUserModal.value = true;
};


const closeEditUserModal = () => {
    showEditUserModal.value = false;
    resetFormData();
};


const handleEditUser = () => {
    const payload = {
        name: userForm.name,
        email: userForm.email,
        role: userForm.role
    };

    // Only include the password if it's not empty
    if (userForm.password !== '') {
        payload.password = userForm.password;
        payload.password_confirmation = userForm.password_confirmation;
    }

    userForm.put(route('users.update', editUser.value.user_id), {
        data: payload,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'User Updated',
                text: 'The user was updated successfully!',
                confirmButtonText: 'OK',
            });
            closeEditUserModal();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error updating the user.',
                confirmButtonText: 'OK',
            });
        }
    });
};



const resetFormData = () => {
    userForm.reset();
};


const deleteUser = (user) => {
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
            userForm.delete(route('users.destroy', user.id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The user has been deleted.', 'success');
                },
                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error deleting the user.',
                    });
                }
            });
        }
    });
};

onMounted(() => {
    initFlowbite();
});
</script>

<template>

    <Head title="Users" />
    <AuthenticatedLayout>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 mt-20 antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between bg-indigo-100  space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <p class="text-2xl mb-3 ml-4 text-gray-900 dark:text-white">Manage Users</p>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <!-- Add User Button -->
                            <button type="button" @click="openCreateUserModal"
                                class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-400 font-medium rounded-lg text-sm px-4 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none dark:focus:ring-indigo-700">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Add New User
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-135 overflow-y-auto">
                        <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-indigo-50 dark:bg-indigo-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Name</th>
                                    <th scope="col" class="px-8 py-3">Email</th>
                                    <th scope="col" class="px-8 py-3">Role</th>
                                    <th scope="col" class="ml-5 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id"
                                    class="border-b cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-8 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.name }}
                                    </th>
                                    <td class="px-8 py-3">{{ user.email }}</td>
                                    <td class="px-8 py-3">
                                        <span v-for="role in user.roles" :key="role.id" class="mr-2">
                                            {{ role.name }}
                                        </span>
                                    </td>
                                    <td class="py-3 flex items-center justify-center space-x-2">
                                        <!-- Edit Button -->
                                        <button type="button" @click="openEditUserModal(user)"
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
                                        <!-- Delete Button -->
                                        <button type="button" @click="deleteUser(user)"
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

            <!-- Edit User Modal -->
            <el-dialog v-model="showEditUserModal" width="50%">
                <template v-slot:title>
                    <span class="font-bold text-2xl px-2">Edit User</span>
                </template>
                <hr class=" mb-4 border-gray-300 dark:border-gray-600" />
                <form @submit.prevent="handleEditUser">
                    <div class="grid grid-cols-1 md:grid-cols-2 px-2 py-2 gap-4">


                        <div class="mb-4">
                            <label for="editUserName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="userForm.name" type="text" id="editUserName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter Name" />
                        </div>

                        <div class="mb-4">
                            <label for="editUserEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="userForm.email" type="email" id="editUserEmail" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter Email" />
                        </div>


                        <div class="mb-4">
                            <label for="editUserPassword" class="block text-sm font-medium text-gray-700">Password
                                (optional)</label>
                            <input v-model="userForm.password" type="password" id="editUserPassword"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter new password" />
                        </div>


                        <div class="mb-4">
                            <label for="editUserPasswordConfirmation"
                                class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input v-model="userForm.password_confirmation" type="password"
                                id="editUserPasswordConfirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Confirm new password" />
                        </div>


                        <div class="mb-4 md:col-span-2">
                            <label for="editUserRole" class="block text-sm font-medium text-gray-700">Role</label>
                            <select v-model="userForm.role" id="editUserRole" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="closeEditUserModal"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Update User
                        </button>
                    </div>
                </form>
            </el-dialog>


            <!-- Add User Modal -->
            <el-dialog v-model="showCreateUserModal" width="50%">
                <template v-slot:header>
                    <span class="font-bold text-2xl px-2">Add User</span>
                </template>
                <hr class="mb-5 border-gray-300 dark:border-gray-600" />
                <form @submit.prevent="handleAddUser">
                    <div class="grid grid-cols-1 md:grid-cols-2 px-2 py-2 gap-4">
                        <div class="mb-4">
                            <label for="addUserName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="userForm.name" type="text" id="addUserName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter Name" />
                        </div>

                        <div class="mb-4">
                            <label for="addUserEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="userForm.email" type="email" id="addUserEmail" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter Email" />
                        </div>

                        <div class="mb-4">
                            <label for="addUserPassword"
                                class="block text-sm font-medium text-gray-700">Password</label>
                            <input v-model="userForm.password" type="password" id="addUserPassword" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter Password" />
                        </div>

                        <div class="mb-4">
                            <label for="addUserPasswordConfirmation"
                                class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input v-model="userForm.password_confirmation" type="password"
                                id="addUserPasswordConfirmation" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Confirm Password" />
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="addUserRole" class="block text-sm font-medium text-gray-700">Role</label>
                            <select v-model="userForm.role" id="addUserRole" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeCreateUserModal"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Add User
                        </button>
                    </div>

                </form>
            </el-dialog>
        </section>
    </AuthenticatedLayout>
</template>
