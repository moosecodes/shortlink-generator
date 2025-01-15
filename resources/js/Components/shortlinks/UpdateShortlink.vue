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
    isActive: 0,
    showUTMFields: true,
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
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-btn color="secondary" @click="toggleUTMFields">{{ state.showUTMFields ? 'Hide' : 'Show' }} UTM Fields</v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <v-btn color="secondary" @click="addNewField">Add Custom Field</v-btn>
                </v-col>
            </v-row>

            <div v-if="state.showUTMFields">
                <v-row v-for="(field, i) in sortedMetadata" :key="i" cols="12" md="4">
                    <v-col>
                        <v-label v-if="field.meta_key.includes('utm_')">
                            <b>UTM Parameter</b>
                        </v-label>
                        <v-label v-else>
                            <b>Custom Parameter</b>
                        </v-label>

                        <v-text-field
                            v-model="field.meta_key"
                            label="Key"
                            required
                            :disabled="field.meta_key.includes('utm_')"
                            class=""
                        ></v-text-field>
                        <v-text-field
                            v-model="field.meta_value"
                            label="Value"
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>
            </div>

            <v-row>
                <v-col>
                    <v-btn type="submit" color="primary">Update Shortlink</v-btn>
                </v-col>
            </v-row>
        </v-form>
        <p v-if="state.message">{{ state.message }}</p>
    </div>
</template>
