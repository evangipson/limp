function sortSongs(albumName) {
    $(".song").hide();
    $(".song[data-album='"+albumName+"']").show();
}

function startSong(url, songTitle) {
    $(".song").removeClass("active");
    var songUrl = "http://evangipson.com/" + url;
    $("#currentSong").text(songTitle);
    document.getElementById("audioPlayer").src = songUrl;
    playSong();
}

function pauseSong() {
    var audioPlayer = document.getElementById("audioPlayer");
    var playButton = document.getElementById("playStatus");
    if (audioPlayer.duration > 0 && !audioPlayer.paused) {
        audioPlayer.pause();
        playButton.innerText = "play_circle_outline";
        $("#playStatus").on("click",function() {
            playSong();
        });
    }
}
function playSong() {
    var audioPlayer = document.getElementById("audioPlayer");
    var pauseButton = document.getElementById("playStatus");
    audioPlayer.play();
    pauseButton.innerText = "pause_circle_outline";
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
        ctx.fillStyle = "rgba(0, 0, 0, 1.0)";
        var fWidth = (elapsedTime / oAudio.duration) * (canvas.clientWidth);
        if (fWidth > 0) {
            ctx.fillRect(0, 0, fWidth, canvas.clientHeight/2);
            ctx.fillStyle = "rgba(0, 0, 0, 0.2)";
            ctx.fillRect(0, 0, canvas.clientWidth, canvas.clientHeight/2);
        }
    }
}

$(document).ready(function() {
    // Apply album art if we have it
    $(".song").each(function() {
        if($(this).attr("data-album-art")) {
            $(this).css("background-image", "url('http://evangipson.com/"+$(this).attr("data-album-art")+"')");
            $(this).css("background-size", "cover");
            // Highlight the text, too
            $(this).children("h2, p").css("background-color", "#597AB3");
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
    });
    // Clicking on songs play songs.
    $(".song").on("click",function(e) {
        startSong(e.target.attributes["data-song-path"].value, e.target.attributes["data-song"].value);
        $(this).addClass("active");
    });
    //set up event to update the progress bar
    document.getElementById("audioPlayer").addEventListener("timeupdate", progressBar, true);
    //set up mouse click to control position of audio
    document.getElementById("progressBar").addEventListener("click", function(e) {
        //this might seem redundant, but this these are needed later - make global to remove these
        var oAudio = document.getElementById('audioPlayer'); 
        var canvas = this;
        canvas.width = canvas.offsetWidth;

        if (!e) {
            e = window.event;
        } //get the latest windows event if it isn't set
        try {
            //calculate the current time based on position of mouse cursor in canvas box
            oAudio.currentTime = oAudio.duration * (e.offsetX / canvas.clientWidth);
        }
        catch (err) {
        // Fail silently but show in F12 developer tools console
            if (window.console && console.error("Error:" + err));
        }
    }, true);
    // If mobile nav option is selected, close the mobile nav.
    $("nav li").on("click", function() {
        document.getElementById("menu").checked = false;
    });
});