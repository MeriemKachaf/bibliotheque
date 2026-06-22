# BiblioTEK — Application web de gestion de bibliothèque

Application web développée dans le cadre du **BTS SIO option SLAM** (Session 2026).  
Développée par **Kachaf Meriem** — H3 Hitema, Paris.

---

## Présentation

BiblioTEK est une application web client léger permettant de gérer une bibliothèque en ligne.  
Elle propose deux types d'utilisateurs : les **membres** qui consultent le catalogue et empruntent des livres, et les **administrateurs** qui gèrent le catalogue, les utilisateurs et les emprunts.

**Application déployée en production :** https://bibliotheque-production-6c3c.up.railway.app

---

## Stack technique

| Technologie | Version | Rôle |
|---|---|---|
| PHP | 8.2+ | Langage backend |
| Laravel | 11 | Framework MVC |
| MySQL | 8 | Base de données relationnelle |
| Bootstrap | 5.3 | Framework CSS |
| TailwindCSS | 4 | Utilitaires CSS |
| Vite | 7 | Bundler assets |
| Railway | — | Hébergement production |
| GitHub | — | Versioning + CI/CD |

---

## Fonctionnalités

### Espace membre
- Inscription avec validation forte (min 12 caractères, majuscules, chiffres, symboles)
- Connexion avec protection throttle (5 tentatives max/minute)
- Consultation du catalogue de livres avec recherche et filtres
- Emprunt de livres (max 2 simultanés, vérification stock)
- Retour de livres
- Gestion du profil (nom, email, téléphone)
- Changement de mot de passe avec historique des 3 derniers
- Suppression de compte (droit à l'effacement — RGPD)
- Export PDF des emprunts

### Espace administrateur
- Gestion complète des livres (CRUD + upload photo)
- Gestion des catégories
- Gestion des utilisateurs (toggle rôle admin/membre)
- Consultation des journaux d'activité (RGPD)
- Vue globale des emprunts avec recherche et filtres
- Dashboard avec statistiques et graphiques

---

## Sécurité

| Mesure | Détail |
|---|---|
| Bcrypt 12 rounds | Hashage des mots de passe |
| CSRF | Token secret sur tous les formulaires |
| Throttle | 5 tentatives de connexion max/minute |
| Historique mots de passe | Interdiction de réutiliser les 3 derniers |
| Regénération de session | Protection contre la fixation de session |
| En-têtes HTTP | X-Frame-Options, X-XSS-Protection, X-Content-Type-Options |
| Middleware admin/membre | Contrôle d'accès par rôle |
| Message d'erreur vague | Ne révèle pas si l'email existe |
| Rôle forcé à membre | Impossible de s'inscrire en tant qu'admin |
| Protection dernier admin | Impossible de rétrograder le dernier administrateur |

---

## Architecture MVC

```
routes/web.php          → Reçoit les requêtes HTTP et les distribue
      ↓
Middlewares             → Filtrent les accès (auth, admin, membre, sécurité)
      ↓
Controllers             → Traitent la logique métier
      ↓
Models (Eloquent)       → Communiquent avec la base de données MySQL
      ↓
Views (Blade)           → Affichent les pages HTML
```

---

## Modélisation UML

### Diagramme de classes

```
+------------------+          +-------------------+
|      User        |          |      Livre        |
+------------------+          +-------------------+
| id               |          | id                |
| name             |          | titre             |
| email            |          | auteur            |
| password         |          | isbn              |
| role             |          | editeur           |
| telephone        |          | annee_publication |
+------------------+          | description       |
| isAdmin(): bool  |          | quantite          |
| emprunts()       |          | photo             |
+--------+---------+          | categorie_id      |
         |                    +-------------------+
         | 1                  | isDisponible()    |
         |                    | photo_url         |
         | *                  +--------+----------+
+--------+---------+                   |
|     Emprunt      |          *        | 1
+------------------+----------+--------+
| id               |
| user_id          |          +-------------------+
| livre_id         |          |    Categorie      |
| date_emprunt     |          +-------------------+
| date_retour_     |          | id                |
|   prevue         |          | nom               |
| date_retour_     |          | description       |
|   effective      |          +-------------------+
| statut           |          | livres()          |
+------------------+          +-------------------+
| statut_label     |
| statut_badge     |
+------------------+

+----------------------+       +------------------------+
|    ActivityLog       |       |    PasswordHistory     |
+----------------------+       +------------------------+
| id                   |       | id                     |
| user_id              |       | user_id                |
| action               |       | password               |
| description          |       | created_at             |
| ip                   |       +------------------------+
| niveau               |
| created_at           |
+----------------------+
| record() [static]    |
+----------------------+
```

### Relations entre les tables

```
User        1 ──── * Emprunt
User        1 ──── * ActivityLog
User        1 ──── * PasswordHistory
Livre       1 ──── * Emprunt
Categorie   1 ──── * Livre
```

### Diagramme de cas d'utilisation

```
                    ┌─────────────────────────────────┐
                    │         Application              │
                    │                                  │
[Visiteur] ─────────┤── S'inscrire                    │
                    │── Se connecter                   │
                    │                                  │
[Membre] ───────────┤── Consulter le catalogue        │
                    │── Emprunter un livre             │
                    │── Rendre un livre                │
                    │── Gérer son profil               │
                    │── Changer son mot de passe       │
                    │── Supprimer son compte           │
                    │── Exporter ses emprunts en PDF   │
                    │                                  │
[Administrateur] ───┤── Gérer les livres (CRUD)       │
  (hérite Membre)   │── Gérer les catégories          │
                    │── Gérer les utilisateurs         │
                    │── Consulter les journaux RGPD    │
                    │── Voir tous les emprunts         │
                    └─────────────────────────────────┘
```

### Diagramme de séquence — Emprunt d'un livre

```
Membre        routes/web.php    MembreMiddleware    EmpruntController    BDD
  │                │                  │                   │               │
  │─POST /emprunts─►│                  │                   │               │
  │                │──vérif membre────►│                   │               │
  │                │                  │──OK────────────────►│               │
  │                │                  │                   │──max 2 emprunts?─►│
  │                │                  │                   │◄─────────────────│
  │                │                  │                   │──déjà emprunté?──►│
  │                │                  │                   │◄─────────────────│
  │                │                  │                   │──stock > 0?──────►│
  │                │                  │                   │◄─────────────────│
  │                │                  │                   │──Emprunt::create──►│
  │                │                  │                   │──decrement(qty)───►│
  │◄────────────────────────────────────redirect─────────│               │
```

---

## Structure du projet

```
bibliotheque/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        ← connexion, inscription, déconnexion
│   │   │   ├── DashboardController.php   ← tableau de bord
│   │   │   ├── LivreController.php       ← CRUD livres
│   │   │   ├── EmpruntController.php     ← emprunts et retours
│   │   │   ├── ProfileController.php     ← profil utilisateur
│   │   │   ├── UserController.php        ← gestion utilisateurs (admin)
│   │   │   ├── CategorieController.php   ← gestion catégories (admin)
│   │   │   └── ActivityLogController.php ← journaux RGPD (admin)
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php        ← filtre accès admin
│   │       ├── MembreMiddleware.php       ← filtre accès membre
│   │       └── SecurityHeadersMiddleware.php ← en-têtes HTTP sécurité
│   ├── Models/
│   │   ├── User.php
│   │   ├── Livre.php
│   │   ├── Emprunt.php
│   │   ├── Categorie.php
│   │   ├── ActivityLog.php
│   │   └── PasswordHistory.php
│   └── Rules/
│       └── NotInPasswordHistory.php      ← règle validation historique mdp
├── database/
│   ├── migrations/                       ← structure des tables
│   └── seeders/                          ← données de test
├── resources/views/                      ← pages HTML Blade
├── routes/web.php                        ← toutes les URLs
├── tests/Unit/                           ← tests unitaires
├── railway.toml                          ← config déploiement Railway
└── nixpacks.toml                         ← config build Railway
```

---

## Installation en local

**Prérequis :** PHP 8.2+, Composer, MySQL, Node.js 20+, XAMPP

```bash
# 1. Cloner le projet
git clone https://github.com/MeriemKachaf/bibliotheque.git
cd bibliotheque

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer le .env
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_DATABASE=bibliotheque
DB_USERNAME=root
DB_PASSWORD=

# 5. Créer les tables et insérer les données de test
php artisan migrate:fresh --seed

# 6. Installer les dépendances JS et compiler les assets
npm install && npm run build

# 7. Lancer le serveur
php artisan serve
```

Accès : http://localhost:8000

---

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | admin@bibliotheque.fr | Admin@123456 |
| Membre | (créer via /register) | — |

---

## Tests unitaires

```bash
php artisan test
```

3 fichiers de tests :
- `UserTest.php` → teste `isAdmin()`
- `LivreTest.php` → teste `isDisponible()` et `photo_url`
- `EmpruntTest.php` → teste les badges et labels de statut

---

## Déploiement (Railway)

L'application est déployée sur Railway avec :
- **CI/CD** : chaque `git push` sur `main` redéploie automatiquement
- **Base de données** : MySQL géré par Railway
- **HTTPS** : certificat automatique
- **Build** : PHP uniquement via nixpacks (assets pré-compilés en local)

---

## Auteur

**Kachaf Meriem** — BTS SIO option SLAM — H3 Hitema Paris — Session 2026
