/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/upload.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
