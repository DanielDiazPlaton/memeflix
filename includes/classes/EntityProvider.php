<?php 

class EntityProvider{

    public static function getEntities($con, $categoryId, $limit){

        $sql = "SELECT * FROM entities ";

        if($categoryId != null){
            $sql .= "WHERE categoryId=:categoryId ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";  // el .= concatena los resultados que se van generando

        // le agrega el string de la consulta
        $query = $con->prepare($sql);

        if($categoryId != null){
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $result[] = new Entity($con, $row);
        }

        return $result;  // Traigo la ejecucion de la clase Entity con los parametros que le proporciono esta clase

    } // fin de la funcion statica getEntities

}

?>