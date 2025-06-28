import { createApp } from 'vue/dist/vue.esm-browser.js'

// Header
const HeaderApp = {
    name: 'HeaderApp',
    data() {
        return { open: false }
    },
    template: `
      <header class="bg-[#0074D9] text-white shadow-md" x-data="{ open: false }">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
          <h1 class="text-xl font-bold">💊 Doliprane Framework</h1>

          <!-- Desktop menu -->
          <nav class="hidden md:flex gap-6 items-center">
            <a href="/" class="hover:underline">Accueil</a>
            <a href="/about" class="hover:underline">À propos</a>
            <a href="/docs" class="hover:underline">Docs</a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="hover:underline">GitHub</a>
          </nav>

          <!-- Burger icon -->
          <button @click="open = !open" class="md:hidden focus:outline-none" aria-label="Toggle navigation">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>

        <!-- Mobile menu -->
        <nav class="md:hidden px-6 pb-4 space-y-2" v-show="open">
          <a href="/" class="block hover:underline">Accueil</a>
          <a href="/about" class="block hover:underline">À propos</a>
          <a href="/docs" class="block hover:underline">Docs</a>
          <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="block hover:underline">GitHub</a>
        </nav>
      </header>
    `
}

// Main
const MainApp = {
    name: 'MainApp',
    template: `
    <main class="flex-grow container mx-auto px-4 py-10 bg-[#FFE600]/30">
      <!-- Le contenu PHP est injecté directement ici -->
    </main>
  `
}

// Footer
const FooterApp = {
    name: 'FooterApp',
    template: `
    <footer class="bg-[#0074D9] text-white text-center py-4 mt-8">
      &copy; ${new Date().getFullYear()} Doliprane Framework — Tous droits réservés
    </footer>
  `
}

// Montage de chaque composant individuellement
createApp(HeaderApp).mount('#header')
createApp(FooterApp).mount('#footer')
