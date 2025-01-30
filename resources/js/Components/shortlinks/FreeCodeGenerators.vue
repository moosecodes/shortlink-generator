<script setup>
import { reactive } from 'vue';
import QrCodeComponent from '@/Components/shortlinks/QrCodeComponent.vue';
import {
    VBtn,
    VForm,
    VTextField,
    VCard,
    VCardTitle,
    VCardText,
} from 'vuetify/lib/components/index.mjs';

const props = defineProps({
    auth: Object,
    flash: Object,
    title: String,
    initialState: Object,
});

const state = reactive({
    userId: 999,
    user_url: '',
    urlRules: [],
    short_url: '',
    metadata: [],
    message: '',
    switchFeature: false,
});

const toggleFeature = () => {
    state.switchFeature = !state.switchFeature;
    return state.switchFeature ? 'shortlink' : 'qr';
};

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
</script>

<template>
    <div>
        <div class="flex justify-center my-4">
            <v-btn
                class="mx-2"
                color="primary"
                :variant="state.switchFeature ? 'outlined' : 'elevated'"
                @click="toggleFeature">
                    Short link
            </v-btn>
            <v-btn
                class="mx-2"
                color="primary"
                :variant="!state.switchFeature ? 'outlined' : 'elevated'"
                @click="toggleFeature">
                    QR Code
            </v-btn>
        </div>
        <v-card v-if="!state.switchFeature">
            <v-card-text>
                <v-form @submit.prevent>
                    <v-card-title>Link Shortener</v-card-title>
                    <v-text-field
                        v-model="state.user_url"
                        variant="solo-filled"
                        label="Paste your URL here"
                        :rules="state.urlRules"
                    />
                    <v-btn
                        v-if="!state.short_url"
                        class="my-4"
                        color="primary"
                        ype="submit"
                        variant="flat"
                        block
                        @click="createFreeLink">
                            Shorten Link
                    </v-btn>
                    <v-btn
                        v-else
                        class="my-4"
                        color="success":href="state.short_url"
                        target="_blank">
                            {{ state.short_url }}
                    </v-btn>
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
                        label="Paste your long link here"
                        :rules="state.urlRules"
                    />
                    <v-btn
                        class="my-4"
                        type="submit"
                        variant="flat"
                        color="primary"
                        block
                        @click="createFreeLink">
                            Generate FREE QR Code
                    </v-btn>
                </v-form>
                <div v-if="state.short_url">
                    <QrCodeComponent :input="state.short_url" />
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>
