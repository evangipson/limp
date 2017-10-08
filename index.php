<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Evan's Music Player</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" type="text/css" href="./style.css?00003" media="screen" />
  </head>
  <body>
    <?
        $albumList =  array();
        $songTiles = array();
        function dirToOptions($path = __DIR__, $level = 0) {
            global $albumList;
            global $songTiles;
            $items = scandir($path);
            // Take each item in our directory scan and handle it.
            foreach($items as $item) {
                // Ignore items strating with a dot (= hidden or nav)
                if (strpos($item, '.') === 0) {
                    continue;
                }
                $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                // Create our nice "local" path for javascript
                $localPath = array_splice(explode("/", $fullPath), 5);
                $localPath = implode("/", $localPath);
                // and a "friendly" song name
                $songName = array_splice(explode(".", $item), 0)[0];
                // Handle what we find.
                if(is_file($fullPath)) {
                    $album = substr($path, strrpos($path, '/') + 1);
                    if(strpos($item, ".mp3") !== false || strpos($item, ".wav") !== false) {
                        $songTiles[] = "<div class='song' data-song-path='".$localPath."' data-album='".$album."' data-song='".$songName."'><h3>$songName</h3><p>$album</p></div>";
                    }
                }
                else if (is_dir($fullPath)) {
                    $album = substr($fullPath, strrpos($fullPath, '/') + 1);
                    if(in_array($album, $albumList) == false) {
                        $albumList[] = $album;
                    }
                    // recursive call to self to add the subitems
                    dirToOptions($fullPath, $level + 1);
                }
            }
        }
        dirToOptions();
        // Randomize albums and songs before display.
        shuffle($albumList);
        shuffle($songTiles);
    ?>
    <nav id="albumList"><? echo "<ul>"; foreach($albumList as $albumTitle) {echo "<li data-album-filter='".$albumTitle."'>$albumTitle</li>";} echo "</ul>"; ?></nav>
    <section id="songList"><? foreach($songTiles as $tile) {echo $tile;} ?></section>
    <section id="currentlyPlaying">
        <p class="play-copy">
            <i class="material-icons" id="playStatus">play_circle_outline</i> <b>Now Playing: </b><span id="currentSong"></span>
        </p>
        <canvas id="progressBar">
            canvas not supported
        </canvas>
    </section>
    <audio id="audioPlayer" src="">
        Your browser does not support the audio format.
    </audio>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./brain.js?00008"></script>
    <div id="browserWarning"><p>Sorry!</p><p>Your browser isn't supported! Try using <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>, <a href="https://www.google.com/chrome/browser/desktop/index.html">Chrome</a>, <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">Edge</a>, or <a href="https://www.apple.com/safari/">Safari</a>.</p></div>
  </body>
</html>