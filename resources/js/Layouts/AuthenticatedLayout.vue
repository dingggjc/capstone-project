<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

// Initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});

const showingNavigationDropdown = ref(false);
</script>

<template>

    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <nav
            class="bg-white border-b border-gray-200 px-4 py-2.5 shadow-md dark:bg-gray-800  dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
                        aria-controls="drawer-navigation"
                        class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>
                    <div class="shrink-0 flex items-center space-x-2">
                        <Link :href="route('dashboard')" class="flex items-center">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class=" text-3xl">Gardenside</span>
                        </Link>
                    </div>


                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-800 bg-white hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 focus:text-gray-800 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{
                                hidden: showingNavigationDropdown,
                                'inline-flex': !showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{
                                hidden: !showingNavigationDropdown,
                                'inline-flex': showingNavigationDropdown,
                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Page Content -->
        <main class="p-4 sm:ml-64 mt-5">
            <slot />
        </main>
        <!-- Sidebar -->

        <aside
            class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full shadow-xl mt-6 bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
            aria-label="Sidenav" id="drawer-navigation">
            <div class="overflow-y-auto pb-2 pt-6 px-3 h-full bg-white dark:bg-gray-800">

                <ul class="space-y-2">
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:indigo:bg-gray-700">
                            <svg class="w-5 ml-2 h-5 text-indigo-500 transition duration-75 dark:text-indigo-400 group-hover:text-indigo-900 dark:group-hover:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 22 21">
                                <path
                                    d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                                <path
                                    d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                            </svg>
                            <span class="ms-3 pt-2 pb-2 text-lg">Dashboard</span>
                        </NavLink>
                    </li>
                    <hr class="my-20 border-gray-300 dark:border-gray-600" />
                    <!-- Pages Section (No Dropdown) -->
                    <li>
                        <div
                            class="flex items-center p-2 w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                            <svg aria-hidden="true"
                                class="flex-shrink-0 w-6 h-6 text-indigo-500 transition duration-75 group-hover:text-indigo-900 dark:text-indigo-400 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap text-lg">Pages</span>
                        </div>
                        <ul class="py-2 space-y-2 block">
                            <li>
                                <NavLink :href="route('transaction.index')"
                                    :active="route().current('transaction.index')"
                                    class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    <span class="ms-10 pt-2 pb-2 text-base">Transaction</span>
                                </NavLink>
                            </li>
                            <li>
                                <NavLink :href="route('productIndex')" :active="route().current('productIndex')"
                                    class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    <span class="ms-10 pt-2 pb-2 text-base">Products</span>
                                </NavLink>
                            </li>
                            <li>
                                <NavLink :href="route('package.index')" :active="route().current('package.index')"
                                    class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    <span class="ms-10 pt-2 pb-2 text-base">Packages</span>
                                </NavLink>
                            </li>
                            <!-- <li>
                                <NavLink :href="route('customer.index')"
                                    class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    <span class="ms-10 pt-2 pb-2">Customer</span>
                                </NavLink>
                            </li> -->
                        </ul>
                    </li>
                    <hr class="my-20 border-gray-300 dark:border-gray-600" />
                    <!-- Reports Section (No Dropdown) -->
                    <li>
                        <div
                            class="flex items-center p-2 w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                            <svg class="w-6 h-6 text-indigo-500 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                    clip-rule="evenodd" />
                            </svg>


                            <span class="flex-1 ml-3 text-left whitespace-nowrap text-lg">Reports</span>
                        </div>
                        <ul class="py-2 space-y-2 block">
                            <li>
                                <NavLink :href="route('sales.report')" :active="route().current('sales.report')"
                                    class="flex items-center p-2 pl-11 text-lg w-full font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    Sales Report
                                </NavLink>

                            </li>
                            <li>
                                <NavLink :href="route('transaction.report')"
                                    :active="route().current('transaction.report')"
                                    class="flex items-center p-2 pl-11 w-full text-lg font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    Transaction
                                    Report
                                </NavLink>
                            </li>
                        </ul>
                    </li>
                    <hr class="my-20 border-gray-300 dark:border-gray-600" />
                    <!-- User Management Section (No Dropdown) -->
                    <li>
                        <div
                            class="flex items-center p-2 w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                            <svg class="w-6 h-6 text-indigo-500 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">User Management</span>
                        </div>
                        <ul class="py-2 space-y-2 block">
                            <li>
                                <NavLink :href="route('users.index')" :active="route().current('users.index')"
                                    class="flex items-center w-full text-base font-medium text-indigo-900 rounded-lg transition duration-75 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">
                                    <span class="ms-10 pt-2 pb-2">Users</span>
                                </NavLink>
                            </li>


                        </ul>
                    </li>
                </ul>
            </div>
        </aside>


    </div>





</template>
<ul class="pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-700"></ul>