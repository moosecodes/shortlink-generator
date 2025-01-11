<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shortlinks = ref([]);

const fetchShortlinks = async () => {
    try {
        const response = await axios.get('/api/shortlinks');
        shortlinks.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active ? `/api/shortlinks/${shortlink.short_code}/deactivate` : `/api/shortlinks/${shortlink.short_code}/activate`;
        await axios.patch(route);
        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

onMounted(fetchShortlinks);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">All Shortlinks</h1>
        <ul>
            <li v-for="shortlink in shortlinks" :key="shortlink.id" class="mb-2">
                <span>{{ shortlink.short_code }} - {{ shortlink.original_url }}</span>
                <button @click="toggleActivation(shortlink)" class="ml-4 px-2 py-1 bg-blue-500 text-white rounded">
                    {{ shortlink.is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </li>
        </ul>
    </div>
</template>
