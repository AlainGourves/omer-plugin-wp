#!/bin/bash

PLUGIN_SLUG="demi-sel-plugin"
VERSION=$(php -r "echo json_decode(file_get_contents('composer.json'))->version;") # Ou depuis votre constante
if [ -z "$VERSION" ]; then
    echo "Impossible de récupérer la version du plugin."
    exit 1
fi
BUILD_DIR="build/${PLUGIN_SLUG}"
ZIP_FILE="${PLUGIN_SLUG}-${VERSION}.zip"

# Nettoyer les précédents builds
rm -rf build
mkdir -p "$BUILD_DIR"

# Copier les fichiers du plugin
rsync -av --progress \
    --exclude '.git/' \
    --exclude '.gitignore' \
    --exclude 'composer.json' \
    --exclude 'composer.lock' \
    --exclude 'phpunit.xml.dist' \
    --exclude 'tests/' \
    --exclude 'vendor-src/' \
    . "$BUILD_DIR/"

# Installer les dépendances de prod dans le dossier de build (si pas déjà fait en local)
# ou mieux, avoir fait un composer install --no-dev dans le répertoire source
# et ensuite copier le vendor/
cp -R vendor "$BUILD_DIR/vendor"

# Créer l'archive ZIP
cd build
zip -r "$ZIP_FILE" "$PLUGIN_SLUG"
cd ..

echo "Plugin build créé: build/$ZIP_FILE"