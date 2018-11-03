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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(6);


/***/ }),

/***/ 6:
/***/ (function(module, exports) {

$(document).ready(function () {

    var ctx = document.getElementById("stats").getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: []
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
                        min: new Date().setMonth(new Date().getMonth() - 1)
                    }
                }]
            }
        }
    });

    var stats = {};
    stats.discord = {};
    stats.twitch = {};
    $.when($.get("https://api.trashmates.fr/stats/twitch/events", function (events) {
        stats.twitch.events = events;
    }), $.get("https://api.trashmates.fr/stats/twitch/messages", function (messages) {
        stats.twitch.messages = messages;
    }), $.get("https://api.trashmates.fr/stats/twitch/viewers", function (viewers) {
        stats.twitch.viewers = viewers;
    }), $.get("https://api.trashmates.fr/stats/discord/events", function (events) {
        stats.discord.events = events;
    }), $.get("https://api.trashmates.fr/stats/discord/messages", function (messages) {
        stats.discord.messages = messages;
    }), $.get("https://api.trashmates.fr/stats/discord/viewers", function (viewers) {
        stats.discord.viewers = viewers;
    })).then(function () {
        for (var type in stats) {
            var _loop = function _loop(key) {
                var dataset = {
                    label: "# of " + type.substr(0, 1).toUpperCase() + type.substr(1) + " " + key.substr(0, 1).toUpperCase() + key.substr(1),
                    data: [],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(255,99,132,1)'],
                    borderWidth: 1
                };

                stats[type][key].forEach(function (stat) {
                    dataset.data.push({ x: stat.date, y: stat.count });
                });

                chart.data.datasets.push(dataset);
                chart.update();
            };

            for (var key in stats[type]) {
                _loop(key);
            }
        }
    });
});

/***/ })

/******/ });