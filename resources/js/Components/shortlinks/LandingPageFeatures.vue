<script setup>
import {
    VBtn,
    VRow,
    VIcon,
    VCard,
    VCardTitle,
    VCardText,
    VList,
    VListItem,
    VListItemTitle,
    VListSubheader,
} from 'vuetify/lib/components/index.mjs';

const rowStyles = {
    'flex': true,
    'flex-column': true,
    'justify-center': true,
    'text-center': true,
    'my-4': true,
};

const freeFeatures = [
    '100 short links per month',
    '100 custom back-halves per month',
    'Link Tracking*',
    'Unlimited link clicks',
];
</script>

<template>
    <v-row>
        <v-card class="mx-auto my-8">
            <v-list>
                <v-list-subheader class="flex justify-center text-h6" color="text-secondary">Sign up for free. Your free plan includes:</v-list-subheader>

                <div class="flex flex-column text-secondary">
                    <v-list-item v-for="(feature, i) in freeFeatures" :key="i">
                        <template v-slot:prepend>
                            <v-icon icon="mdi-check-circle-outline" class="text-green" />
                        </template>
                        <v-list-item-title>{{ feature }}</v-list-item-title>
                    </v-list-item>
                </div>
            </v-list>
        </v-card>
    </v-row>

    <v-row :class="rowStyles">
        <p class="my-8 text-h4">The Best Engagement Platform</p>
        <p>Everything You Need to Build Your Brand: Manage Links, Create QR Codes, and Engage Audiences Everywhere from One Powerful Platform.</p>
        <div class="flex justify-center my-4">
            <v-btn :href="route('register')" color="primary" variant="flat" class="mx-2 my-4">Start now for FREE</v-btn>
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
        <p class="my-4 text-h4">Beyond Link Shortening: A Complete Connection Platform</p>
        <p class="my-4">Effortless Insights: Track, Analyze, and Optimize Your Clicks and Scans All in One Place</p>
        <v-btn class="my-4" :href="route('register')" color="primary" variant="flat">Start now for FREE</v-btn>
    </v-row>
</template>
