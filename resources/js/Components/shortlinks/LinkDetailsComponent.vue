<script setup>
import { ref, watch } from 'vue';
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
                    :color="link.is_active ? 'white ' : 'black'"
                    :variant="link.is_active ? 'outlined' : 'outlined'">

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="link.is_active ? 'bg-white' : 'bg-black'">

                        <div>
                            <v-chip variant="text">


                                <span v-if="isRecent(link)" :class="link.is_active ? 'text-green-500' : 'text-primary'" class="mr-2">
                                    [ NEW ]
                                </span>

                                <span
                                    :class="link.is_active ? 'text-green-500' : 'text-primary'"
                                    class="font-weight-bold mx-2">
                                    {{ link.short_code }}
                                </span>

                                <span
                                    class="text-lowercase font-weight-bold mx-2"
                                    :class="link.is_active ? 'text-green-500' : 'text-primary'">
                                    {{ link.is_active ? 'active' : 'inactive' }}
                                </span>

                                <v-icon
                                    class="mx-2"
                                    :color="link.is_active ? 'green-500' : 'primary'">{{ link.is_active ? 'mdi-signal-variant' : 'mdi-rss-off' }}
                                </v-icon>

                            </v-chip>
                        </div>
                    </v-card-actions>

                    <v-card-actions :class="link.is_active ? 'bg-white' : 'bg-black'">

                        <v-col cols="12" md="6">

                            <div class="flex flex-wrap flex-column">

                                <Link
                                    :href="`/link/edit/byShortCode/${link.short_code}`"
                                    :class="link.is_active ? 'text-blue-grey' : 'text-white'"
                                    class="font-weight-bold mb-2">
                                    {{ link.title }}
                                </Link>

                                <a
                                    :href="link?.short_url"
                                    target="_blank"
                                    :class="link.is_active ? 'text-black' : 'text-blue-grey-lighten-2'">
                                        {{ link?.short_url }}
                                </a>

                                <a
                                    :href="link?.user_url"
                                    target="_blank"
                                    :class="link.is_active ? 'text-black' : 'text-blue-grey-lighten-2'">
                                        {{ link?.user_url }}
                                </a>

                            </div>

                        </v-col>

                        <v-col cols="12" md="6" class="flex flex-column">
                                <v-btn
                                    v-if="!route().current('link.analytics')"
                                    variant="outlined"
                                    :href="route('link.analytics', { shortlink_id: link.id })"
                                    :prepend-icon="link?.total_clicks + link?.qr_scans > 0 ? 'mdi-signal-cellular-3' : 'mdi-signal-off'"
                                    color="accent"
                                    class="m-2 font-weight-bold"
                                >
                                    Analytics
                                </v-btn>

                                <v-btn
                                    variant="outlined"
                                    :prepend-icon="'mdi-eye'"
                                    :href="link?.short_url"
                                    :color="link.is_active ? 'black' : 'white'"
                                    target="_blank"
                                    class="m-2 font-weight-bold">
                                        View Link
                                </v-btn>

                                <v-btn
                                    v-if="!route().current('link.update')"
                                    variant="outlined"
                                    :prepend-icon="'mdi-link-edit'"
                                    :color="link.is_active ? 'black' : 'white'"
                                    :href="`/link/edit/byShortCode/${link.short_code}`"
                                    class="m-2 font-weight-bold">
                                        Edit
                                </v-btn>

                        </v-col>
                    </v-card-actions>

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="link.is_active ? 'bg-white' : 'bg-black'"
                    >
                        <div>
                            <v-btn
                                variant="flat"
                                :color="link.is_active ? 'primary' : 'success'"
                                @click="handleActivation(link, i)"
                                class="mx-2 border-md">
                                    <v-icon>{{ link.is_active ? 'mdi-stop' : 'mdi-play' }}</v-icon>
                            </v-btn>

                            <v-btn
                                v-if="(route().current('link.update') || route().current('show.links'))"
                                variant="outlined"
                                :disabled="!!link.is_active"
                                :color="link.is_active ? 'black' : 'white bg-primary'"
                                :prepend-icon="'mdi-delete'"
                                @click="deleteShortlink(link)"
                                class="m-2 font-weight-bold">
                                    DELETE
                            </v-btn>
                        </div>

                        <div class="d-flex justify-between text-blue-grey-lighten-2 mx-2">
                            <small
                                variant="text"
                                class="mx-2"
                                :class="link.is_active ? 'text-primary font-weight-bold' : 'text-primary font-weight-bold'">
                                {{ link?.total_clicks + link?.qr_scans }} engagements
                            </small>

                            <small>
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
                            </small>
                        </div>
                    </v-card-actions>

                </v-card>

            </v-col>

        </v-row>

        <div v-if="route().current('link.update')">

            <v-row v-for="(link, i) in filteredShortlinks" :key="link?.id">

                <v-col cols="12" md="12">

                    <v-card
                        :color="link.is_active ? 'white ' : 'primary'"
                        :variant="link.is_active ? 'outlined' : 'outlined'"
                    >

                        <QrCodeComponent :input="link?.short_url" :scans="link?.qr_scans"/>

                    </v-card>

                </v-col>

            </v-row>

        </div>
    </div>
</template>
