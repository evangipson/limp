<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evan Gipson's Music | Powered by LiMP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" type="text/css" href="./style.css?00019" media="screen" />
  </head>
  <body>
    <?php include "./directory-crawl.php"; ?>
    <nav id="albumList">
        <input type="checkbox" id="menu">
        <label for="menu" class="mobile-menu-toggle"><i class="material-icons">menu</i></label>
        <? echo "<ul>"; foreach($albumList as $albumTitle) {echo "<li data-album-filter='".$albumTitle."'>$albumTitle</li>";} echo "</ul>"; ?>
    </nav>
    <section id="songList"><? foreach($songTiles as $tile) {echo $tile;} ?></section>
    <section id="currentlyPlaying">
        <i class="material-icons" id="playStatus">play_circle_outline</i>
        <p class="play-copy">
            <b>Now Playing: </b><span id="currentSong"></span>
        </p>
        <canvas id="progressBar">
            canvas not supported
        </canvas>
    </section>
    <audio id="audioPlayer" src="">
        Your browser does not support the audio format.
    </audio>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./brain.js?00019"></script>
    <div id="browserWarning">
        <p>Sorry!</p>
        <p>Your browser isn't supported! Try using <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>, <a href="https://www.google.com/chrome/browser/desktop/index.html">Chrome</a>, <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">Edge</a>, or <a href="https://www.apple.com/safari/">Safari</a>.</p>
        <p>Or <a href="http://1.bp.blogspot.com/-DdAY10KROWQ/T2idCRGlFQI/AAAAAAAACD8/L5kFXRHaZNY/s1600/NN2_1996_03_netscape-com.jpg" target="_blank">Netscape Navigator</a>.</P>
    </div>
  </body>
</html>