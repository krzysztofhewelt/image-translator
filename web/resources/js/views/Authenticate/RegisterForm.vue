<template>
  <div>
    <v-img class="mx-auto my-6" max-width="400" :src="logo" alt="Logo"></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <div class="text-subtitle-1 text-medium-emphasis">Username</div>

      <v-text-field
        density="compact"
        placeholder="Enter username"
        prepend-inner-icon="mdi-account-outline"
        variant="outlined"
      ></v-text-field>

      <div class="text-subtitle-1 text-medium-emphasis">Email</div>

      <v-text-field
        density="compact"
        placeholder="Enter email"
        prepend-inner-icon="mdi-email-outline"
        variant="outlined"
      ></v-text-field>

      <div
        class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
      >
        Password
      </div>

      <v-text-field
        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'"
        density="compact"
        placeholder="Enter your password"
        prepend-inner-icon="mdi-lock-outline"
        variant="outlined"
        @click:append-inner="visible = !visible"
      ></v-text-field>

      <v-btn
        class="mb-8 font-weight-bold"
        color="blue"
        size="large"
        variant="tonal"
        block
      >
        Register
      </v-btn>

      <v-card-text class="text-center">
        Already registered?
        <router-link
          class="text-blue text-decoration-none"
          :to="{ name: 'Login' }"
        >
          Log-in now. <v-icon icon="mdi-chevron-right"></v-icon>
        </router-link>
      </v-card-text>
    </v-card>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import logo from '@/assets/logo.png';

const visible = ref(false);
</script>
