import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
  envPrefix: ['BASE_'],

  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.ts'],
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: {
      '@': resolve(__dirname, './resources/js'),
    },
  },
});
