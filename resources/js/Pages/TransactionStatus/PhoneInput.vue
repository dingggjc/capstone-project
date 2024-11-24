<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const phoneNumber = ref('');
const errorMessage = ref('');
const successMessage = ref('');

const submitPhoneNumber = () => {
    const form = useForm({
        phone: phoneNumber.value,
    });

    form.post('/send-sms', {
        onError: (errors) => {
            errorMessage.value = errors.phone ? errors.phone[0] : '';
            successMessage.value = '';
        },
        onSuccess: () => {
            successMessage.value = 'Message sent successfully!';
            errorMessage.value = '';
            phoneNumber.value = ''; // Clear the input after success
        },
    });
};
</script>

<style scoped>
.error {
    color: red;
}

.success {
    color: green;
}
</style>

<template>
    <div>
        <h1>Enter Your Phone Number</h1>
        <form @submit.prevent="submitPhoneNumber">
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" v-model="phoneNumber" placeholder="Enter phone number" required />
            </div>
            <button type="submit">Send SMS</button>
        </form>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
    </div>
</template>
