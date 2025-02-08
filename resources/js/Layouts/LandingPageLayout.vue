<script setup>
import Banner from '@/Components/Banner.vue';
import LandingPageFooter from '@/Components/LandingPage/LandingPageFooter.vue';
import AppBarComponent from '@/Components/LandingPage/AppBarComponent.vue';
import { Head } from '@inertiajs/vue3';
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
