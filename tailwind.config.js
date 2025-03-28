/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./components/**/*.php",
    "./includes/**/*.php",
    "./assets/js/**/*.ts",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#E58356',
        'secondary': '#E9EFD6',
        'dark-olive': '#4D5D2A',
        'olive-light': '#F3F6E9',
        'olive-dark': '#525B31',
      },
      fontFamily: {
        'sans': ['Mulish', 'sans-serif'],
        'serif': ['Volkhov', 'serif'],
      },
      backgroundColor: {
        'hero': '#F3F6E9',
      }
    },
  },
  plugins: [],
} 