<script setup>
import {VForm, VTextField, VContainer, VRow, VCol} from 'vuetify/components';
import { ref } from 'vue';

const valid = ref(false);
const firstname = ref('');
const lastname = ref('');
const email = ref('');

const nameRules = [
  value => !!value || 'Name is required.',
  value => (value && value.length <= 10) || 'Name must be less than 10 characters.',
];

const emailRules = [
  value => !!value || 'E-mail is required.',
  value => /.+@.+\..+/.test(value) || 'E-mail must be valid.',
];
</script>

<template>
  <v-form v-model="valid">
    <v-container>
      <v-row>
        <v-col cols="12" md="4">
          <v-text-field
            v-model="firstname"
            :counter="10"
            :rules="nameRules"
            label="First name"
            required
            variant="solo-filled"
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field
            v-model="lastname"
            :counter="10"
            :rules="nameRules"
            label="Last name"
            required
            variant="solo-filled"
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field
            v-model="email"
            :rules="emailRules"
            label="E-mail"
            required
            variant="solo-filled"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<style>
.v-field__input {
    background-color: inherit;
}
.v-field-label {
    color: inherit;
}
</style>
