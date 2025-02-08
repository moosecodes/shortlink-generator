<script setup>
import { VRow, VCol } from 'vuetify/lib/components/index.mjs';
</script>

<template>
    <v-row>
        <v-col cols="12" md="12">
            <p class="text-3xl font-semibold">
                {{ $page.props.title }}
            </p>
        </v-col>
    </v-row>
</template>
