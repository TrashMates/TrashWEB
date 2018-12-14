// TrashMates - GAME SCAN INITIATIVE
// AUTHOR: TiCubius
// VERSION: 3.10

let Game = require(`../Class/Game.js`)
let $UI = require(`../Class/UI.js`)

$(document).ready(() => {

	let game_id = location.href.split(`/`)[location.href.split(`/`).length - 1]
	let game = new Game(game_id)

	// EVENT: Stat choosen
	$(`.js-stat`).click((e) => {

		// UI Elements
		$(`.js-stat`).toggle()
		$(`#progressbar`).toggle()

		// Fetching
		let stat_id = $(e.currentTarget).data(`statid`)
		game.fetchStats(stat_id).then((stat) => {

			stat.fetchStreams().then(() => {

				// UI Element
				$(`#progressbar`).toggle()
				$(`#settings`).toggle()
				$(`#streams`).toggle()
				$(`#words`).toggle()

				// Change languages
				$(`#languages`).change((e) => {

					let language = $(e.currentTarget).val()
					stat.fetchStreams(language).then((streams) => {

						// Generate Streams data
						let totalViewers = stat.getTotalViewers(language)
						let streamsTableHead = [{content: `language`}, {content: `channel`}, {content: `title (${streams.length} streams)`}, {content: `viewers (${totalViewers})`}, {content: `ratio`}]
						let streamsTableBody = streams.map((s) => {
							return [{class: `center`, content: s.language}, {class: `center`, content: s.channel.username}, {content: s.title}, {class: `center`, content: s.viewers}, {class: `center`, content: `${(s.viewers / totalViewers * 100).toFixed(3)}%`}]
						})
						$UI.populateTable(`streams`, streamsTableHead, streamsTableBody)

						// Generate Words data
						let wordsTableHead = [{content: `word`}, {content: 'count'}, {content: `ratio`}]
						let wordsTableBody = stat.getTitleStats(language).map((s) => {
							return [{class: `center`, content: s.word}, {class: `center`, content: s.count}, {class: `center`, content: `${s.ratio}%`}]
						})
						$UI.populateTable(`words`, wordsTableHead, wordsTableBody)

					}).catch(console.error)

				}).trigger(`change`)


				// Fetch languages
				let languages = [{content: `all`}]
				languages = languages.concat(stat.getAllStreamsLanguages().map((l) => {
					return {content: l}
				}))

				// Populate languages Select
				$UI.populateSelect(`languages`, languages)

			})

		}).catch(console.error)


	})

	// EVENT: Fetch new stats
	$(`#scanNow`).click((e) => {
		$(`#progressbar`).toggle()
		axios.put(location.href).then(() => {

			$(`#progressbar`).toggle()
			location.reload(true)

		})
	})

	// EVENT: Go back to stats list
	$(`#back`).click((e) => {
		$(`.js-stat`).toggle()
		$(`#settings`).toggle()
		$(`#streams`).toggle()
		$(`#words`).toggle()
	})
})
