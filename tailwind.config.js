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
        'primary': '#546A7B',
        'secondary': '#F4F6F8',
        'accent': '#98C379',
      },
      fontFamily: {
        'sans': ['Mulish', 'sans-serif'],
        'serif': ['Volkhov', 'serif'],
      },
    },
  },
  plugins: [],
} 