import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/app': 'http://localhost:8000',  // Asegúrate de que apunta a tu servidor Laravel
    }
  }
});
