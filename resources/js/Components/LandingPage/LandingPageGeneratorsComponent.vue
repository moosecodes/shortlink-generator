<script setup>
import QrCodeComponent from '@/Components/QrCodes/QrCodeComponent.vue';
import { reactive } from 'vue';
import {
    VBtn,
    VCard,
    VCardText,
    VCardTitle,
    VForm,
    VRow,
    VTextField,
} from 'vuetify/lib/components/index.mjs';

const props = defineProps({
    auth: Object,
    flash: Object,
    title: String,
    initialState: Object,
});

const state = reactive({
    target_url: '',
    urlRules: [],
    short_url: '',
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
            target_url: state.target_url,
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
    <v-row
        class="flex-column mx-auto my-4 flex max-w-3xl justify-center text-center sm:px-6 lg:px-8"
    >
        <div class="">
            <p class="text-h3 my-8">
                Transform Your Links into Powerful Connections
            </p>
            <p class="my-4">
                Simplify engagement with our powerful URL shortener, dynamic QR
                Codes, and customized landing pages. Effortlessly connect your
                audience to the right content while building, editing, and
                tracking every interaction on our intuitive platform.
            </p>
        </div>
    </v-row>

    <v-row
        class="flex-column mx-auto my-4 flex max-w-3xl justify-center text-center sm:px-6 lg:px-8"
    >
        <v-btn
            class="my-2"
            color="primary"
            :variant="state.switchFeature ? 'outlined' : 'elevated'"
            @click="toggleFeature"
        >
            Short link
        </v-btn>

        <v-btn
            class="my-2"
            color="primary"
            :variant="!state.switchFeature ? 'outlined' : 'elevated'"
            @click="toggleFeature"
        >
            QR Code
        </v-btn>
    </v-row>

    <v-row
        class="flex-column mx-auto flex max-w-3xl justify-center text-center sm:px-6 lg:px-8"
    >
        <v-card v-if="!state.switchFeature">
            <v-card-title>Link Shortener</v-card-title>

            <v-card-text>
                <v-form @submit.prevent>
                    <v-text-field
                        v-model="state.target_url"
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
                        @click="createFreeLink"
                    >
                        Shorten Link
                    </v-btn>

                    <v-btn
                        v-else
                        class="my-4"
                        color="success"
                        :href="state.short_url"
                        target="_blank"
                    >
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
                        v-model="state.target_url"
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
                        @click="createFreeLink"
                    >
                        Generate FREE QR Code
                    </v-btn>
                </v-form>

                <div v-if="state.short_url">
                    <QrCodeComponent :input="state.short_url" />
                </div>
            </v-card-text>
        </v-card>
    </v-row>
</template>
