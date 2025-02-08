<script setup>
import { computed, reactive, ref } from 'vue';
import { VBtn, VCol, VForm, VRow, VTextField } from 'vuetify/components';

const state = reactive({
    shortlink: {
        title: '',
        custom_short_code: '',
        target_url: 'http://www.reddit.com',
        metadatas: [],
    },
    showFormFields: true,
    message: '',
});

const valid = ref(false);
const formRef = ref(null); // Reference to the v-form

// Validation rules
const rules = {
    required: (value) => !!value || 'This field is required.',
    url: (value) => {
        const pattern = /^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w-./?%&=]*)?$/i;
        return pattern.test(value) || 'Enter a valid URL';
    },
    minLength: (value) => value.length >= 3 || 'Must be at least 3 characters',
};

// Create Shortlink Function
const createNewLink = async () => {
    const { valid } = await formRef.value.validate(); // Validate form before submission
    if (!valid) return; // Stop if validation fails

    try {
        const response = await axios.post('/api/manage/new', {
            user_id: '',
            title: state.shortlink.title,
            target_url: state.shortlink.target_url,
            metadatas: state.shortlink.metadatas,
            custom_short_code: state.shortlink.custom_short_code,
        });

        state.message = 'Shortlink created successfully!';
    } catch (error) {
        console.error('Error creating shortlink:', error);
        state.message = 'Failed to create shortlink.';
    }
};

// Toggle UTM Fields
const toggleUTMFields = () => {
    if (state.shortlink.metadatas.length) {
        state.shortlink.metadatas = state.shortlink.metadatas.slice(0, 0);
    } else {
        state.shortlink.metadatas = [
            { meta_key: 'utm_source', meta_value: '' },
            { meta_key: 'utm_medium', meta_value: '' },
            { meta_key: 'utm_campaign', meta_value: '' },
            { meta_key: 'utm_term', meta_value: '' },
            { meta_key: 'utm_content', meta_value: '' },
        ];
    }
};

// Add new custom metadata field
const addNewMetaField = () => {
    state.shortlink.metadatas.push({ meta_key: '', meta_value: '' });
};

// Computed parameter preview
const computedParameters = computed(() => {
    let parameters = state.shortlink.metadatas.map((m) =>
        m.meta_key && m.meta_value ? `${m.meta_key}=${m.meta_value}` : null,
    );
    parameters = parameters.filter((p) => p);
    return parameters.join('&');
});
</script>

<template>
    <v-row>
        <v-col cols="12">
            <h1 class="mb-4 text-3xl font-semibold">{{ $page.props.title }}</h1>
        </v-col>
    </v-row>

    <!-- v-form validation -->
    <v-form ref="formRef" v-model="valid" @submit.prevent="createNewLink">
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="state.shortlink.title"
                    variant="solo"
                    label="Shortlink Title (optional)"
                />

                <v-text-field
                    v-model="state.shortlink.target_url"
                    variant="solo"
                    label="Target URL"
                    required
                    :rules="[rules.required, rules.url]"
                />

                <v-text-field
                    v-model="state.shortlink.custom_short_code"
                    variant="solo"
                    label="Custom Short Code (optional)"
                />
            </v-col>
        </v-row>

        <!-- Preview -->
        <v-row v-if="state.shortlink.target_url.length">
            <v-col cols="12">
                <p>Expanded URL Preview</p>
                {{ state.shortlink.target_url }}/{{
                    computedParameters ? '?' + computedParameters : ''
                }}
            </v-col>
        </v-row>

        <!-- Toggle UTM Fields -->
        <v-row class="my-4">
            <v-col>
                <v-btn color="info" @click="toggleUTMFields">
                    {{ state.shortlink.metadatas.length ? 'Remove' : 'Add' }}
                    UTM Fields
                </v-btn>
            </v-col>
        </v-row>

        <!-- UTM Fields -->
        <v-row v-if="state.showFormFields">
            <v-col cols="12">UTM Fields</v-col>
            <v-col
                v-for="(field, i) in state.shortlink.metadatas.filter((data) =>
                    data.meta_key.startsWith('utm_'),
                )"
                :key="i"
                cols="12"
                md="4"
            >
                <v-text-field
                    variant="solo"
                    v-model="field.meta_key"
                    label="Key"
                />
                <v-text-field
                    variant="solo"
                    v-model="field.meta_value"
                    label="Value"
                />
            </v-col>
        </v-row>

        <!-- Custom Fields -->
        <v-row>
            <v-col>Custom Fields</v-col>
            <v-col
                v-for="(field, i) in state.shortlink.metadatas.filter(
                    (data) => !data.meta_key.startsWith('utm_'),
                )"
                :key="i"
                cols="12"
            >
                <v-text-field
                    v-model="field.meta_key"
                    variant="solo"
                    label="Key"
                    :rules="[rules.required]"
                />
                <v-text-field
                    v-model="field.meta_value"
                    variant="solo"
                    label="Value"
                    :rules="[rules.required]"
                />
            </v-col>
        </v-row>

        <!-- Add Custom Field Button -->
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="addNewMetaField"
                    >Add Custom Field</v-btn
                >
            </v-col>
        </v-row>

        <!-- Submit Button -->
        <v-row>
            <v-col>
                <v-btn type="submit" color="primary">Create Shortlink</v-btn>
            </v-col>
            <v-col>
                <p v-if="state.message">{{ state.message }}</p>
            </v-col>
        </v-row>
    </v-form>
</template>
