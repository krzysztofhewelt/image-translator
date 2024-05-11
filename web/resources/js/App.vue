<template>
  <v-layout>
    <template v-if="$route.name !== 'Login' && $route.name !== 'Register' && $route.name !== 'ErrorNotFound'">
      <TheDrawer></TheDrawer>
    </template>

    <v-main style="width: 100%">
      <PassRoute></PassRoute>
    </v-main>
  </v-layout>
</template>
<script setup lang="ts">
import TheDrawer from '@/components/TheDrawer.vue';
import PassRoute from '@/components/PassRoute.vue';
</script>
