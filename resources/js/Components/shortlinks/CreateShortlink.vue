
<script setup>
import { ref } from 'vue';

const originalUrl = ref('');
const utmSource = ref('');
const utmMedium = ref('');
const utmCampaign = ref('');
const utmTerm = ref('');
const utmContent = ref('');
const isActive = ref(true);
const message = ref('');

const submitForm = async () => {
    try {
        const response = await axios.post('/api/shortlinks', {
            original_url: originalUrl.value,
            utm_source: utmSource.value,
            utm_medium: utmMedium.value,
            utm_campaign: utmCampaign.value,
            utm_term: utmTerm.value,
            utm_content: utmContent.value,
            is_active: isActive.value,
        });
        message.value = 'Shortlink created successfully!';
        // Reset form fields
        originalUrl.value = '';
        utmSource.value = '';
        utmMedium.value = '';
        utmCampaign.value = '';
        utmTerm.value = '';
        utmContent.value = '';
        isActive.value = true;
    } catch (error) {
        console.error('Error creating shortlink:', error);
        message.value = 'Error creating shortlink.';
    }
};
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Create Shortlink</h1>
        <form @submit.prevent="submitForm">
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
            <div>
                <label for="isActive">Is Active:</label>
                <input type="checkbox" id="isActive" v-model="isActive" />
            </div>
            <button type="submit">Create Shortlink</button>
        </form>
        <p v-if="message">{{ message }}</p>
    </div>
</template>
