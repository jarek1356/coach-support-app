/** @type {import('tailwindcss').Config} */
module.exports = {
  // UWAGA: Ta sekcja jest kluczowa!
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}", 
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}