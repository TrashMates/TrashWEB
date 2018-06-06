/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ 7:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(8);


/***/ }),

/***/ 8:
/***/ (function(module, exports) {

$(document).ready(function () {

    var searching = false;
    $("#game").on("keyup", function (e) {
        var game = $("#game").val();

        setTimeout(function () {
            if (game === $("#game").val() && game !== "" && !searching) {
                $("#progessbar").show();
                searching = true;
                searchGame(game);
            }
        }, 1500);
    });

    var streamers_ctx = document.getElementById("streamers-stats").getContext('2d');
    var streamers_chart = new Chart(streamers_ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [],
                backgroundColor: [],
                borderColor: ['rgba(255,99,132,1)'],
                borderWidth: 1
            }],
            labels: []
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });

    var viewers_ctx = document.getElementById("viewers-stats").getContext('2d');
    var viewers_chart = new Chart(viewers_ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [],
                backgroundColor: [],
                borderColor: ['rgba(255,99,132,1)'],
                borderWidth: 1
            }],
            labels: []
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });

    var searchGame = function searchGame(game) {
        // CLEAR STREAMER DATA
        streamers_chart.data.datasets[0].data = [];
        streamers_chart.data.datasets[0].backgroundColor = [];
        streamers_chart.data.labels = [];

        // CLEAR VIEWERS DATA
        viewers_chart.data.datasets[0].data = [];
        viewers_chart.data.datasets[0].backgroundColor = [];
        viewers_chart.data.labels = [];

        $.get("https://api.laravel.local/tools/game?game=" + game, function (streams) {
            $(".stats").show();
            $("#progessbar").hide();

            searching = false;
            var allStreams = {};
            streams.forEach(function (stream) {

                if (!allStreams.hasOwnProperty(stream.language)) {
                    allStreams[stream.language] = [];
                }
                allStreams[stream.language].push(stream);
            });

            for (var language in allStreams) {
                var viewers_count = 0;
                for (var stream in allStreams[language]) {
                    viewers_count += allStreams[language][stream]['viewer_count'];
                }

                // GENERATE RANDOM COLOR FOR THE BACKGROUND
                var color = getRandomColor();

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
    };

    var getRandomColor = function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';

        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }

        return color;
    };
});

/***/ })

/******/ });