<?php
// classes/Deactivator.php
namespace DemiSelPlugin;

/**
 * Gère les actions à la désactivation du plugin.
 */
class Deactivator {

    /**
     * Méthode appelée lors de la désactivation du plugin.
     * Peut être utilisée pour nettoyer les options ou les tables de base de données.
     */
    public static function deactivate() {
        // Exemple : Supprimer une option lors de la désactivation
        // delete_option( 'demi_sel_plugin_settings' );

        // Vider les règles de réécriture
        flush_rewrite_rules();
    }
}
