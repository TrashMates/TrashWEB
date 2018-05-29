// TrashMates - JWPlayer Stream
// VERSION: 1.00
// AUTHOR: TiCubius <trashmates@protonmail.com>

var playerInstance = jwplayer("stream");
playerInstance.setup({
    "sources": [{
        "file": "https://live.trashmates.fr/TiCubius.m3u8",
        "label": "GOOD",
        "type": "hls"
    }]
});