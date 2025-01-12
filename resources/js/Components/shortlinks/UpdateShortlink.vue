<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { VBtn } from 'vuetify/components';

const { shortlink_id } = defineProps(['shortlink_id']);

const shortlink = ref(null);
const originalUrl = ref('');
const utmSource = ref('');
const utmMedium = ref('');
const utmCampaign = ref('');
const utmTerm = ref('');
const utmContent = ref('');
const message = ref('');

const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/show/${shortlink_id.shortlink_id}`);
        shortlink.value = response.data;
        originalUrl.value = shortlink.value.original_url;
        utmSource.value = shortlink.value.utm_source;
        utmMedium.value = shortlink.value.utm_medium;
        utmCampaign.value = shortlink.value.utm_campaign;
        utmTerm.value = shortlink.value.utm_term;
        utmContent.value = shortlink.value.utm_content;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch('/api/shortlinks/update', {
            id: shortlink_id,
            original_url: originalUrl.value,
            utm_source: utmSource.value,
            utm_medium: utmMedium.value,
            utm_campaign: utmCampaign.value,
            utm_term: utmTerm.value,
            utm_content: utmContent.value,
        });

        message.value = 'Shortlink updated successfully!';
    } catch (error) {
        console.error('Error updating shortlink:', error);
        message.value = 'Error updating shortlink.';
    }
};

onMounted(fetchShortlink);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-2">Update Shortlink</h1>
        <div class="font-bold font-gray-500 my-2">{{ shortlink_id.shortlink_id }}</div>
        <form @submit.prevent="submitForm">
            <v-btn color="primary" class="my-2" type="submit">Update Shortlink</v-btn>
            <p v-if="message">{{ message }}</p>
            <div>
                <label for="originalUrl">Original URL:</label>
                <input type="text" id="originalUrl" v-model="originalUrl" required />
            </div>
            <div>
                <label for="utmSource">UTM Source:</label>
                <input type="text" id="utmSource" v-model="utmSource" />
            </div>
            <div>
                <label for="utmMedium">UTM Medium:</label>
                <input type="text" id="utmMedium" v-model="utmMedium" />
            </div>
            <div>
                <label for="utmCampaign">UTM Campaign:</label>
                <input type="text" id="utmCampaign" v-model="utmCampaign" />
            </div>
            <div>
                <label for="utmTerm">UTM Term:</label>
                <input type="text" id="utmTerm" v-model="utmTerm" />
            </div>
            <div>
                <label for="utmContent">UTM Content:</label>
                <input type="text" id="utmContent" v-model="utmContent" />
            </div>
        </form>
    </div>
</template>
