<script setup>
import AppBarComponent from '@/Components/shortlinks/AppBarComponent.vue';

import {
    VApp,
    VContainer,
    VFooter,
    VMain,
    VRow,
    VCol,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    title: String,
});
</script>

<template>
    <v-app>

        <AppBarComponent />

        <v-main>
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>

        <v-footer>
            <v-row>
                <v-col>
                    Why Shortlinks?
                </v-col>
                <v-col>
                    Products
                </v-col>
                <v-col>
                    Feature
                </v-col>
                <v-col>
                    Legal
                </v-col>
                <v-col>
                    About
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    &copy; {{ new Date().getFullYear() }} FreedomFace, LLC. All rights reserved.
                </v-col>
            </v-row>
        </v-footer>
    </v-app>
</template>
