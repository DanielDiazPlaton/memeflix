<?php 
class Video {
    private $con, $sqlData, $entity;

    public function __construct($con, $input)
    {
        $this->con = $con;

        if(is_array($input)){
            $this->sqlData = $input;
        }else{

            $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC); 

        }

        $this->entity = new Entity($con, $this->sqlData["entityId"]);
    } // fin del constructor

    public function getId(){
        return $this->sqlData["id"];
    } // end the function getId 

    public function getTitle(){
        return $this->sqlData["title"];
    } // end the function getTitle 

    public function getDescription(){
        return $this->sqlData["description"];
    } // end the function getDescription() 

    public function getFilePath(){
        return $this->sqlData["filePath"];
    } // end the function getFilePath 

    public function getThumbnail(){
        return $this->entity->getThumbnail();
    } // end the function getThumbnail() 

    public function getEpisodeNumber(){
        return $this->sqlData["episode"];
    } // end the function getEpisodeNumber()

    public function getSeasonNumber(){
        return $this->sqlData["season"];
    } // end the function getSeasonNumber()

    public function getEntityId(){
        return $this->sqlData["entityId"];
    } // end the function getEntityId()

    public function incrementViews() {
        $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $query->bindValue(":id", $this->getId());
        $query->execute();
    }  // end the function incrementViews() 

    public function getSeasonAndEpisode() {
        if($this->isMovie()){
            return;
        }

        $season = $this->getSeasonNumber();
        $episode = $this->getEpisodeNumber();

        return "Season $season, Episode $episode";
    } // end the function getSeasonAndEpisode()

    public function isMovie() {
        return $this->sqlData["isMovie"] == 1;
    }// end the function isMovie() 

    public function isInProgress($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress 
                                        WHERE videoId=:videoId AND username=:username");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;

    } // end the isInProgress()

    public function hasSeen($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress 
                                        WHERE videoId=:videoId AND username=:username
                                        AND finished=1");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;
    } // end the hasSeen()

} // end the class Video

?>