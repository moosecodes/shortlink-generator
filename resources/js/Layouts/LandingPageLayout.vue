<script setup>
import AppBarComponent from '@/Components/shortlinks/AppBarComponent.vue';
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import {
    VApp,
    VContainer,
    VFooter,
    VMain,
    VRow,
    VCol,
    VLayout,
} from 'vuetify/lib/components/index.mjs';

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

        <v-footer>
            <v-row>
                <v-col> Why Shortlinks? </v-col>
                <v-col> Products </v-col>
                <v-col> Feature </v-col>
                <v-col> Legal </v-col>
                <v-col> About </v-col>
            </v-row>
            <v-row>
                <v-col>
                    &copy; {{ new Date().getFullYear() }} FreedomFace, LLC. All
                    rights reserved.
                </v-col>
            </v-row>
        </v-footer>
    </v-app>
</template>
