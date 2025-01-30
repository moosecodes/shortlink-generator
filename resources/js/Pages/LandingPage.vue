<script setup>
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import { reactive } from 'vue';
import QrCodeComponent from '@/Components/shortlinks/QrCodeComponent.vue';
import LandingPageFeatures from '@/Components/shortlinks/LandingPageFeatures.vue';
import {
    VBtn,
    VRow,
    VForm,
    VTextField,
    VContainer,
    VCard,
    VCardTitle,
    VCardText,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    auth: Object,
    flash: Object,
    title: String,
});

const state = reactive({
    userId: 999,
    user_url: '',
    urlRules: [],
    short_url: '',
    metadata: [],
    message: '',
    status: false,
});

const createFreeLink = async () => {
    try {
        const response = await axios.post('/api/shortlinks/free', {
            user_id: state.userId,
            user_url: state.user_url,
            free_metadata: state.metadata,
        });
        state.short_url = response.data.short_url;
        state.message = response;
    } catch (error) {
        console.error('Error creating shortlink:', error);
        state.message = error;
    }
};

const toggleFeature = () => {
    state.status = !state.status;
    return state.status ? 'shortlink' : 'qr';
};
</script>

<template>
    <LandingPageLayout title="Landing Page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <v-container :max-width="780">
                <v-row class="flex flex-column justify-center text-center my-4">
                    <p class="my-8 text-h3">Transform Your Links into Powerful Connections</p>
                    <p class="my-4">Simplify engagement with our powerful URL shortener, dynamic QR Codes, and customized landing pages. Effortlessly connect your audience to the right content while building, editing, and tracking every interaction on our intuitive platform.</p>

                    <div class="flex justify-center my-4">
                        <v-btn color="primary" :variant="state.status ? 'outlined' : 'elevated'" class="mx-2" @click="toggleFeature">Short link</v-btn>
                        <v-btn color="primary" :variant="!state.status ? 'outlined' : 'elevated'" class="mx-2" @click="toggleFeature">QR Code</v-btn>
                    </div>

                    <v-card v-if="!state.status">
                        <v-card-text>
                            <v-form @submit.prevent>
                                <v-card-title>Link Shortener</v-card-title>
                                <v-text-field
                                    v-model="state.user_url"
                                    variant="solo-filled"
                                    :rules="state.urlRules"
                                    label="Paste your URL here"
                                ></v-text-field>
                                <v-btn v-if="!state.short_url" class="my-4" type="submit" @click="createFreeLink" variant="flat" color="primary" block>Shorten Link</v-btn>
                                <v-btn v-else :href="state.short_url" target="_blank" class="my-4" color="success">{{ state.short_url }}</v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                    <v-card v-else>
                        <v-card-text>
                            <v-form @submit.prevent>
                                <v-card-title>Generate Free QR Code</v-card-title>
                                <v-text-field
                                    v-model="state.user_url"
                                    variant="solo-filled"
                                    :rules="state.urlRules"
                                    label="Paste your long link here"
                                ></v-text-field>
                                <v-btn class="my-4" type="submit" @click="createFreeLink" variant="flat" color="primary" block>Generate FREE QR Code</v-btn>
                            </v-form>
                            <div v-if="state.short_url">
                                <QrCodeComponent :input="state.short_url" />
                            </div>
                        </v-card-text>
                    </v-card>

                </v-row>

                <LandingPageFeatures />
            </v-container>
        </div>
    </LandingPageLayout>
</template>
