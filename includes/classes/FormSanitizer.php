<?php 
// Definicion de clase que le da formato a el registro
class FormSanitizer {

    public static function sanitizeFormString($inputText){

        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        // $inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;

    } // Fin de la funcion sanitizeFormString

    public static function sanitizeFormUsername($inputText){
        
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;

    } // Fin de la funcion sanitizeFormUsername

    public static function sanitizeFormPassword($inputText){
        
        $inputText = strip_tags($inputText);
        return $inputText;

    } // Fin de la funcion sanitizeFormPassword

    public static function sanitizeFormEmail($inputText){
        
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;

    } // Fin de la funcion sanitizeFormEmail


} // Fin del clase FormSanitizer


?>