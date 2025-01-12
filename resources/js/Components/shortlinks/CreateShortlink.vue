<script setup>
import { ref } from 'vue';
import { VForm, VContainer, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const originalUrl = ref('');
const utmSource = ref('');
const utmMedium = ref('');
const utmCampaign = ref('');
const utmTerm = ref('');
const utmContent = ref('');
const isActive = ref(true);
const message = ref('');
const valid = ref(false);

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
    <v-app>
        <v-main>
            <v-container>
                <h1 class="text-2xl font-bold my-2">New Shortlink</h1>
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
                    <v-btn type="submit" color="primary" class="mt-4">Create Shortlink</v-btn>
                </v-form>
                <p v-if="message">{{ message }}</p>
            </v-container>
        </v-main>
    </v-app>
</template>
