<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { VBtn, VRow, VCol, VCard } from 'vuetify/lib/components/index.mjs';

const props = defineProps({
    shortlinks: Array,
    locations: Array,
});

const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
    <AppLayout title="Dashboard">

        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Click Engagement</p>
            </v-col>
            <div class="d-flex justify-center my-12">
            <v-card variant="text" width="100%" class="d-flex justify-center">
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

        </div>
        </v-row>

        <v-row v-if="props?.shortlinks?.length">
            <v-col cols="12" md="12">
                <div class="my-2 text-3xl font-semibold">Recent Activity</div>
            </v-col>

            <v-col v-for="item in props.shortlinks" :key="item.shortCode" cols="12" md="4">

                <p class="font-weight-bold">
                    <Link :href="item.short_url" target="_blank">
                        {{ item.title || 'No Title' }}
                    </Link>
                </p>

                <p>
                    <Link :href="item.short_url" target="_blank">
                        {{ item.short_url }}
                    </Link>
                </p>

                <v-btn
                    :href="route('link.analytics', { shortlink_id: item.id })"
                    prepend-icon="mdi-signal"
                    variant="outlined"
                    class="my-4"
                >
                    Analytics
                </v-btn>

                <v-btn
                    v-if="!route().current('link.update')"
                    variant="outlined"
                    prepend-icon="mdi-link-edit"
                    :href="`/link/edit/byShortCode/${item.short_code}`"
                    class="my4-2">
                    Edit Link
                </v-btn>

            </v-col>
        </v-row>

        <v-row v-else>
            <v-col cols="12" md="12">
                <v-btn class="text-2xl font-semibold" color="primary" :href="route('link.create')">Create New Link</v-btn>
            </v-col>
        </v-row>
    </AppLayout>
</template>
