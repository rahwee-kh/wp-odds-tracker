jQuery(function ($) {

    let matches = [];
    let filtered = [];

    let selectedDate = WPOT_API.date || "today";


    function fetchMatches() {
        $("#wpot-loading").show();
        $("#wpot-body").hide();

        $.ajax({
            url: WPOT_API.ajax,
            data: {
                action: "wpot_get_matches",
                date: selectedDate
            },
            dataType: "json",
            success: function (resp) {
                matches = resp;
                filtered = resp;
                populateLeagueFilter();
                render();
                $("#wpot-loading").hide();
                $("#wpot-body").show();
            }
        });
    }

    function populateLeagueFilter(){
        let leagues = [...new Set(matches.map(m => m.league))].sort();
        const select = $("#wpot-filter-league");
        select.empty();
        select.append(`<option value="">All Leagues</option>`);
        leagues.forEach(l => select.append(`<option value="${l}">${l}</option>`));
    }

    function render(){
        const tbody = $("#wpot-body");
        tbody.empty();

        filtered.forEach(m => {
            let time = isNaN(m.time) ? m.time : new Date(m.time * 1000).toLocaleTimeString();
            tbody.append(`
                <tr>
                    <td>${m.league}</td>
                    <td>${time}</td>
                    <td>${m.home}</td>
                    <td>${m.score}</td>
                    <td>${m.away}</td>
                    <td>${m.odds.join(' | ')}</td>
                    <td>${m.status}</td>
                </tr>
            `);
        });
    }

    // Search
    $("#wpot-search").on("input", function(){
        const q = $(this).val().toLowerCase();
        filtered = matches.filter(m => 
            m.home.toLowerCase().includes(q) ||
            m.away.toLowerCase().includes(q)
        );
        render();
    });

    // League Filter
    $("#wpot-filter-league").on("change", function(){
        const league = $(this).val();
        filtered = league ? matches.filter(m => m.league === league) : matches;
        render();
    });

    // Sort
    $("#wpot-sort-time").on("change", function(){
        const order = $(this).val();
        filtered.sort((a,b) => {
            if (order === "asc") return a.time > b.time ? 1 : -1;
            return a.time < b.time ? 1 : -1;
        });
        render();
    });

    $("#wpot-date-apply").on("click", function(){
    const manual = $("#wpot-date-custom").val();
    const preset = $("#wpot-date-select").val();

    if (manual) {
            selectedDate = manual;  // YYYY-MM-DD
        } else {
            selectedDate = preset;  // today or tomorrow
        }

        fetchMatches();
    });


    // Auto refresh
    fetchMatches();
    setInterval(fetchMatches, WPOT_API.interval * 1000);
});
