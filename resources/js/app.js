// TrashWEB
// VERSION: 3.50
// AUTHOR: TiCubius

/**
 * ProgressBar - Displays the progress bar
 */
progressBarShow = () => {
    document.querySelector(`#progressbar`).classList.remove(`d-none`)
}

/**
 * ProgressBar - Hides the progress bar
 */
progressBarHide = () => {
    document.querySelector(`#progressbar`).classList.add(`d-none`)
}

/**
 * When everything is loaded
 */
window.onload = function() {

    progressBarHide()

}

axios.interceptors.request.use((config) => {
    progressBarShow()

    return config
})

axios.interceptors.response.use((response) => {
    progressBarHide()

    return response
})
