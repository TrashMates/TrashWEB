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
/******/ ({

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

// TrashMates - GAME SCAN INITIATIVE
// AUTHOR: TiCubius
// VERSION: 3.10

var Game = __webpack_require__(11);
var $UI = __webpack_require__(21);

$(document).ready(function () {

	var game_id = location.href.split('/')[location.href.split('/').length - 1];
	var game = new Game(game_id);

	// EVENT: Stat choosen
	$('.js-stat').click(function (e) {

		// UI Elements
		$('.js-stat').toggle();
		$('#progressbar').toggle();

		// Fetching
		var stat_id = $(e.currentTarget).data('statid');
		game.fetchStats(stat_id).then(function (stat) {

			stat.fetchStreams().then(function () {

				// UI Element
				$('#progressbar').toggle();
				$('#settings').toggle();
				$('#streams').toggle();
				$('#words').toggle();

				// Change languages
				$('#languages').change(function (e) {

					var language = $(e.currentTarget).val();
					stat.fetchStreams(language).then(function (streams) {

						// Generate Streams data
						var totalViewers = stat.getTotalViewers(language);
						var streamsTableHead = [{ content: 'language' }, { content: 'channel' }, { content: 'title (' + streams.length + ' streams)' }, { content: 'viewers (' + totalViewers + ')' }, { content: 'ratio' }];
						var streamsTableBody = streams.map(function (s) {
							return [{ class: 'center', content: s.language }, { class: 'center', content: s.channel.username }, { content: s.title }, { class: 'center', content: s.viewers }, { class: 'center', content: (s.viewers / totalViewers * 100).toFixed(3) + '%' }];
						});
						$UI.populateTable('streams', streamsTableHead, streamsTableBody);

						// Generate Words data
						var wordsTableHead = [{ content: 'word' }, { content: 'count' }, { content: 'ratio' }];
						var wordsTableBody = stat.getTitleStats(language).map(function (s) {
							return [{ class: 'center', content: s.word }, { class: 'center', content: s.count }, { class: 'center', content: s.ratio + '%' }];
						});
						$UI.populateTable('words', wordsTableHead, wordsTableBody);
					}).catch(console.error);
				}).trigger('change');

				// Fetch languages
				var languages = [{ content: 'all' }];
				languages = languages.concat(stat.getAllStreamsLanguages().map(function (l) {
					return { content: l };
				}));

				// Populate languages Select
				$UI.populateSelect('languages', languages);
			});
		}).catch(console.error);
	});

	// EVENT: Fetch new stats
	$('#scanNow').click(function (e) {
		$('#progressbar').toggle();
		axios.put(location.href).then(function () {

			$('#progressbar').toggle();
			location.reload(true);
		});
	});

	// EVENT: Go back to stats list
	$('#back').click(function (e) {
		$('.js-stat').toggle();
		$('#settings').toggle();
		$('#streams').toggle();
		$('#words').toggle();
	});
});

/***/ }),

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// TrashMates - GameStat
// AUTHOR: TiCubius
// VERSION: V3.10

var GameStat = __webpack_require__(20);

var Game = function () {

	/**
  * Creates a new instance of Game
  * @param {Number} id
  */
	function Game(id) {
		_classCallCheck(this, Game);

		this.id = id;
		this.name = null;
		this.picture = null;

		this.stats = [];

		this.headers = { "token": "tFdUNcvfRK0qvfBU" };
		this.fetchGame();
	}

	/**
  * Fetches all game informations
  *
  * @returns {Promise<Object>}
  */


	_createClass(Game, [{
		key: "fetchGame",
		value: function fetchGame() {
			var _this = this;

			return new Promise(function (resolve, reject) {

				if (_this.name !== null) {
					return resolve({ "id": _this.id, "name": _this.name, "picture": _this.picture });
				}

				var url = "https://" + location.host + "/api/twitch/games/" + _this.id;
				axios({ url: url, headers: _this.headers }).then(function (response) {
					_this.name = response.data.name;
					_this.picture = response.data.picture;

					return resolve({ "id": _this.id, "name": _this.name, "picture": _this.picture });
				}).catch(reject);
			});
		}

		/**
   *
   * @param {Number?} id
   */

	}, {
		key: "getStats",
		value: function getStats(id) {
			var stats = this.stats;
			if (id !== undefined) {
				stats = stats.find(function (s) {
					return s.getId() === id;
				});
			}

			return stats;
		}

		/**
   * Fetches all stats for the game
   *
   * @param {Number?} id
   * @returns {Promise<any>|Promise<GameStat>}
   */

	}, {
		key: "fetchStats",
		value: function fetchStats(id) {
			var _this2 = this;

			return new Promise(function (resolve, reject) {

				if (_this2.stats.length > 0) {
					return resolve(_this2.getStats(id));
				}

				axios({ url: "https://" + location.host + "/api/twitch/games/" + _this2.id + "/stats", headers: _this2.headers }).then(function (response) {
					response.data.stats.forEach(function (stat) {
						_this2.stats.push(new GameStat(_this2, stat.id));
					});

					return resolve(_this2.getStats(id));
				}).catch(reject);
			});
		}
	}]);

	return Game;
}();

