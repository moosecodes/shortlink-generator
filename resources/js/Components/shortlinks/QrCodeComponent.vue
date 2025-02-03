<script setup>
import { reactive, watch } from 'vue';
import { VImg, VChip } from 'vuetify/components';
import QRCode from 'qrcode';

const props = defineProps({
    input: String,
    scans: Number,
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
                    dark:"#fe2c55ff",
                    light:"#ffffffff"
                }
            });
        }
    } catch (error) {
        console.error('Error generating QR code:', error);
    }
};

// Watch for changes to props.input and generate the QR code when it changes
watch(() => props.input, (newInput) => {
    if(!newInput) return;
    newInput +=  newInput.indexOf("?") < 0 ? "?qr=1" : "&qr=1";
    state.qrURL = newInput;
    generateQRCode(newInput);
}, { immediate: true });
</script>

<template>
    <div class="flex flex-col items-center my-4">
        <v-chip class="my-4">{{ scans }} QR Scans</v-chip>

        <v-img
            :width="200"
            :src="state.base64QRCode"
        />

        <v-chip class="my-4">
            <a :href="state.qrURL" target="_blank">{{ state.qrURL }}</a>
        </v-chip>
    </div>
</template>
