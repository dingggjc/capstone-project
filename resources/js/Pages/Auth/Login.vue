<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Log in" />

    <div class="flex justify-center items-center min-h-screen bg-gray-100">

        <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex">
            <!-- Left Side: Image Section -->
            <div class="hidden md:block w-1/2 bg-gray-200">
                <img src="/images/6333187-removebg-preview.png" class="h-full w-full object-cover" />
            </div>
            <!-- Right Side: Login Form -->
            <div class="w-full md:w-1/2 p-6 md:p-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Hello</h2>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="email" value="Your email" class="block text-sm font-medium text-gray-700" />

                        <TextInput id="email" type="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            v-model="form.email" required autofocus autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Your password"
                            class="block text-sm font-medium text-gray-700" />

                        <TextInput id="password" type="password"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            v-model="form.password" required autocomplete="current-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-sm text-indigo-600 hover:underline focus:outline-none">
                        Lost Password?
                        </Link>
                        <PrimaryButton
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            Login to your account
                        </PrimaryButton>
                    </div>

                    <div class="mt-4 text-center">
                        <Link :href="route('register')" class="text-sm text-gray-600 hover:underline">
                        Not registered? Create account
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>
