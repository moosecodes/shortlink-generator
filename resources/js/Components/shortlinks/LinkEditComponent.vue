<script setup>
import { router } from '@inertiajs/vue3';
import { onMounted, reactive, computed } from 'vue';
import { VBtn, VCard, VTextField, VForm, VRow, VCol, VChip } from 'vuetify/components';
import LinkDetailsComponent from './LinkDetailsComponent.vue';
import { fetchShortlinkbyShortCode, updateLink } from '@/Components/shortlinks/requests';

const props = defineProps({
    auth: Object,
    flash: Object,
    filteredShortlinks: Object,
    linkShortCode: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    message: '',
    valid: false,
});

const sortedMetadatas = computed(() => {
    if (!state.shortlink?.metadatas) {
        return [];
    }
    return state.shortlink.metadatas.slice().sort((a, b) => {
        const aHasUtm = a.meta_key.startsWith('utm_');
        const bHasUtm = b.meta_key.startsWith('utm_');
        if (aHasUtm && !bHasUtm) return -1;
        if (!aHasUtm && bHasUtm) return 1;
        return 0;
    });
});

const addCustomParam = () => {
    state.shortlink.metadatas.push({ meta_key: '', meta_value: '' });
};

const addUTMFields = () => {
  const metaTemplate = [
    { meta_key: 'utm_source', meta_value: '' },
    { meta_key: 'utm_medium', meta_value: '' },
    { meta_key: 'utm_campaign', meta_value: '' },
    { meta_key: 'utm_term', meta_value: '' },
    { meta_key: 'utm_content', meta_value: '' },
  ];

  metaTemplate.forEach(templateItem => {
    const exists = state.shortlink.metadatas.some(
      metadata => metadata.meta_key === templateItem.meta_key
    );
    if (!exists) {
      state.shortlink.metadatas.push(templateItem);
    }
  });
};

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

onMounted(async () => {
    await fetchShortlinkbyShortCode(props.linkShortCode.linkShortCode).then((response) => {
        state.shortlink = response;
    });
});
</script>

<template>
        <v-row>
            <v-col cols="12" md="12">
                <h1 class="text-3xl font-semibold">Edit Link</h1>
                <p class="text-2xl font-weight-black my-4">{{state.shortlink?.title || state.shortlink?.short_code}}</p>
                <div v-if="state.shortlink" class="my-4">
                    <v-text-field
                        v-model="state.shortlink.short_code"
                        variant="outlined"
                        label="Customize Short Code"
                        required
                    />
                </div>
                <!-- <LinkDetailsComponent v-if="state.shortlink" :filteredShortlinks="[state.shortlink]" /> -->
            </v-col>
        </v-row>

        <v-form
            v-model="state.valid"
            @submit.prevent="updateLink(state.shortlink)">

            <v-row>
                <v-card>
                    <v-col cols="12" md="12">
                        <v-btn
                            type="submit"
                            color="blue-grey"
                            @click="navigateTo('dashboard')"
                            class="m-4"
                        >
                            {{state.message ? state.message : 'Update Shortlink' }}
                        </v-btn>

                        <v-btn
                            color="blue-grey"
                            @click="addUTMFields"
                            class="m-4"
                        >
                            Add UTM Fields
                        </v-btn>
                </v-col>

                <!-- Custom Parameters -->
                <v-col cols="12" md="12">
                    <p class="text-2xl"><b>Custom Parameters</b></p>
                </v-col>

                <v-col v-for="(field, i) in sortedMetadatas.filter(d => !d.meta_key.includes('utm_'))" :key="i" cols="12" md="4">
                    <div class="mb-4"><b>Custom Parameter {{ i + 1 }}</b></div>
                    <v-text-field
                        v-model="field.meta_key"
                        variant="outlined"
                        label="Key"
                        required
                    />
                    <v-text-field
                        v-model="field.meta_value"
                        variant="outlined"
                        label="Value"
                        required
                        :disabled="!field.meta_key"
                    />
                </v-col>

                <v-col cols="12" md="12">
                    <v-card color="blue-grey">
                        <v-btn
                            color="blue-grey"
                            @click="addCustomParam"
                            class="m-4"
                        >Add Custom Parameter</v-btn>
                    </v-card>
                </v-col>
            </v-card>
        </v-row>

            <!-- UTM Parameters -->
            <v-row>
                <v-card>
                    <v-col cols="12" md="12">
                        <p class="text-2xl"><b>UTM Parameters</b></p>
                    </v-col>

                    <v-col v-for="(field, i) in sortedMetadatas.filter(d => d.meta_key.includes('utm_'))" :key="i" cols="12" md="12">
                        <v-chip class="mb-4">{{ field.meta_key }}</v-chip>

                        <v-text-field
                            v-model="field.meta_value"
                            label="Value"
                            required
                        />
                    </v-col>
                </v-card>
            </v-row>
        </v-form>
</template>
