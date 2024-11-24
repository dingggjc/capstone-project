<template>
    <div class="send-sms-container">
        <h1>Send SMS</h1>
        <form @submit.prevent="submit">
            <div>
                <label for="number">Phone Number:</label>
                <input type="text" v-model="number" placeholder="Enter phone number" required />
            </div>
            <button type="submit">Send SMS</button>
        </form>

        <!-- Display success or error messages -->
        <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
        <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    setup() {
        const number = ref('');
        const successMessage = ref('');
        const errorMessage = ref('');

        const submit = async () => {
            successMessage.value = '';
            errorMessage.value = '';

            try {
                await Inertia.post('/send-sms', { number: number.value });
                successMessage.value = 'SMS sent successfully!';
                number.value = ''; // Clear the input field
            } catch (error) {
                errorMessage.value = error.response?.data?.error || 'An error occurred.';
            }
        };

        return { number, submit, successMessage, errorMessage };
    }
};
</script>

<style scoped>
.send-sms-container {
    max-width: 400px;
    margin: auto;
}

.alert {
    margin-top: 10px;
}

.alert-success {
    color: green;
}

.alert-danger {
    color: red;
}
</style>
