<template>
  <v-text-field hide-details="auto" label="Main input"></v-text-field>

  <v-overlay
    :model-value="test"
    class="align-center justify-center"
    persistent
  >
    <v-progress-circular
      color="primary"
      size="64"
      indeterminate
    ></v-progress-circular>
  </v-overlay>

  <v-container class="mb-6">
    <v-container class="d-flex">
      <v-autocomplete
        label="Source language"
        :items="languages"
        item-title="name"
        item-value="code"
      ></v-autocomplete>

      <v-icon icon="mdi-arrow-right" size="48"></v-icon>
      <v-autocomplete
        label="Target language"
        :items="languages"
        item-title="name"
        item-value="code"
      ></v-autocomplete>
    </v-container>

    <v-row align="start">
      <v-col v-for="n in 15" :key="n" md="4">
        <v-card class="mx-auto" href="https://google.pl">
          <v-img
            height="400px"
            src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
            cover
          ></v-img>

          <v-card-title>
            Top western road trips <v-icon icon="mdi-home" />
          </v-card-title>
        </v-card>
      </v-col>
      <v-responsive width="100%"></v-responsive>
    </v-row>
  </v-container>

  <v-divider></v-divider>
  <v-pagination :length="5"></v-pagination>
</template>
<script setup lang="ts">

import { languages } from '@/utils/languages.ts';

const test = defineModel('test', { default: false });
</script>
