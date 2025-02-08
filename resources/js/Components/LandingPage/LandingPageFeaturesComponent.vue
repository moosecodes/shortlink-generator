<script setup>
import {
    VBtn,
    VCard,
    VCardText,
    VCardTitle,
    VRow,
} from 'vuetify/lib/components/index.mjs';
import PricingComponent from '../Pricing/PricingComponent.vue';

const rowStyles = {
    flex: true,
    'flex-column': true,
    'justify-center': true,
    'text-center': true,
    'my-4': true,
};

const freeFeatures = [
    '50 Free Short Links / month',
    '50 Free Aliases / month',
    'Qr Code Generation',
    'Unlimited Clicks and Scans',
    'Custom Domains (coming soon)',
];
</script>

<template>
    <PricingComponent />

    <v-row :class="rowStyles">
        <p class="text-h4 my-8">The Best Engagement Platform</p>
        <p>
            Everything You Need to Build Your Brand: Manage Links, Create QR
            Codes, and Engage Audiences Everywhere from One Powerful Platform.
        </p>
        <div class="my-4 flex justify-center">
            <v-btn
                :href="route('register')"
                color="primary"
                variant="flat"
                class="mx-2 my-4"
                >Start now for FREE</v-btn
            >
        </div>

        <div class="flex justify-between">
            <div class="flex justify-between">
                <v-card v-for="i in 3" :key="i">
                    <img src="https://picsum.photos/600/400" />
                    <v-card-title>Link Management</v-card-title>
                    <v-card-text>Link Management</v-card-text>
                </v-card>
            </div>
        </div>
    </v-row>

    <v-row :class="rowStyles">
        <p class="text-h4 my-4">
            Beyond Link Shortening: A Complete Connection Platform
        </p>
        <p class="my-4">
            Effortless Insights: Track, Analyze, and Optimize Your Clicks and
            Scans All in One Place
        </p>
        <v-btn
            class="my-4"
            :href="route('register')"
            color="primary"
            variant="flat"
            >Start now for FREE</v-btn
        >
    </v-row>
</template>
