<script setup>
import { VRow, VCol, VBtn, VChip, VDivider, VCard, VCardItem, VCardActions } from 'vuetify/components';
import dayjs from 'dayjs';
import QrCodeComponent from './QrCodeComponent.vue';
import { deleteShortlink, toggleActivation } from './requests';

defineProps({
    filteredShortlinks: Object,
});

const editLink = (shortlink) => {
    window.location.href = `/link/edit/byShortCode/${shortlink.short_code}`
};

const isRecent = (shortlink) => {
    const now = dayjs();
    const createdAt = dayjs(shortlink.created_at);
    const updatedAt = dayjs(shortlink.updated_at);

    return now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15;
};
</script>

<template>
    <v-row v-for="link in filteredShortlinks" :key="link.id">
        <v-col cols="12" md="12">
            <v-card
                :color="link?.is_active ? 'indigo' : 'indigo darken-4'"
                :variant="link?.is_active ? 'elevated' : 'tonal'"
                target="_blank"
            >
            <v-card-actions :class="link.is_active ? 'd-flex flex-wrap justify-end bg-indigo-darken-3' : 'd-flex justify-between flex-wrap bg-black'">
                    <v-chip variant="flat" class="mx-2" color="secondary">{{ link.is_premium ? 'PREMIUM' : 'FREE' }}</v-chip>

                    <v-btn
                        v-if="!route().current('link.update')"
                        variant="outlined"
                        prepend-icon="mdi-link"
                        @click="editLink(link)"
                        class="m-2">
                            Edit Link
                    </v-btn>

                    <v-btn
                        v-if="route().current('link.update')"
                        variant="flat"
                        prepend-icon="mdi-delete"
                        color="error"
                        @click="deleteShortlink(link)"
                        class="m-2">
                            Delete
                    </v-btn>

                    <v-btn
                        variant="outlined"
                        :href="link.short_url"
                        :disabled="!link.is_active"
                        target="_blank"
                        prepend-icon="mdi-eye"
                        class="m-2">
                            View Link
                    </v-btn>

                    <v-btn
                        variant="flat"
                        :prepend-icon="link.is_active ? 'mdi-stop' : 'mdi-play'"
                        :color="link.is_active ? 'error' : 'success'"
                        @click="toggleActivation(link)"
                        class="m-2">
                            {{ link.is_active ? 'Disable' : 'Enable' }}
                    </v-btn>
                </v-card-actions>

                <v-card-actions :class="link.is_active ? 'd-flex flex-wrap justify-end bg-indigo-darken-2' : 'd-flex flex-wrap justify-end bg-black'">
                    <v-chip v-if="isRecent(link)" variant="flat" color="indigo" class="mr-2">New</v-chip>

                    <small class="mx-2">Created: <b>{{ new Date(link.created_at).toLocaleString() }}</b></small>
                    <small class="mx-2">Expires: <b>{{ new Date(link.expires_at).toLocaleString() }}</b></small>
                </v-card-actions>



                <v-card-item class="text-overline flex justify-start">
                    <v-chip class="mr-2"><b>{{ link.short_code }}</b></v-chip>
                    <v-chip
                        class="mr-2"
                        :color="link?.is_active ? 'green' : 'red'"
                        variant="flat">
                        {{ link.is_active ? 'Active' : 'Inactive' }}
                    </v-chip>
                </v-card-item>

                <div class="flex flex-wrap flex-column">
                    <v-chip class="text-caption m-2">
                        <a :href="link.short_url" target="_blank">
                            {{ link.short_url }}
                        </a>
                    </v-chip>
                    <v-chip class="text-caption m-2">
                        <a :href="link.user_url" target="_blank">
                            {{ link.user_url }}
                        </a>
                    </v-chip>
                </div>

                <v-card-item>
                    <div class="flex justify-center">
                        <QrCodeComponent class="mx-2" :input="link.short_url" />
                    </div>
                    <div class="flex flex-wrap flex-column">
                        <v-chip class="my-2 mr-2">{{ link.qr_scans }} QR Scans</v-chip>
                        <v-chip class="my-2 mr-2">{{ link.total_clicks }} Clicks</v-chip>
                        <v-chip class="my-2 mr-2">{{ link.total_clicks + link.qr_scans }} Total Impressions</v-chip>
                        <v-divider></v-divider>
                        <v-chip class="my-2 mr-2">{{ link.unique_clicks }} Unique Clicks</v-chip>
                    </div>
                </v-card-item>
            </v-card>
        </v-col>
    </v-row>
</template>
