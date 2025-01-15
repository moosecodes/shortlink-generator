<script setup>
import { onMounted, ref } from 'vue';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const props = defineProps({
    shortlink_id: {
        type: Object,
        required: true,
    },
});

const shortlink = ref({});
const isActive = ref(0);
const showUTMFields = ref(true);
const utmValues = ref({});
const message = ref('');
const valid = ref(false);

const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/show/${props.shortlink_id.shortlink_id}`);

        shortlink.value = response.data;
        shortlink.value.customFields = [];
        shortlink.value.metadata.forEach(item => {
            if (!item.meta_key.includes('utm_')) {
                shortlink.value.customFields.push({ meta_key: item.meta_key, meta_value: item.meta_value });
            }
        });

        console.log("Shortlink metadata: ", shortlink.value.metadata);
        console.log("customFields variable: ", shortlink.value.customFields);

        shortlink.value.metadata.forEach((item) => {
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
            original_url: shortlink.value.original_url,
            metadata: [
                { meta_key: 'utm_source', meta_value: utmValues.value.utm_source },
                { meta_key: 'utm_medium', meta_value: utmValues.value.utm_medium },
                { meta_key: 'utm_campaign', meta_value: utmValues.value.utm_campaign },
                { meta_key: 'utm_term', meta_value: utmValues.value.utm_term },
                { meta_key: 'utm_content', meta_value: utmValues.value.utm_content },
                ...shortlink.value.customFields,
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
    shortlink.value.customFields.push({ meta_key: '', meta_value: '' });
};

onMounted(fetchShortlink)
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">Edit Shortlink {{ props.shortlink_id.shortlink_id }}</h1>
    </div>
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
                v-model="shortlink.original_url"
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
        <v-row>
            <v-col v-for="(field, index) in shortlink.customFields" :key="index" cols="12" md="6">
                <v-text-field
                    v-model="field.meta_key"
                    :label="`Custom Key ${index + 1}`"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="field.meta_value"
                    :label="`Custom Value ${index + 1}`"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
    </v-form>
</template>
