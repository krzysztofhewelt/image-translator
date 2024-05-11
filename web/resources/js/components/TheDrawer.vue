<template>
  <v-navigation-drawer expand-on-hover rail>
    <v-list>
      <v-list-item
        prepend-icon="mdi-account-circle"
        :subtitle="user.email"
        :title="user.username"
      ></v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item
        prepend-icon="mdi-home"
        title="Home"
        :to="{ name: 'Home' }"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-account"
        title="Shared with me"
        value="shared"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-logout"
        title="Logout"
        link
        @click="logout"
      ></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>
<script setup lang="ts">
import { deleteUserAndToken } from '@/utils/authentication.ts';
import { getUserData } from '@/utils/authentication.ts';
import router from '@/router';

const logout = () => {
  deleteUserAndToken();
  router.push({ name: 'Login'})
}

const user = getUserData();
</script>
