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

// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius

$(document).ready(function () {

	// EVENTS
	$('#gameAdd').on('click', function (e) {
		e.preventDefault();

		var game_name = prompt("What game would you like to scan ?");
		if (game_name !== null) {
			storeGame(game_name).then(function (data) {
				var game = data.data;
				addGameToList(game);
			}).catch(console.error);
		}
	});

	/**
  * POST - Submits the given game to the server, to add id
  *
  * @param {string} game
  * @returns {*|AxiosPromise<any>}
  */
	var storeGame = function storeGame(game) {
		return axios.post(location.href, { name: game });
	};

	/**
  * Adds a Game to the games list
  * @param {object} game
  */
	var addGameToList = function addGameToList(game) {
		var html = '<a class="game" href="' + location.href + '/' + game.id + '">\n\t\t\t\t\t<img src="' + game.picture.replace('{width}', '285').replace('{height}', '380') + '">\n\t\t\t\t\t<h4>' + game.name + '</h4>\n\t\t\t</a>';

		$('.game:last').after(html);
	};
});

/***/ })

/******/ });