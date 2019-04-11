// TrashMates - GameStat
// AUTHOR: TiCubius
// VERSION: V3.10

let GameStat = require("./GameStat.js")

class Game
{

	/**
	 * Creates a new instance of Game
	 * @param {Number} id
	 */
	constructor(id)
	{
		this.id = id
		this.name = null
		this.picture = null

		this.stats = []

		this.headers = {"token": "tFdUNcvfRK0qvfBU"}
		this.fetchGame()
	}

	/**
	 * Fetches all game informations
	 *
	 * @returns {Promise<Object>}
	 */
	fetchGame()
	{
		return new Promise((resolve, reject) => {

			if (this.name !== null) {
				return resolve({"id": this.id, "name": this.name, "picture": this.picture})
			}

			let url = `https://${location.host}/api/twitch/games/${this.id}`
			axios({url, headers: this.headers}).then((response) => {
				this.name = response.data.name
				this.picture = response.data.picture

				return resolve({"id": this.id, "name": this.name, "picture": this.picture})
			}).catch(reject)

		})
	}

	/**
	 *
	 * @param {Number?} id
	 */
	getStats(id)
	{
		let stats = this.stats
		if (id !== undefined) {
			stats = stats.find((s) => {return s.getId() === id})
		}

		return stats
	}

	/**
	 * Fetches all stats for the game
	 *
	 * @param {Number?} id
	 * @returns {Promise<any>|Promise<GameStat>}
	 */
	fetchStats(id)
	{
		return new Promise((resolve, reject) => {

			if (this.stats.length > 0) {
				return resolve(this.getStats(id))
			}

			axios({url: `https://${location.host}/api/twitch/games/${this.id}/stats`, headers: this.headers}).then((response) => {
				response.data.stats.forEach((stat) => {
					this.stats.push(new GameStat(this, stat.id))
				})

				return resolve(this.getStats(id))
			}).catch(reject)

		})
	}

}

module.exports = Game
