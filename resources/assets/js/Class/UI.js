// TrashMates - UI
// AUTHOR: TiCubius
// VERSION: 3.10

class UI {

	/**
	 * Populates a table
	 *
	 * @param {string} HTMLElementID
	 * @param {Array<Object>} tableHeader
	 * @param {Array<Object>} tableData
	 */
	static populateTable(HTMLElementID, tableHeader, tableData) {
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
			properties.forEach((p) => {
				HTML += `${p}="${column[p]}"`
			})
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
				properties.forEach((p) => {
					HTML += `${p}="${column[p]}"`
				})
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
	 * Populates a select
	 *
	 * @param HTMLElementID
	 * @param selectData
	 */
	static populateSelect(HTMLElementID, selectData) {

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
			properties.forEach((p) => {
				HTML += `${p}="${option[p]}"`
			})
			HTML += `>${option.content}</option>`
		})

		// Fill the HTMLElemnt
		HTMLElement.html(HTML)

		// OPTIMIZATION: Show the HTMLElement
		HTMLElement.show()

	}

}

module.exports = UI