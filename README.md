# 💊 Doliprane Framework

> **Framework PHP léger et moderne**, conçu pour apprendre, prototyper rapidement ou créer des projets propres et maintenables, en s’inspirant de Laravel et Symfony.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.0-blue?logo=tailwindcss)
![VanillaJS](https://img.shields.io/badge/JavaScript-Vanilla-yellow?logo=javascript)
![Eloquent](https://img.shields.io/badge/ORM-Eloquent-orange)
![Router](https://img.shields.io/badge/Router-Custom-lightgrey)
![Template Engine](https://img.shields.io/badge/Templates-Engine-success)
![i18n](https://img.shields.io/badge/i18n-Multilang-green)

---

## 🚀 Installation rapide

1. **Clone le projet**

```bash
git clone https://github.com/Justclara42/Doliprane-Framework.git
cd Doliprane-Framework
```

2. **Installe les dépendances PHP**

```bash
composer install
```

3. **Installe les dépendances CSS/JS**

```bash
npm install
```

4. **Compile les assets**

```bash
npm run dev
```

5. **Configure l’environnement**

Copie `.env.example` → `.env` puis adapte les paramètres :

```env
APP_NAME=Doliprane
APP_ENV=local
DB_HOST=localhost
DB_NAME=doliprane
DB_USER=root
DB_PASS=
```

6. **Lance le serveur local**

```bash
php -S localhost:8000 -t public
```

👉 Accède au site : [http://localhost:8000](http://localhost:8000)

---

## 🧩 Composants inclus

| Composant                | Description                                              |
| ------------------------ | -------------------------------------------------------- |
| ✅ **Routeur**            | Routing type Laravel (`/post/1/slug`)                    |
| ✅ **Controllers**        | Avec injection de paramètres automatiques                |
| ✅ **Views PHP**          | Templates avec `View::layout()`                          |
| ✅ **Moteur de template** | Fichiers `.dtf` compilés dans `/storage/cache/views/`    |
| ✅ **Traductions i18n**   | Multi-langues JSON automatiques (`fr_FR`, `en_US`, etc.) |
| ✅ **Eloquent ORM**       | Utilise Laravel Eloquent pour les modèles                |
| ✅ **Tailwind CSS**       | Intégré en local, compilé avec `npm run dev`             |
| ✅ **Vanilla JS**         | Pas de dépendances JS lourdes                            |
| ✅ **Menu responsive**    | Menu burger en JS natif                                  |
| ✅ **Icônes Lucide**      | Icônes SVG intégrés sans CDN                             |
| ✅ **Docs embarquée**     | Documentation dynamique incluse dans `/docs`             |

---

## 📁 Arborescence

```
Doliprane-Framework/
├── app/
│   ├── Controllers/
│   ├── Core/
│   │   ├── App.php
│   │   ├── Router.php
│   │   ├── View.php
│   │   ├── Lang.php           ← Traduction multilangue
│   │   ├── TemplateEngine.php ← Moteur de templates .dtf
│   └── Models/
│
├── bootstrap/
│   └── helpers.php           ← Fonctions globales
│
├── config/
│   ├── env.php
│   └── routes.php
│
├── public/
│   ├── index.php             ← Point d'entrée unique
│   └── assets/               ← CSS + JS compilés
│
├── resources/
│   ├── css/
│   ├── js/
│   └── lang/                 ← Fichiers JSON de traduction
│
├── storage/
│   └── cache/views/          ← Vues compilées depuis .dtf
│
├── templates/
│   ├── layouts/
│   │   ├── base.dtf
│   │   └── ...
│   └── home.dtf etc.
│
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 📘 Documentation

La documentation est intégrée dans le site :
👉 [http://localhost:8000/docs](http://localhost:8000/docs)

Elle couvre :

* Introduction au framework
* Système de routes
* Contrôleurs
* Modèles & ORM
* Vues & Tailwind
* Template engine
* Système de traduction i18n
* APIs REST
* ...et plus à venir

---

## 🧑‍💻 Contribuer

> N'hésitez pas à proposer des PR, créer des issues ou forker le projet.

---

## 🔗 Liens utiles

* 🌐 Démo locale : [http://localhost:8000](http://localhost:8000)
* 📚 Docs TailwindCSS : [https://tailwindcss.com/docs](https://tailwindcss.com/docs)
* 💾 Dépôt GitHub : [Justclara42/Doliprane-Framework](https://github.com/Justclara42/Doliprane-Framework)

---

## 🧠 Licence

Ce projet est open-source sous licence MIT.

---

> Créé avec ❤️ par **Clara Holderbaum**
