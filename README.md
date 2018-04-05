# LiMP
*LiMP* is a Custom (Li)ghtweight (M)usic (P)layer, written in PHP and JavaScript. It streams music from a server, and gives a nice interface for you to navigate and filter your music, all on a single page without any reloading!

# Structure
LiMP relies on a certain structure to generate the list of music:
```
LiMP
│   index.php
│   brain.js
│   style.css
|   directory-crawl.php
|
└───musicFolder1
│   │   musicFile1.mp3
│   │   musicFile2.mp3
|   |   albumArt1.png
│   │
│   └───subfolder1
│       │   musicFile3.mp3
│       │   musicFile4.wav
|       |   albumArtAlt.bmp
│       │   ...
│   
└───musicFolder2
    │   musicFile5.mp3
    │   musicFile6.wav
    |   albumArt2.jpg
```

# TODO
Here's some stuff I plan to do. You can get the full list on the [Issues page for LiMP](https://github.com/evangipson/limp/issues).
- Add ability for hash in URL to load site and start playing song
