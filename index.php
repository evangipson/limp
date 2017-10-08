<html>
  <head>
    <title>Evan's Music Player</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --currently-playing-height: 3.5rem;
        }
        body, html {
            margin: 0;
            padding: 0;
            font-family: arial, sans-serif;
        }
        body {
            padding-top: var(--currently-playing-height); /* this lines up with the height of #currentlyPlaying */
        }
        .song {
            display: inline-block;
            text-align: center;
            margin: 0 1rem 1rem 0;
            padding: 3rem 2rem;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.08);
        }
        #currentlyPlaying {
            height: var(--currently-playing-height); /* this lines up with the padding-top of body */
            width: calc(100vw - 4rem);
            background: lightgrey;
            position: absolute;
            top: 0;
            left: 0;
            padding: 0 2rem;
        }
        .song, nav li, #currentlyPlaying i, #progressBar {
            cursor: pointer;
        }
        .song > * {
            pointer-events: none;
        }
        /* Sidebar Navigation */
        nav ul {
            margin: 0;
            padding: 0;
        }
        nav li {
            list-style-type: none;
            display: block;
            width: calc(100% - 2rem);
            border-bottom: 2px lightgrey solid;
            padding: 1rem 1rem;
        }
        nav li:hover, nav li.active {
            background: #ededed;
        }
        nav li:last-child() {
            border-bottom: none;
        }
        #progressBar {
            width: 100%;
            height: 1.5rem;
            margin: 1.25rem 0 0 1rem;
        }
        #songList {
            text-align: center;
        }
        /* Desktop styles */
        @media screen and (min-width:768px) {
            nav, #songList {
                overflow-y: scroll;
                float: left;
            }
            nav {
                width: 15rem;
                height: calc(100vh - var(--currently-playing-height));
                overflow-x: hidden;
            }
            #songList {
                width: calc(100% - 15rem - 4rem);
                height: calc(100vh - var(--currently-playing-height) - 2rem);
                padding: 1rem 2rem;
            }
            #currentlyPlaying i {
                position: relative;
                top: 0.4rem;
            }
            #currentlyPlaying p {
                position: relative;
                top: -0.5rem;
            }
            #progressBar, .play-copy {
                display: inline-block;
            }
            #progressBar {
                position: relative;
                width: 65%;
                height: 1.5rem;
                top: 0.63rem;
            }
            .play-copy {
                width: 30%;
            }
        }
    </style>
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
  </body>
</html>