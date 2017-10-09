<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evan Gipson's Music | Powered by LiMP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" type="text/css" href="./style.css?00014" media="screen" />
  </head>
  <body>
    <?
        $albumList =  array();
        $songTiles = array();
        function dirToOptions($path = __DIR__, $level = 0) {
            global $albumList;
            global $songTiles;
            $albumArtPicture = false;
            $fullPath = "";
            $items = scandir($path);
            // First try and find album art.
            foreach($items as $item) {
                $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                if(is_file($fullPath)) {
                    // Handle album art
                    if(strpos($item, ".jpg") !== false || strpos($item, ".jpeg") !== false || strpos($item, ".png") !== false || strpos($item, ".bmp") !== false) {
                        $albumArtPicture = array_splice(explode("/", $fullPath), 5);
                        $albumArtPicture = implode("/", $albumArtPicture);
                    }
                }
            }
            // Take each item in our directory scan and handle it.
            foreach($items as $item) {
                // Ignore items starting with a dot (= hidden or nav)
                if (strpos($item, '.') === 0) {
                    continue;
                }
                $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                // Create our nice "local" path for javascript
                $localPath = array_splice(explode("/", $fullPath), 5);
                $localPath = implode("/", $localPath);
                // and a "friendly" song name
                $songName = array_splice(explode(".", $item), 0)[0];
                // Get timestamp from album
                $stat = stat($fullPath);
                // Handle what we find.
                if(is_file($fullPath)) {
                    $album = substr($path, strrpos($path, '/') + 1);
                    // Handle music files
                    if(strpos($item, ".mp3") !== false || strpos($item, ".wav") !== false) {
                        $songTiles[] = "<div class='song' ".($albumArtPicture !== false ? "data-album-art='".$albumArtPicture."'" : "")." data-song-path='".$localPath."' data-album='".$album."' data-song='".$songName."'>
                            <h2>$songName</h2>
                            <p>$album</p>
                            <p>".date("Y",$stat['mtime'])."</p>
                            <div class='song-play-button'>
                                <i class='material-icons'>play_circle_outline</i>
                            </div>
                        </div>";
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
    <script src="./brain.js?00014"></script>
    <div id="browserWarning">
        <p>Sorry!</p>
        <p>Your browser isn't supported! Try using <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>, <a href="https://www.google.com/chrome/browser/desktop/index.html">Chrome</a>, <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">Edge</a>, or <a href="https://www.apple.com/safari/">Safari</a>.</p>
        <p>Or <a href="http://1.bp.blogspot.com/-DdAY10KROWQ/T2idCRGlFQI/AAAAAAAACD8/L5kFXRHaZNY/s1600/NN2_1996_03_netscape-com.jpg" target="_blank">Netscape Navigator</a>.</P>
    </div>
  </body>
</html>