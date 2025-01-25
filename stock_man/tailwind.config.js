/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  darkMode: 'class',
  content: [
    './templates/**/*.html.twig', 
    './assets/**/*.js',
    "./node_modules/flowbite/**/*.js",
    "./assets/**/*.css",
  ],
  theme: {
    extend: {
      colors: {},
    },
  },
  plugins: [],
}

