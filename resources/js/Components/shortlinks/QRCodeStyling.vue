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
    generateQRCode(newInput);
}, { immediate: true });
</script>

<template>
<div class="flex flex-col items-center">
    <v-img
        :width="100"
        aspect-ratio="16/9"
        cover
        :src="state.base64QRCode"
    ></v-img>
    <p>QR Code</p>
</div>
</template>
