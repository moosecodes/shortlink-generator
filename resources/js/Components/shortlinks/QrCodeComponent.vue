<script setup>
import { reactive, watch } from 'vue';
import { VImg, VChip, VCard, VCardActions } from 'vuetify/components';
import QRCode from 'qrcode';

const props = defineProps({
    input: Object,
});

const state = reactive({
    base64QRCode: '',
    scans: 0,
    qrURL: '',
});

const generateQRCode = async (input) => {
    try {
        if (input) {
            state.base64QRCode = await QRCode.toDataURL(input, {
                errorCorrectionLevel: 'H',
                type: 'image/jpeg',
                margin: 4,
                scale: 4,
                color: {
                    dark: '#fe2c55ff',
                    light: '#ffffffff',
                },
            });
        }
    } catch (error) {
        console.error('Error generating QR code:', error);
    }
};

// Watch for changes to props.input and generate the QR code when it changes
watch(
    () => props.input.short_url,
    (newInput) => {
        if (!newInput) return;
        newInput += newInput.indexOf('?') < 0 ? '?qr=1' : '&qr=1';
        state.qrURL = newInput;
        generateQRCode(newInput);
    },
    { immediate: true },
);
</script>

<template>
    <v-card
        class="my-4"
        :color="props.input.is_active ? 'white ' : 'black'"
        :variant="props.input.is_active ? 'flat' : 'flat'"
    >
        <v-card-actions class="flex-column flex">
            <v-card-actions>
                <v-chip variant="outlined" class="mx-2"
                    >Clicks: {{ input.total_clicks }}</v-chip
                >
                <v-chip variant="outlined" class="mx-2"
                    >Unique: {{ input.unique_clicks }}</v-chip
                >
            </v-card-actions>

            <v-card-actions>
                <v-chip variant="outlined" class="mx-2"
                    >Last 7 Days: xxx clicks</v-chip
                >
                <v-chip variant="outlined" class="mx-2"
                    >Weekly Change: xx%</v-chip
                >
            </v-card-actions>
        </v-card-actions>
    </v-card>

    <v-card
        class="my-4"
        :color="props.input.is_active ? 'white ' : 'black'"
        :variant="props.input.is_active ? 'flat' : 'flat'"
    >
        <v-card-actions class="flex-column flex">
            <v-img :width="200" :src="state.base64QRCode" />

            <v-chip variant="outlined" class="font-weight-bold m-2"
                >QR Scans: {{ props.input.qr_scans }}</v-chip
            >
        </v-card-actions>
    </v-card>
</template>
