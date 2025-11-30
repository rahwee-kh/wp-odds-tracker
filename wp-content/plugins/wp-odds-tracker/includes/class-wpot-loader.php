<?php

class WPOT_Loader {

    public function init(){
        require_once WPOT_DIR . 'includes/class-wpot-admin.php';
        require_once WPOT_DIR . 'includes/class-wpot-frontend.php';
        require_once WPOT_DIR . 'includes/class-wpot-crawler.php';
        require_once WPOT_DIR . 'includes/class-wpot-cron.php';
        require_once WPOT_DIR . 'includes/class-wpot-ajax.php';
        require_once WPOT_DIR . 'includes/class-wpot-rest.php';

        new WPOT_Admin();
        new WPOT_Frontend();
        new WPOT_Cron();
        new WPOT_Ajax();
        new WPOT_REST();
    }
}
