<template>
  <div>
    <v-img class="mx-auto my-6" max-width="400" :src="logo" alt="Logo"></v-img>

    <form @submit.prevent="handleLogin">
      <v-card
        class="mx-auto pa-12 pb-8"
        elevation="8"
        max-width="500px"
        rounded="lg"
      >
        <div class="text-subtitle-1 text-medium-emphasis">Account</div>

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
          :error-messages="password.errorMessage.value"
          @click:append-inner="visible = !visible"
        ></v-text-field>

        <v-btn
          type="submit"
          class="mb-8 font-weight-bold"
          color="blue"
          size="large"
          variant="tonal"
          block
        >
          Log In
        </v-btn>

        <v-card-text class="text-center">
          <router-link
            class="text-blue text-decoration-none"
            :to="{ name: 'Register' }"
          >
            Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
          </router-link>
        </v-card-text>
      </v-card>
    </form>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import logo from '@/assets/logo.png';
import { useLoginMutation } from '@/api/authenticate/hooks.ts';
import { loginSchema } from '@/consts/validationSchemas.ts';
import { useField, useForm } from 'vee-validate';
import { LoginForm } from '@/types/forms/Auth.ts';
import { saveToken, saveUser } from '@/utils/authentication.ts';
import router from '@/router';

// TODO: main page (list of translations, change self password, drawer with user info, search bar, upload new)
// TODO: show translation

const { handleSubmit } = useForm<LoginForm>({
  validationSchema: loginSchema,
});

const { mutate } = useLoginMutation((data) => {
  saveToken(data.token);
  saveUser({
    id: data.user.id,
    username: data.user.username,
    email: data.user.email,
  });
  router.push({ name: 'Home' });
});

const email = useField('email');
const password = useField('password');
const visible = ref(false);

const handleLogin = handleSubmit((values) => {
  mutate({ email: values.email, password: values.password });
});
</script>
