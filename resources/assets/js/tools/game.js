// TrashMates - Game Toolkit
// VERSION: V3.00
// AUTHOR: TiCubius

streams = []

$(document).ready(() => {

    if ($(location).attr(`hash`) === ``) {
        window.location.href = `https://id.twitch.tv/oauth2/authorize?client_id=46vs5ngk9er091esnyovygnumieu5w5&redirect_uri=http://admin.trashweb.devbox/tools/stalker&response_type=token&force_verify=true`
    }

    let access_token = $(location).attr(`hash`).split(`=`)[1].split(`&`)[0]
    

    $(`#lang`).on("keydown", (e) => {
        let language = $(`#lang`).val()
        let streamsSorted = streams

        if (e.keyCode === 13) {
            if (language !== "all") {
                streamsSorted = streams.filter((e) => {return e.language === language})
            }

            let streamsAnalyzed = arrayCount(streamsSorted.map(e => {return e.viewer_count}))

            populateTable(streamsAnalyzed)
        }
    })

    $(`#game`).on("keydown", (e) => {
        streams = []
        if (e.keyCode === 13) {
            let game = $(`#game`).val()
            $(`#lang`).val("")

            $(`#progessbar`).show()
            getGameInformations([game], access_token).then((games) => {
                getStreamsForGame(games[0].id, access_token).then((streams) => {
                    $(`#progessbar`).hide()
                    table.classList.remove("hidden")
                    
                    streamsAnalyzed = arrayCount(streams.map(e => {return e.viewer_count}))
                    populateTable(streamsAnalyzed)

                }).catch(console.error)
            }).catch(console.error)
        }
    })
})

let getGameInformations = (games, access_token) => {
    let url = `https://api.twitch.tv/helix/games?name=${games.join("&name=")}`
    console.log(`[*] - Fetching ${url}`)

    return new Promise((resolve, reject) => {

        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${access_token}`
            }
        }).then((response) => {
            resolve(response.data.data)
        }).catch(reject)

    })
}

let getStreamsForGame = (game_id, access_token, pagination) => {
    if (pagination === undefined) {
        pagination = ""
    }

    let url = `https://api.twitch.tv/helix/streams?game_id=${game_id}&first=100&after=${pagination}`
    console.log(`[*] - Fetching ${url}`)

    return new Promise((resolve, reject) => {

        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${access_token}`
            }
        }).then((response) => {
            response.data.data.forEach((stream) => {
                streams.push(stream)
            })

            pagination = response.data.pagination.cursor

            if (response.data.data.length >= 100) {
                resolve(getStreamsForGame(game_id, access_token, pagination))
            }
            resolve(streams)

        }).catch(reject)
    })
}

let populateTable = (streams) => {
    
    $("#stats tr").remove()
    let table = document.querySelector(`#stats`)

    streams.forEach((stream) => {                    
        let row = table.insertRow()

        row.insertCell().innerHTML = stream.value
        row.insertCell().innerHTML = stream.count
        row.insertCell().innerHTML = `${stream.percent}%`
        row.insertCell().innerHTML = `${stream.above}%`
        row.insertCell().innerHTML = `${stream.minPosition} - ${stream.maxPosition}`
    })
}

arrayCount = (array) => {

    // FIRST: Sort array
    array = array.sort((a, b) => b-a)

    // THEN: Count

    let countArray = []
    let above = 0
    let maxPosition = array.length
    let minPosition = array.length

    for (let i = 0; i <= array[0]; i++) {
        let count = array.filter((e) => e === i).length
        if (count != 0) {
            let percent = (count / array.length * 100)
            above += percent
            minPosition -= count
            countArray.push({ "value": i, "count": count, "percent": percent.toFixed(2), "above": above.toFixed(2), "maxPosition": maxPosition, "minPosition": minPosition})
            maxPosition -= count
        }
    }

    return countArray
}