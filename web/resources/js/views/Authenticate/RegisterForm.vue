<template>
  <div>
    <v-img class="mx-auto my-6" max-width="400" :src="logo" alt="Logo"></v-img>

    <form @submit.prevent="handleRegister">
      <v-card
        class="mx-auto pa-12 pb-8"
        elevation="8"
        max-width="500"
        rounded="lg"
      >
        <div class="text-subtitle-1 text-medium-emphasis">Username</div>

        <v-text-field
          class="mb-2"
          v-model="username.value.value"
          density="compact"
          placeholder="Enter username"
          prepend-inner-icon="mdi-account-outline"
          variant="outlined"
          :error-messages="username.errorMessage.value"
        ></v-text-field>

        <div class="text-subtitle-1 text-medium-emphasis">Email</div>

        <v-text-field
          class="mb-2"
          v-model="email.value.value"
          density="compact"
          placeholder="Enter email"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
          :error-messages="email.errorMessage.value"
        ></v-text-field>

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Password
        </div>

        <v-text-field
          class="mb-2"
          v-model="password.value.value"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
          :error-messages="password.errorMessage.value"
        ></v-text-field>

        <v-btn
          type="submit"
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
    </form>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import logo from '@/assets/logo.png';
import { useField, useForm } from 'vee-validate';
import { RegisterForm } from '@/types/forms/Auth.ts';
import { registerSchema } from '@/consts/validationSchemas.ts';
import { useRegisterMutation } from '@/api/authenticate/hooks.ts';
import { saveToken, saveUser } from '@/utils/authentication.ts';
import router from '@/router';

const { handleSubmit } = useForm<RegisterForm>({
  validationSchema: registerSchema,
});

const { mutate } = useRegisterMutation((data) => {
  saveToken(data.token);
  saveUser({
    id: data.user.id,
    username: data.user.username,
    email: data.user.email,
  });
  router.push({ name: 'Home' });
});

const username = useField('username');
const email = useField('email');
const password = useField('password');
const visible = ref(false);

const handleRegister = handleSubmit((values) => {
  mutate({
    username: values.username,
    email: values.email,
    password: values.password,
  });
});
</script>
