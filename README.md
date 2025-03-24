# 🚀 Guide d'installation - Digico (Laravel API Multi-Tenant + Passport)

Bienvenue dans le guide d’installation du projet **Digico**. Cette API Laravel utilise la gestion de tenants (multi-entreprises) avec le package `stancl/tenancy` et l’authentification via `laravel/passport`.

---

## 📦 Étape 1 : Installer les dépendances

```bash
composer install
```

---

## ⚙️ Étape 2 : Créer le fichier `.env`

Créer un fichier `.env` à partir de l’exemple :

```bash
cp .env.example .env
```

> 🛠️ N'oublie pas de configurer les variables dans `.env` (base de données, clés Passport, URL, etc.)

---

## 🧱 Étape 3 : Lancer les migrations

Cette commande initialise la base de données :

```bash
php artisan migrate
```

> ✅ Laravel te proposera de créer la base si elle n’existe pas.

---

## 🏢 Étape 4 : Créer un tenant

```bash
php artisan tenant:create nom_du_tenant
```

> Exemple : `php artisan tenant:create module`

---

## 👤 Étape 5 : Créer un utilisateur pour un tenant

```bash
php artisan user:create email motdepasse slug_du_tenant
```

> Exemple :  
> `php artisan user:create jillian.rezette@gmail.com Module2025@ module`

---

## 🔐 Étape 6 : Générer les clés de Laravel Passport

```bash
php artisan passport:keys
```

---

## 🔑 Étape 7 : Créer un client Password Grant

```bash
php artisan passport:client --password
```

> Exemple de retour :
```
INFO  Password grant client created successfully.

Client ID ...................................................................................................................................... 1  
Client secret ........................................................................................... GBYRGNnIZddJ045MNpuwSpzP6NB5UVlHLIMMP22K
```

📝 Copie ces identifiants dans le fichier `.env` :

```env
PASSPORT_PASSWORD_GRANT_CLIENT_ID=1
PASSPORT_PASSWORD_GRANT_CLIENT_SECRET=GBYRGNnIZddJ045MNpuwSpzP6NB5UVlHLIMMP22K
```

---

## ▶️ Étape 8 : Lancer le serveur local

```bash
php artisan serve
```

> Accès à l’API : [http://localhost:8000](http://localhost:8000)

---
## ▶️ Note

Quelque étape pour ajouter un module dans l'api

Dans providers.php, ajouter le service provider du module
```bash
\Diji\module\ModuleServiceProvider::class
```

Dans le composer.json, ajouter le module dans autoload
```bash
"autoload": {
        "psr-4": {
            "App\\": "app/",
            "diji\\nom_module\\": "modules/nom_module"
            
        }
    }
```
---

## ✅ Résumé rapide des commandes

| Action                           | Commande                                                        |
|----------------------------------|------------------------------------------------------------------|
| Installer les dépendances        | `composer install`                                               |
| Créer `.env`                     | `cp .env.example .env`                                           |
| Lancer les migrations            | `php artisan migrate`                                            |
| Créer un tenant                  | `php artisan tenant:create nom_du_tenant`                        |
| Créer un utilisateur             | `php artisan user:create email motdepasse slug_du_tenant`        |
| Générer les clés Passport        | `php artisan passport:keys`                                      |
| Créer un client OAuth2           | `php artisan passport:client --password`                         |
| Lancer le serveur local          | `php artisan serve`                                              |

---

## 🎉 Tu es prêt à utiliser l'API Digico !

Utilise un outil comme Postman, Insomnia ou Thunder Client pour tester les endpoints sécurisés.
