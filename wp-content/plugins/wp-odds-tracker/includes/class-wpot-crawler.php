<?php



class WPOT_Crawler {

    /**
     * Fetch & cache matches from the admin-defined source
     */
    public static function fetch(){

        $url = get_option('wpot_source_url');
        
        if (!$url) {
            update_option('wpot_last_error', 'Missing data source URL');
            return false;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            update_option('wpot_last_error', 'Invalid source URL');
            return false;
        }

        $response = wp_remote_get($url, [
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'WP Odds Tracker'
            ]
        ]);

        if (is_wp_error($response)) {
            update_option('wpot_last_error', $response->get_error_message());
            return false;
        }

        $json = json_decode(wp_remote_retrieve_body($response), true);

        if (!$json) {
            update_option('wpot_last_error', 'JSON decode failed');
            return false;
        }

        $normalized = [];

        foreach ($json as $match) {

            $timestamp = strtotime($match['matchDateTime']);

            // Determine match status & score
            $score = '-';
            $status = 'scheduled';

            if ($match['matchIsFinished'] && !empty($match['matchResults'])) {
                // Endergebnis is usually last in array
                $lastResult = end($match['matchResults']);
                $score = $lastResult['pointsTeam1'] . '-' . $lastResult['pointsTeam2'];
                $status = 'finished';
            }

            $normalized[] = [
                'league' => $match['leagueName'] ?? 'Unknown League',
                'home'   => $match['team1']['teamName'] ?? '',
                'away'   => $match['team2']['teamName'] ?? '',
                'time'   => $timestamp,
                'score'  => $score,
                'odds'   => ['-', '-', '-'], // OpenLigaDB provides no odds
                'status' => $status,
            ];
        }

        // Save as "today"
        $date = date('Y-m-d');
        set_transient('wpot_matches_' . $date, $normalized, 15 * MINUTE_IN_SECONDS);

        update_option('wpot_last_update', current_time('timestamp'));
        update_option('wpot_last_error', '');

        return $normalized;
    }

 

}




    

