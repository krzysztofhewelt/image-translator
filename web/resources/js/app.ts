import { createApp } from 'vue';
import App from './App.vue';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import axiosSetup from '@/utils/axiosSetup';
import router from '@/router/index.ts';
import { VueQueryPlugin } from '@tanstack/vue-query';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
});

axiosSetup();

const app = createApp(App).use(router).use(VueQueryPlugin).use(vuetify);
app.mount('#app');
