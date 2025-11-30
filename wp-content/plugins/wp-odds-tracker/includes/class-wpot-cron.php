<?php

if (!defined('ABSPATH')) exit;

class WPOT_Cron {

    public function __construct() {

        // register interval
        add_filter('cron_schedules', [$this, 'custom_schedules']);

        // cron hook behavior
        add_action('wpot_cron_fetch', [$this, 'cron_task']);

        // activation/deactivation hooks
        register_activation_hook(WPOT_DIR.'wp-odds-tracker.php', [$this, 'activate']);
        register_deactivation_hook(WPOT_DIR.'wp-odds-tracker.php', [$this, 'deactivate']);
    }

    public function custom_schedules($schedules) {
        $schedules['ten_minutes'] = [
            'interval' => 600, // 600 seconds = 10 minutes
            'display'  => __('Every 10 Minutes')
        ];
        return $schedules;
    }

    /**
     * Schedule event on plugin activation
     */
    public function activate(){
        if (!wp_next_scheduled('wpot_cron_fetch')) {
            wp_schedule_event(time(), 'ten_minutes', 'wpot_cron_fetch');
        }
    }

    /**
     * Unschedule event on plugin deactivation
     */
    public function deactivate(){
        wp_clear_scheduled_hook('wpot_cron_fetch');
    }

    /**
     * Cron callback → run crawler
     */
    public function cron_task(){
        WPOT_Crawler::fetch();
    }
}
