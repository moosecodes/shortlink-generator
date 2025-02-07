<script setup>
import LinkDetailsBodyComponent from '@/Components/LinkDetails/LinkDetailsBodyComponent.vue';
import LinkDetailsFooterComponent from '@/Components/LinkDetails/LinkDetailsFooterComponent.vue';
import LinkDetailsHeaderComponent from '@/Components/LinkDetails/LinkDetailsHeaderComponent.vue';
import QrCodeComponent from '@/Components/QrCodes/QrCodeComponent.vue';
import { reactive, watch } from 'vue';
import { VCard, VCol, VRow } from 'vuetify/components';

const props = defineProps({
    filteredShortlinks: Object,
    required: true,
});

const state = reactive({
    filteredShortlinks: [],
});

const handleUpdateStatus = (i) => {
    state.filteredShortlinks[i].is_active =
        !state.filteredShortlinks[i].is_active;
};

watch(
    () => props.filteredShortlinks,
    (newValue) => {
        state.filteredShortlinks = newValue;
    },
    { immediate: true },
);
</script>

<template>
    <v-row v-if="!filteredShortlinks?.length">
        <p>No short links currently exist. Create a new link to get started!</p>
    </v-row>
    <div v-else>
        <v-row v-for="(link, index) in state.filteredShortlinks" :key="link.id">
            <v-col cols="12" md="12">
                <v-card
                    color="white"
                    :variant="link.is_active ? 'outlined' : 'plain'"
                >
                    <LinkDetailsHeaderComponent :data="link" />

                    <LinkDetailsBodyComponent :data="link" />

                    <LinkDetailsFooterComponent
                        :data="link"
                        @update-status="handleUpdateStatus(index)"
                    />
                </v-card>

                <QrCodeComponent
                    v-if="route().current('link.analytics')"
                    :input="link"
                />
            </v-col>
        </v-row>
    </div>
</template>
