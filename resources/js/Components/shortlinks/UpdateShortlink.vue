<script setup>
import { onMounted, reactive, computed } from 'vue';
import { VLabel, VForm, VRow, VCol, VTextField, VBtn, VCard, VCardTitle, VCardSubtitle, VCardText, VCardItem, VExpansionPanels, VExpansionPanel } from 'vuetify/components';
import axios from 'axios';

const props = defineProps({
    shortlink_id: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    shortlink: {
        id: 0,
        shortlink: '',
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
        console.log(response.data);

        state.shortlink.metadata = response.data.metadata;
        console.log(state.shortlink);

    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch(`/api/shortlinks/update`, {
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
onMounted(fetchShortlink);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Update Shortlink</h1>

        <v-form v-model="state.valid" @submit.prevent="submitForm">
            <v-row>
                <v-col cols="12" md="12">
                    <v-card>
                        <v-card-item>
                            <v-card-title>{{ props.shortlink_id.shortlink_id }}</v-card-title>
                            <v-card-subtitle>{{ state.shortlink.original_url }}</v-card-subtitle>
                        </v-card-item>
                        <v-card-text>
                            Active: {{ state.shortlink.is_active ? 'Yes' : 'No' }}
                        </v-card-text>
                        <div class="flex justify-between mb-4">
                            <div>
                                <v-btn class="mx-4" color="secondary" @click="addNewField">Add Custom Field</v-btn>
                                <v-btn class="mx-4" color="secondary" @click="toggleUTMFields">{{ state.showUTMFields ? 'Hide' : 'Edit' }} Fields</v-btn>
                            </div>
                            <v-btn class="mx-4" type="submit" color="primary">{{state.message ? state.message : 'Update Shortlink' }}</v-btn>
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