module.exports = Game;

/***/ }),

/***/ 20:
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// TrashMates - Game Stat
// AUTHOR: TiCubius
// VERSION: 3.10

var GameStat = function () {

	/**
  * Creates a new intance of GameStat
  *
  * @param {Game} game
  * @param {Number} id
  */
	function GameStat(game, id) {
		_classCallCheck(this, GameStat);

		this.game = game;
		this.id = id;

		this.headers = { "token": "tFdUNcvfRK0qvfBU" };
		this.streams = [];
	}

	/**
  * Returns the ID of the stat
  *
  * @returns {Number}
  */


	_createClass(GameStat, [{
		key: "getId",
		value: function getId() {
			return this.id;
		}

		/**
   * Returns all Streams
   *
   * @param {string?} language
   * @returns {Array<Object>}
   */

	}, {
		key: "getStreams",
		value: function getStreams(language) {
			var streams = this.streams;
			if (language !== undefined && language !== "all") {
				streams = streams.filter(function (s) {
					return s.language === language;
				});
			}

			return streams.sort(function (a, b) {
				return a.viewers < b.viewers;
			});
		}

		/**
   * Returns the total number of viewer for the given language
   *
   * @param {string?} language
   * @return {Number}
   */

	}, {
		key: "getTotalViewers",
		value: function getTotalViewers(language) {
			var streams = this.getStreams();
			if (language !== undefined && language !== "all") {
				streams = this.getStreams(language);
			}

			if (streams.length === 0) {
				return [];
			}

			return streams.map(function (s) {
				return s.viewers;
			}).reduce(function (a, b) {
				return a + b;
			});
		}

		/**
   * Returns all languages in fetched streams
   *
   * @returns {Array<string>}
   */

	}, {
		key: "getAllStreamsLanguages",
		value: function getAllStreamsLanguages() {
			return Array.from(new Set(this.streams.map(function (s) {
				return s.language;
			})));
		}

		/**
   * Fetches all stream for specified language
   *
   * @param {string?} language
   * @returns {Promise<Object>}
   */

	}, {
		key: "fetchStreams",
		value: function fetchStreams(language) {
			var _this = this;

			return new Promise(function (resolve, reject) {

				if (_this.streams.length !== 0) {
					return resolve(_this.getStreams(language));
				}

				var url = "https://" + location.host + "/api/twitch/games/" + _this.game.id + "/stats/" + _this.id;
				axios({ url: url, headers: _this.headers }).then(function (response) {
					_this.streams = response.data.streams;

					return resolve(_this.getStreams(language));
				}).catch(reject);
			});
		}

		/**
   * Returns the words in all streams' title, along with stats
   *
   * @param {string?} language
   * @returns {Array<Object>}
   */

	}, {
		key: "getTitleStats",
		value: function getTitleStats(language) {
			var streams = this.getStreams(language);
			var stats = [];

			// Flattens the array if words, present in titles, of each streams
			var words = [].concat.apply([], streams.map(function (s) {
				return s.title.split(" ");
			}));

			var totalWords = words.length;
			words.forEach(function (word) {

				var found = stats.find(function (s) {
					return s.word.toLowerCase() === word.toLowerCase();
				});
				if (found !== undefined) {
					found.count += 1;
					found.ratio = (found.count / totalWords * 100).toFixed(3);
				} else {
					stats.push({ word: word, count: 1, ratio: (1 / totalWords * 100).toFixed(3) });
				}
			});

			return stats.sort(function (a, b) {
				return a.ratio < b.ratio;
			});
		}
	}]);

	return GameStat;
}();

module.exports = GameStat;

/***/ }),

/***/ 21:
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// TrashMates - UI
// AUTHOR: TiCubius
// VERSION: 3.10

var UI = function () {
	function UI() {
		_classCallCheck(this, UI);
	}

	_createClass(UI, null, [{
		key: "populateTable",


		/**
   * Populates a table
   *
   * @param {string} HTMLElementID
   * @param {Array<Object>} tableHeader
   * @param {Array<Object>} tableData
   */
		value: function populateTable(HTMLElementID, tableHeader, tableData) {
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
		}

		/**
   * Populates a select
   *
   * @param HTMLElementID
   * @param selectData
   */

	}, {
		key: "populateSelect",
		value: function populateSelect(HTMLElementID, selectData) {

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
		}
	}]);

	return UI;
}();

module.exports = UI;

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ })

/******/ });