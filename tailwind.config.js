/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.jsx", // Thêm dòng này nếu bạn sử dụng JSX
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

