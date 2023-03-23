/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    extend: {
      colors: {
        mainColor: "#1B2631",
        checkbox: "#2B435A",
        blueTitle: "#318BF0",
        bleu_btn: "#348BEF",
        rouge_btn: "#ED474A",
      },
      fontFamily: {
        Poppins: ["Poppins", "sans-serif"],
      },
      borderRadius: {
        "4xl": "3.125rem",
      },
    },
  },
  plugins: [],
};
