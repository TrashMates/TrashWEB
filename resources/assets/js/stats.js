$(document).ready(() => {

    let ctx = document.getElementById("stats").getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        //autoSkip:true,
                    },
                    type: 'time',
                    time: {
                        unit: 'day',
                        distribution: 'series',
                        min: (new Date()).setMonth(new Date().getMonth() - 1)
                    },
                }]
            }
        }
    });


    let stats = {};
    stats.discord = {};
    stats.twitch = {};
    $.when(
        $.get("https://api.laravel.local/stats/twitch/events", (events) => {
            stats.twitch.events = events
        }),
        $.get("https://api.laravel.local/stats/twitch/messages", (messages) => {
            stats.twitch.messages = messages
        }),
        $.get("https://api.laravel.local/stats/twitch/viewers", (viewers) => {
            stats.twitch.viewers = viewers
        }),

        $.get("https://api.laravel.local/stats/discord/events", (events) => {
            stats.discord.events = events
        }),
        $.get("https://api.laravel.local/stats/discord/messages", (messages) => {
            stats.discord.messages = messages
        }),
        $.get("https://api.laravel.local/stats/discord/viewers", (viewers) => {
            stats.discord.viewers = viewers
        }),
    ).then(() => {
        for (let type in stats) {
            for (let key in stats[type]) {
                let dataset = {
                    label: "# of " + type.substr(0, 1).toUpperCase() + type.substr(1) + " " + key.substr(0, 1).toUpperCase() + key.substr(1),
                    data: [],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWidth: 1
                };

                stats[type][key].forEach((stat) => {
                    dataset.data.push({x: stat.date, y: stat.count});

                });

                chart.data.datasets.push(dataset);
                chart.update();
            }
        }
    });

});