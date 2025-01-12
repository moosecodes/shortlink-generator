<script setup>
import { ref, onMounted } from 'vue';
import { VForm, VContainer, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

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
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">
            Update Shortlink for <span :class="shortlink?.is_active ? 'text-green-500' : 'text-red-500'">{{ shortlink?.short_code }}</span>
        </h1>
    </div>
    <v-form v-model="valid" @submit.prevent="submitForm">
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="originalUrl"
                    label="Original URL"
                    required
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmSource"
                    label="UTM Source"
                    required
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmMedium"
                    label="UTM Medium"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmCampaign"
                    label="UTM Campaign"
                    required
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmTerm"
                    label="UTM Term"
                    required
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmContent"
                    label="UTM Content"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="isActive"
                    label="Is Active"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
        <v-btn type="submit" color="primary" class="mt-4">Update Shortlink</v-btn>
    </v-form>
    <p v-if="message">{{ message }}</p>
</template>
