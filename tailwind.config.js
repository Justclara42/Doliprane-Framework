/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.dtf",       // tes fichiers source
    "./storage/cache/views/**/*.php",   // les templates compilés
    "./public/**/*.php",                // les .php éventuels (ex: index.php)
    "./app/**/*.php"                    // si tu utilises des composants générant du HTML
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
