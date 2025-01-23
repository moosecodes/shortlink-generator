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
import QrCodeComponent from './QrCodeComponent.vue';

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
    <v-container>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h3">Transform Your Links into Powerful Connections</p>

            <div class="flex justify-center my-4">
                <v-btn color="primary" :variant="state.status ? 'outlined' : 'elevated'" class="mx-2" @click="toggleFeature">Short link</v-btn>
                <v-btn color="primary" :variant="!state.status ? 'outlined' : 'elevated'" class="mx-2" @click="toggleFeature">QR Code</v-btn>
            </div>

            <p class="my-4">Simplify engagement with our powerful URL shortener, dynamic QR Codes, and customized landing pages. Effortlessly connect your audience to the right content while building, editing, and tracking every interaction on our intuitive platform.</p>

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
                        <v-btn class="my-4" type="submit" @click="createFreeLink" variant="flat" color="primary" block>Generate QR Code</v-btn>
                    </v-form>
                    <div v-if="state.short_url">
                        <QrCodeComponent :input="state.short_url" />
                    </div>
                </v-card-text>
            </v-card>
        </v-row>

        <v-row class="flex flex-column justify-center text-center my-4">
            <v-card class="mx-auto my-8">
                <v-list>
                    <v-list-subheader class="flex justify-center text-h6" color="text-secondary">Sign up for free. Your free plan includes:</v-list-subheader>

                    <div class="flex text-secondary">
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check-circle-outline" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>4 short links/month</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check-circle-outline" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>4 custom back-halves/month</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-check-circle-outline" class="text-green"></v-icon>
                            </template>
                            <v-list-item-title>Unlimited link clicks</v-list-item-title>
                        </v-list-item>
                    </div>

                </v-list>

            </v-card>
        </v-row>

        <v-row class="flex justify-evenly">
            <div class="mx-1">
                <v-img src="https://placehold.co/100x50" />
                <v-card-text>Customer</v-card-text>
            </div>
            <div class="mx-1">
                <v-img src="https://placehold.co/100x50" />
                <v-card-text>Customer</v-card-text>
            </div>
            <div class="mx-1">
                <v-img src="https://placehold.co/100x50" />
                <v-card-text>Customer</v-card-text>
            </div>
            <div class="mx-1">
                <v-img src="https://placehold.co/100x50" />
                <v-card-text>Customer</v-card-text>
            </div>
            <div class="mx-1">
                <v-img src="https://placehold.co/100x50" />
                <v-card-text>Customer</v-card-text>
            </div>
        </v-row>

        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">The Best Engagement Platform</p>
            <p>Everything You Need to Build Your Brand: Manage Links, Create QR Codes, and Engage Audiences Everywhere from One Powerful Platform.</p>
            <div class="flex justify-center my-4">
                <v-btn :href="route('register')" color="primary" variant="flat" class="mx-2 my-4">Start now for free</v-btn>
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
            <p class="my-8 text-h4">Trusted by Millions Worldwide for Over a Decade</p>
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
            <p class="my-8 text-h4">Hear What Our Customers Have to Say</p>
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
            <p class="my-4 text-h4">Beyond Link Shortening: A Complete Connection Platform</p>
            <p class="my-4">Effortless Insights: Track, Analyze, and Optimize Your Clicks and Scans All in One Place</p>
            <v-btn class="my-4" :href="route('register')" color="primary" variant="flat">Start now for free</v-btn>
        </v-row>
    </v-container>
</template>
