<?php
    $action = $_POST['action'];
    $path = $_POST['path']."lyrics";
    $chosenLyric = $_POST['chosen'];
    $max = $_POST['max'];
    function chooseLyric($lineChosen) {
        global $path;
        global $chosenLyric;
        global $max;
        
        $max = $max - 1;
        $chosenFile = $_POST['song'];

        $chosenSong = ucfirst(str_replace(".txt","",str_replace("-"," ",$chosenFile)));
        $chosen = file_get_contents($path."/".$chosenFile);

        $lyrics = $chosen;
        $path = $_POST['path'];
        $lyrics = explode(",", nl2br($lyrics));
        $title = array_slice($lyrics,0);
        $lyrics = array_splice($lyrics,1);
        $lyricCount= count($lyrics);
        $lineChosen = $chosenLyric + $lineChosen;
        echo "
            <div id='lyric-data' data-path='".$path."' data-lyric-song='".$chosenFile."' data-lyric-current='".$lineChosen."' data-lyric-count='".$lyricCount."'></div>
            <h3>".$chosenSong." - ".$title[0]."</h3>";
        if($lineChosen === 0) {}
        else {
            echo "<div class='lyric-prev'><img src='".$path."/img/prev-arrow.png'/></div>";
        }
            
            echo "<p class='lyric'>".$lyrics[ $lineChosen ]."</p>";
        if($lineChosen == $max) {}
        else {
            echo "<div class='lyric-next'><img src='".$path."/img/next-arrow.png'/></div>";
        }
    }
    if($action === "next") {
        chooseLyric(+1);
    }
    if($action === "prev") {
        chooseLyric(-1);
    }
