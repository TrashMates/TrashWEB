// TrashMates - JWPlayer Stream
// VERSION: 1.00
// AUTHOR: TiCubius <trashmates@protonmail.com>

var playerInstance = jwplayer("stream");
playerInstance.setup({
	sources: [{
		file: "https://live.trashmates.fr/live.m3u8"
	}]
});
