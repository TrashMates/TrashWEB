$(document).ready(() => {

    let searching = false;
    $("#game").on("keyup", (e) => {
        let game = $("#game").val();

        setTimeout(() => {
            if (game === $("#game").val() && game !== "" && !searching) {
                searching = true;
                searchGame(game)
            }
        }, 1500)
    });

    let streamers_ctx = document.getElementById("streamers-stats").getContext('2d');
    let streamers_chart = new Chart(streamers_ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [],
                backgroundColor: [],
                borderColor: [
                    'rgba(255,99,132,1)',
                ],
                borderWidth: 1,
            }],
            labels: []
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    let viewers_ctx = document.getElementById("viewers-stats").getContext('2d');
    let viewers_chart = new Chart(viewers_ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [],
                backgroundColor: [],
                borderColor: [
                    'rgba(255,99,132,1)',
                ],
                borderWidth: 1,
            }],
            labels: []
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    let searchGame = (game) => {
        // CLEAR STREAMER DATA
        streamers_chart.data.datasets[0].data = [];
        streamers_chart.data.datasets[0].backgroundColor = [];
        streamers_chart.data.labels = [];

        // CLEAR VIEWERS DATA
        viewers_chart.data.datasets[0].data = [];
        viewers_chart.data.datasets[0].backgroundColor = [];
        viewers_chart.data.labels = [];

        $.get("https://api.laravel.local/tools/game?game=" + game, (streams) => {
            $(".stats").show();

            searching = false;
            let allStreams = {};
            streams.forEach((stream) => {

                if (! allStreams.hasOwnProperty(stream.language)) {
                    allStreams[stream.language] = [];
                }
                allStreams[stream.language].push(stream);

            });

            for (let language in allStreams) {
                let viewers_count = 0;
                for (let stream in allStreams[language]) {
                    viewers_count += allStreams[language][stream]['viewer_count'];
                }

                // GENERATE RANDOM COLOR FOR THE BACKGROUND
                let color = getRandomColor();

                // HOW MANY STREAMERS FOR THIS LANGUAGE
                streamers_chart.data.datasets[0].data.push(allStreams[language].length);
                streamers_chart.data.datasets[0].backgroundColor.push(color);
                streamers_chart.data.labels.push(language);
                streamers_chart.update();

                // HOW MANY VIEWER FOR THIS LANGUAGE
                viewers_chart.data.datasets[0].data.push(viewers_count);
                viewers_chart.data.datasets[0].backgroundColor.push(color);
                viewers_chart.data.labels.push(language);
                viewers_chart.update();

            }
        });
    }

    let getRandomColor = () => {
        let letters = '0123456789ABCDEF';
        let color = '#';

        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }

        return color;
    }

});