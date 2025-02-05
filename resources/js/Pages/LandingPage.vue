<script setup>
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import LandingPageFeaturesComponent from '@/Components/shortlinks/LandingPageFeaturesComponent.vue';
import LandingPageGeneratorsComponent from '@/Components/shortlinks/LandingPageGeneratorsComponent.vue';

import { VRow, VContainer } from 'vuetify/lib/components/index.mjs';

defineProps({
    auth: Object,
    flash: Object,
    title: String,
});
</script>

<template>
    <LandingPageLayout title="Landing Page">
        <div class="mx-auto max-w-3xl py-12 sm:px-6 lg:px-8">
            <v-container fluid>
                <v-row class="flex-column my-4 flex justify-center text-center">
                    <p class="text-h3 my-8">
                        Transform Your Links into Powerful Connections
                    </p>
                    <p class="my-4">
                        Simplify engagement with our powerful URL shortener,
                        dynamic QR Codes, and customized landing pages.
                        Effortlessly connect your audience to the right content
                        while building, editing, and tracking every interaction
                        on our intuitive platform.
                    </p>
                    <LandingPageGeneratorsComponent />
                </v-row>
                <LandingPageFeaturesComponent />
            </v-container>
        </div>
    </LandingPageLayout>
</template>
