<script setup>
import { router } from '@inertiajs/vue3';
import { VBtn, VRow, VCol, VCard } from 'vuetify/lib/components/index.mjs';

const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
    <v-row class="justify-center">
        <v-card variant="text">
            <v-btn
                variant="outlined"
                prepend-icon="mdi-link-plus"
                color="primary"
                class="m-4"
                @click="navigateTo('link.create')">
                    New Shortlink
            </v-btn>

            <v-btn
                disabled
                variant="text"
                prepend-icon="mdi-qrcode-scan"
                color="white"
                class="m-4"
                @click="navigateTo('link.create')">
                    New QR Code
            </v-btn>

            <v-btn
                disabled
                variant="text"
                prepend-icon="mdi-file-document-outline"
                color="white"
                class="m-4"
                @click="navigateTo('link.create')">
                    New Page
            </v-btn>
        </v-card>
    </v-row>
</template>

