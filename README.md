# Plugin WordPress Demi-Sel

Plugin pour intégrer dans Elementor un composant Vue JS avec une grille de produits.

## Description

Bientôt.

## Installation

### Installation avec Composer

#### Prérequis :

* [Local](https://localwp.com)
* [Composer](https://getcomposer.org/) (présent avec Local).

__Note__: Pour toutes les commandes `bash` par la suite, il est important d'utiliser le Shell de Local (Bouton "Site shell" sous le nom du site): en faisant ainsi, Composer est déjà installé et PHP se trouve dans le PATH, ce qui n'est pas forcément le cas autrement.

#### Étapes :

1.  **Accédez au répertoire des plugins de votre installation WordPress :**
    ```bash
    cd /path/to/your/wordpress/wp-content/plugins/
    ```
2.  **Clonez le dépôt Git du plugin :**
    ```bash
    git clone https://github.com/AlainGourves/omer-plugin-wp demi-sel-plugin
    ```
3.  **Naviguez dans le répertoire du plugin :**
    ```bash
    cd demi-sel-plugin
    ```
4.  **Installez les dépendances Composer (en mode production) :**
    ```bash
    composer install --no-dev --optimize-autoloader
    ```
    Cette commande va télécharger toutes les bibliothèques tierces nécessaires et optimiser l'autoloader.
5.  **Activez le plugin**
6.

## Développement

### Prérequis

* PHP (>7.4)
* Composer
* Git

### Structure du projet

```
demi-sel-plugin/
├── dist/
│   └── admin.js        # JS de la page de réglage du plugin
├── src/                # Classes PHP du plugin (autoloadées via PSR-4)
│   ├── Activator.php
│   ├── AdminPage.php
│   ├── Deactivator.php
│   ├── FrontendEnqueue.php
│   └── Plugin.php
├── public/             # Fichiers CSS, JS de l'app Vue
│   ├── css/
│   └── js/
├── vendor/             # Dépendances Composer (non versionnées, générées)
├── demi-sel-plugin.php # Fichier principal du plugin
├── composer.json       # Fichier de configuration Composer
├── composer.lock       # Fichier de verrouillage des dépendances Composer
├── .gitignore          # Fichiers et dossiers ignorés par Git
├── README.md           # Ce fichier
└── build-plugin.sh     # Script de build (pour générer les releases .zip)
```

### Script de Build

Pour créer un fichier `.zip` distribuable de votre plugin (incluant les dépendances Composer mais excluant les fichiers de développement), utiliser le script `build-plugin.sh` :

1.  S'assurer que les dépendances de production sont à jour :
    ```bash
    composer install --no-dev --optimize-autoloader
    ```
2.  S'assurer que le script est exécutable :
    ```bash
    chmod +x build-plugin.sh
    ```
3.  Exécutez le script :**
    ```bash
    ./build-plugin.sh
    ```

Ce script générera un fichier ZIP (par exemple `demi-sel-plugin-X.Y.Z.zip`) dans le dossier `build/` qui peut être utilisé pour l'installation manuelle via l'interface d'administration WordPress.
