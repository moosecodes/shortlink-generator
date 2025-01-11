<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

// Define props
const {shortlink_id} = defineProps(['shortlink_id']);

// Reactive state for shortlink details
const shortlink = ref(null);

// Fetch the shortlink data
const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/${shortlink_id}`);
        shortlink.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

onMounted(async () => {
    await nextTick();
    fetchShortlink();
});
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Shortlink Details</h1>
        <div v-if="shortlink">
            <ul>
                <li><strong>Short Code:</strong> {{ shortlink.short_code }}</li>
                <li><strong>Original URL:</strong> {{ shortlink.original_url }}</li>
                <li><strong>UTM Source:</strong> {{ shortlink.utm_source }}</li>
                <li><strong>UTM Medium:</strong> {{ shortlink.utm_medium }}</li>
                <li><strong>UTM Campaign:</strong> {{ shortlink.utm_campaign }}</li>
                <li><strong>UTM Term:</strong> {{ shortlink.utm_term }}</li>
                <li><strong>UTM Content:</strong> {{ shortlink.utm_content }}</li>
                <li><strong>Total Clicks:</strong> {{ shortlink.total_clicks }}</li>
                <li><strong>Unique Clicks:</strong> {{ shortlink.unique_clicks }}</li>
                <li><strong>Is Active:</strong> {{ shortlink.is_active ? 'Yes' : 'No' }}</li>
            </ul>
        </div>
        <div v-else>
            <p>Loading...</p>
        </div>
    </div>
</template>
