<script setup>
import PricingTableComponent from '@/Components/Pricing/PricingTableComponent.vue';
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import { VContainer } from 'vuetify/lib/components/index.mjs';
</script>

<template>
    <LandingPageLayout title="Edit Shortlink">
        <v-container class="text-center">
            <p class="text-h3 my-8">More value in each click</p>
            <p class="text-h4 my-8">Link Tracking</p>
            <p class="text-h4 my-8">QR Code Scan Tracking</p>
            <p class="text-h4 my-8">Superior value</p>
            <p class="text-h4 my-8">Start Free Now</p>
            <p>
                Everything You Need to Build Your Brand: Manage Links, Create QR
                Codes, and Engage Audiences Everywhere from One Powerful
                Platform.
            </p>
        </v-container>

        <PricingTableComponent />
    </LandingPageLayout>
</template>
