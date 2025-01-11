<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shortlinks = ref([]);

const fetchShortlinks = async () => {
    try {
        const response = await axios.get('/api/shortlinks/show/all');
        shortlinks.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active
            ? `/api/shortlinks/deactivate/${shortlink.short_code}`
            : `/api/shortlinks/activate/${shortlink.short_code}`;

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
        <h1 class="text-2xl font-bold m-4">All Shortlinks</h1>
        <div v-for="shortlink in shortlinks" :key="shortlink.id" class="m-4">
            <div v-if="shortlink.is_active">
                <a
                    :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                    target="_blank"
                    class="text-green-500">
                    <b>{{ shortlink.short_code }}</b> - {{ shortlink.original_url }}
                </a>
            </div>
            <div v-else>{{ shortlink.short_code }} - {{ shortlink.original_url }}</div>
            <button @click="toggleActivation(shortlink)" class="ml-4 px-2 py-1 bg-blue-500 text-white rounded">
                {{ shortlink.is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </div>
    </div>
</template>
