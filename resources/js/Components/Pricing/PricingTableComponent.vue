<script setup>
import { ref } from 'vue';
import {
    VBtn,
    VCard,
    VCardSubtitle,
    VCardTitle,
    VCol,
    VContainer,
    VDivider,
    VIcon,
    VList,
    VListItem,
    VRow,
} from 'vuetify/lib/components/index.mjs';

const plans = ref([
    {
        name: 'Free',
        price: 0.0,
        route: 'register',
        features: [
            '5 short links/month',
            '5 vanity urls/month',
            '5 QR codes/month',
            'Shortlink click tracking',
            'Unique clicks tracking',
        ],
        recommended: false,
    },
    {
        name: 'Core',
        price: 11.99,
        route: 'https://buy.stripe.com/test_9AQ9AK4QO3mn7YY6oo',
        features: [
            '500 short links/month',
            '500 vanity urls/month',
            '500 QR codes/month',
            'Shortlink click tracking',
            'Unique clicks tracking',
            'QR code scan tracking',
            'Vanity back-half',
            'UTM Metadata parameters',
            'No link expiration',
        ],
        recommended: true,
    },
    {
        name: 'Pro',
        price: 19.99,
        route: 'https://buy.stripe.com/test_dR67sC2IG2ij5QQfYZ',
        features: [
            '1500 short links/month',
            '1500 vanity urls/month',
            '1500 QR codes/month',
            'Shortlink click tracking',
            'Unique clicks tracking',
            'QR code scan tracking',
            'Vanity back-half',
            'Location metrics',
            'UTM Metadata parameters',
            'Custom metadata parameters',
            'Password protected short links',
            'Link activation toggles',
            'No link expiration',
        ],
        recommended: false,
    },
]);
</script>

<style scoped>
.border-primary {
    border: 2px solid #fe2c55;
}
</style>

<template>
    <v-container class="my-10 text-center">
        <h2 class="text-h4 font-weight-bold mb-6">Choose Your Plan</h2>

        <v-row>
            <v-col v-for="(plan, i) in plans" :key="i" cols="12" sm="6" md="4">
                <v-card
                    class="pa-6 rounded-lg"
                    :class="{ 'border-primary': plan.recommended }"
                >
                    <v-card-title class="text-h5 font-weight-bold">
                        {{ plan.name }}
                    </v-card-title>

                    <v-card-subtitle class="text-h6 font-weight-bold my-2">
                        ${{ plan.price }}/mo
                    </v-card-subtitle>

                    <v-divider class="my-4"></v-divider>

                    <v-list dense>
                        <v-list-item
                            v-for="(feature, index) in plan.features"
                            :key="index"
                        >
                            <v-icon color="success" class="mr-2"
                                >mdi-check</v-icon
                            >
                            <span>{{ feature }}</span>
                        </v-list-item>
                    </v-list>

                    <v-btn
                        :color="plan.recommended ? 'primary' : 'white'"
                        class="mt-4"
                        block
                        large
                        :href="plan.route"
                    >
                        {{ plan.recommended ? 'Get Started' : 'Choose Plan' }}
                    </v-btn>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
