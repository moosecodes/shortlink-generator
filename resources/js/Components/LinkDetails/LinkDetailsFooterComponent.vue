<script setup>
import { defineEmits } from 'vue';
import { VBtn, VCardActions, VIcon } from 'vuetify/components';
import { toggleActivation } from '../shortlinks/requests';

const emit = defineEmits(['update-status']);

const props = defineProps({
    data: Object,
    i: Number,
});

const handleActivation = async (id, is_active) => {
    const response = await toggleActivation(id, is_active);
    emit('update-status', is_active);
};
</script>

<template>
    <v-card-actions
        class="d-flex flex-wrap justify-between"
        :class="data.is_active ? 'bg-white' : 'bg-black'"
    >
        <div>
            <v-btn
                variant="outlined"
                :color="
                    data.is_active ? 'white bg-primary' : 'white bg-success'
                "
                @click="handleActivation(data.id, data.is_active)"
                class="border-md mx-2"
            >
                <v-icon>{{ data.is_active ? 'mdi-stop' : 'mdi-play' }}</v-icon>
            </v-btn>

            <v-btn
                v-if="
                    !data.is_active &&
                    (route().current('link.update') ||
                        route().current('show.links'))
                "
                variant="flat"
                :color="data.is_active ? 'black' : 'white bg-primary'"
                :prepend-icon="'mdi-delete'"
                @click="deleteShortlink(data.short_code)"
                class="font-weight-bold m-2"
            >
                DELETE
            </v-btn>
        </div>

        <div
            class="d-flex mx-2 justify-between"
            :class="data.is_active ? 'text-black' : 'text-white'"
        >
            <small
                variant="text"
                class="mx-2"
                :class="
                    data.is_active
                        ? 'text-primary font-weight-bold'
                        : 'text-primary font-weight-bold'
                "
            >
                {{ data.total_clicks + data.qr_scans }} engagements
            </small>

            <small>
                <span variant="text">
                    {{
                        new Date(data.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                        })
                    }}
                </span>

                <span> - </span>

                <span variant="text">
                    {{
                        new Date(data.expires_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                        })
                    }}
                </span>
            </small>
        </div>
    </v-card-actions>
</template>
