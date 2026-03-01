/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./app/Controllers/**/*.php",
    "./public/**/*.html",
    "./index.php"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          purple: '#5A2C76', 
          dark: '#2C0A4B',  
        }
      }
    },
  },
  plugins: [],
}

