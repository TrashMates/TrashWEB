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
/***/ (function(module, exports) {

// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius

$(document).ready(function () {

	// EVENTS
	$("#scanNow").on("click", function (e) {
		e.preventDefault();

		$("#progressbar").show();
		scanGame().then(function (data) {
			$("#progressbar").hide();
			window.location.reload(true);
		}).catch(console.error);
	});

	$(".js-stat-date").on("click", function (e) {
		e.preventDefault();
		var statid = $(e.currentTarget).data("statid");

		$(".js-stat").hide();
		$("#stat-" + statid).show();

		$("#words").show();
		populateTable("words", analyzeTitles(statid));
	});

	/**
  * PUT - Submits a PUT|PATCH request to the server
  * which will launch a scan of the game right now, on Twitch
  *
  * @returns {*|AxiosPromise<any>}
  */
	var scanGame = function scanGame() {
		return axios.put(location.href);
	};

	/**
  * Analyze all titles of a scan
  * @param scanid
  */
	var analyzeTitles = function analyzeTitles(scanid) {
		var titles = [];
		var words = [];
		var wordsMap = {};

		// Get all titles in Array
		Array.from($("#stat-" + scanid + " .title")).forEach(function (title) {
			titles.push($(title).text().toLowerCase());
		});

		// Split all words
		titles.forEach(function (title) {
			title.split(/\s+/).forEach(function (word) {
				words.push(word);
			});
		});

		// Word Map
		words.forEach(function (word) {
			if (wordsMap.hasOwnProperty(word)) {
				wordsMap[word] += 1;
			} else {
				wordsMap[word] = 1;
			}
		});

		return wordsMap;
	};

	/**
  *
  * @param {string} tableid
  * @param {Object} data
  */
	var populateTable = function populateTable(tableid, data) {
		$("#" + tableid + " tbody").text(" ");

		Object.keys(data).map(function (word) {
			var HTML = "<tr>\n\t\t\t\t\t<td>" + word + "</td>\n\t\t\t\t\t<td>" + parseInt(data[word]) + "</td>\n\t\t\t\t</tr>";

			$("#" + tableid + " tbody").append(HTML);
		});
	};
});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ })

/******/ });