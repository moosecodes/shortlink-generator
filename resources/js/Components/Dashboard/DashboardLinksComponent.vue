<script setup>
import { defineEmits } from 'vue';
import { VBtn, VCol, VRow } from 'vuetify/lib/components/index.mjs';
import { toggleActivation } from '../../utils/requests';

defineProps({
    links: Object,
});

const emit = defineEmits(['update-status']);

const handleActivation = async (id, is_active) => {
    const response = await toggleActivation(id, is_active);
    emit('update-status', is_active);
};
</script>

<template>
    <v-row v-if="links?.length">
        <v-col cols="12" md="12">
            <div class="my-2 text-3xl font-semibold">Short Links</div>
        </v-col>

        <v-col
            v-for="{
                short_code,
                target_url,
                short_url,
                final_url,
                title,
                id,
                is_active,
                is_premium,
                expires_at,
            } in links"
            :key="short_code"
            cols="12"
            md="12"
        >
            <p>
                {{ title || 'Title not set.' }}
            </p>

            <p>
                <a
                    :href="route('link.analytics', { shortlink_id: id })"
                    :class="is_active ? 'text-accent' : 'text-warning'"
                    class="font-weight-bold"
                >
                    {{ short_code }}
                </a>
            </p>

            <p>
                <a
                    :href="short_url"
                    target="_blank"
                    :class="is_active ? 'text-accent' : 'text-warning'"
                    class="font-weight-bold"
                >
                    {{ short_url }}
                </a>
            </p>

            <v-btn
                variant="outlined"
                :icon="is_active ? 'mdi-stop' : 'mdi-play'"
                :color="is_active ? 'white bg-primary' : 'white bg-success'"
                @click="handleActivation(id, is_active)"
                class="border-md mr-4"
            >
            </v-btn>

            <v-btn
                variant="outlined"
                :icon="'mdi-eye'"
                :href="short_url"
                :class="is_active ? 'text-accent' : 'text-warning'"
                :target="'_blank'"
                class="font-weight-bold m-2"
            >
            </v-btn>

            <v-btn
                v-if="!route().current('link.update')"
                variant="outlined"
                icon="mdi-link-edit"
                :href="`/link/edit/byShortCode/${short_code}`"
                :class="is_active ? 'text-accent' : 'text-warning'"
                class="m-4"
            >
            </v-btn>
        </v-col>
    </v-row>

    <v-row v-else>
        <v-col cols="12" md="12">
            <v-btn
                class="text-2xl font-semibold"
                color="primary"
                :href="route('link.create')"
            >
                Create New Link
            </v-btn>
        </v-col>
    </v-row>
</template>
