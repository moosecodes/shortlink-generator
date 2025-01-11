<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { VBtn } from 'vuetify/components';

const shortlinks = ref([]);

const fetchShortlinks = async () => {
    try {
        console.log('Fetching shortlinks...');
        const response = await axios.get('/api/shortlinks/show/all');
        shortlinks.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

const editShortlink = async (shortlink) => {
    window.location.href = `/shortlinks/update/${shortlink.short_code}`
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
    <div class="">
        <h1 class="text-2xl font-bold m-2">All Shortlinks</h1>

        <div v-if="!shortlinks.length" class="m-2">
            No shortlinks found.
        </div>

        <div
            v-for="shortlink in shortlinks"
            :key="shortlink.id"
            class="flex align-center justify-between m-2 p-2 border border-gray-200 rounded-lg">

            <div>
                <a v-if="shortlink.is_active"
                    :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                    target="_blank"
                    class="text-green-500">
                    <b>{{ shortlink.short_code }}</b>
                </a>
                <span v-else
                    class="text-gray-500">
                    {{ shortlink.short_code }}
                </span>
            </div>

            <div>
                <a v-if="shortlink.is_active"
                :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                target="_blank">
                <b>{{ shortlink.original_url }}</b>
                </a>
                <span v-else>
                    {{ shortlink.original_url }}
                </span>
            </div>

            <div>
                <span><b>{{ `localhost/api/shortlinks/redirect/${shortlink.short_code}` }}</b></span>

            </div>

            <small><b>{{ shortlink.is_active ? 'active' : 'disabled' }}</b></small>

            <div>
                <v-btn
                    color="black"
                    @click="editShortlink(shortlink)">
                    Edit
                </v-btn>
                <v-btn
                    :color="shortlink.is_active ? 'red' : 'green'"
                    @click="toggleActivation(shortlink)"
                    class="m-2">
                    {{ shortlink.is_active ? 'Deactivate' : 'Activate' }}
                </v-btn>
            </div>

        </div>
    </div>
</template>
