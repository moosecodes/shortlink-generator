<script setup>
import { ref } from 'vue';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const originalUrl = ref('https://www.google.com');
const utmSource = ref('');
const showUTMFields = ref(true);
const customFields = ref([]);
const utmMedium = ref('');
const utmCampaign = ref('');
const utmTerm = ref('');
const utmContent = ref('');
const isActive = ref(0);
const message = ref('');
const valid = ref(false);

const submitForm = async () => {
    try {
        const response = await axios.post('/api/shortlinks', {
            original_url: originalUrl.value,
            metadata: [
                { meta_key: 'utm_source', meta_value: utmSource.value },
                { meta_key: 'utm_medium', meta_value: utmMedium.value },
                { meta_key: 'utm_campaign', meta_value: utmCampaign.value },
                { meta_key: 'utm_term', meta_value: utmTerm.value },
                { meta_key: 'utm_content', meta_value: utmContent.value },
                ...customFields.value,
            ],
            is_active: isActive.value,
        });
        message.value = 'Shortlink created successfully!';
    } catch (error) {
        console.error('Error creating shortlink:', error);
        message.value = 'Error creating shortlink.';
    }
};

const toggleUTMFields = () => {
    showUTMFields.value = !showUTMFields.value;
}
const addNewField = () => {
    customFields.value.push({ meta_key: '', meta_value: '' });
};
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">New Shortlink</h1>
    </div>

    <v-form v-model="valid" @submit.prevent="submitForm">
        <v-row>
            <v-col>
                <v-btn type="submit" color="primary">Create Shortlink</v-btn>
            </v-col>
            <v-col>
                <p v-if="message">{{ message }}</p>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="12">
                <v-text-field
                    v-model="originalUrl"
                    label="Redirect to (URL)"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="toggleUTMFields">{{ showUTMFields ? 'Hide' : 'Show' }} UTM Fields</v-btn>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmSource"
                    label="UTM Source"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmMedium"
                    label="UTM Medium"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmCampaign"
                    label="UTM Campaign"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmTerm"
                    label="UTM Term"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmContent"
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
