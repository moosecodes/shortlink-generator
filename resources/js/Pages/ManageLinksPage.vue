<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, reactive, onMounted } from 'vue';
import { VBtn, VRow, VCol, VSelect, VCard, VCardItem, VCardTitle } from 'vuetify/components';
import { usePage } from '@inertiajs/vue3'
import LinkDetailsComponent from '@/Components/shortlinks/LinkDetailsComponent.vue';
import { fetchUserShortlinks, navigateTo } from '@/Components/shortlinks/requests';

const page = usePage() // TODO: look up docs for usePage() and useOther()

const props = defineProps({
    auth: Object,
    flash: Object,
});

const state = reactive({
    shortlinks: [],
    linkFilter: 'All',
});

const filteredShortlinks = computed(() => {
    const { linkFilter, shortlinks } = state;

    if(!shortlinks) {
        return [];
    }
    if (linkFilter === 'All') {
        return shortlinks.slice().reverse();
    } else if (linkFilter === 'Active') {
        return shortlinks.filter(link => link.is_active).reverse();
    } else {
        return shortlinks.filter(link => !link.is_active).reverse();
    }
});

onMounted(async () => {
    console.log(page);
    state.shortlinks = await fetchUserShortlinks();
});
</script>

<template>
    <AppLayout title="Manage Links">
        <div class="mb-4">
            <h1 class="text-3xl font-semibold">Manage Links</h1>
            <p v-if="!state.shortlinks?.length">
                No short links currently exist. Create a new link to get started!
            </p>
        </div>

        <div class="d-flex justify-center my-12">
            <v-card variant="text" width="100%" class="d-flex justify-center">
                <v-btn
                    variant="outlined"
                    prepend-icon="mdi-link-plus"
                    color="white"
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

        <v-row>
            <v-col>
                <h2 class="my-2 font-bold">{{ state.linkFilter }} Links ({{ filteredShortlinks?.length }}/{{ state.shortlinks?.length }})</h2>
            </v-col>

            <v-col>
                <v-select
                    v-model="state.linkFilter"
                    variant="solo"
                    :items="['All', 'Active', 'Inactive']"
                    label="Filter Shortlinks"
                ></v-select>
            </v-col>
        </v-row>

        <LinkDetailsComponent v-if="filteredShortlinks" :filteredShortlinks="filteredShortlinks" />
    </AppLayout>
</template>
