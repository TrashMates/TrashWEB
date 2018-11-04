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

// TrashMates - Game Toolkit
// VERSION: V3.00
// AUTHOR: TiCubius

streams = [];

$(document).ready(function () {

    if ($(location).attr("hash") === "") {
        window.location.href = "https://id.twitch.tv/oauth2/authorize?client_id=46vs5ngk9er091esnyovygnumieu5w5&redirect_uri=http://admin.trashweb.devbox/tools/stalker&response_type=token&force_verify=true";
    }

    var access_token = $(location).attr("hash").split("=")[1].split("&")[0];

    $("#lang").on("keydown", function (e) {
        var language = $("#lang").val();
        var streamsSorted = streams;

        if (e.keyCode === 13) {
            if (language !== "all") {
                streamsSorted = streams.filter(function (e) {
                    return e.language === language;
                });
            }

            var _streamsAnalyzed = arrayCount(streamsSorted.map(function (e) {
                return e.viewer_count;
            }));

            populateTable(_streamsAnalyzed);
        }
    });

    $("#game").on("keydown", function (e) {
        streams = [];
        if (e.keyCode === 13) {
            var game = $("#game").val();
            $("#lang").val("");

            $("#progessbar").show();
            getGameInformations([game], access_token).then(function (games) {
                getStreamsForGame(games[0].id, access_token).then(function (streams) {
                    $("#progessbar").hide();
                    table.classList.remove("hidden");

                    streamsAnalyzed = arrayCount(streams.map(function (e) {
                        return e.viewer_count;
                    }));
                    populateTable(streamsAnalyzed);
                }).catch(console.error);
            }).catch(console.error);
        }
    });
});

var getGameInformations = function getGameInformations(games, access_token) {
    var url = "https://api.twitch.tv/helix/games?name=" + games.join("&name=");
    console.log("[*] - Fetching " + url);

    return new Promise(function (resolve, reject) {

        axios.get(url, {
            headers: {
                'Authorization': "Bearer " + access_token
            }
        }).then(function (response) {
            resolve(response.data.data);
        }).catch(reject);
    });
};

var getStreamsForGame = function getStreamsForGame(game_id, access_token, pagination) {
    if (pagination === undefined) {
        pagination = "";
    }

    var url = "https://api.twitch.tv/helix/streams?game_id=" + game_id + "&first=100&after=" + pagination;
    console.log("[*] - Fetching " + url);

    return new Promise(function (resolve, reject) {

        axios.get(url, {
            headers: {
                'Authorization': "Bearer " + access_token
            }
        }).then(function (response) {
            response.data.data.forEach(function (stream) {
                streams.push(stream);
            });

            pagination = response.data.pagination.cursor;

            if (response.data.data.length >= 100) {
                resolve(getStreamsForGame(game_id, access_token, pagination));
            }
            resolve(streams);
        }).catch(reject);
    });
};

var populateTable = function populateTable(streams) {

    $("#stats tr").remove();
    var table = document.querySelector("#stats");

    streams.forEach(function (stream) {
        var row = table.insertRow();

        row.insertCell().innerHTML = stream.value;
        row.insertCell().innerHTML = stream.count;
        row.insertCell().innerHTML = stream.percent + "%";
        row.insertCell().innerHTML = stream.above + "%";
        row.insertCell().innerHTML = stream.minPosition + " - " + stream.maxPosition;
    });
};

arrayCount = function arrayCount(array) {

    // FIRST: Sort array
    array = array.sort(function (a, b) {
        return b - a;
    });

    // THEN: Count

    var countArray = [];
    var above = 0;
    var maxPosition = array.length;
    var minPosition = array.length;

    var _loop = function _loop(i) {
        var count = array.filter(function (e) {
            return e === i;
        }).length;
        if (count != 0) {
            var percent = count / array.length * 100;
            above += percent;
            minPosition -= count;
            countArray.push({ "value": i, "count": count, "percent": percent.toFixed(2), "above": above.toFixed(2), "maxPosition": maxPosition, "minPosition": minPosition });
            maxPosition -= count;
        }
    };

    for (var i = 0; i <= array[0]; i++) {
        _loop(i);
    }

    return countArray;
};

/***/ })

/******/ });