<script setup>
import { VFooter, VCol, VRow } from 'vuetify/lib/components/index.mjs';
</script>

<template>
    <v-footer>
        <v-row>
            <v-col> Why Shortlinks? </v-col>
            <v-col> Products </v-col>
            <v-col> Feature </v-col>
            <v-col> Legal </v-col>
            <v-col> About </v-col>
        </v-row>
        <v-row>
            <v-col>
                &copy; {{ new Date().getFullYear() }} FreedomFace, LLC. All
                rights reserved.
            </v-col>
        </v-row>
    </v-footer>
</template>
