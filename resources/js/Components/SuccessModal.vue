<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Success</h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="sr-only">Close</span>
                    &times;
                </button>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ message }}</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button @click="closeModal"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-700">
                    Close
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['close']);

function closeModal() {
    emit('close');
}
</script>