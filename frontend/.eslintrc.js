module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'plugin:vue/essential',
    'airbnb-base',
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  rules: {
    'max-len': 'off',
    'import/extensions': 'off',
    'import/no-unresolved': 'off',
    'no-restricted-globals': 'off',
    'no-param-reassign': 'off',
    'no-console': 'off',
    'no-return-assign': 'off',
    'no-bitwise': 'off',
    radix: 'off',
    'no-plusplus': 'off',
    'vue/no-use-v-if-with-v-for': 'off',
    'no-loop-func': 'off',
    'array-callback-return': 'off',
    'no-shadow': 'off',
    'consistent-return': 'off',
    'import/no-extraneous-dependencies': 'off',
  },
};
