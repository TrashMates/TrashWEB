// SHIT - Streamer Helper Initiative Tool
// VERSION: V3.10
// AUTHOR: TiCubius


let Game = require("../Class/Game.js")

$(document).ready(() => {

	let gameid = location.href.split("/")[location.href.split("/").length -1]
	let game = new Game(gameid)


	// BUTTON: Fetch new stats
	$(`#scanNow`).click((e) => {
		$(`#progressbar`).show()
		axios.put(location.href).then((response) => {
			$(`#progressbar`).hide()

			addStreamDate(response.data)
		})
	})

	// BUTTON: Click on a stat
	$(`.js-stat`).click((e) => {

		$(`#progressbar`).show()
		$(`#js-stats`).hide()


		let statid = $(e.currentTarget).data(`statid`)
		let stats = game.findStat(statid)

		// Fetch all streams
		stats.fetchStreams().then((streams) => {

			$(`#progressbar`).hide()
			$(`#settings`).show()
			$(`#streams`).show()

			$(`#languages`).change((e) => {

				let lang = $(e.currentTarget).val()
				let filteredStreams = streams

				if (lang !== `all`) {
					filteredStreams = streams.filter((s) => (s.language === lang))
				}

				// Generate streams table
				let streamTableData = filteredStreams.map((s) => [{class: `center`, content: s.language}, {class: `center`, content: s.channel.username}, {content: s.title}, {class: `center`, content: s.viewers}])
				streamTableData.push([{colspan: 3, class: `center`, content: `TOTAL`}, {class: `center`, content: stats.getStreamsTotalViewers(lang)}])
				populateTable(`streams`, [{class: `center`, content: `language`}, {content: `channel`}, {content: `title`}, {class: `center`, content: `viewers`}], streamTableData)

			})

			// Generate stream select
			let streamSelectLanguages = [{value: `all`, content: `All Languages`}]
			streamSelectLanguages = streamSelectLanguages.concat(stats.getStreamsLanguages().map((l) => {return {value: l, content: l}}))
			populateSelect(`languages`, streamSelectLanguages)

			$(`#languages`).val(`all`).trigger(`change`)
		})

	})

	$(`#back`).click((e) => {
		e.preventDefault()

		$(`#js-stats`).show()
		$(`#streams`).hide()
		$(`#settings`).hide()
	})


	let addStreamDate = (stat) => {
		location.reload(true)
	}

	/**
	 * Populate a table with the given data
	 * @param HTMLElementID
	 * @param tableHeader
	 * @param tableData
	 */
	let populateTable = (HTMLElementID, tableHeader, tableData) => {

		// Remove every child of the HTMLElement
		let HTMLElement = $(`#${HTMLElementID}`)
		HTMLElement.text(` `)

		// OPTIMIZATION: Hide HTMLElement
		HTMLElement.hide()

		// Generate HTML - Table Header
		let HTML = `<thead>`
		tableHeader.forEach((column) => {
			let properties = Object.keys(column).filter((property) => (property !== "content"))

			HTML += `<th `
			properties.forEach((p) => {HTML += `${p}="${column[p]}"`})
			HTML += `>${column.content}</th>`
		})
		HTML += `</thead>`

		// Generate HTML - Table Body
		HTML += `<tbody>`
		tableData.forEach((row) => {
			HTML += `<tr>`
			row.forEach((column) => {
				let properties = Object.keys(column).filter((property) => (property !== "content"))

				HTML += `<td `
				properties.forEach((p) => {HTML += `${p}="${column[p]}"`})
				HTML += `>${column.content}</td>`
			})
			HTML += `</tr>`
		})
		HTML += `</tbody>`

		// Fill the HTMLElement
		HTMLElement.html(HTML)

		// OPTIMIZATION: Shows the HTMLElement
		HTMLElement.show()

	}

	/**
	 * Populate a select with the given data
	 * @param HTMLElementID
	 * @param selectData
	 */
	let populateSelect = (HTMLElementID, selectData) => {

		// Remove every child of the HTMLElement
		let HTMLElement = $(`#${HTMLElementID}`)
		HTMLElement.text(` `)

		// OPTIMIZATION: Hide HTMLElement
		HTMLElement.hide()

		// Generate HTML - Options
		let HTML = ``
		selectData.forEach((option) => {
			let properties = Object.keys(option).filter((property) => (property !== "content"))

			HTML += `<option `
			properties.forEach((p) => {HTML += `${p}="${option[p]}"`})
			HTML += `>${option.content}</option>`
		})

		// Fill the HTMLElemnt
		HTMLElement.html(HTML)

		// OPTIMIZATION: Show the HTMLElement
		HTMLElement.show()

	}

})