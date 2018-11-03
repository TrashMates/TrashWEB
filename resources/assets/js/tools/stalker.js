// TrashMates - Stalker
// VERSION: V3.00
// AUTHOR: TiCubius

followings = []

$(document).ready(() => {

    if ($(location).attr(`hash`) === ``) {
        window.location.href = `https://id.twitch.tv/oauth2/authorize?client_id=46vs5ngk9er091esnyovygnumieu5w5&redirect_uri=http://admin.trashweb.devbox/tools/stalker&response_type=token&force_verify=true`
    }

    let access_token = $(location).attr(`hash`).split(`=`)[1].split(`&`)[0]
    $(`#channel`).on(`keydown`, (e) => {
        if (e.keyCode === 13) {
            let username = $(`#channel`).val()

            $(`#progessbar`).show()
            $("#informations").show()
            $("#informations").html(`Fetching ${username}'s informations...`)
            getUsersInformationsFromUsername([username], access_token).then((users) => {
                let user = users[0]

                $("#informations").html(`Fetching ${username}'s followers...`)
                getUserFollowers(user.id, access_token).then((followers) => {

                    for(let i = 0; i < followers.length; i++) {
                        setTimeout(() => {
                            $("#informations").html(`[${i+1}/${followers.length}] - Fetching ${followers[i].from_name}'s following...`)

                            getUserFollowing(followers[i].from_id, access_token).then((following) => {
                                followings.push(following)
                                
                                axios.post("https://api.trashweb.devbox/tools/stalk", following).then(console.log).catch(console.error)

                            }).catch(console.error)

                        }, i*1500)
                    }
                    
                }).catch(console.error)
            }).catch(console.error)
        }
    })

})

let getUsersInformationsFromUsername = (usernames, access_token, users) => {

    if (users === undefined) {
        users = []
    }

    let url = `https://api.twitch.tv/helix/users?login=${usernames.splice(0, 2).join('&login=')}`
    console.log(`[*] - Fetching ${url}`)

    return new Promise((resolve, reject) => {

        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${access_token}`
            }
        }).then((response) => {
            response.data.data.forEach((user) => {
                users.push(user)
            })

            if (usernames.length >= 1) {
                resolve(getUsersInformationsFromUsername(usernames, access_token, users))
            }
            resolve(users)

        }).catch(reject)

    })
}

let getUserFollowers = (userid, access_token, users, pagination) => {

    if (users === undefined) {
        users = []
    }

    if (pagination === undefined) {
        pagination = ""
    }

    let url = `https://api.twitch.tv/helix/users/follows?to_id=${userid}&first=100&after=${pagination}`
    console.log(`[*] - Fetching ${url}`)

    return new Promise((resolve, reject) => {

        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${access_token}`
            }
        }).then((response) => {
            response.data.data.forEach((user) => {
                users.push(user)
            })

            pagination = response.data.pagination.cursor

            if (users.length < response.data.total) {
                resolve(getUserFollowers(userid, access_token, users, pagination))
            }
            resolve(users)

        }).catch(reject)

    })
}

let getUserFollowing = (userid, access_token, users, pagination) => 
{

    if (users === undefined) {
        users = []
    }

    if (pagination === undefined) {
        pagination = ""
    }

    let url = `https://api.twitch.tv/helix/users/follows?from_id=${userid}&first=100&after=${pagination}`
    console.log(`[*] - Fetching ${url}`)

    return new Promise((resolve, reject) => {

        axios.get(url, {
            headers: {
                'Authorization': `Bearer ${access_token}`
            }
        }).then((response) => {
            response.data.data.forEach((user) => {
                users.push(user)
            })

            pagination = response.data.pagination.cursor

            if (users.length < response.data.total) {
                resolve(getUserFollowing(userid, access_token, users, pagination))
            }
            resolve(users)

        }).catch(reject)

    })
}
