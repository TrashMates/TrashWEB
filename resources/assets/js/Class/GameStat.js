class GameStat {

	/**
	 * Stat
	 * @param {Game} game
	 * @param {number} id
	 */
	constructor(game, id) {
		this.id = id

		this.game = game
		this.streams = []

		this.headers = {"token": "tFdUNcvfRK0qvfBU"}
	}

	/**
	 * Fetches all streams for the stat
	 * @returns {Promise<any>}
	 */
	fetchStreams() {
		return new Promise((resolve, reject) => {

			if (this.streams.length > 0) {
				return resolve(this.streams)
			}

			axios({url: `https://${location.host}/api/twitch/games/${this.game.id}/stats/${this.id}`, headers: this.headers}).then((response) => {
				this.streams = response.data.streams

				return resolve(this.streams)
			}).catch(reject)

		})
	}

	/**
	 * Returns the total of viewers for the given lang
	 * @param {string} lang
	 * @returns {int}
	 */
	getStreamsTotalViewers(lang)
	{
		if (this.streams.length === 0) {
			return 0
		}

		if (lang !== "all") {
			let streams = this.streams.filter((s) => {return s.language === lang}).map((s) => (s.viewers))
			return streams.length > 0 ? streams.reduce((a, b) => (a + b)) : 0
		}

		let streams = this.streams.map((e) => (e.viewers))
		return streams.length > 0 ? this.streams.map((s) => (s.viewers)).reduce((a, b) => (a + b)) : 0
	}

	/**
	 * Returns all streams languages & remove duplicates
	 * @returns {(string | *[])[]}
	 */
	getStreamsLanguages()
	{
		return Array.from(new Set(this.streams.map((s) => s.language)))
	}

}

module.exports = GameStat