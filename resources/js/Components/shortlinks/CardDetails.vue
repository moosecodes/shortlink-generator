<script setup>
import { VBtn, VChip, VCol, VCard, VCardItem, VCardActions } from 'vuetify/components';
import dayjs from 'dayjs';
import QrCodeComponent from './QrCodeComponent.vue';

const props = defineProps({
    shortlink: Object,
    redirects: Object,
});

const updateShortlink = (shortlink) => {
    window.location.href = `/link/update/${shortlink.short_code}`
};

const deleteShortlink = async({ short_code }) => {
    const confirmDelete = confirm(`Are you sure you want to delete this shortlink ${short_code}?`);

    if (confirmDelete) {
        try {
            await axios.delete(`/api/link/delete/${short_code}`);
            window.location.reload();
        } catch (error) {
            console.error('Error deleting shortlink:', error);
        }
    }
}

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active
            ? `/api/link/deactivate/${shortlink.short_code}`
            : `/api/link/activate/${shortlink.short_code}`;

        await axios.patch(route);

        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

const isRecent = (shortlink) => {
    const now = dayjs();
    const createdAt = dayjs(shortlink.created_at);
    const updatedAt = dayjs(shortlink.updated_at);

    return now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15;
};
</script>

<template>
    <v-col cols="12" md="12" :class="{ 'border-green-500 border-2 mb-4': isRecent(shortlink) }">
        <v-card
            :color="shortlink?.is_active ? 'indigo' : 'indigo darken-4'"
            :variant="shortlink?.is_active ? 'elevated' : 'tonal'"
            target="_blank"
        >
            <v-card-item>
                <div class="text-overline mb-1 flex justify-between align-center">
                    <div>
                        <v-chip class="mr-2"><b>{{ shortlink.short_code }}</b></v-chip>
                        <v-chip
                            :color="shortlink?.is_active ? 'green' : 'red'"
                            variant="flat">
                            {{ shortlink.is_active ? 'Active' : 'Inactive' }}
                        </v-chip>
                    </div>
                    <div>
                        <v-chip class="my-2 mr-2">{{ shortlink.total_clicks }} Clicks</v-chip>
                        <v-chip class="my-2 mr-2">{{ shortlink.unique_clicks }} Unique Clicks</v-chip>
                    </div>
                </div>
                <v-chip class="text-caption my-2 mr-2 item-justify-start">
                    <a :href="shortlink.short_url" target="_blank">
                        {{ shortlink.short_url }}
                    </a>
                </v-chip>
            </v-card-item>

            <v-card-item>
                <div class="d-flex justify-between">
                    <QrCodeComponent class="mx-2" :input="shortlink.short_url" />
                </div>
            </v-card-item>

            <v-card-actions :class="shortlink.is_active ? 'd-flex flex-wrap justify-end bg-indigo-darken-2' : 'd-flex flex-wrap justify-end bg-black'">
                <small class="mx-2">Created: <b>{{ new Date(shortlink.created_at).toLocaleString() }}</b></small>
                <small class="mx-2">Expires: <b>{{ new Date(shortlink.expires_at).toLocaleString() }}</b></small>
            </v-card-actions>

            <v-card-actions :class="shortlink.is_active ? 'd-flex flex-wrap justify-between bg-indigo-darken-3' : 'd-flex justify-between flex-wrap bg-black'">
                <v-chip variant="flat" class="mx-2">{{ shortlink.is_premium ? 'PREMIUM' : 'FREE' }}</v-chip>
                <div class="d-flex flex-wrap">
                    <v-btn
                        variant="outlined"
                        :href="shortlink.short_url"
                        target="_blank"
                        prepend-icon="mdi-eye"
                        class="m-2">
                        View Link
                    </v-btn>

                    <v-btn
                        variant="outlined"
                        prepend-icon="mdi-link"
                        @click="updateShortlink(shortlink)"
                        class="m-2">
                        Edit Link
                    </v-btn>

                    <v-btn
                        variant="flat"
                        :prepend-icon="shortlink.is_active ? 'mdi-cancel' : 'mdi-connection'"
                        :color="shortlink.is_active ? 'error' : 'success'"
                        @click="toggleActivation(shortlink)"
                        class="m-2">
                        {{ shortlink.is_active ? 'Disable' : 'Enable' }}
                    </v-btn>

                    <v-btn
                        v-if="!shortlink.is_active"
                        variant="flat"
                        prepend-icon="mdi-delete"
                        color="error"
                        @click="deleteShortlink(shortlink)"
                        class="m-2">
                        Delete
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-col>
</template>
