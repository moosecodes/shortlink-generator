<script setup>
import { reactive, watch } from 'vue';
import { VImg } from 'vuetify/components';
import QRCode from 'qrcode';

const props = defineProps({
    input: String,
});

const state = reactive({
    base64QRCode: '',
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
                    dark:"#000000ff",
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
    generateQRCode(newInput);
}, { immediate: true });
</script>

<template>
<div class="flex flex-col items-center">
    <v-img
        :width="200"
        :src="state.base64QRCode"
    />
    <p class="my-4">QR Code for {{ props.input }}</p>
</div>
</template>
