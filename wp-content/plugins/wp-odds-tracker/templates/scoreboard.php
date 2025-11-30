
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

        <input type="text" id="wpot-search" class="team-search" placeholder="Search team...">
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

    <div class="wpot-table-wrapper">
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
                <tr class="match-<?= esc_attr($m['status']) ?>">
                    <td><?= esc_html($m['league']); ?></td>
                    <td><?= esc_html(date('H:i', $m['time'])); ?></td>
                    <td><?= esc_html($m['home']); ?></td>
                    <td><?= esc_html($m['score']); ?></td>
                    <td><?= esc_html($m['away']); ?></td>
                    <td><?= implode(' | ', array_map('esc_html', $m['odds'])); ?></td>
                    <td class="status"><?= esc_html($m['status']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
