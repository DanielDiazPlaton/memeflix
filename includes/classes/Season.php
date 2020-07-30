<?php 
class Season {

    private $seasonNumber, $videos;

    public function __construct($seasonNumber, $videos)
    {
        $this->seasonNumber = $seasonNumber;
        $this->videos = $videos;

    } // end the __constructor

    public function getSeasonNumber(){
        return $this->seasonNumber;
    } // and the function getSeasonNumber

    public function getVideos(){
        return $this->videos;
    } // and the function getVideos

} // end the class Season
?>