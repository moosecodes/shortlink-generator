<script setup>
import { VRow, VCol, VSelect } from 'vuetify/components';
import { reactive, watch, defineEmits } from 'vue';

// Define the event emitter
const emit = defineEmits(['filterChanged']);

defineProps({
    filteredCount: Number, // Number of links after filtering
    totalCount: Number, // Total number of links
});

const state = reactive({
    filter: 'All',
});

// Watch for changes in the filter and emit the event
watch(
    () => state.filter,
    (newFilter) => {
        emit('filterChanged', newFilter);
    },
);
</script>

<template>
    <v-row>
        <v-col>
            <h2 class="my-2 font-bold">
                {{ state.filter }} Links ({{ filteredCount }}/{{ totalCount }})
            </h2>
        </v-col>

        <v-col>
            <v-select
                v-model="state.filter"
                variant="solo"
                :items="[
                    'All',
                    'Active',
                    'Inactive',
                    'Expired',
                    'Not Expired',
                    'Clicks: High to Low',
                    'Clicks: Low to High',
                ]"
                label="Filter Shortlinks"
            ></v-select>
        </v-col>
    </v-row>
</template>
