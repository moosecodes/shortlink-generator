<script setup>
import { watch, reactive, watchEffect } from 'vue';
import { VRow, VCol, VBtn, VChip, VDivider, VCard, VCardItem, VCardActions } from 'vuetify/components';
import dayjs from 'dayjs';
import QrCodeComponent from './QrCodeComponent.vue';
import { deleteShortlink, toggleActivation } from './requests';

const props = defineProps({
    filteredShortlinks: Object,
    required: true,
});

const state = reactive({
    filteredShortlinks: {},
});

const editLink = (link) => {
    window.location.href = `/link/edit/byShortCode/${link.short_code}`
};

const isRecent = (link) => {
    const now = dayjs();
    const createdAt = dayjs(link?.created_at);
    const updatedAt = dayjs(link?.updated_at);

    return now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15;
};

const handleActivation = async (link, i) => {
    await toggleActivation(link);
    state.filteredShortlinks[i].is_active = !state.filteredShortlinks[i].is_active;
};

watchEffect(() => {
    state.filteredShortlinks = props.filteredShortlinks;
});

</script>

<template>
    <div v-if="!state.filteredShortlinks?.length">
        <p>No shortlinks currently exist. Create a new link to get started!</p>
    </div>

    <div v-else>
        <v-row v-for="(link, i) in state.filteredShortlinks" :key="link?.id">
        <v-col cols="12" md="12">
            <v-card
                :color="state.filteredShortlinks[i].is_active ? 'indigo' : 'indigo darken-4'"
                :variant="state.filteredShortlinks[i].is_active ? 'elevated' : 'tonal'"
                target="_blank"
            >

            <v-card-actions :class="state.filteredShortlinks[i].is_active ? 'd-flex flex-wrap justify-end bg-indigo-darken-3' : 'd-flex justify-between flex-wrap bg-black'">
                <v-chip
                    variant="flat"
                    class="mx-2"
                    color="secondary">{{ link?.is_premium ? 'PREMIUM' : 'FREE' }}
                </v-chip>

                <v-btn
                    v-if="!route().current('link.update')"
                    variant="outlined"
                    prepend-icon="mdi-link"
                    @click="editLink(link)"
                    class="m-2">
                        Edit Link
                </v-btn>

                <v-btn
                    v-if="route().current('link.update') || route().current('show.links')"
                    variant="flat"
                    prepend-icon="mdi-delete"
                    color="error"
                    @click="deleteShortlink(link)"
                    class="m-2">
                        Delete
                </v-btn>

                <v-btn
                    variant="outlined"
                    :href="link?.short_url"
                    :disabled="!state.filteredShortlinks[i].is_active"
                    target="_blank"
                    prepend-icon="mdi-eye"
                    class="m-2">
                        View Link
                </v-btn>

                <v-btn
                    variant="flat"
                    :prepend-icon="state.filteredShortlinks[i].is_active ? 'mdi-stop' : 'mdi-play'"
                    :color="state.filteredShortlinks[i].is_active ? 'error' : 'success'"
                    @click="handleActivation(link, i)"
                    class="m-2">
                        {{ state.filteredShortlinks[i].is_active ? 'Disable' : 'Enable' }}
                </v-btn>
            </v-card-actions>

                <v-card-actions
                    :class="state.filteredShortlinks[i].is_active
                        ? 'd-flex flex-wrap justify-end bg-indigo-darken-2'
                        : 'd-flex flex-wrap justify-end bg-black'"
                >
                    <v-chip
                        v-if="isRecent(link)"
                        variant="flat"
                        color="indigo"
                        class="mr-2"
                    >New</v-chip>

                    <small class="mx-2">
                        Created: <b>{{ new Date(link?.created_at).toLocaleString() }}</b>
                    </small>
                    <small class="mx-2">
                        Expires: <b>{{ new Date(link?.expires_at).toLocaleString() }}</b>
                    </small>
                </v-card-actions>

                <v-row>
                    <v-col cols="12" md="12">
                        <v-chip class="m-2"><b>{{ link?.short_code }}</b></v-chip>
                        <v-chip
                            class="text-overline mr-2"
                            :color="state.filteredShortlinks[i].is_active ? 'green' : 'red'"
                            variant="flat">
                            {{ state.filteredShortlinks[i].is_active ? 'Active' : 'Inactive' }}
                        </v-chip>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6">
                        <div class="flex flex-wrap flex-column">
                            <v-chip class="m-2">
                                <a :href="link?.short_url" target="_blank">
                                    {{ link?.short_url }}
                                </a>
                            </v-chip>
                            <v-chip class="m-2">
                                <a :href="link?.user_url" target="_blank">
                                    {{ link?.user_url }}
                                </a>
                            </v-chip>
                            <v-chip class="m-2">{{ link?.qr_scans }} QR Scans</v-chip>
                            <v-chip class="m-2">{{ link?.total_clicks }} Clicks</v-chip>
                            <v-chip class="m-2">{{ link?.total_clicks + link?.qr_scans }} Total Impressions</v-chip>
                            <v-chip class="m-2 mb-4">{{ link?.unique_clicks }} Unique Clicks</v-chip>
                        </div>
                    </v-col>

                    <v-col cols="12" md="6">
                        <QrCodeComponent :input="link?.short_url" />
                    </v-col>

                </v-row>
            </v-card>
        </v-col>
    </v-row>
    </div>

</template>
