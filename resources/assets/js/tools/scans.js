// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius

$(document).ready(() => {

	// EVENTS
	$(`#scanNow`).on(`click`, (e) => {
		e.preventDefault()

		$("#progressbar").show()
		scanGame().then((data) => {
			$("#progressbar").hide()
			window.location.reload(true)
		}).catch(console.error)
	})

	$(`.js-stat-date`).on(`click`, (e) => {
		e.preventDefault()
		let statid = $(e.currentTarget).data("statid")

		$(`.js-stat`).hide()
		$(`#stat-${statid}`).show()

		$(`#words`).show()
		populateTable(`words`, analyzeTitles(statid))
	})

	/**
	 * PUT - Submits a PUT|PATCH request to the server
	 * which will launch a scan of the game right now, on Twitch
	 *
	 * @returns {*|AxiosPromise<any>}
	 */
	let scanGame = () => {
		return axios.put(location.href)
	}

	/**
	 * Analyze all titles of a scan
	 * @param scanid
	 */
	let analyzeTitles = (scanid) => {
		let titles = []
		let words = []
		let wordsMap = {}

		// Get all titles in Array
		Array.from($(`#stat-${scanid} .title`)).forEach((title) => {
			titles.push($(title).text().toLowerCase())
		})

		// Split all words
		titles.forEach((title) => {
			title.split(/\s+/).forEach((word) => {
				words.push(word)
			})
		})

		// Word Map
		words.forEach((word) => {
			if (wordsMap.hasOwnProperty(word)) {
				wordsMap[word] += 1
			} else {
				wordsMap[word] = 1
			}
		})

		return wordsMap
	}

	/**
	 *
	 * @param {string} tableid
	 * @param {Object} data
	 */
	let populateTable = (tableid, data) => {
		$(`#${tableid} tbody`).text(` `)

		Object.keys(data).map((word) => {
			let HTML =
				`<tr>
					<td>${word}</td>
					<td>${parseInt(data[word])}</td>
				</tr>`

			$(`#${tableid} tbody`).append(HTML)
		})
	}

})