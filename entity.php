<?php 

require_once("includes/header.php");

if(!isset($_GET["id"])){ // Si no  pasa ningun valor de la variable GET
    ErrorMessage::show("No ID passed into page"); // No carga el demas codigo de este script
}

$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo($entity);

$seasonProvider = new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->create($entity);  // pass the parameters to the entity page, just the movie id

$categoryContainers = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainers->showCategory($entity->getCategoryId(), "You might also like");  // pass the parameters to the entity page, just the movie id
?>