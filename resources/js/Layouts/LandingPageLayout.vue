<script setup>
import AppBarComponent from '@/Components/shortlinks/AppBarComponent.vue';
import LandingPageFooter from '@/Components/shortlinks/LandingPageFooter.vue';
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import { VApp, VContainer, VMain } from 'vuetify/lib/components/index.mjs';

defineProps({
    title: String,
});
</script>

<template>
    <v-app>
        <Head :title="title" />

        <Banner />

        <AppBarComponent />

        <v-main>
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>

        <LandingPageFooter />
    </v-app>
</template>
