<template>
  <v-container fill-height fluid>
    <v-row align="center" justify="center" class="pa-16">
      <v-col cols="12" class="text-center">
        <h1 style="font-size: 124px; line-height: 1;">404</h1>
        <p style="font-size: 32px; margin-bottom: 20px;">
          Page not found
        </p>
        <v-btn :to="{ name: 'MainPage' }" variant="flat" color="blue">
          Go to main page
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>


<script></script>
