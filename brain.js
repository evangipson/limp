function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function sortSongs(albumName) {
    $(".song").hide();
    $(".song").removeClass("visible");
    $(".song[data-album='"+albumName+"']").show();
    $(".song[data-album='"+albumName+"']").addClass("visible");
}

function toggleSongPlay(url, songTitle) {
    var audioPlayer = document.getElementById("audioPlayer");
    var songUrl = "http://evangipson.com/" + url;
    if($("#currentSong").text() === songTitle) {
        if (audioPlayer.duration > 0 && !audioPlayer.paused) {
            $(".active .song-play-icon").text("play_circle_outline");
            pauseSong();
        }
        else {
            $(".active .song-play-icon").text("pause_circle_outline");
            playSong();
        }
    }
    else {
        document.getElementById("audioPlayer").src = songUrl;
        $("#currentSong").text(songTitle);
        $(".active .song-play-icon").text("pause_circle_outline");
        playSong();
    }
}

// Get the next non-hidden song and play it!
// TODO: Update query string with autoplay maybe?
function playNextSong() {
}

function pauseSong() {
    var audioPlayer = document.getElementById("audioPlayer");
    var playButton = document.getElementById("playStatus");
    if (audioPlayer.duration > 0 && !audioPlayer.paused) {
        playButton.innerText = "play_circle_outline";
        audioPlayer.pause();
        // Allow playing!
        $("#playStatus").on("click",function() {
            playSong();
        });
    }
}

function playSong() {
    var audioPlayer = document.getElementById("audioPlayer");
    var pauseButton = document.getElementById("playStatus");
    pauseButton.innerText = "pause_circle_outline";
    audioPlayer.play();
    // Allow pausing!
    $("#playStatus").on("click",function() {
        pauseSong();
    });
}

//display and update progress bar
function progressBar() { 
    var oAudio = document.getElementById('audioPlayer');
    var canvas = document.getElementById("progressBar");
    canvas.width = canvas.offsetWidth;
    //get current time in seconds
    var elapsedTime = Math.round(oAudio.currentTime);
    //update the progress bar
    if (canvas.getContext) {
        var ctx = canvas.getContext("2d");
        //clear canvas before painting
        ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
        ctx.fillStyle = "rgba(255, 255, 255, 0.7)";
        var fWidth = (elapsedTime / oAudio.duration) * (canvas.clientWidth);
        if (fWidth > 0) {
            ctx.fillRect(0, 0, fWidth, canvas.clientHeight);
            ctx.fillStyle = "rgba(0, 0, 0, 0.1)";
            ctx.fillRect(0, 0, canvas.clientWidth, canvas.clientHeight);
        }
    }
}

$(window).on('load', function() {
    // Autoplay if we have the query string
    /*if(getParameterByName("ap") === true) {
        playSong();
    }*/
    // Apply album art if we have it
    $(".song").each(function() {
        if($(this).attr("data-album-art")) {
            $(this).css("background-image", "url('http://evangipson.com/"+$(this).attr("data-album-art")+"')");
            $(this).css("background-size", "cover");
            // Highlight the text, too
            $(this).children("h2, p").css("background-color", "#202020");
        }
    })
    // Clicking on the album list filters the songs.
    $("nav li").on("click",function(e) {
        if($(this).hasClass("active")) {
            $(this).removeClass("active");
            // Clear our filter
            $(".song").show();
        }
        else {
            $("nav li").removeClass("active");
            $(this).addClass("active");
            sortSongs(e.target.attributes["data-album-filter"].value);
        }
        // If mobile nav option is selected, close the mobile nav.
        document.getElementById("menu").checked = false;
        // Always scroll the document to the top.
        $(document).scrollTop(0);
        // Also scroll the songlist to the top.
        $("#songList").scrollTop(0);
        // Set the background to the album image
        $(".large-bg").css("background-image", "url('http://evangipson.com/"+$(".song.visible").attr("data-album-art")+"')");
    });
    // Clicking on songs play songs.
    $(".song").on("click",function(e) {
        // Clear active state besides on this one.
        $(".song").removeClass("active");
        $(this).addClass("active");
        // Handle either playing or pausing the song.
        // NOTE: I really don't like passing in title this way, but it has to do for now since
        // the PHP handles all the regex.
        toggleSongPlay(e.target.attributes["data-song-path"].value, e.target.children[0].innerText);
        // Update hash without history
        var songName = e.target.attributes["data-song"].value;
        var songAlbum = e.target.attributes["data-album"].value;
        history.replaceState(null, null, document.location.pathname + '#' + songName + ":" + songAlbum);
    });
    // set up event to update the progress bar
    $("#audioPlayer").on("timeupdate", progressBar);
    // If someone lets a song play all the way, start the next one!
    $("#audioPlayer").on("ended", function() {
        /* If we have an active song in the list, start the next song.
        * Otherwise, the user has navigated away. Let's play a more relevant song.
        * (Or loop around to the first song). */
        $nextVisibleSong = $(".active").nextAll('.song:visible').first();
        $nextVisibleSong.length ? $nextVisibleSong.click() : $("#songList").find(".song:visible").first().click();
    });
    // set up mouse click to control position of audio
    document.getElementById("progressBar").addEventListener("click", function(e) {
        // this might seem redundant, but this these are needed later - make global to remove these
        var oAudio = document.getElementById('audioPlayer'); 
        var canvas = this;
        canvas.width = canvas.offsetWidth;

        if (!e) {
            e = window.event;
        } // get the latest windows event if it isn't set
        try {
            //calculate the current time based on position of mouse cursor in canvas box
            oAudio.currentTime = oAudio.duration * (e.offsetX / canvas.clientWidth);
        }
        catch (err) {
        // Fail silently but show in F12 developer tools console
            if (window.console && console.error("Error:" + err));
        }
    }, true);
    // Now check for a fragment in the hash and click that song if there is one.
    var songHash = window.location.hash.substr(1);
    if(songHash) {
        var songAndAlbum = songHash.split(":");
        var songName = songAndAlbum[0];
        var albumName = songAndAlbum[1];
        var $matchedSong = $(".song[data-song='" + songName + "'][data-album='" + albumName + "']");
        // Try to play the song!
        $matchedSong.click();
        // Automatically filter that album.
        $("#albumList li[data-album-filter='" + albumName + "']").click();
        // Move that song to be first, so the user sees it.
        $matchedSong.prependTo("#songList");
    }
    else {
        // Pre-select the latest album to make sure to not show the listener everything at once.
        $("#albumList ul li").first().click();
    }
});