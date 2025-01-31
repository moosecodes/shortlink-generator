<script setup>
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import LandingPageFeaturesComponent from '@/Components/shortlinks/LandingPageFeaturesComponent.vue';
import LandingPageGeneratorsComponent from '@/Components/shortlinks/LandingPageGeneratorsComponent.vue';

import {
    VRow,
    VContainer,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    auth: Object,
    flash: Object,
    title: String,
});
</script>

<template>
    <LandingPageLayout title="Landing Page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-12">
            <v-container fluid>
                <v-row class="flex flex-column justify-center text-center my-4">
                    <p class="my-8 text-h3">Transform Your Links into Powerful Connections</p>
                    <p class="my-4">Simplify engagement with our powerful URL shortener, dynamic QR Codes, and customized landing pages. Effortlessly connect your audience to the right content while building, editing, and tracking every interaction on our intuitive platform.</p>
                    <LandingPageGeneratorsComponent />
                </v-row>
                <LandingPageFeaturesComponent />
            </v-container>
        </div>
    </LandingPageLayout>
</template>
