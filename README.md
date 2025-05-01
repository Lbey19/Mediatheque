# Projet Médiathèque (Laravel)

Ce projet est une application web de gestion de médiathèque développée avec le framework Laravel. Elle permet aux utilisateurs de consulter les médias disponibles (livres, CDs), de les emprunter (sous conditions), et aux administrateurs de gérer le catalogue, les utilisateurs et les emprunts.

## Installation pour le professeur

Suivez ces étapes pour installer et lancer le projet sur votre machine locale (WAMP, MAMP, LAMP, etc.).

1.  **Cloner le dépôt :**
    *   Ouvrez votre terminal ou Git Bash.
    *   Naviguez vers le dossier où vous souhaitez placer le projet (par exemple, votre dossier `www` ou `htdocs`).
    *   Exécutez la commande :
        ```bash
        git clone https://github.com/Lbey19/Mediatheque.git
        ```
    *   Entrez dans le dossier du projet :
        ```bash
        cd Mediatheque
        ```

2.  **Installer les dépendances PHP :**
    *   Assurez-vous d'avoir [Composer](https://getcomposer.org/) installé.
    *   Exécutez la commande :
        ```bash
        composer install
        ```
        *(Cela peut prendre quelques minutes pour télécharger toutes les bibliothèques nécessaires).*

3.  **Configuration de l'environnement :**
    *   Ce dépôt inclut un fichier `.env.example` qui sert de modèle. Copiez-le pour créer votre propre fichier de configuration :
        ```bash
        cp .env.example .env
        ```
        *(Sous Windows, si `cp` ne fonctionne pas, vous pouvez faire `copy .env.example .env` ou copier/coller manuellement le fichier et le renommer en `.env`).*
    *   Générez la clé d'application unique pour sécuriser votre installation :
        ```bash
        php artisan key:generate
        ```
    *   Ouvrez le fichier `.env` que vous venez de créer avec un éditeur de texte.
    *   **Vérifiez et modifiez** les lignes suivantes pour correspondre à votre configuration de base de données MySQL locale :
        ```dotenv
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1           # Généralement correct pour une installation locale
        DB_PORT=3306               # Port MySQL par défaut
        DB_DATABASE=maison_du_livre  # IMPORTANT : Nom de la base de données que vous allez créer/utiliser
        DB_USERNAME=root             # IMPORTANT : Votre nom d'utilisateur MySQL (souvent 'root')
        DB_PASSWORD=                 # IMPORTANT : Votre mot de passe MySQL (laisser vide si 'root' n'a pas de mot de passe)
        ```
    *   Sauvegardez les modifications du fichier `.env`.

4.  **Base de données :**
    *   Ouvrez votre outil de gestion de base de données (par exemple, phpMyAdmin via `http://localhost/phpmyadmin/`).
    *   Créez une **nouvelle base de données vide**. Nommez-la exactement comme vous l'avez indiqué dans la variable `DB_DATABASE` du fichier `.env` (par exemple, `maison_du_livre`). Assurez-vous d'utiliser un encodage comme `utf8mb4_unicode_ci`.
    *   Sélectionnez cette nouvelle base de données vide.
    *   Allez dans l'onglet **"Importer"**.
    *   Cliquez sur "Choisir un fichier" (ou équivalent) et sélectionnez le fichier `maison_du_livre.sql` qui se trouve à la racine de ce dépôt cloné.
    *   Cliquez sur "Exécuter" (ou "Go") pour importer la structure et les données dans votre base de données.

5.  **Lien de stockage :**
    *   Pour que les images des livres et CDs s'affichent correctement, vous devez créer un lien symbolique entre `public/storage` et `storage/app/public`. Exécutez la commande :
        ```bash
        php artisan storage:link
        ```
    *   *Note : Sous Windows, cette commande peut nécessiter des droits d'administrateur ou des ajustements selon votre environnement. Si les images n'apparaissent pas, vérifiez que le lien a bien été créé dans le dossier `public` et qu'il pointe vers `storage/app/public`.*

6.  **Installer les dépendances Node.js :**
    *   Si vous souhaitez modifier les assets front-end (CSS, JS) ou si le projet utilise Vite/Mix pour la compilation, vous aurez besoin de Node.js et npm.
    *   Installez les dépendances Node :
        ```bash
        npm install
        ```
    *   Compilez les assets (pour le développement) :
        ```bash
        npm run dev
        ```
        *(Laissez cette commande tourner dans un terminal séparé pendant que vous développez, ou exécutez `npm run build` pour une compilation unique).*

7.  **Lancer le serveur de développement :**
    *   Vous pouvez maintenant lancer le serveur de développement intégré de Laravel :
        ```bash
        php artisan serve
        ```
    *   Ouvrez votre navigateur et allez à l'adresse indiquée (généralement `http://127.0.0.1:8000` ou `http://localhost:8000`).

L'application Médiathèque devrait maintenant être fonctionnelle sur votre machine.

## Scénarios de Test / Comptes Utilisateurs

Pour tester différentes fonctionnalités et restrictions, vous pouvez utiliser les comptes suivants (créés via les seeders ou présents dans l'export SQL) :


*   **Tester la limite d'emprunts :**
    *   Créez un nouveau compte utilisateur via le formulaire d'inscription standard.
    *   Essayez d'emprunter plus de 3 médias (livres/CDs). Le système devrait vous empêcher de dépasser cette limite.

*   **Compte bloqué (Adhésion expirée) :**
    *   **Email :** `mohamed.benadrouche@gmail.com`
    *   **Mot de passe :** `password`
    *   **Scénario :** Essayez de réserver/emprunter un livre ou un CD. L'action devrait être bloquée en raison de l'expiration de l'adhésion.

*   **Compte avec emprunt en retard :**
    *   **Email :** `Clara@gmail.com`
    *   **Mot de passe :** `password`
    *   **Scénario :** Essayez de réserver/emprunter un nouveau livre ou CD. L'action devrait être bloquée en raison d'un emprunt précédent non retourné à temps.

*   **Compte Administrateur :**
    *   **Email :** `admin@example.com`
    *   **Mot de passe :** `password`
    *   **Scénario :** Connectez-vous avec ce compte. Sur le tableau de bord principal (Dashboard) qui s'affiche après connexion, vous devriez trouver un lien pour accéder au **Panneau d'Administration**. Cliquez sur ce lien pour gérer les utilisateurs, les livres, les CDs, les emprunts, etc.
    *   **Note importante sur l'admin (Filament) :** Après avoir utilisé une action de création, modification ou suppression dans une ressource du panneau d'administration, il peut être nécessaire de cliquer sur un des liens du menu latéral (par exemple "Dashboard" ou la liste de la ressource) pour quitter la page de l'action et voir la liste mise à jour.



---

*(Les sections suivantes sont les sections standard du README de Laravel)*

## About Laravel

Laravel is a web application framework with expressive, elegant syntax... *(gardez le reste si vous le souhaitez)*

## Learning Laravel

...

## Laravel Sponsors

...

## Contributing

...

## Code of Conduct

...

## Security Vulnerabilities

...

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).