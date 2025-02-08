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
                        :color="plan.recommended ? 'primary' : 'grey'"
                        class="mt-4"
                        block
                        large
                        @click="selectPlan(plan)"
                    >
                        {{ plan.recommended ? 'Get Started' : 'Choose Plan' }}
                    </v-btn>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

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
        features: [
            '10 short links/month',
            '10 vanity urls/month',
            '10 QR codes/month',
            'Links expire after 7 days',
        ],
        recommended: true,
    },
    {
        name: 'Core',
        price: 9.99,
        features: [
            '100 short links/month',
            '100 vanity urls/month',
            '100 QR codes/month',
            'Shortlink click tracking',
            'Unique clicks tracking',
            'Location & traffic analytics',
            'UTM Metadata parameters',
            'Custom metadata parameters',
            'Link activation toggles',
            'No link expiration',
        ],
        recommended: false,
    },
    {
        name: 'Growth',
        price: 50,
        features: [
            'Unlimited short links/month',
            'Unlimited vanity urls/month',
            'Unlimited QR codes/month',
            'Shortlink click tracking',
            'Unique clicks tracking',
            'Location & traffic analytics',
            'UTM Metadata parameters',
            'Custom metadata parameters',
            'Link activation toggles',
            'No link expiration',
        ],
        recommended: false,
    },
]);

const selectPlan = (plan) => {
    console.log(`Selected Plan: ${plan.name}`);
    // Redirect or trigger checkout logic here
};
</script>

<style scoped>
.border-primary {
    border: 2px solid #1976d2;
}
</style>
