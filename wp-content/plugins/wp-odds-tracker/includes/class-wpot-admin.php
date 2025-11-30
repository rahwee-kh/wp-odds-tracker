<?php


if (!defined('ABSPATH')) exit;

class WPOT_Admin {

    public function __construct()
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_post_wpot_save_settings', [$this, 'save_settings']);
    }

    /**
     * Register menu item
     */
    public function menu() {
        add_menu_page(
            'WP Odds Tracker',          // Page title
            'Odds Tracker',             // Menu title
            'manage_options',           // Capability
            'wpot-settings',            // Slug -> ?page=wpot-settings
            [$this, 'settings_page'],   // Callback
            'dashicons-chart-line',     // Icon
            80                          // Position
        );
    }

    /**
     * Render settings page
     */
    public function settings_page() {

        if (!current_user_can('manage_options')) {
            return;
        }

        // Load existing values (with defaults)
        $source_url       = get_option('wpot_source_url', '');
        $refresh_interval = (int) get_option('wpot_refresh_interval', 30);
        $max_matches      = (int) get_option('wpot_max_matches', 50);
        $league_whitelist = get_option('wpot_league_whitelist', '');

        ?>
        <div class="wrap">
            <h1>WP Odds Tracker Settings</h1>

            <?php if (!empty($_GET['updated'])): ?>
                <div class="notice notice-success is-dismissible">
                    <p>Settings saved.</p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wpot_save_settings', 'wpot_settings_nonce'); ?>
                <input type="hidden" name="action" value="wpot_save_settings">

                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="wpot_source_url">Data source URL</label>
                            </th>
                            <td>
                                <input type="url"
                                       id="wpot_source_url"
                                       name="wpot_source_url"
                                       class="regular-text"
                                       value="<?php echo esc_attr($source_url); ?>">
                                <p class="description">
                                    Example: https://example.com/odds-feed.json
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="wpot_refresh_interval">Auto-refresh interval (seconds)</label>
                            </th>
                            <td>
                                <input type="number"
                                       id="wpot_refresh_interval"
                                       name="wpot_refresh_interval"
                                       min="5"
                                       class="small-text"
                                       value="<?php echo esc_attr($refresh_interval); ?>">
                                <p class="description">
                                    How often the scoreboard should refresh via AJAX. Default: 30 seconds.
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="wpot_max_matches">Max number of matches</label>
                            </th>
                            <td>
                                <input type="number"
                                       id="wpot_max_matches"
                                       name="wpot_max_matches"
                                       min="1"
                                       class="small-text"
                                       value="<?php echo esc_attr($max_matches); ?>">
                                <p class="description">
                                    Limit how many matches are returned / shown to avoid huge payloads.
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="wpot_league_whitelist">League whitelist</label>
                            </th>
                            <td>
                                <textarea id="wpot_league_whitelist"
                                          name="wpot_league_whitelist"
                                          rows="4"
                                          cols="50"
                                          class="large-text"><?php echo esc_textarea($league_whitelist); ?></textarea>
                                <p class="description">
                                    Comma-separated list of league names to include. Example: 
                                    Premier League,LaLiga,Serie A
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Handle form submission
     */
    public function save_settings() {

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        // Nonce check
        if (!isset($_POST['wpot_settings_nonce']) ||
            !wp_verify_nonce($_POST['wpot_settings_nonce'], 'wpot_save_settings')) {
            wp_die('Nonce verification failed');
        }

        // Sanitize and save
        $source_url       = isset($_POST['wpot_source_url']) ? esc_url_raw($_POST['wpot_source_url']) : '';
        $refresh_interval = isset($_POST['wpot_refresh_interval']) ? (int) $_POST['wpot_refresh_interval'] : 30;
        $max_matches      = isset($_POST['wpot_max_matches']) ? (int) $_POST['wpot_max_matches'] : 50;
        $league_whitelist = isset($_POST['wpot_league_whitelist']) ? sanitize_textarea_field($_POST['wpot_league_whitelist']) : '';

        if ($refresh_interval <= 0) {
            $refresh_interval = 30;
        }

        if ($max_matches <= 0) {
            $max_matches = 50;
        }

        update_option('wpot_source_url', $source_url);
        update_option('wpot_refresh_interval', $refresh_interval);
        update_option('wpot_max_matches', $max_matches);
        update_option('wpot_league_whitelist', $league_whitelist);

        // Redirect back with success flag
        $redirect = add_query_arg(
            ['page' => 'wpot-settings', 'updated' => 'true'],
            admin_url('admin.php')
        );

        wp_safe_redirect($redirect);
        exit;
    }
}
