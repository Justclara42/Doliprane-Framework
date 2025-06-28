document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const burger = document.getElementById('burger-btn');
    const mobileNav = document.getElementById('mobile-nav');

    if (burger && mobileNav) {
        burger.addEventListener('click', () => {
            mobileNav.classList.toggle('hidden');
        });
    }

    // Lucide icons
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }

    // Toggle aside sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            const expanded = sidebar.dataset.expanded === 'true';
            sidebar.dataset.expanded = !expanded;
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            sidebar.querySelectorAll('.text').forEach(el => el.classList.toggle('hidden'));
            const icon = toggleBtn.querySelector('i');
            icon.setAttribute('data-lucide', expanded ? 'chevron-right' : 'chevron-left');
            if (window.lucide) window.lucide.replace();
        });
    }

    // Docs dynamic content
    const docButtons = document.querySelectorAll('[data-doc]');
    const contentBox = document.getElementById('doc-content');

    const docs = {
        intro: `
  <h2 class="text-3xl font-bold mb-4 text-blue-800">💊 Doliprane Framework</h2>
  <p class="mb-4 text-lg text-gray-700">
    <strong>Doliprane Framework</strong> est un micro-framework PHP moderne, inspiré de Laravel, Symfony et Alpine, conçu pour être <span class="text-blue-600 font-semibold">simple, léger, rapide</span> et <span class="text-green-600 font-semibold">facile à prendre en main</span>.
  </p>

  <div class="grid md:grid-cols-2 gap-6 text-sm leading-relaxed text-gray-700 bg-yellow-50 border border-yellow-300 p-4 rounded shadow-sm">
    <div>
      <h3 class="text-lg font-semibold text-blue-700 mb-2">🔧 Composants inclus</h3>
      <ul class="list-disc list-inside space-y-1">
        <li><strong>Routeur natif</strong> avec paramètres dynamiques</li>
        <li><strong>Vue & Template</strong> simple avec système de layout</li>
        <li><strong>ORM Eloquent</strong> intégré (relations, migrations...)</li>
        <li><strong>Support API REST</strong> avec routes spécifiques</li>
        <li><strong>TailwindCSS</strong> intégré et compilé localement</li>
        <li><strong>Icônes Lucide</strong> en natif</li>
        <li><strong>Structure MVC</strong> légère</li>
      </ul>
    </div>
    <div>
      <h3 class="text-lg font-semibold text-blue-700 mb-2">🚀 Installation rapide</h3>
      <ol class="list-decimal list-inside space-y-1">
        <li>Cloner le projet :
          <pre class="bg-gray-800 text-green-300 text-xs p-2 rounded mt-1"><code>git clone https://github.com/Justclara42/Doliprane-Framework.git</code></pre>
        </li>
        <li>Se rendre dans le dossier :
          <pre class="bg-gray-800 text-green-300 text-xs p-2 rounded mt-1"><code>cd Doliprane-Framework</code></pre>
        </li>
        <li>Installer les dépendances :
          <pre class="bg-gray-800 text-green-300 text-xs p-2 rounded mt-1"><code>composer install && npm install</code></pre>
        </li>
        <li>Lancer le serveur local :
          <pre class="bg-gray-800 text-green-300 text-xs p-2 rounded mt-1"><code>php -S localhost:8000 -t public</code></pre>
        </li>
      </ol>
    </div>
  </div>

  <div class="mt-6 text-center">
    <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="inline-block text-black px-4 py-2 bg-blue-600 hover:bg-blue-700 font-semibold rounded shadow">
      🔗 Voir le dépôt GitHub
    </a>
  </div>
`,
        routes: `
  <h2 class="text-3xl font-bold mb-4 text-blue-800">🛣️ Système de routing</h2>

  <p class="mb-4 text-lg text-gray-700">
    Le système de routing du <strong>Doliprane Framework</strong> est inspiré de Laravel et Symfony. Il vous permet de définir des URL <span class="text-green-600 font-semibold">simples, lisibles et dynamiques</span>, qui mappent vers vos contrôleurs et méthodes.
  </p>

  <div class="bg-yellow-50 border border-yellow-300 rounded p-4 mb-6 text-sm text-gray-700 shadow">
    <h3 class="text-lg font-semibold text-blue-700 mb-2">📁 Fichier de configuration</h3>
    <p>Les routes sont définies dans le fichier <code class="bg-gray-200 px-2 py-1 rounded">/config/routes.php</code>.</p>
    <p>Chaque route est un tableau de 3 éléments :</p>
    <ul class="list-disc list-inside mt-2 space-y-1">
      <li>La méthode HTTP (GET, POST, etc.)</li>
      <li>Le chemin de l’URL (statique ou dynamique)</li>
      <li>Le contrôleur et sa méthode (<code>Controller@method</code>)</li>
    </ul>
    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-xs overflow-auto">
<code>
return [
  ['GET', '/', 'HomeController@index'],
  ['GET', '/about', 'PageController@about'],
  ['GET', '/post/{id}/{slug}', 'PostController@view'],
  ['POST', '/contact', 'FormController@submit'],
];
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-blue-600 p-4 rounded mb-6 shadow">
    <h3 class="text-lg font-semibold text-blue-700 mb-2">🧠 Routes dynamiques avec paramètres</h3>
    <p>Les routes peuvent contenir des paramètres dynamiques entre accolades : <code class="bg-gray-100 px-1 rounded">{id}</code>, <code class="bg-gray-100 px-1 rounded">{slug}</code>, etc.</p>
    <p>Ces valeurs sont automatiquement extraites et passées en argument à la méthode du contrôleur.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-xs overflow-auto">
<code>
['GET', '/post/{id}/{slug}', 'PostController@view']
</code></pre>

    <p class="mt-2">Dans le contrôleur :</p>
    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-1 text-xs overflow-auto">
<code>
namespace App\Controllers;

class PostController {
    public function view(\$id, \$slug) {
        echo "Post ID : \$id, Slug : \$slug";
    }
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-purple-600 p-4 rounded mb-6 shadow">
    <h3 class="text-lg font-semibold text-purple-700 mb-2">⚙️ Support API REST</h3>
    <p>Le framework prend aussi en charge des routes API (idéal pour AJAX ou clients JS).</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-2 text-xs overflow-auto">
<code>
['GET',    '/api/posts',         'Api\\PostController@index'],
['GET',    '/api/posts/{id}',    'Api\\PostController@show'],
['POST',   '/api/posts',         'Api\\PostController@store'],
['PUT',    '/api/posts/{id}',    'Api\\PostController@update'],
['DELETE', '/api/posts/{id}',    'Api\\PostController@destroy'],
</code></pre>
  </div>

  <div class="mt-6 text-sm text-gray-600">
    📝 Astuce : les routes sont analysées via des expressions régulières. Assurez-vous que les paramètres dans le chemin correspondent aux arguments de la méthode appelée.
  </div>
`,
        controllers: `
  <h2 class="text-3xl font-bold mb-4 text-blue-800">🧩 Les Contrôleurs</h2>

  <p class="text-lg text-gray-700 mb-4">
    Les <strong>contrôleurs</strong> sont les pièces maîtresses du framework <span class="text-yellow-600 font-semibold">Doliprane</span>. Ils reçoivent les requêtes, orchestrent la logique métier, manipulent les modèles et retournent des vues.
  </p>

  <div class="bg-white border-l-4 border-blue-600 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-blue-700 mb-2">📂 Création d'un contrôleur</h3>
    <p>Les contrôleurs se trouvent dans le répertoire <code class="bg-gray-100 px-2 py-1 rounded">app/Controllers/</code>.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class PageController extends Controller {
    public function about() {
        View::layout('base', 'about');
    }
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-green-600 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-green-700 mb-2">🔧 Paramètres dynamiques depuis l’URL</h3>
    <p>Les paramètres capturés depuis les routes (par exemple : <code>{id}</code> ou <code>{slug}</code>) sont automatiquement injectés en arguments.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
class PostController extends Controller {
    public function view($id, $slug) {
        echo "Affichage du post #$id avec le slug : $slug";
    }
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-purple-600 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-purple-700 mb-2">📦 Appel d’un modèle avec Eloquent</h3>
    <p>Grâce à <span class="font-semibold text-purple-600">Eloquent</span>, tu peux facilement interagir avec ta base de données dans tes contrôleurs.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
// Exemple d'utilisation d'un modèle Post
use App\Models\Post;

class PostController extends Controller {
    public function view($id) {
        $post = Post::find($id);

        if (!$post) {
            http_response_code(404);
            echo "Post introuvable";
            return;
        }

        View::layout('base', 'posts/show', ['post' => $post]);
    }
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-indigo-600 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-indigo-700 mb-2">🧠 Exemple complet avec relation Eloquent</h3>
    <p>Utilise les relations Eloquent comme <code>hasMany</code>, <code>belongsTo</code> dans tes modèles, et accède à ces données facilement.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
// Dans le modèle Category.php
public function posts() {
    return $this->hasMany(Post::class);
}

// Dans le contrôleur
use App\Models\Category;

public function show($id) {
    $category = Category::with('posts')->find($id);
    View::layout('base', 'categories/show', ['category' => $category]);
}
</code></pre>
  </div>

  <p class="text-sm text-gray-600 mt-4">
    ℹ️ Astuce : un contrôleur peut retourner une vue, une réponse JSON (pour une API), rediriger, ou même envoyer une erreur 404 personnalisée.
  </p>
`,
        models: `
  <h2 class="text-3xl font-bold mb-6 text-green-700">📦 Modèles (Models)</h2>

  <p class="text-lg text-gray-700 mb-4">
    Les <strong class="text-green-600">modèles</strong> représentent les entités de ta base de données. Grâce à <span class="font-semibold text-purple-700">Eloquent ORM</span>, tu peux manipuler les données facilement en objet.
  </p>

  <div class="bg-white border-l-4 border-green-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-green-700 mb-2">📁 Structure d’un modèle</h3>
    <p>Les modèles se trouvent dans le dossier <code class="bg-gray-100 px-2 py-1 rounded">app/Models/</code> et héritent de <code>Eloquent</code>.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';      // Nom de la table
    protected $primaryKey = 'id';    // Clé primaire
    public $timestamps = true;       // Gère created_at / updated_at

    protected $fillable = ['title', 'content', 'user_id']; // Champs modifiables
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-purple-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-purple-700 mb-2">⚙️ Configuration des champs</h3>
    <p>Utilise <code>$fillable</code> ou <code>$guarded</code> pour protéger les colonnes de ta BDD lors des insertions/modifications.</p>

    <ul class="list-disc list-inside mt-2 text-gray-700">
      <li><code>$fillable</code> autorise uniquement certains champs</li>
      <li><code>$guarded</code> protège certains champs (tout sauf...)</li>
    </ul>
  </div>

  <div class="bg-white border-l-4 border-yellow-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-yellow-600 mb-2">🧬 Relations entre modèles</h3>
    <p>Eloquent permet de créer des relations faciles à manipuler :</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
class Post extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
}

class User extends Model {
    public function posts() {
        return $this->hasMany(Post::class);
    }
}
</code></pre>

    <ul class="list-disc list-inside mt-2 text-gray-700">
      <li><strong>hasOne</strong> – relation 1-1</li>
      <li><strong>hasMany</strong> – relation 1-n</li>
      <li><strong>belongsTo</strong> – relation inverse</li>
      <li><strong>belongsToMany</strong> – relation n-n via pivot</li>
    </ul>
  </div>

  <div class="bg-white border-l-4 border-indigo-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-indigo-700 mb-2">🔍 Requêtes simples avec Eloquent</h3>
    <p>Grâce aux modèles, tu peux effectuer facilement des requêtes :</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
// Trouver un post par ID
$post = Post::find(1);

// Tous les posts d'un user
$user = User::find(2);
$posts = $user->posts;

// Créer un post
Post::create([
  'title' => 'Nouveau post',
  'content' => 'Contenu du post',
  'user_id' => 2
]);
</code></pre>
  </div>

  <p class="text-sm text-gray-600 mt-4">
    📘 Astuce : Utilise <code>with()</code> pour charger les relations automatiquement : <br>
    <code class="bg-gray-100 px-2 py-1 rounded">Post::with('user')->get();</code>
  </p>
`
        ,
        views: `
  <h2 class="text-3xl font-bold mb-6 text-blue-700">🖥️ Vues (Views)</h2>

  <p class="text-lg text-gray-700 mb-4">
    Le système de vues de Doliprane Framework permet de séparer proprement la logique de présentation (HTML/CSS) de la logique métier (controllers).
  </p>

  <div class="bg-white border-l-4 border-blue-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-blue-700 mb-2">📂 Emplacement des fichiers</h3>
    <p>Les fichiers de vues sont stockés dans le dossier <code class="bg-gray-100 px-2 py-1 rounded">/templates</code>.</p>
    <p>Ils sont appelés via la méthode <code>View::layout()</code> ou <code>View::render()</code> dans les contrôleurs.</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
// Exemple d'appel dans un contrôleur
use App\Core\View;

public function about() {
    View::layout('base', 'about'); // base = layout, about = contenu
}
</code></pre>
  </div>

  <div class="bg-white border-l-4 border-indigo-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-indigo-700 mb-2">⚙️ Structure d’un layout (ex: base.php)</h3>
    <p>Le layout est une enveloppe HTML générale avec l’inclusion du fichier de vue dynamique :</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
<!-- Exemple simplifié de base.php -->
&lt;body&gt;
  &lt;header&gt;...&lt;/header&gt;

  &lt;main&gt;
    &lt;?php include \$templateFile; ?&gt;
  &lt;/main&gt;

  &lt;footer&gt;...&lt;/footer&gt;
&lt;/body&gt;
</code></pre>

    <p class="text-sm text-gray-600 mt-2">
      <strong class="text-indigo-600">💡 Astuce :</strong> Tu peux créer plusieurs layouts comme <code>base.php</code>, <code>admin.php</code>, etc.
    </p>
  </div>

  <div class="bg-white border-l-4 border-green-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-green-700 mb-2">🎨 Utilisation de TailwindCSS</h3>
    <p>Tailwind est préinstallé dans Doliprane pour un design rapide et responsive. Tu peux l'utiliser directement dans tes templates PHP :</p>

    <pre class="bg-gray-800 text-green-300 p-3 rounded mt-3 text-sm overflow-auto">
<code>
&lt;div class="bg-white text-gray-800 p-6 rounded shadow-md"&gt;
    &lt;h1 class="text-2xl font-bold text-blue-600"&gt;Bienvenue&lt;/h1&gt;
    &lt;p&gt;Page d'accueil de Doliprane Framework&lt;/p&gt;
&lt;/div&gt;
</code></pre>

    <p class="mt-4">
      🔗 <a href="https://tailwindcss.com/docs" target="_blank" class="text-blue-600 underline font-semibold">Consulter la documentation officielle de TailwindCSS v4</a>
    </p>
  </div>

  <div class="bg-white border-l-4 border-yellow-500 p-4 rounded mb-6 shadow">
    <h3 class="text-xl font-semibold text-yellow-600 mb-2">🧠 Astuces avancées</h3>
    <ul class="list-disc list-inside text-gray-700">
      <li>Créer des composants PHP réutilisables (ex : boutons, cartes, alertes)</li>
      <li>Utiliser <code>isset()</code> et <code>foreach</code> pour afficher dynamiquement des données</li>
      <li>Utiliser des classes conditionnelles en PHP/Tailwind avec <code><?= $condition ? 'class-a' : 'class-b' ?></code></li>
    </ul>
  </div>

  <p class="text-sm text-gray-600 mt-6">
    📁 Tous tes fichiers de vue doivent rester légers : pas de requêtes SQL, pas de logique métier !
  </p>
`
        ,
        api: `
            <h2 class="text-2xl font-bold mb-2">API REST</h2>
            <p>Voici des exemples de routes API :</p>
            <pre><code class="language-php">['GET', '/api/posts', 'Api\\PostController@index']
['GET', '/api/posts/{id}', 'Api\\PostController@show']
['POST', '/api/posts', 'Api\\PostController@store']</code></pre>
        `,
        env: `
            <h2 class="text-2xl font-bold mb-2">Fichier .env</h2>
            <p>Contient vos variables d'environnement :</p>
            <pre><code class="language-text">DB_HOST=localhost
DB_NAME=doliprane
DB_USER=root
DB_PASS=</code></pre>
        `
    };

    docButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-doc');
            if (docs[key]) {
                contentBox.innerHTML = docs[key];
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            }
        });
    });
});