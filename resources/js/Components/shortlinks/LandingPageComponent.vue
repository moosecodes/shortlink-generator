<script setup>
import { reactive } from 'vue';
import {
    VBtn,
    VRow,
    VImg,
    VIcon,
    VForm,
    VTextField,
    VContainer,
    VCard,
    VCardTitle,
    VCardText,
    VList,
    VListItem,
    VListItemTitle,
    VListSubheader,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    auth: Object,
    flash: Object,
    title: String,
});

const state = reactive({
    userId: 999,
    original_url: '',
    urlRules: [],
    short_url: '',
    metadata: [],
    message: '',
});

const createFreeLink = async () => {
    try {
        const response = await axios.post('/api/shortlinks/free', {
            user_id: state.userId,
            original_url: state.original_url,
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
    <v-container>
        <v-row class="flex flex-column justify-center text-center mb-4">
            <p class="my-8 text-h4">Build stronger digital connections</p>
            <div class="flex justify-center my-4">
                <v-btn color="primary" variant="flat" class="mx-2">Short link</v-btn>
                <v-btn color="primary" variant="flat" class="mx-2">QR Code</v-btn>
            </div>
            <p>Use our URL shortener, QR Codes, and landing pages to engage your audience and connect them to the right information. Build, edit, and track everything inside the Bitly Connections Platform.</p>
            <p class="my-4 text-h5">Paste your long link here</p>
            <v-form fast-fail @submit.prevent>
                <v-text-field
                    v-model="state.original_url"
                    :rules="state.urlRules"
                    label="Enter URL"
                ></v-text-field>
                <v-btn class="my-4" type="submit" @click="createFreeLink" variant="flat" color="primary" block>Shorten</v-btn>
                <v-btn class="my-2"><a :href="state.short_url" target="_blank">{{ state.short_url }}</a></v-btn>
            </v-form>
        </v-row>

        <v-row class="flex flex-column justify-center text-center my-4">
            <v-card class="mx-auto my-8">
                <v-list>
                    <v-list-subheader class="flex justify-center text-h6" color="text-secondary">Sign up for free. Your free plan includes:</v-list-subheader>

                    <div class="flex text-secondary">
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>4 short links/month</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>4 custom back-halves/month</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>Unlimited link clicks</v-list-item-title>
                        </v-list-item>
                    </div>

                </v-list>

            </v-card>
            <div>Icons Icons Icons Icons Icons</div>
        </v-row>

        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">The Bitly Connections Platform</p>
            <p>All the products you need to build brand connections, manage links and QR Codes, and connect with audiences everywhere, in a single unified platform.</p>
            <div class="flex justify-center my-4">
                <v-btn :href="route('register')" color="primary" variant="flat" class="mx-2 my-4">Get started for free</v-btn>
                <!-- <v-btn :href="route('register')" color="primary" variant="flat" class="mx-2">Get a quote</v-btn> -->
            </div>

            <div class="flex justify-center">
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Link Management</v-card-title>
                    <v-card-text>Link Management</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Link Management</v-card-title>
                    <v-card-text>Link Management</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Link Management</v-card-title>
                    <v-card-text>Link Management</v-card-text>
                </v-card>
            </div>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">Adopted and loved by millions of users for over a decade</p>
            <div class="flex justify-center">
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Customer</v-card-title>
                    <v-card-text>Customer</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Customer</v-card-title>
                    <v-card-text>Customer</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Customer</v-card-title>
                    <v-card-text>Customer</v-card-text>
                </v-card>
            </div>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">What our customers are saying</p>
            <div class="flex justify-center">
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Testimonial</v-card-title>
                    <v-card-text>Testimonial</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Testimonial</v-card-title>
                    <v-card-text>Testimonial</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Testimonial</v-card-title>
                    <v-card-text>Testimonial</v-card-text>
                </v-card>
                <v-card class="mx-2">
                    <v-img src="https://placehold.co/400"></v-img>
                    <v-card-title>Testimonial</v-card-title>
                    <v-card-text>Testimonial</v-card-text>
                </v-card>
            </div>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">More than a link shortener</p>
            <p>Knowing how your clicks and scans are performing should be as easy as making them. Track, analyze, and optimize all your connections in one place.</p>
            <v-btn :href="route('register')" color="primary" variant="flat">Get started for free</v-btn>
        </v-row>
    </v-container>
</template>
