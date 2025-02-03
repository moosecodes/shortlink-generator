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
                    :color="state.filteredShortlinks[i].is_active ? 'primary ' : 'primary'"
                    :variant="state.filteredShortlinks[i].is_active ? 'outlined' : 'outlined'"
                >
                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="state.filteredShortlinks[i].is_active ? 'bg-white' : 'bg-grey-darken-4'"
                    >
                        <div>
                            <v-btn
                                variant="flat"
                                :color="state.filteredShortlinks[i].is_active ? 'primary' : 'success'"
                                @click="handleActivation(link, i)"
                                class="m-2 border-md">
                                    <v-icon>{{ state.filteredShortlinks[i].is_active ? 'mdi-stop' : 'mdi-play' }}</v-icon>
                            </v-btn>

                            <v-chip class="mx-2 font-weight-bold" :color="state.filteredShortlinks[i].is_active ? 'black' : 'white'">{{ link?.short_code }}</v-chip>

                            <v-icon :color="state.filteredShortlinks[i].is_active ? 'green-500' : 'primary'">{{ state.filteredShortlinks[i].is_active ? 'mdi-signal-variant' : 'mdi-rss-off' }}</v-icon>

                            <span
                                class="text-lowercase font-weight-bold mx-2"
                                :class="state.filteredShortlinks[i].is_active ? 'text-green-500' : 'text-primary'"
                                variant="flat">
                                {{ state.filteredShortlinks[i].is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <v-btn
                                :variant="!state.filteredShortlinks[i].is_active ? 'outlined' : 'outlined'"
                                :disabled="!state.filteredShortlinks[i].is_active"
                                :href="link?.short_url"
                                :color="state.filteredShortlinks[i].is_active ? 'black' : 'white'"
                                target="_blank"
                                prepend-icon="mdi-eye"
                                class="m-2 font-weight-bold">
                                    View Link
                            </v-btn>

                            <v-btn
                                v-if="!route().current('link.update')"
                                variant="outlined"
                                prepend-icon="mdi-link-edit"
                                :color="state.filteredShortlinks[i].is_active ? 'black' : 'white'"
                                @click="editLink(link)"
                                class="mx-2 font-weight-bold">
                                    Edit Parameters
                            </v-btn>

                            <v-btn
                                v-if="(route().current('link.update') || route().current('show.links'))"
                                variant="outlined"
                                :disabled="state.filteredShortlinks[i].is_active ? false : true"
                                prepend-icon="mdi-delete"
                                :color="state.filteredShortlinks[i].is_active ? 'red' : 'white'"
                                @click="deleteShortlink(link)"
                                class="mx-2 font-weight-bold">
                                    Delete
                            </v-btn>
                        </div>
                    </v-card-actions>

                    <v-card-actions :class="state.filteredShortlinks[i].is_active ? 'bg-white' : 'bg-grey-darken-4'">
                        <v-col cols="12" md="6">
                            <div class="flex flex-wrap flex-column ">
                                <p class="m-2 font-weight-black">
                                    <a @click="editLink(link)">
                                        Optional Name
                                    </a>
                                </p>

                                <p class="m-2">
                                    <a :href="link?.short_url" target="_blank">
                                        {{ link?.short_url }}
                                    </a>
                                </p>

                                <p class="m-2">
                                    <a :href="link?.user_url" target="_blank">
                                        {{ link?.user_url }}
                                    </a>
                                </p>

                            </div>
                        </v-col>
                    </v-card-actions>

                    <v-card-actions
                        class="d-flex flex-wrap justify-start"
                        :class="state.filteredShortlinks[i].is_active ? 'bg-white' : 'bg-grey-darken-4'"
                    >
                        <v-btn
                            :href="route('link.analytics', { shortlink_id: link.id })"
                            :prepend-icon="link?.total_clicks + link?.qr_scans > 0 ? 'mdi-signal-cellular-3' : 'mdi-signal-cellular-outline'"
                            color="bg-black"
                            variant="flat"
                            class="m-2 font-weight-bold"
                        >
                            {{ link?.total_clicks + link?.qr_scans }} total engagements
                        </v-btn>

                        <div class="flex flex-wrap items-center">
                            <v-chip variant="text" :disabled="true">Created:</v-chip>
                            <v-btn class="font-weight-bold text-primary">
                                {{
                                    new Date(link?.created_at)
                                        .toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric',
                                        })
                                }}
                            </v-btn>

                            <v-chip variant="text" :disabled="true">Expires:</v-chip>
                            <v-btn class="font-weight-bold text-primary">
                                {{
                                    new Date(link?.expires_at)
                                        .toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric',
                                        })
                                }}
                            </v-btn>

                            <v-icon v-if="isRecent(link)" color="primary">
                                mdi-new-box
                            </v-icon>

                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <div v-if="route().current('link.update')">
            <v-row v-for="(link, i) in state.filteredShortlinks" :key="link?.id">
                <v-col cols="12" md="12">
                    <v-card
                        :color="state.filteredShortlinks[i].is_active ? 'white ' : 'primary'"
                        :variant="state.filteredShortlinks[i].is_active ? 'outlined' : 'outlined'"
                    >
                        <QrCodeComponent :input="link?.short_url" :scans="link?.qr_scans"/>
                    </v-card>
                </v-col>
            </v-row>
        </div>

    </div>
</template>
