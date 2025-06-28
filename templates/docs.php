<div id="docs" class="flex min-h-[80vh]">
    <!-- Sidebar -->
    <aside id="sidebar"
           class="transition-all duration-300 ease-in-out bg-white shadow p-4 space-y-4 w-64 overflow-hidden"
           data-expanded="true">
        <button id="toggleSidebar" class="flex items-center gap-2 text-sm font-semibold mb-4 hover:underline">
            <i data-lucide="chevron-left"></i>
            <span class="text">Réduire</span>
        </button>

        <nav class="flex flex-col space-y-3">
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="intro">
                <i data-lucide="book" class="pointer-events-none"></i>
                <span class="text">Introduction</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Introduction</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="routes">
                <i data-lucide="git-branch" class="pointer-events-none"></i>
                <span class="text">Système de routes</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Système de routes</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="controllers">
                <i data-lucide="layers" class="pointer-events-none"></i>
                <span class="text">Contrôleurs</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Contrôleurs</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="models">
                <i data-lucide="layers" class="pointer-events-none"></i>
                <span class="text">Models</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Models</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="views">
                <i data-lucide="eye" class="pointer-events-none"></i>
                <span class="text">Views</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Views</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="eloquent">
                <i data-lucide="database" class="pointer-events-none"></i>
                <span class="text">Eloquent ORM</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Eloquent ORM</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="relations">
                <i data-lucide="link" class="pointer-events-none"></i>
                <span class="text">Relations ORM</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Relations ORM</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="api">
                <i data-lucide="cloud" class="pointer-events-none"></i>
                <span class="text">API REST</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">API REST</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="env">
                <i data-lucide="settings" class="pointer-events-none"></i>
                <span class="text">Fichier .env</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Fichier .env</span>
            </button>
        </nav>
    </aside>

<!--    <aside id="sidebar"
           class="transition-all duration-300 ease-in-out bg-white shadow p-4 space-y-4 w-64 overflow-hidden"
           data-expanded="true">

        <button id="toggleSidebar" class="flex items-center gap-2 text-sm font-semibold mb-4 hover:underline">
            <i data-lucide="chevron-left"></i>
            <span class="text">Réduire</span>
        </button>

        <nav class="flex flex-col space-y-4">
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="intro">
                <i data-lucide="book" class="pointer-events-none"></i>
                <span class="text">Introduction</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Introduction</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="install">
                <i data-lucide="download" class="pointer-events-none"></i>
                <span class="text">Installation</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Introduction</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="router">
                <i data-lucide="git-branch" class="pointer-events-none"></i>
                <span class="text">Router</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Introduction</span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="env">
                <i data-lucide="settings" class="pointer-events-none"></i>
                <span class="text">.env</span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow text-nowrap tooltip">Introduction</span>
            </button>
        </nav>
    </aside> -->

    <!-- Content -->
    <section id="doc-content" class="flex-1 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Documentation</h2>
        <p>Choisissez un sujet dans le menu à gauche.</p>
    </section>
</div>
