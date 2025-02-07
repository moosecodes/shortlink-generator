<script setup>
import dayjs from 'dayjs';
import { ref, watch } from 'vue';
import {
    VRow,
    VCol,
    VBtn,
    VChip,
    VIcon,
    VCard,
    VCardActions,
    VCardTitle,
} from 'vuetify/components';
import { Link } from '@inertiajs/vue3';
import { deleteShortlink, toggleActivation } from './requests';
import QrCodeComponent from './QrCodeComponent.vue';

const props = defineProps({
    filteredShortlinks: Object,
    required: true,
});

let filteredShortlinks = ref([]);

const isRecent = (created_at, updated_at) => {
    const now = dayjs();
    const createdAt = dayjs(created_at);
    const updatedAt = dayjs(updated_at);

    return (
        now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15
    );
};

const handleActivation = async (id, is_active, i) => {
    await toggleActivation(id, is_active);
    filteredShortlinks[i].is_active = !filteredShortlinks[i].is_active;
};

watch(
    () => props.filteredShortlinks,
    (newValue) => {
        filteredShortlinks = newValue;
    },
    { immediate: true },
);
</script>

<template>
    <v-row v-if="!filteredShortlinks?.length">
        <p>No short links currently exist. Create a new link to get started!</p>
    </v-row>

    <div v-else>
        <v-row
            v-for="(
                {
                    id,
                    title,
                    updated_at,
                    is_active,
                    short_code,
                    user_url,
                    short_url,
                    total_clicks,
                    qr_scans,
                    expires_at,
                    created_at,
                    unique_clicks,
                },
                i
            ) in filteredShortlinks"
            :key="id"
        >
            <v-col cols="12" md="12">
                <v-card
                    :color="is_active ? 'white ' : 'white'"
                    :variant="is_active ? 'outlined' : 'plain'"
                >
                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="is_active ? 'bg-white' : 'bg-black'"
                    >
                        <v-card-title>
                            <span
                                v-if="isRecent(short_code, updated_at)"
                                :class="
                                    is_active
                                        ? 'text-green-500'
                                        : 'text-primary'
                                "
                                class="mr-2"
                            >
                                [ NEW ]
                            </span>
                            <v-chip variant="outlined">
                                <span
                                    :class="
                                        is_active
                                            ? 'text-green-500'
                                            : 'text-primary'
                                    "
                                    class="font-weight-bold mx-2"
                                >
                                    {{ short_code }}
                                </span>
                            </v-chip>
                        </v-card-title>

                        <div>
                            <v-chip variant="text">
                                <span
                                    class="text-lowercase font-weight-bold mx-2"
                                    :class="
                                        is_active
                                            ? 'text-green-500'
                                            : 'text-primary'
                                    "
                                >
                                    {{ is_active ? 'active' : 'inactive' }}
                                </span>

                                <v-icon
                                    class="mx-2"
                                    :color="is_active ? 'green-500' : 'primary'"
                                    >{{
                                        is_active
                                            ? 'mdi-signal-variant'
                                            : 'mdi-rss-off'
                                    }}
                                </v-icon>
                            </v-chip>
                        </div>
                    </v-card-actions>

                    <v-card-actions
                        :class="is_active ? 'bg-white' : 'bg-black'"
                    >
                        <v-col cols="12" md="12">
                            <div class="flex-column mb-2 flex flex-wrap">
                                <Link
                                    :href="`/link/edit/byShortCode/${short_code}`"
                                    :class="
                                        is_active
                                            ? 'text-blue-grey'
                                            : 'text-white'
                                    "
                                    class="font-weight-bold mb-2"
                                >
                                    {{ title }}
                                </Link>

                                <a
                                    :href="short_url"
                                    target="_blank"
                                    class="font-weight-bold my-2"
                                    :class="
                                        is_active
                                            ? 'text-black'
                                            : 'text-blue-grey-lighten-2'
                                    "
                                >
                                    {{ short_url }}
                                </a>

                                <a
                                    :href="user_url"
                                    target="_blank"
                                    :class="
                                        is_active
                                            ? 'text-black'
                                            : 'text-blue-grey-lighten-2'
                                    "
                                >
                                    {{ user_url }}
                                </a>
                            </div>

                            <v-btn
                                v-if="!route().current('link.analytics')"
                                variant="outlined"
                                :href="
                                    route('link.analytics', {
                                        shortlink_id: id,
                                    })
                                "
                                :prepend-icon="
                                    total_clicks + qr_scans > 0
                                        ? 'mdi-signal-cellular-3'
                                        : 'mdi-signal-off'
                                "
                                color="accent"
                                class="font-weight-bold my-2"
                            >
                                Analytics
                            </v-btn>

                            <v-btn
                                variant="outlined"
                                :prepend-icon="'mdi-eye'"
                                :href="short_url"
                                :color="is_active ? 'black' : 'white'"
                                target="_blank"
                                class="font-weight-bold my-2"
                            >
                                View Link
                            </v-btn>

                            <v-btn
                                v-if="!route().current('link.update')"
                                variant="outlined"
                                :prepend-icon="'mdi-link-edit'"
                                :color="is_active ? 'black' : 'white'"
                                :href="`/link/edit/byShortCode/${short_code}`"
                                class="font-weight-bold my-2"
                            >
                                Edit
                            </v-btn>
                        </v-col>
                    </v-card-actions>

                    <v-card-actions
                        class="d-flex flex-wrap justify-between"
                        :class="is_active ? 'bg-white' : 'bg-black'"
                    >
                        <div>
                            <v-btn
                                variant="outlined"
                                :color="
                                    is_active
                                        ? 'white bg-primary'
                                        : 'white bg-success'
                                "
                                @click="handleActivation(id, is_active, i)"
                                class="border-md mx-2"
                            >
                                <v-icon>{{
                                    is_active ? 'mdi-stop' : 'mdi-play'
                                }}</v-icon>
                            </v-btn>

                            <v-btn
                                v-if="
                                    !is_active &&
                                    (route().current('link.update') ||
                                        route().current('show.links'))
                                "
                                variant="flat"
                                :color="
                                    is_active ? 'black' : 'white bg-primary'
                                "
                                :prepend-icon="'mdi-delete'"
                                @click="deleteShortlink(short_code)"
                                class="font-weight-bold m-2"
                            >
                                DELETE
                            </v-btn>
                        </div>

                        <div
                            class="d-flex mx-2 justify-between"
                            :class="is_active ? 'text-black' : 'text-white'"
                        >
                            <small
                                variant="text"
                                class="mx-2"
                                :class="
                                    is_active
                                        ? 'text-primary font-weight-bold'
                                        : 'text-primary font-weight-bold'
                                "
                            >
                                {{ total_clicks + qr_scans }} engagements
                            </small>

                            <small>
                                <span variant="text">
                                    {{
                                        new Date(created_at).toLocaleDateString(
                                            'en-US',
                                            {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                            },
                                        )
                                    }}
                                </span>

                                <span> - </span>

                                <span variant="text">
                                    {{
                                        new Date(expires_at).toLocaleDateString(
                                            'en-US',
                                            {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                            },
                                        )
                                    }}
                                </span>
                            </small>
                        </div>
                    </v-card-actions>
                </v-card>

                <QrCodeComponent
                    v-if="route().current('link.analytics')"
                    :input="{
                        user_url,
                        short_url,
                        short_code,
                        is_active,
                        total_clicks,
                        unique_clicks,
                        qr_scans,
                    }"
                />
            </v-col>
        </v-row>
    </div>
</template>
