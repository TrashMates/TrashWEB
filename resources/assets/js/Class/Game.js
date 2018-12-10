let GameStat = require("./GameStat.js")

class Game
{

	/**
	 * Game
	 * @param id
	 */
	constructor(id)
	{
		this.id = id
		this.name = null
		this.picture = null

		this.stats = []
		this.headers = {"token": "tFdUNcvfRK0qvfBU"}

		this.fetch()
		this.fetchStats()
	}


	/**
	 * Fetches all game information
	 * @returns {Promise<any>}
	 */
	fetch()
	{
		return new Promise((resolve, reject) => {
			if (this.name !== null) {
				return resolve({"id": this.id, "name": this.name, "picture": this.picture})
			}

			axios({url: `https://${location.host}/api/twitch/games/${this.id}`, headers: this.headers}).then((response) => {
				this.name = response.data.name
				this.picture = response.data.picture

				return resolve({"id": this.id, "name": this.name, "picture": this.picture})
			}).catch(reject)
		})
	}

	/**
	 * Fetches all stats for the game
	 * @returns {Promise<any>}
	 */
	fetchStats()
	{
		return new Promise((resolve, reject) => {
			if (this.stats.length > 0) {
				return resolve(this.stats)
			}

			axios({url: `https://${location.host}/api/twitch/games/${this.id}/stats`, headers: this.headers}).then((response) => {
				response.data.stats.forEach((retrievedStat) => {
					this.stats.push(new GameStat(this, retrievedStat.id))
				})

				return resolve(this.stats)
			}).catch(reject)
		})
	}

	/**
	 *
	 * @param id
	 * @return {GameStat}
	 */
	findStat(id)
	{
		return this.stats.find((s) => {return (s.id === id)})
	}
	
}

module.exports = Game