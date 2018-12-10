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
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius


var Game = __webpack_require__(11);

$(document).ready(function () {

	var gameid = location.href.split("/")[location.href.split("/").length - 1];
	var game = new Game(gameid);

	// BUTTON: Fetch new stats
	$("#scanNow").click(function (e) {
		$("#progressbar").show();
		axios.put(location.href).then(function (response) {
			$("#progressbar").hide();

			addStreamDate(response.data);
		});
	});

	// BUTTON: Click on a stat
	$(".js-stat").click(function (e) {

		$("#progressbar").show();
		$("#js-stats").hide();

		var statid = $(e.currentTarget).data("statid");
		var stats = game.findStat(statid);

		// Fetch all streams
		stats.fetchStreams().then(function (streams) {

			$("#progressbar").hide();
			$("#settings").show();
			$("#streams").show();

			$("#languages").change(function (e) {

				var lang = $(e.currentTarget).val();
				var filteredStreams = streams;

				if (lang !== "all") {
					filteredStreams = streams.filter(function (s) {
						return s.language === lang;
					});
				}

				// Generate streams table
				var streamTableData = filteredStreams.map(function (s) {
					return [{ class: "center", content: s.language }, { class: "center", content: s.channel.username }, { content: s.title }, { class: "center", content: s.viewers }];
				});
				streamTableData.push([{ colspan: 3, class: "center", content: "TOTAL" }, { class: "center", content: stats.getStreamsTotalViewers(lang) }]);
				populateTable("streams", [{ class: "center", content: "language" }, { content: "channel" }, { content: "title" }, { class: "center", content: "viewers" }], streamTableData);
			});

			// Generate stream select
			var streamSelectLanguages = [{ value: "all", content: "All Languages" }];
			streamSelectLanguages = streamSelectLanguages.concat(stats.getStreamsLanguages().map(function (l) {
				return { value: l, content: l };
			}));
			populateSelect("languages", streamSelectLanguages);

			$("#languages").val("all").trigger("change");
		});
	});

	$("#back").click(function (e) {
		e.preventDefault();

		$("#js-stats").show();
		$("#streams").hide();
		$("#settings").hide();
	});

	var addStreamDate = function addStreamDate(stat) {
		location.reload(true);
	};

	/**
  * Populate a table with the given data
  * @param HTMLElementID
  * @param tableHeader
  * @param tableData
  */
	var populateTable = function populateTable(HTMLElementID, tableHeader, tableData) {

		// Remove every child of the HTMLElement
		var HTMLElement = $("#" + HTMLElementID);
		HTMLElement.text(" ");

		// OPTIMIZATION: Hide HTMLElement
		HTMLElement.hide();

		// Generate HTML - Table Header
		var HTML = "<thead>";
		tableHeader.forEach(function (column) {
			var properties = Object.keys(column).filter(function (property) {
				return property !== "content";
			});

			HTML += "<th ";
			properties.forEach(function (p) {
				HTML += p + "=\"" + column[p] + "\"";
			});
			HTML += ">" + column.content + "</th>";
		});
		HTML += "</thead>";

		// Generate HTML - Table Body
		HTML += "<tbody>";
		tableData.forEach(function (row) {
			HTML += "<tr>";
			row.forEach(function (column) {
				var properties = Object.keys(column).filter(function (property) {
					return property !== "content";
				});

				HTML += "<td ";
				properties.forEach(function (p) {
					HTML += p + "=\"" + column[p] + "\"";
				});
				HTML += ">" + column.content + "</td>";
			});
			HTML += "</tr>";
		});
		HTML += "</tbody>";

		// Fill the HTMLElement
		HTMLElement.html(HTML);

		// OPTIMIZATION: Shows the HTMLElement
		HTMLElement.show();
	};

	/**
  * Populate a select with the given data
  * @param HTMLElementID
  * @param selectData
  */
	var populateSelect = function populateSelect(HTMLElementID, selectData) {

		// Remove every child of the HTMLElement
		var HTMLElement = $("#" + HTMLElementID);
		HTMLElement.text(" ");

		// OPTIMIZATION: Hide HTMLElement
		HTMLElement.hide();

		// Generate HTML - Options
		var HTML = "";
		selectData.forEach(function (option) {
			var properties = Object.keys(option).filter(function (property) {
				return property !== "content";
			});

			HTML += "<option ";
			properties.forEach(function (p) {
				HTML += p + "=\"" + option[p] + "\"";
			});
			HTML += ">" + option.content + "</option>";
		});

		// Fill the HTMLElemnt
		HTMLElement.html(HTML);

		// OPTIMIZATION: Show the HTMLElement
		HTMLElement.show();
	};
});

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var GameStat = __webpack_require__(12);

