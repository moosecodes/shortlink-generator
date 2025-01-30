<script setup>
import { onMounted, reactive, computed } from 'vue';
import CardDetails from './CardDetails.vue';
import { VForm, VRow, VCol, VTextField, VBtn, VCard } from 'vuetify/components';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    auth: Object,
    flash: Object,
    shortlink_id: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    userId: page.props.auth.user?.id,
    shortlink: {
        id: 0,
        user_url: '',
        is_active: 0,
        metadatas: [],
    },
    redirects: [],
    showUTMFields: false,
    message: '',
    valid: false,
});

const fetchShortlink = async () => {
    try {
        const response = await axios.post(`/api/link/details`, {
            id: props.shortlink_id.shortlink_id,
            userId: state.userId,
        });
        state.shortlink = response.data;
        state.shortlink.metadatas = response.data.metadatas;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch(`/api/link/update`, {
            id: state.shortlink.id,
            short_code: state.shortlink.short_code,
            user_url: state.shortlink.user_url,
            metadatas: state.shortlink.metadatas,
        });
        state.message = 'Shortlink updated successfully!';
    } catch (error) {
        console.error('Error updating shortlink:', error);
        state.message = error;
    }
};

const toggleUTMFields = () => {
    state.showUTMFields = !state.showUTMFields;
}

const addNewField = () => {
    state.shortlink.metadatas.push({ meta_key: '', meta_value: '' });
};

const sortedMetadatas = computed(() => {
    return state.shortlink.metadatas.slice().sort((a, b) => {
        const aHasUtm = a.meta_key.startsWith('utm_');
        const bHasUtm = b.meta_key.startsWith('utm_');
        if (aHasUtm && !bHasUtm) return -1;
        if (!aHasUtm && bHasUtm) return 1;
        return 0;
    });
});

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

onMounted(fetchShortlink);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Update Shortlink</h1>

        <v-form
            v-model="state.valid"
            @submit.prevent="submitForm">

            <CardDetails :shortlink="state.shortlink" :redirects="state.redirects" />

            <v-row class="my-4">
                <v-col cols="12" md="12">
                    <v-card>
                        <div class="flex justify-center my-4">
                            <v-btn
                                type="submit"
                                color="primary"
                                @click="navigateTo('dashboard')">{{state.message ? state.message : 'Update Shortlink' }}</v-btn>
                            <v-btn
                                color="secondary"
                                @click="addNewField">Add Custom Field</v-btn>
                            <v-btn
                                color="secondary"
                                @click="toggleUTMFields">{{ state.showUTMFields ? 'Hide' : 'Edit' }} Fields</v-btn>
                        </div>
                    </v-card>
                </v-col>
            </v-row>

            <div>
                <v-row v-for="(field, i) in sortedMetadatas" :key="i">
                    <v-col cols="12" md="12">
                        <div v-if="!field.meta_key.includes('utm_')"><b>Custom Parameter {{ i - 4 }}</b></div>
                        <div v-else><b>UTM Parameter</b></div>
                        <div>Key: {{ field.meta_key }}</div>
                        <div>Value: {{ field.meta_value }}</div>
                        <v-text-field
                            v-if="state.showUTMFields && !field.meta_key.includes('utm_')"
                            v-model="field.meta_key"
                            label="Key"
                            required
                            class=""
                        />
                        <v-text-field
                            v-if="state.showUTMFields"
                            v-model="field.meta_value"
                            label="Value"
                            required
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-btn class="mx-4" type="submit" color="primary">{{state.message ? state.message : 'Update Shortlink' }}</v-btn>
                </v-row>
            </div>
        </v-form>
    </div>
</template>
