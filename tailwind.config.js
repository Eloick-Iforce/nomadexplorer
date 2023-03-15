/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    container: {
      center: true,
      padding: "2rem",
    },
    extend: {
      colors: {
        mainColor: "#1B2631",
        checkbox: "#2B435A",
        blueTitle: "#318BF0",
      },
      fontFamily: {
        Poppins: ["Poppins", "sans-serif"],
      },
      borderRadius: {
        "4xl": "3.125rem",
      }
    },
  },
  plugins: [],
};
