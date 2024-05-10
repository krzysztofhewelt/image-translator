<template>
  <v-layout>
    <template v-if="$route.name !== 'Login' && $route.name !== 'Register' && $route.name !== 'ErrorNotFound'">
      <TheDrawer></TheDrawer>
    </template>

    <v-main style="width: 100%">
      <PassRoute></PassRoute>
      <!--        <v-text-field-->
      <!--          hide-details="auto"-->
      <!--          label="Main input"-->
      <!--        ></v-text-field>-->

      <!--        <v-container class="mb-6">-->
      <!--          <v-container class="d-flex">-->
      <!--            <v-autocomplete-->
      <!--              label="Source language"-->
      <!--              v-model="test"-->
      <!--              :items="languages"-->
      <!--              item-title="name"-->
      <!--              item-value="code"-->
      <!--            ></v-autocomplete>-->

      <!--            <v-icon icon="mdi-arrow-right" size="48"></v-icon>-->
      <!--            <v-autocomplete-->
      <!--              label="Target language"-->
      <!--              v-model="test"-->
      <!--              :items="languages"-->
      <!--              item-title="name"-->
      <!--              item-value="code"-->
      <!--            ></v-autocomplete>-->
      <!--          </v-container>-->

      <!--          <v-row align="start">-->
      <!--            <v-col v-for="n in 15" :key="n" md="4">-->
      <!--              <v-card class="mx-auto" href="https://google.pl">-->
      <!--                <v-img-->
      <!--                  height="400px"-->
      <!--                  src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"-->
      <!--                  cover-->
      <!--                ></v-img>-->

      <!--                <v-card-title>-->
      <!--                  Top western road trips <v-icon icon="mdi-home" />-->
      <!--                </v-card-title>-->
      <!--              </v-card>-->
      <!--            </v-col>-->
      <!--            <v-responsive width="100%"></v-responsive>-->
      <!--          </v-row>-->
      <!--        </v-container>-->

      <!--        <v-divider></v-divider>-->
      <!--        <v-pagination :length="5"></v-pagination>-->
    </v-main>
  </v-layout>
</template>
<script setup>
import { languages } from '@/utils/languages';
import TheDrawer from '@/components/TheDrawer.vue';
import PassRoute from '@/components/PassRoute.vue';

const test = defineModel('test', { default: 'auto' });
</script>
