<?php 

class PreviewProvider {

    private $con, $username;

    public function __construct($con, $username)
    {
        $this->con = $con;
        $this->username = $username;
    }  // fin del constructor

    public function createCategoryPreviewVideo($categoryId) {
        $entitiesArray = EntityProvider::getEntities($this->con, $categoryId, 1);

        if(sizeof($entitiesArray) == 0){
            ErrorMessage::show("No TV Shows to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);

    } // createTVShowPreviewVideo()

    public function createTVShowPreviewVideo() {
        $entitiesArray = EntityProvider::getTVShowEntities($this->con, null, 1);

        if(sizeof($entitiesArray) == 0){
            ErrorMessage::show("No TV Shows to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);

    } // createTVShowPreviewVideo()

    public function createMoviesPreviewVideo() {
        $entitiesArray = EntityProvider::getMoviesEntities($this->con, null, 1);

        if(sizeof($entitiesArray) == 0){
            ErrorMessage::show("No Movies to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);

    } // createMoviesPreviewVideo()


    public function createPreviewVideo($entity){

        if($entity == null){

            $entity = $this->getRandomEntity();

        }

        $id = $entity->getId();
        $name = $entity->getName();
        $preview = $entity->getPreview();
        $thumbnail = $entity->getThumbnail();


        $videoId = VideoProvider::getEntityVideoForUser($this->con, $id, $this->username);
        $video = new Video($this->con, $videoId);
        
        $inProgress = $video->isInProgress($this->username);
        $playButtonText = $inProgress ? "Continue watching" : "Play";

        $seasonEpisode = $video->getSeasonAndEpisode();
        $subHeading = $video->isMovie() ? "" : "<h4>$seasonEpisode</h4>";

        return "<div class='previewContainer'>

                    <img src='$thumbnail' class='previewImage' hidden>

                    <video autoplay muted class='previewVideo' onended='previewEnded()'>

                        <source src='$preview' type='video/mp4'>

                    </video>

                    <div class='previewOverlay'>
                    
                        <div class='mainDetails'>

                            <h3>$name</h3>
                            $subHeading
                            <div class='buttons'>

                                <button onclick= 'watchVideo($videoId)'><i class='fas fa-play'></i> $playButtonText</button>
                                <button onclick='volumeToggle(this)'><i class='fas fa-volume-mute'></i></button>

                            </div>

                        </div>

                    </div>

                </div>";

    } // Fin de la funcion createPreviewVideo

    public function createEntityPreviewSquad($entity){
        $id = $entity->getId();
        $thumbnail = $entity->getThumbnail();
        $name = $entity->getName();

        return "<a href='entity.php?id=$id'>
                    <div class='previewContainer small'>
                        <img src='$thumbnail' title='$name'>
                    </div> 
                </a>";
    } // Fin de la funcion createEntityPreviewSquad 

    /* Funcion que hace que la vista previa sea random (aleatoria) */
    private function getRandomEntity(){

        /**
         * ORDER BY RAND() es una funcion de mysql para generar cnosulta aleatoria y 
         * LIMIT 1 -> es para que solo traiga una consulta a la vez y no mas
         */
        // $query = $this->con->prepare("SELECT *FROM entities ORDER BY RAND() LIMIT 1");
        // $query->execute();

        /**
         * Traigo la consulta de los nombres, fetch para uno solo y fetchAll para todas las columnas
         */
        // $row = $query->fetch(PDO::FETCH_ASSOC);
        
        // return new Entity($this->con, $row); // ejecuto la clase de las entidades que me permite 
        // con las funciones getID, getName, getPreview y getThumbnail obtener los datos de la tabla entities
        // de manera mas ordenada

        $entity = EntityProvider::getEntities($this->con, null, 1);
        return $entity[0];

    } // Fin de la funcion getRandomEntity

}

?>