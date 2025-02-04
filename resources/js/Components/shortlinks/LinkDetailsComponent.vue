<script setup>
import { computed, ref, watch } from 'vue';
import { VRow, VCol, VBtn, VChip, VIcon, VCard, VCardActions } from 'vuetify/components';
import dayjs from 'dayjs';
import QrCodeComponent from './QrCodeComponent.vue';
import { deleteShortlink, toggleActivation } from './requests';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    filteredShortlinks: Object,
    required: true,
});

let filteredShortlinks = ref([]);

const isActive = computed(() => {
    return filteredShortlinks.reduce((acc, link, i) => {
        acc[i] = !!link.is_active;
        return acc;
    }, {});
});

const isRecent = (link) => {
    const now = dayjs();
    const createdAt = dayjs(link?.created_at);
    const updatedAt = dayjs(link?.updated_at);

    return now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15;
};

const handleActivation = async (link, i) => {
    await toggleActivation(link);
    filteredShortlinks[i].is_active = !filteredShortlinks[i].is_active;
};

watch(() => props.filteredShortlinks, (newValue) => {
    filteredShortlinks = newValue;
}, { immediate: true });
</script>

<template>
    <div v-if="!filteredShortlinks?.length">
        <p>No short links currently exist. Create a new link to get started!</p>
    </div>

    <div v-else>
        <v-row v-for="(link, i) in filteredShortlinks" :key="link?.id">

            <v-col cols="12" md="12">

                <v-card
                    :color="isActive[i] ? 'white ' : 'grey'"
                    :variant="isActive[i] ? 'outlined' : 'outlined'">

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="isActive[i] ? 'bg-white' : 'bg-grey-darken-4'">

                        <div>
                            <v-chip variant="text">
                                <v-icon :color="isActive[i] ? 'green-500' : 'primary'">{{ isActive[i] ? 'mdi-signal-variant' : 'mdi-rss-off' }}</v-icon>

                                <span
                                    class="text-lowercase font-weight-bold mx-2"
                                    :class="isActive[i] ? 'text-green-500' : 'text-primary'">
                                    {{ isActive[i] ? 'active' : 'inactive' }}
                                </span>
                            </v-chip>

                            <span v-if="isRecent(link)" class="mx-2 mb-2">
                                <v-icon color="primary">mdi-new-box</v-icon>
                            </span>
                        </div>

                        <div>
                            <v-btn
                                variant="outlined"
                                :href="route('link.analytics', { shortlink_id: link.id })"
                                :prepend-icon="link?.total_clicks + link?.qr_scans > 0 ? 'mdi-signal-cellular-3' : 'mdi-signal-off'"
                                :color="isActive[i] ? 'bg-success' : 'white'"
                                class="mx-2 font-weight-bold"
                            >
                                Stats
                            </v-btn>
                            <v-btn
                                v-if="!route().current('link.update')"
                                variant="outlined"
                                :color="isActive[i] ? 'black' : 'white'"
                                :href="`/link/edit/byShortCode/${link.short_code}`"
                                class="mx-2 font-weight-bold">
                                    <v-icon>mdi-link-edit</v-icon>
                            </v-btn>

                            <v-btn
                                variant="outlined"
                                :href="link?.short_url"
                                :color="isActive[i] ? 'black' : 'white'"
                                target="_blank"
                                class="mx-2 font-weight-bold">
                                    <v-icon>mdi-eye</v-icon>
                            </v-btn>

                            <v-btn
                                v-if="(route().current('link.update') || route().current('show.links'))"
                                variant="plain"
                                :disabled="isActive[i]"
                                :color="isActive[i] ? 'red' : 'white bg-red'"
                                @click="deleteShortlink(link)"
                                class="mx-2 font-weight-bold">
                                    <v-icon>mdi-delete</v-icon>
                            </v-btn>

                        </div>
                    </v-card-actions>

                    <v-card-actions :class="isActive[i] ? 'bg-white' : 'bg-grey-darken-4'">

                        <v-col cols="12" md="6">

                            <div class="flex flex-wrap flex-column">

                                <Link
                                    :href="`/link/edit/byShortCode/${link.short_code}`"
                                    :class="isActive[i] ? 'text-blue-grey' : 'text-white'"
                                    class="font-weight-bold mb-2">
                                    {{ link.title }}
                                </Link>

                                <Link
                                    :href="link?.short_url"
                                    target="_blank"
                                    :class="isActive[i] ? 'text-black' : 'text-blue-grey-lighten-2'">
                                        {{ link?.short_url }}
                                </Link>

                                <Link
                                    :href="link?.user_url"
                                    target="_blank"
                                    :class="isActive[i] ? 'text-black' : 'text-blue-grey-lighten-2'">
                                        {{ link?.user_url }}
                                </Link>

                            </div>

                            <div class="flex flex-column justify-start text-blue-grey-lighten-2 my-2">
                                <p>
                                    <span variant="text">
                                        {{
                                            new Date(link?.created_at)
                                                .toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric',
                                                })
                                        }}
                                    </span>

                                    <span> - </span>

                                    <span variant="text">
                                        {{
                                            new Date(link?.expires_at)
                                                .toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric',
                                                })
                                        }}
                                    </span>
                                </p>

                                <p
                                variant="text"
                                    :class="isActive[i] ? 'text-blue-grey' : 'white'">
                                    {{ link?.total_clicks + link?.qr_scans }} engagements
                                </p>
                            </div>




                        </v-col>

                    </v-card-actions>

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="isActive[i] ? 'bg-white' : 'bg-grey-darken-4'"
                    >
                        <div>

                            <v-btn
                                variant="flat"
                                :color="isActive[i] ? 'primary' : 'success'"
                                @click="handleActivation(link, i)"
                                class="mx-2 border-md">
                                    <v-icon>{{ isActive[i] ? 'mdi-stop' : 'mdi-play' }}</v-icon>
                            </v-btn>

                        </div>

                    </v-card-actions>

                </v-card>

            </v-col>

        </v-row>

        <div v-if="route().current('link.update')">

            <v-row v-for="(link, i) in filteredShortlinks" :key="link?.id">

                <v-col cols="12" md="12">

                    <v-card
                        :color="isActive[i] ? 'white ' : 'primary'"
                        :variant="isActive[i] ? 'outlined' : 'outlined'"
                    >

                        <QrCodeComponent :input="link?.short_url" :scans="link?.qr_scans"/>

                    </v-card>

                </v-col>

            </v-row>

        </div>
    </div>
</template>
