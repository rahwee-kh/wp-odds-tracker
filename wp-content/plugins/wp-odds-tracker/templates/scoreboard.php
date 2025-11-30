
<div id="wpot-scoreboard">
    <!-- Filters -->
    <div id="wpot-controls" class="wpot-controls">

        <select id="wpot-filter-league">
            <option value="">All Leagues</option>
        </select>

        <select id="wpot-sort-time">
            <option value="asc">Time ↑</option>
            <option value="desc">Time ↓</option>
        </select>

        <input type="text" id="wpot-search" placeholder="Search team...">
    </div>

    <div class="wpot-date-filter">
        <select id="wpot-date-select">
            <option value="today">Today</option>
            <option value="tomorrow">Tomorrow</option>
        </select>

        <input type="date" id="wpot-date-custom">
        <button id="wpot-date-apply">Apply</button>
    </div>

    <!-- Loader skeleton -->
    <div id="wpot-loading" class="wpot-loading">
        Loading matches...
    </div>

    
    <table class="wpot-table">
        <thead>
            <tr>
                <th>League</th>
                <th>Time</th>
                <th>Home</th>
                <th>Score</th>
                <th>Away</th>
                <th>Odds</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody id="wpot-body">
            <?php foreach ($matches as $m): ?>
                <div class="wpot-card match-<?= esc_attr($m['status']) ?>">
                <div class="row"><strong><?= esc_html($m['league']); ?></strong></div>
                <div class="row"><?= esc_html($m['home']); ?> vs <?= esc_html($m['away']); ?></div>
                <div class="row">Time: <?= esc_html(date('H:i', $m['time'])); ?></div>
                <div class="row">Score: <?= esc_html($m['score']); ?></div>
                <div class="row">Odds: <?= implode(' | ', $m['odds']); ?></div>
                <div class="row status"><?= esc_html($m['status']); ?></div>
            </div>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>
