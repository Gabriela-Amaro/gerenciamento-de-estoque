/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  darkMode: 'class',
  content: [
    './templates/**/*.html.twig', 
    './assets/**/*.js',
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#ffffff', // Cor clara
          dark: '#1e293b',  // Cor escura
        },
        text: {
          light: '#000000', // Texto claro
          dark: '#f1f5f9',  // Texto escuro
        },
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

