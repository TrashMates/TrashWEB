// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius

$(document).ready(() => {

	// EVENTS
	$(`#gameAdd`).on(`click`, (e) => {
		e.preventDefault()

		let game_name = prompt("What game would you like to scan ?")
		if (game_name !== null) {
			storeGame(game_name).then((data) => {
				let game = data.data
				addGameToList(game)
			}).catch(console.error)
		}
	})

	/**
	 * POST - Submits the given game to the server, to add id
	 *
	 * @param {string} game
	 * @returns {*|AxiosPromise<any>}
	 */
	let storeGame = (game) => {
		return axios.post(location.href, {name: game})
	}

	/**
	 * Adds a Game to the games list
	 * @param {object} game
	 */
	let addGameToList = (game) => {
		let html =
			`<a class="game" href="${location.href}/${game.id}">
					<img src="${game.picture.replace('{width}', '285').replace('{height}', '380')}">
					<h4>${game.name}</h4>
			</a>`

		$(`.game:last`).after(html);
	}

})