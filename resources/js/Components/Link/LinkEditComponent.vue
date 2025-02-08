<script setup>
import { fetchShortlinkbyShortCode, updateLink } from '@/utils/requests';
import { router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, reactive } from 'vue';
import { VBtn, VCol, VForm, VRow, VTextField } from 'vuetify/components';

const page = usePage();

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

    metaTemplate.forEach((templateItem) => {
        const exists = state.shortlink.metadatas.some(
            (metadata) => metadata.meta_key === templateItem.meta_key,
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
    await fetchShortlinkbyShortCode(page.props.linkShortCode).then(
        (response) => {
            state.shortlink = response;
        },
    );
});
</script>

<template>
    <div>
        <v-row>
            <v-col cols="12" md="12">
                <h1 class="text-3xl font-semibold">{{ page.props.title }}</h1>
            </v-col>

            <v-col cols="12" md="12">
                <v-form
                    v-model="state.valid"
                    @submit.prevent="updateLink(state.shortlink)"
                >
                    <p class="font-weight-black text-2xl">
                        {{
                            state.shortlink?.title ||
                            state.shortlink?.short_code
                        }}
                    </p>

                    <v-text-field
                        v-if="state.shortlink"
                        v-model="state.shortlink.short_code"
                        variant="solo-filled"
                        label="Customize Short Code"
                        required
                    />

                    <v-btn
                        type="submit"
                        color="primary"
                        @click="navigateTo('dashboard')"
                        class="my-2"
                    >
                        {{ state.message ? state.message : 'Update Shortlink' }}
                    </v-btn>

                    <!-- UTM Parameters -->
                    <p class="text-1xl"><b>UTM Parameters</b></p>

                    <v-btn color="white" @click="addUTMFields" class="my-2">
                        Add UTM Fields
                    </v-btn>

                    <div
                        v-for="(field, i) in sortedMetadatas.filter((d) =>
                            d.meta_key.includes('utm_'),
                        )"
                        :key="i"
                    >
                        <p class="font-weight-bold mb-4">
                            {{ field.meta_key }}
                        </p>

                        <v-text-field
                            v-model="field.meta_value"
                            variant="solo-filled"
                            label="Value"
                            required
                        />
                    </div>

                    <!-- Custom Parameters -->
                    <p class="text-1xl"><b>Custom Parameters</b></p>

                    <v-btn color="white" @click="addCustomParam" class="my-4">
                        Add Custom Parameter
                    </v-btn>

                    <div
                        v-for="(field, i) in sortedMetadatas.filter(
                            (d) => !d.meta_key.includes('utm_'),
                        )"
                        :key="i"
                    >
                        <p class="mb-4">
                            <b>Custom Parameter {{ i + 1 }}</b>
                        </p>

                        <v-text-field
                            v-model="field.meta_key"
                            variant="solo-filled"
                            label="Key"
                            required
                        />
                        <v-text-field
                            v-model="field.meta_value"
                            variant="solo-filled"
                            label="Value"
                            required
                            :disabled="!field.meta_key"
                        />
                    </div>
                </v-form>
            </v-col>
        </v-row>
    </div>
</template>
