// /** @type {import('tailwindcss').Config} */
export default {
  content: ["./resources/**/**/**/*.blade.php",
            "./resources/**/*.css",
            "./resources/**/*.js",
            "./resources/**/**.vue",
        ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    ],
}

