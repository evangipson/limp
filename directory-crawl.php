<? 
$albumList = array();
$songTiles = array();
function hashFriendlyName($songName) {
    // Lower case everything
    $songName = strtolower($songName);
    // Make colons dashes so that URL hashes on ":" work
    $songName = preg_replace("/[:]/", "-", $songName);
    // Make commas dashes so that URL hashes on "," work
    $songName = preg_replace("/[,]/", "-", $songName);
    // Convert whitespaces and underscore to dash
    $songName = preg_replace("/[\s_]/", "-", $songName);
    // Clean up multiple dashes or whitespaces
    $songName = preg_replace("/[\s-]+/", "-", $songName);
    return $songName;
}
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
        // Get timestamp from item
        $stat = stat($fullPath);
        // Handle what we find.
        if(is_file($fullPath)) {
            // Find album (this is different for files and songs)
            $album = substr($path, strrpos($path, '/') + 1);
            // Handle music files
            if(strpos($item, ".mp3") !== false || strpos($item, ".wav") !== false) {
                $friendlyAlbumName = hashFriendlyName($album);
                $friendlySongName = hashFriendlyName($songName);
                $songTiles[] = "<div class='song' ".($albumArtPicture !== false ? "data-album-art='".$albumArtPicture."'" : "")." data-song-path='".$localPath."' data-album='".$friendlyAlbumName."' data-song='".$friendlySongName."'>
                    <h2>$songName</h2>
                    <p>$album</p>
                    <p>".date("Y",$stat['mtime'])."</p>
                    <div class='song-play-button'>
                        <i class='material-icons song-play-icon'>play_circle_outline</i>
                    </div>
                </div>";
            }
        }
        else if (is_dir($fullPath)) {
            // Find album (this is different for files and songs)
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
// Sort albums by last modified
usort($albumList, function($a, $b) {
    return filemtime($a) < filemtime($b);
});
// Randomize songs before display.
// shuffle($songTiles);
?>