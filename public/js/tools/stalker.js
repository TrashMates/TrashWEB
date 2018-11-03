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

// TrashMates - Stalker
// VERSION: V3.00
// AUTHOR: TiCubius

followings = [];

$(document).ready(function () {

    if ($(location).attr("hash") === "") {
        window.location.href = "https://id.twitch.tv/oauth2/authorize?client_id=46vs5ngk9er091esnyovygnumieu5w5&redirect_uri=http://admin.trashweb.devbox/tools/stalker&response_type=token&force_verify=true";
    }

    var access_token = $(location).attr("hash").split("=")[1].split("&")[0];
    $("#channel").on("keydown", function (e) {
        if (e.keyCode === 13) {
            var username = $("#channel").val();

            $("#progessbar").show();
            $("#informations").show();
            $("#informations").html("Fetching " + username + "'s informations...");
            getUsersInformationsFromUsername([username], access_token).then(function (users) {
                var user = users[0];

                $("#informations").html("Fetching " + username + "'s followers...");
                getUserFollowers(user.id, access_token).then(function (followers) {
                    var _loop = function _loop(i) {
                        setTimeout(function () {
                            $("#informations").html("[" + (i + 1) + "/" + followers.length + "] - Fetching " + followers[i].from_name + "'s following...");

                            getUserFollowing(followers[i].from_id, access_token).then(function (following) {
                                followings.push(following);

                                axios.post("https://api.trashweb.devbox/tools/stalk", following).then(console.log).catch(console.error);
                            }).catch(console.error);
                        }, i * 1500);
                    };

                    for (var i = 0; i < followers.length; i++) {
                        _loop(i);
                    }
                }).catch(console.error);
            }).catch(console.error);
        }
    });
});

var getUsersInformationsFromUsername = function getUsersInformationsFromUsername(usernames, access_token, users) {

    if (users === undefined) {
        users = [];
    }

    var url = "https://api.twitch.tv/helix/users?login=" + usernames.splice(0, 2).join('&login=');
    console.log("[*] - Fetching " + url);

    return new Promise(function (resolve, reject) {

        axios.get(url, {
            headers: {
                'Authorization': "Bearer " + access_token
            }
        }).then(function (response) {
            response.data.data.forEach(function (user) {
                users.push(user);
            });

            if (usernames.length >= 1) {
                resolve(getUsersInformationsFromUsername(usernames, access_token, users));
            }
            resolve(users);
        }).catch(reject);
    });
};

var getUserFollowers = function getUserFollowers(userid, access_token, users, pagination) {

    if (users === undefined) {
        users = [];
    }

    if (pagination === undefined) {
        pagination = "";
    }

    var url = "https://api.twitch.tv/helix/users/follows?to_id=" + userid + "&first=100&after=" + pagination;
    console.log("[*] - Fetching " + url);

    return new Promise(function (resolve, reject) {

        axios.get(url, {
            headers: {
                'Authorization': "Bearer " + access_token
            }
        }).then(function (response) {
            response.data.data.forEach(function (user) {
                users.push(user);
            });

            pagination = response.data.pagination.cursor;

            if (users.length < response.data.total) {
                resolve(getUserFollowers(userid, access_token, users, pagination));
            }
            resolve(users);
        }).catch(reject);
    });
};

var getUserFollowing = function getUserFollowing(userid, access_token, users, pagination) {

    if (users === undefined) {
        users = [];
    }

    if (pagination === undefined) {
        pagination = "";
    }

    var url = "https://api.twitch.tv/helix/users/follows?from_id=" + userid + "&first=100&after=" + pagination;
    console.log("[*] - Fetching " + url);

    return new Promise(function (resolve, reject) {

        axios.get(url, {
            headers: {
                'Authorization': "Bearer " + access_token
            }
        }).then(function (response) {
            response.data.data.forEach(function (user) {
                users.push(user);
            });

            pagination = response.data.pagination.cursor;

            if (users.length < response.data.total) {
                resolve(getUserFollowing(userid, access_token, users, pagination));
            }
            resolve(users);
        }).catch(reject);
    });
};

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ })

/******/ });