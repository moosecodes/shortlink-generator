<script setup>
import { router } from '@inertiajs/vue3';
import {
    VBtn,
    VRow,
    VImg,
    VIcon,
    VTextField,
    VContainer,
    VCard,
    VList,
    VListItem,
    VListItemTitle,
    VListSubheader,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    title: String,
});

const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
    <v-container>
        <v-row class="flex flex-column justify-center text-center mb-4">
            <p class="my-8 text-h4">Build stronger digital connections</p>
            <div class="flex justify-center my-4">
                <v-btn color="red" variant="flat" class="mx-2">Short link</v-btn>
                <v-btn color="red" variant="flat" class="mx-2">QR Code</v-btn>
            </div>
            <p>Use our URL shortener, QR Codes, and landing pages to engage your audience and connect them to the right information. Build, edit, and track everything inside the Bitly Connections Platform.</p>
            <p class="my-4 text-h5">Paste your long link here</p>
            <v-text-field>Paste your long link here</v-text-field>
            <v-btn :href="route('register')" color="red" variant="flat">Shorten</v-btn>

            <v-card class="mx-auto my-8" max-width="300">
                <v-list>
                    <v-list-subheader class="text-h6">Sign up for free. Your free plan includes:</v-list-subheader>

                    <v-list-item color="primary">
                        <template v-slot:prepend>
                            <v-icon icon="mdi-star"></v-icon>
                        </template>
                        <v-list-item-title>5 short links/month</v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary">
                        <template v-slot:prepend>
                            <v-icon icon="mdi-star"></v-icon>
                        </template>
                        <v-list-item-title>3 custom back-halves/month</v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary">
                        <template v-slot:prepend>
                            <v-icon icon="mdi-star"></v-icon>
                        </template>
                        <v-list-item-title>Unlimited link clicks</v-list-item-title>
                    </v-list-item>
                </v-list>

            </v-card>
            <div>Icons Icons Icons Icons Icons</div>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">The Bitly Connections Platform</p>
            <p>All the products you need to build brand connections, manage links and QR Codes, and connect with audiences everywhere, in a single unified platform.</p>
            <div class="flex justify-center my-4">
                <v-btn :href="route('register')" color="red" variant="flat" class="mx-2">Get started for free</v-btn>
                <v-btn :href="route('register')" color="red" variant="flat" class="mx-2">Get a qoute</v-btn>
            </div>

            <v-img></v-img>
            <v-img></v-img>
            <v-img></v-img>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">Adopted and loved by millions of users for over a decade</p>
            <p>box box box box</p>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">What our customers are saying</p>
            <p>testimonials</p>
            <p>testimonials</p>
            <p>testimonials</p>
            <p>testimonials</p>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <p class="my-8 text-h4">More than a link shortener</p>
            <p>Knowing how your clicks and scans are performing should be as easy as making them. Track, analyze, and optimize all your connections in one place.</p>
            <v-btn :href="route('register')" color="red" variant="flat">Get started for free</v-btn>
        </v-row>
    </v-container>
</template>
