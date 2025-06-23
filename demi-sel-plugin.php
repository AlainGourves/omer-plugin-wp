<?php
/**
 * Plugin Name:       Demi-sel Plugin
 * Plugin URI:        https://demi-sel.net
 * Description:       Un plugin WordPress pour intégrer une application Vue.js et gérer ses réglages.
 * Version:           0.2.5
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Alain Gourvès
 * Author URI:        alain.gourves@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       demi-sel-plugin
 * Domain Path:       /languages
 */

// Sécurité : empêche l'accès direct au fichier
defined( 'ABSPATH' ) || die;

// Définir les constantes du plugin
define( 'DEMI_SEL_PLUGIN_VERSION', '0.1.0' );
define( 'DEMI_SEL_PLUGIN_SHORTCODE', 'demi_sel' );
define( 'DEMI_SEL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DEMI_SEL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DEMI_SEL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Chargement des classes du plugin via Composer PSR-4 autoloader.
 * C'est la méthode recommandée pour une gestion propre des dépendances et de l'organisation du code.
 */
if ( file_exists( DEMI_SEL_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
    require_once DEMI_SEL_PLUGIN_PATH . 'vendor/autoload.php';
}

/**
 * Exécute le code à l'activation du plugin.
 * Crée les options par défaut ou les tables de base de données si nécessaire.
 */
function activate_demi_sel_plugin() {
    DemiSelPlugin\Activator::activate();
}
register_activation_hook( __FILE__, 'activate_demi_sel_plugin' );

/**
 * Exécute le code à la désactivation du plugin.
 * Nettoie les options, supprime les tables de base de données si nécessaire.
 */
function deactivate_demi_sel_plugin() {
    DemiSelPlugin\Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_demi_sel_plugin' );

/**
 * Commence l'exécution du plugin.
 * Initialise toutes les fonctionnalités et les hooks.
 */
function run_demi_sel_plugin() {
    $plugin = new DemiSelPlugin\Plugin();
    $plugin->run();
}
run_demi_sel_plugin();