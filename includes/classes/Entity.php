<?php 

class Entity{

    private $con, $sqlData;

    public function __construct($con, $input)
    {
        $this->con = $con;

        if(is_array($input)){
            $this->sqlData = $input;
        }else{

            $query = $this->con->prepare("SELECT * FROM entities WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC); 

        }
    } // fin del constructor

    public function getId(){
        return $this->sqlData["id"];
    } // fin de la funcion getID 

    public function getName(){
        return $this->sqlData["name"];
    } // fin de la funcion getName 

    public function getThumbnail(){
        return $this->sqlData["thumbnail"];
    } // fin de la funcion getThumbnail
    
    public function getPreview(){
        return $this->sqlData["preview"];
    } // fin de la funcion getID 

    public function getCategoryId(){
        return $this->sqlData["categoryId"];
    } // fin de la funcion getCategoryId 

    public function getSeasons(){
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id
                                    AND isMovie=0 ORDER BY season, episode ASC"); // ordena season and episode
                                        // en orden ascendente
        $query->bindValue(":id", $this->getId());
        $query->execute();

        $seasons = array();
        $videos = array();

        $currentSeason = null;

        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            if($currentSeason != null && $currentSeason != $row["season"]){
                $seasons[] = new Season($currentSeason, $videos);
                $videos = array();
            }

            $currentSeason = $row["season"];
            $videos[] = new Video($this->con, $row);
        }

        if($videos != 0){
            $seasons[] = new Season($currentSeason, $videos);
        }

        return $seasons;

    } // end the function getSeasons()

}

?>