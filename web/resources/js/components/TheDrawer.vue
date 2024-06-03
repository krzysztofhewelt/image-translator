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
        :to="{ name: 'MainPage' }"
      ></v-list-item>
      <ChangePasswordDialog>
        <template v-slot:activator="{ onClick }">
          <v-list-item
            @click="onClick"
            prepend-icon="mdi-account"
            title="Change password"
            link
          >
          </v-list-item>
        </template>
      </ChangePasswordDialog>
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
import ChangePasswordDialog from '@/components/ChangePasswordDialog.vue';

const logout = () => {
  deleteUserAndToken();
  router.push({ name: 'Login' });
};

const user = getUserData();
</script>
