<script setup>
import { onMounted, ref } from 'vue';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const originalUrl = ref('');
const customFields = ref([]);
const isActive = ref(0);
const message = ref('');
const valid = ref(false);
const shortlink = ref({});
const showUTMFields = ref(true);
const utmValues = ref({
    utmSource: '',
    utmMedium: '',
    utmCampaign: '',
    utmTerm: '',
    utmContent: '',
});

const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/show/3b910d2e`);
        shortlink.value = response.data;
        originalUrl.value = shortlink.value.original_url;

        shortlink.metadata = shortlink.value.metadata.reduce((acc, item) => {
            if (item.meta_key) {
                acc[item.meta_key] = item.meta_value;
            } else {
                acc.customFields.push({ key: item.meta_key, value: item.meta_value });
            }
            return acc;
        }, { customFields: [] });
        response.data.metadata.forEach((item) => {
            utmValues.value[item.meta_key] = item.meta_value;
        });
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch('/api/shortlinks/update', {
            id: shortlink.value.id,
            original_url: originalUrl.value,
            metadata: [
                { key: 'utm_source', value: utmValues.value.utm_source },
                { key: 'utm_medium', value: utmValues.value.utm_medium },
                { key: 'utm_campaign', value: utmValues.value.utm_campaign },
                { key: 'utm_term', value: utmValues.value.utm_term },
                { key: 'utm_content', value: utmValues.value.utm_content },
                ...customFields.value,
            ],
            is_active: isActive.value,
        });

        message.value = 'Shortlink updated successfully!';
    } catch (error) {
        console.error('Error updating shortlink:', error);
        message.value = 'Error updating shortlink.';
    }
};

const toggleUTMFields = () => {
    showUTMFields.value = !showUTMFields.value;
}
const addNewField = () => {
    customFields.value.push({ key: '', value: '' });
};

onMounted(fetchShortlink)
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">Edit Shortlink</h1>
    </div>
{{ shortlink }}
{{  utmValues }}
    <v-form v-model="valid" @submit.prevent="submitForm">
        <v-row>
            <v-col>
                <v-btn type="submit" color="primary">Update Shortlink</v-btn>
            </v-col>
            <v-col>
                <p v-if="message">{{ message }}</p>
            </v-col>
        </v-row>
        <v-row>
            <v-text-field
                v-model="originalUrl"
                label="Original URL"
                disabled>
            </v-text-field>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="toggleUTMFields">{{ showUTMFields ? 'Hide' : 'Show' }} UTM Fields</v-btn>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmValues.utm_source"
                    label="UTM Source"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmValues.utm_medium"
                    label="UTM Medium"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmValues.utm_campaign"
                    label="UTM Campaign"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmValues.utm_term"
                    label="UTM Term"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmValues.utm_content"
                    label="UTM Content"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="addNewField">Add Custom Field</v-btn>
            </v-col>
        </v-row>
        <v-row >
            <v-col v-for="(field, index) in customFields" :key="index" cols="12" md="6">
                <v-text-field
                    v-model="field.key"
                    :label="`Custom Key ${index + 1}`"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="field.value"
                    :label="`Custom Value ${index + 1}`"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
    </v-form>
</template>
