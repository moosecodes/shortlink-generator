<script setup>
import dayjs from 'dayjs';
import { VCardActions, VCardTitle, VChip, VIcon } from 'vuetify/components';

const props = defineProps({
    data: Object,
});

const isRecent = (created_at, updated_at) => {
    const now = dayjs();
    const createdAt = dayjs(created_at);
    const updatedAt = dayjs(updated_at);

    return (
        now.diff(createdAt, 'minute') < 15 || now.diff(updatedAt, 'minute') < 15
    );
};
</script>

<template>
    <v-card-actions
        class="d-flex flex-wrap justify-between"
        :class="props.data.is_active ? 'bg-white' : 'bg-black'"
    >
        <v-card-title>
            <span
                v-if="isRecent(props.data.short_code, props.data.updated_at)"
                :class="
                    props.data.is_active ? 'text-green-500' : 'text-primary'
                "
                class="mr-2"
            >
                [ NEW ]
            </span>
            <v-chip variant="outlined">
                <span
                    :class="
                        props.data.is_active ? 'text-green-500' : 'text-primary'
                    "
                    class="font-weight-bold mx-2"
                >
                    {{ props.data.short_code }}
                </span>
            </v-chip>
        </v-card-title>

        <div>
            <v-chip variant="text">
                <span
                    class="text-lowercase font-weight-bold mx-2"
                    :class="
                        props.data.is_active ? 'text-green-500' : 'text-primary'
                    "
                >
                    {{ props.data.is_active ? 'active' : 'inactive' }}
                </span>

                <v-icon
                    class="mx-2"
                    :color="props.data.is_active ? 'green-500' : 'primary'"
                    >{{
                        props.data.is_active
                            ? 'mdi-signal-variant'
                            : 'mdi-rss-off'
                    }}
                </v-icon>
            </v-chip>
        </div>
    </v-card-actions>
</template>
