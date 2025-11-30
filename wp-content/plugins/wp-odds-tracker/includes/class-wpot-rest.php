<?php


class WPOT_REST {

    public function __construct(){
        add_action('rest_api_init', [$this,'routes']);
    }

    public function routes(){
        register_rest_route('wpot/v1','/matches', [
            'methods'  => 'GET',
            'callback' => [$this,'matches']
        ]);
    }

    public function matches($req){
        $date = $req['date'] ?? 'today';
        return wpot_get_cached_data($date);
    }
}
