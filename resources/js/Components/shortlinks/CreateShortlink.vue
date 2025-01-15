<script setup>
import { ref, reactive, computed } from 'vue';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const state = reactive({
    shortlink: {
        shortlink: '',
        original_url: 'https://www.potty.com',
        metadata: [
            { meta_key: 'utm_source', meta_value: 'potty' },
            { meta_key: 'utm_medium', meta_value: 'email' },
            { meta_key: 'utm_campaign', meta_value: 'newsletter' },
            { meta_key: 'utm_term', meta_value: 'spring_sale' },
            { meta_key: 'utm_content', meta_value: 'cta_button' },
        ],
    },
    showUTMFields: true,
    message: '',
    valid: false,
});

const message = ref('');
const valid = ref(false);

const submitForm = async () => {
    try {
        const response = await axios.post('/api/shortlinks/create', {
            original_url: state.shortlink.original_url,
            metadata: state.shortlink.metadata,
        });
        state.message = response;
    } catch (error) {
        console.error('Error creating shortlink:', error);
        state.message = error;
    }
};

const toggleUTMFields = () => {
    console.log(state.showUTMFields)
    state.showUTMFields = !state.showUTMFields;
}
const addNewField = () => {
    state.shortlink.metadata.push({ meta_key: '', meta_value: '' });
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
                    v-model="state.shortlink.original_url"
                    label="Redirect to (URL)"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="toggleUTMFields">{{ state.showUTMFields ? 'Hide' : 'Show' }} UTM Fields</v-btn>
            </v-col>
        </v-row>
        <div v-if="state.showUTMFields">
            <v-row>
                <v-col v-for="(field, i) in state.shortlink.metadata" :key="i" cols="12" md="4">
                    <v-text-field
                        v-model="field.meta_key"
                        label="Key"
                        :label="field.meta_key"
                    ></v-text-field>
                    <v-text-field
                        v-model="field.meta_value"
                        label="Value"
                        :label="field.meta_value"
                    ></v-text-field>
                </v-col>
            </v-row>
        </div>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="addNewField">Add Custom Field</v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>
