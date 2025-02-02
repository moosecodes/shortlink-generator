<script setup>
import { reactive, watchEffect } from 'vue';
import { VRow, VCol, VBtn, VChip, VIcon, VCard, VCardActions } from 'vuetify/components';
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
                    :color="state.filteredShortlinks[i].is_active ? 'primary ' : 'blue-grey'"
                    :variant="state.filteredShortlinks[i].is_active ? 'outlined' : 'outlined'"
                    target="_blank"
                >
                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="state.filteredShortlinks[i].is_active ? 'bg-primary' : 'bg-blue-grey'"
                    >
                        <div>
                            <v-btn
                                variant="flat"
                                :color="state.filteredShortlinks[i].is_active ? 'bg-black' : 'success'"
                                @click="handleActivation(link, i)"
                                class="m-2">
                                    <v-icon>{{ state.filteredShortlinks[i].is_active ? 'mdi-stop' : 'mdi-play' }}</v-icon>
                            </v-btn>

                            <v-btn
                                :variant="!state.filteredShortlinks[i].is_active ? 'outlined' : 'outlined'"
                                :href="link?.short_url"
                                :disabled="!state.filteredShortlinks[i].is_active"
                                target="_blank"
                                prepend-icon="mdi-eye"
                                class="m-2">
                                    View Link
                            </v-btn>

                            </div>

                            <div>
                                <v-btn
                                    v-if="!route().current('link.update')"
                                    variant="outlined"
                                    :color="state.filteredShortlinks[i].is_active ? 'white' : 'white'"
                                    prepend-icon="mdi-link"
                                    @click="editLink(link)"
                                    class="mx-2">
                                        Edit Link
                                </v-btn>

                                <v-btn
                                    v-if="(route().current('link.update') || route().current('show.links'))"
                                    variant="outlined"
                                    :disabled="state.filteredShortlinks[i].is_active ? false : true"
                                    prepend-icon="mdi-delete"
                                    :color="state.filteredShortlinks[i].is_active ? 'white' : 'white'"
                                    @click="deleteShortlink(link)"
                                    class="mx-2">
                                        Delete
                                </v-btn>
                            </div>

                        <div>
                            <v-chip
                                class="text-overline mx-2"
                                :color="state.filteredShortlinks[i].is_active ? 'green' : 'white'"
                                variant="flat">
                                {{ state.filteredShortlinks[i].is_active ? 'Active' : 'Inactive' }}
                            </v-chip>

                            <v-chip class="mx-2" color="secondary"><b>{{ link?.short_code }}</b></v-chip>

                        </div>
                    </v-card-actions>

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="state.filteredShortlinks[i].is_active ? 'bg-primary' : 'bg-blue-grey'"
                    >
                        <v-chip
                            v-if="isRecent(link)"
                            variant="flat"
                            color="indigo"
                            class="mx-2"
                        >
                            New
                        </v-chip>

                        <v-chip
                            v-if="link?.is_premium"
                            variant="flat"
                            class="mx-2"
                            color="indigo"
                        >
                            PREMIUM
                        </v-chip>
                        <v-chip
                            v-else
                            variant="flat"
                            class="mx-2"
                            color="indigo"
                        >
                            FREE!
                        </v-chip>

                        <v-btn :disabled="true" class="mx-2">
                            Created: <b>{{ new Date(link?.created_at).toLocaleString() }}</b>
                        </v-btn>

                        <v-btn :disabled="true" class="mx-2">
                            Expires: <b>{{ new Date(link?.expires_at).toLocaleString() }}</b>
                        </v-btn>
                    </v-card-actions>

                    <v-card-actions>
                        <v-col cols="12" md="6">
                            <div class="flex flex-wrap flex-column">
                                <v-chip class="m-2">{{ link?.qr_scans }} QR Scans</v-chip>
                                <v-chip class="m-2">{{ link?.total_clicks }} Clicks</v-chip>
                                <v-chip class="m-2">{{ link?.total_clicks + link?.qr_scans }} Total Impressions</v-chip>
                                <v-chip class="m-2 mb-8">{{ link?.unique_clicks }} Unique Clicks</v-chip>

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

                            </div>
                        </v-col>

                        <v-col cols="12" md="6"  align="center">
                            <QrCodeComponent :input="link?.short_url" />
                        </v-col>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
