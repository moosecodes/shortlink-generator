<script setup>
import { onMounted, reactive, computed } from 'vue';
import { VForm, VChip, VRow, VCol, VTextField, VBtn, VCard, VCardActions, VCardItem } from 'vuetify/components';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    shortlink_id: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    shortlink: {
        id: 0,
        original_url: '',
        is_active: 0,
        metadata: [],
    },
    showUTMFields: false,
    message: '',
    valid: false,
});

const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/show/${props.shortlink_id.shortlink_id}`);
        state.shortlink = response.data;
        state.shortlink.metadata = response.data.metadata;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch(`/api/shortlink/update`, {
            id: state.shortlink.id,
            short_code: state.shortlink.short_code,
            original_url: state.shortlink.original_url,
            metadata: state.shortlink.metadata,
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
    state.shortlink.metadata.push({ meta_key: '', meta_value: '' });
};

const sortedMetadata = computed(() => {
    return state.shortlink.metadata.slice().sort((a, b) => {
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

const deleteShortlink = async({ short_code }) => {
    const confirmDelete = confirm(`Are you sure you want to delete this shortlink ${short_code}?`);

    if (confirmDelete) {
        try {
            await axios.delete(`/api/shortlinks/delete/${short_code}`);
            window.location.reload();
        } catch (error) {
            console.error('Error deleting shortlink:', error);
        }
    }
}

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active
            ? `/api/shortlinks/deactivate/${shortlink.short_code}`
            : `/api/shortlinks/activate/${shortlink.short_code}`;

        await axios.patch(route);

        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

onMounted(fetchShortlink);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Update Shortlink</h1>

        <v-form
            v-model="state.valid"
            @submit.prevent="submitForm">
            <v-card
                class="mx-auto"
                :color="state.shortlink?.is_active ? 'indigo' : 'indigo darken-4'"
                :variant="state.shortlink?.is_active ? 'elevated' : 'tonal'">
                    <v-card-item>
                        <div class="text-overline mb-1 flex justify-between align-center">
                            <div>
                                <v-chip class="my-2 mr-2"><b>{{ state.shortlink.short_code }}</b></v-chip>
                                <v-chip
                                    class="my-2"
                                    :color="state.shortlink?.is_active ? 'green' : 'red'"
                                    variant="flat">
                                    {{ state.shortlink.is_active ? 'Active' : 'Inactive' }}</v-chip>
                            </div>
                            <div>
                                <v-chip class="my-2 mr-2">{{ state.shortlink.total_clicks }} Clicks</v-chip>
                                <v-chip class="my-2 mr-2">{{ state.shortlink.unique_clicks }} Unique Clicks</v-chip>
                            </div>
                        </div>
                    </v-card-item>

                    <v-card-item>
                        <div class="d-flex flex-wrap flex-column">
                            <v-chip class="text-caption my-2 mr-2 item-justify-start">
                                <a :href="state.shortlink.short_url"
                                    target="_blank">
                                    {{ state.shortlink.short_url }}
                                </a>
                            </v-chip>
                            <v-chip class="text-caption my-2 mr-2 item-justify-start">
                                {{
                                    state.redirects?.
                                        filter(redirect => state.shortlink.short_code === redirect.short_code)
                                        [0]
                                        ?.redirect
                                }}
                            </v-chip>
                        </div>
                    </v-card-item>

                    <v-card-actions :class="state.shortlink.is_active ? 'd-flex flex-wrap bg-indigo-darken-2' : 'd-flex flex-wrap bg-black'">
                        <small class="mx-2">Created: <b>{{ new Date(state.shortlink.created_at).toLocaleString() }}</b></small>
                        <small class="mx-2">Updated: <b>{{ new Date(state.shortlink.updated_at).toLocaleString() }}</b></small>
                    </v-card-actions>

                    <v-card-actions :class="state.shortlink.is_active ? 'd-flex flex-wrap justify-end bg-indigo-darken-3' : 'd-flex justify-end flex-wrap bg-black'">
                        <div class="d-flex flex-wrap">
                            <v-btn
                                variant="outlined"
                                :href="`/api/shortlinks/redirect/${state.shortlink.short_code}`"
                                target="_blank"
                                prepend-icon="mdi-eye"
                                class="m-2">
                                View Link
                            </v-btn>

                            <v-btn
                                variant="flat"
                                :prepend-icon="state.shortlink.is_active ? 'mdi-cancel' : 'mdi-connection'"
                                :color="state.shortlink.is_active ? 'error' : 'success'"
                                @click="toggleActivation(state.shortlink)"
                                class="m-2">
                                {{ state.shortlink.is_active ? 'Disable' : 'Enable' }}
                            </v-btn>

                            <v-btn
                                v-if="!state.shortlink.is_active"
                                variant="flat"
                                prepend-icon="mdi-delete"
                                color="error"
                                @click="deleteShortlink(state.shortlink)"
                                class="m-2">
                                Delete
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
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
                <v-row v-for="(field, i) in sortedMetadata" :key="i">
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
                        ></v-text-field>
                        <v-text-field
                            v-if="state.showUTMFields"
                            v-model="field.meta_value"
                            label="Value"
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-btn class="mx-4" type="submit" color="primary">{{state.message ? state.message : 'Update Shortlink' }}</v-btn>
                </v-row>
            </div>
        </v-form>
    </div>
</template>