var Game = function () {

	/**
  * Game
  * @param id
  */
	function Game(id) {
		_classCallCheck(this, Game);

		this.id = id;
		this.name = null;
		this.picture = null;

		this.stats = [];
		this.headers = { "token": "tFdUNcvfRK0qvfBU" };

		this.fetch();
		this.fetchStats();
	}

	/**
  * Fetches all game information
  * @returns {Promise<any>}
  */


	_createClass(Game, [{
		key: "fetch",
		value: function fetch() {
			var _this = this;

			return new Promise(function (resolve, reject) {
				if (_this.name !== null) {
					return resolve({ "id": _this.id, "name": _this.name, "picture": _this.picture });
				}

				axios({ url: "https://" + location.host + "/api/twitch/games/" + _this.id, headers: _this.headers }).then(function (response) {
					_this.name = response.data.name;
					_this.picture = response.data.picture;

					return resolve({ "id": _this.id, "name": _this.name, "picture": _this.picture });
				}).catch(reject);
			});
		}

		/**
   * Fetches all stats for the game
   * @returns {Promise<any>}
   */

	}, {
		key: "fetchStats",
		value: function fetchStats() {
			var _this2 = this;

			return new Promise(function (resolve, reject) {
				if (_this2.stats.length > 0) {
					return resolve(_this2.stats);
				}

				axios({ url: "https://" + location.host + "/api/twitch/games/" + _this2.id + "/stats", headers: _this2.headers }).then(function (response) {
					response.data.stats.forEach(function (retrievedStat) {
						_this2.stats.push(new GameStat(_this2, retrievedStat.id));
					});

					return resolve(_this2.stats);
				}).catch(reject);
			});
		}

		/**
   *
   * @param id
   * @return {GameStat}
   */

	}, {
		key: "findStat",
		value: function findStat(id) {
			return this.stats.find(function (s) {
				return s.id === id;
			});
		}
	}]);

	return Game;
}();

module.exports = Game;

/***/ }),
/* 12 */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var GameStat = function () {

	/**
  * Stat
  * @param {Game} game
  * @param {number} id
  */
	function GameStat(game, id) {
		_classCallCheck(this, GameStat);

		this.id = id;

		this.game = game;
		this.streams = [];

		this.headers = { "token": "tFdUNcvfRK0qvfBU" };
	}

	/**
  * Fetches all streams for the stat
  * @returns {Promise<any>}
  */


	_createClass(GameStat, [{
		key: "fetchStreams",
		value: function fetchStreams() {
			var _this = this;

			return new Promise(function (resolve, reject) {

				if (_this.streams.length > 0) {
					return resolve(_this.streams);
				}

				axios({ url: "https://" + location.host + "/api/twitch/games/" + _this.game.id + "/stats/" + _this.id, headers: _this.headers }).then(function (response) {
					_this.streams = response.data.streams;

					return resolve(_this.streams);
				}).catch(reject);
			});
		}

		/**
   * Returns the total of viewers for the given lang
   * @param {string} lang
   * @returns {int}
   */

	}, {
		key: "getStreamsTotalViewers",
		value: function getStreamsTotalViewers(lang) {
			if (this.streams.length === 0) {
				return 0;
			}

			if (lang !== "all") {
				var _streams = this.streams.filter(function (s) {
					return s.language === lang;
				}).map(function (s) {
					return s.viewers;
				});
				return _streams.length > 0 ? _streams.reduce(function (a, b) {
					return a + b;
				}) : 0;
			}

			var streams = this.streams.map(function (e) {
				return e.viewers;
			});
			return streams.length > 0 ? this.streams.map(function (s) {
				return s.viewers;
			}).reduce(function (a, b) {
				return a + b;
			}) : 0;
		}

		/**
   * Returns all streams languages & remove duplicates
   * @returns {(string | *[])[]}
   */

	}, {
		key: "getStreamsLanguages",
		value: function getStreamsLanguages() {
			return Array.from(new Set(this.streams.map(function (s) {
				return s.language;
			})));
		}
	}]);

	return GameStat;
}();

module.exports = GameStat;

/***/ })
/******/ ]);