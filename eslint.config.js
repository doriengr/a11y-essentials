import { includeIgnoreFile } from '@eslint/compat';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'eslint/config';
import js from '@eslint/js';
import globals from 'globals';

const gitignorePath = fileURLToPath(new URL('.gitignore', import.meta.url));

export default defineConfig([
    js.configs.recommended,
    includeIgnoreFile(gitignorePath),
    {
        languageOptions: {
            sourceType: 'module',
            globals: {
                ...globals.browser,
                ...globals.node,
            },
        },
        rules: {
            strict: 'error',
            camelcase: 'warn',
            eqeqeq: 'warn',
            'no-use-before-define': 'error',
            'new-cap': 'error',
            'no-eq-null': 'off',
        },
    },
]);
