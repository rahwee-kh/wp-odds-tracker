# WP Odds Tracker

### Shortcodes
[wpot_scoreboard date="today"]

### Settings
- Data source (HTML or JSON)
- Auto refresh interval
- League whitelist
- Match limit

### Cron
Runs every 10 min:
- Crawls URL
- Normalizes data
- Saves to cache

### Requirements
- No 3rd party odds plugins
- Only wp_remote_get / DOMDocument / json_decode
