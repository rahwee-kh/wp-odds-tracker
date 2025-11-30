<?php



class WPOT_Ajax {

    public function __construct(){
        add_action('wp_ajax_wpot_get_matches', [$this, 'data']);
        add_action('wp_ajax_nopriv_wpot_get_matches', [$this, 'data']);
    }

    public function data(){

        $date = sanitize_text_field($_GET['date'] ?? 'today');

        // normalize to Y-m-d
        if ($date === 'today') {
            $date = date('Y-m-d');
        } elseif ($date === 'tomorrow') {
            $date = date('Y-m-d', strtotime('+1 day'));
        }

        $cache_key = 'wpot_matches_' . $date;
        $data = get_transient($cache_key);

        wp_send_json($data ?: []);
    }

}

