<?php
// classes/Plugin.php
namespace DemiSelPlugin;

/**
 * Classe principale du plugin.
 * Gère l'initialisation des fonctionnalités du plugin.
 */
class Plugin {

    /**
     * @var AdminPage L'instance de la classe AdminPage.
     */
    protected $admin_page;

    /**
     * @var FrontendEnqueue L'instance de la classe FrontendEnqueue.
     */
    protected $frontend_enqueue;

    /**
     * Constructeur de la classe Plugin.
     * Initialise les autres classes.
     */
    public function __construct() {
        $this->admin_page = new AdminPage();
        $this->frontend_enqueue = new FrontendEnqueue();
    }

    /**
     * Exécute le plugin.
     * Enregistre tous les hooks nécessaires.
     */
    public function run() {
        // Enregistre les hooks pour la page d'administration
        $this->admin_page->register_hooks();

        // Enregistre les hooks pour l'injection de scripts et de styles sur le frontend
        $this->frontend_enqueue->register_hooks();

        // Vous pouvez ajouter d'autres fonctionnalités ici
    }
}
