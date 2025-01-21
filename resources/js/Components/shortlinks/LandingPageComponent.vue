<script setup>
import { router } from '@inertiajs/vue3';
import {
    VBtn,
    VRow,
    VImg,
    VTextField,
    VContainer
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
            <h2 class="my-8">Build stronger digital connections</h2>
            <v-btn variant="flat">Short link</v-btn>
            <v-btn variant="flat">QR Code</v-btn>
            <p>Use our URL shortener, QR Codes, and landing pages to engage your audience and connect them to the right information. Build, edit, and track everything inside the Bitly Connections Platform.</p>
            <p>Paste your long link here</p>
            <v-text-field></v-text-field>
            <v-btn variant="flat">Shorten</v-btn>
            <p>Sign up for free. Your free plan includes:</p>
            <p>5 short links/month</p>
            <p>3 custom back-halves/month</p>
            <p>Unlimited link clicks</p>
            <div>Icons Icons Icons Icons Icons</div>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <h2 class="my-8">The Bitly Connections Platform</h2>
            <p>All the products you need to build brand connections, manage links and QR Codes, and connect with audiences everywhere, in a single unified platform.</p>
            <v-btn variant="flat">Get started for free</v-btn>
            <v-btn variant="flat">Get a qoute</v-btn>
            <v-img></v-img>
            <v-img></v-img>
            <v-img></v-img>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <h2 class="my-8">Adopted and loved by millions of users for over a decade</h2>
            <p>box box box box</p>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <h2 class="my-8">What our customers are saying</h2>
            <p>testimonials</p>
            <p>testimonials</p>
            <p>testimonials</p>
            <p>testimonials</p>
        </v-row>
        <v-row class="flex flex-column justify-center text-center my-4">
            <h2 class="my-8">More than a link shortener</h2>
            <p>Knowing how your clicks and scans are performing should be as easy as making them. Track, analyze, and optimize all your connections in one place.</p>
            <v-btn variant="flat">Get started for free</v-btn>
        </v-row>
    </v-container>
</template>
