// TrashMates - Game Stat
// AUTHOR: TiCubius
// VERSION: 3.10

class GameStat
{

	/**
	 * Creates a new intance of GameStat
	 *
	 * @param {Game} game
	 * @param {Number} id
	 */
	constructor(game, id)
	{
		this.game = game
		this.id = id

		this.headers = {"token": "tFdUNcvfRK0qvfBU"}
		this.streams = []
	}


	/**
	 * Returns the ID of the stat
	 *
	 * @returns {Number}
	 */
	getId()
	{
		return this.id
	}

	/**
	 * Returns all Streams
	 *
	 * @param {string?} language
	 * @returns {Array<Object>}
	 */
	getStreams(language)
	{
		let streams = this.streams
		if ((language !== undefined) && (language !== `all`)) {
			streams = streams.filter((s) => {
				return s.language === language
			})
		}

		return streams.sort((a, b) => {return a.viewers < b.viewers})
	}

	/**
	 * Returns the total number of viewer for the given language
	 *
	 * @param {string?} language
	 * @return {Number}
	 */
	getTotalViewers(language)
	{
		let streams = this.getStreams()
		if ((language !== undefined) && (language !== `all`))
		{
			streams = this.getStreams(language)
		}

		if (streams.length === 0) {
			return []
		}

		return streams.map((s) => {return s.viewers}).reduce((a, b) => (a+b))
	}

	/**
	 * Returns all languages in fetched streams
	 *
	 * @returns {Array<string>}
	 */
	getAllStreamsLanguages()
	{
		return Array.from(new Set(this.streams.map((s) => s.language)))
	}

	/**
	 * Fetches all stream for specified language
	 *
	 * @param {string?} language
	 * @returns {Promise<Object>}
	 */
	fetchStreams(language)
	{
		return new Promise((resolve, reject) => {

			if (this.streams.length !== 0) {
				return resolve(this.getStreams(language))
			}

			let url = `https://${location.host}/api/twitch/games/${this.game.id}/stats/${this.id}`
			axios({url, headers: this.headers}).then((response) => {
				this.streams = response.data.streams

				return resolve(this.getStreams(language))
			}).catch(reject)

		})
	}


	/**
	 * Returns the words in all streams' title, along with stats
	 *
	 * @param {string?} language
	 * @returns {Array<Object>}
	 */
	getTitleStats(language)
	{
		let streams = this.getStreams(language)
		let stats = []

		// Flattens the array if words, present in titles, of each streams
		let words = [].concat.apply([], streams.map((s) => {return s.title.split(" ")}))

		let totalWords = words.length
		words.forEach((word) => {

			let found = stats.find((s) => {return s.word.toLowerCase() === word.toLowerCase()})
			if (found !== undefined) {
				found.count += 1
				found.ratio = (found.count / totalWords * 100).toFixed(3)
			} else {
				stats.push({word, count: 1, ratio: (1 / totalWords * 100).toFixed(3)})
			}

		})

		return stats.sort((a, b) => {return a.ratio < b.ratio})
	}

}

module.exports = GameStat