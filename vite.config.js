import { defineConfig, loadEnv } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
// import vue2 from '@vitejs/plugin-vue2';

const env = loadEnv('', process.cwd());

const port = parseInt(env.VITE_PORT ?? '5173');
const production = process.env.NODE_ENV === 'production';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/site.js',

                // Control Panel assets.
                // https://statamic.dev/extending/control-panel#adding-css-and-js-assets
                // 'resources/css/cp.css',
                // 'resources/js/cp.js',
            ],
            refresh: [...refreshPaths, 'content/**', 'users/**'],
        }),
        // vue2(),
    ],
    server: {
        port,
        hmr: {
            host: 'localhost',
        },
    },
    esbuild: {
        drop: production ? ['console', 'debugger'] : [],
    },
});
