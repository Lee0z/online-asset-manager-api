import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { resolve } from 'node:path';

export default defineConfig({
    plugins: [vue()],
    test: {
        environment: 'jsdom',
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
