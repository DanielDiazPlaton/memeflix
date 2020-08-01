<?php 

class Account {

    private $con;
    private $errorArray = array(); // En este array almacenare los mensajes de error, los cuales
    // en cada funcion de esta clase los ire anexando

    /* ===============CONSTRUCTOR====================== */

    public function __construct($con) {

        $this->con = $con;
        
    } // Fin de la funcion __construct

    public function updateDetails($fn, $ln, $em, $un) {

        $this->validateFirstName($fn); // This hace referencia a la funcion que pertenece a esta clase
        $this->validateLastName($ln);
        $this->validateNewEmails($em, $un);

        if(empty($this->errorArray)){
            $query = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em 
                                            WHERE username=:un");
            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":em", $em);
            $query->bindValue(":un", $un);

            return $query->execute();

        }

        return false;
    } // end the function updateDetails()

    /* ===============      register      ====================== */

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2){

        // Paso los parametros de esta funcion a los parametros de validacion de las otras funciones
        $this->validateFirstName($fn); // This hace referencia a la funcion que pertenece a esta clase
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)){ // Si no hay error en los datos se ejecuta la funcion de insertar los datos

            return $this->insertUserDetails($fn, $ln, $un, $em, $pw); 

        }

        return false;

    } // Fin de la funcion register




    /* ===============      login      ====================== */

    public function login($un, $pw){

        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("SELECT * FROM  users WHERE username=:un AND password=:pw");

        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 1){
            return true;
        }

        array_push($this->errorArray, Constants::$loginFailed);
        return false;

        
    }  // fin de la funcion login




    /* ===============           insertUserDetails           ====================== */

    private function insertUserDetails($fn, $ln, $un, $em, $pw){  // inserto los valores a la base de datos

        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password)
                                        VALUES (:fn, :ln, :un, :em, :pw)");

        $query->bindValue(":fn", $fn);
        $query->bindValue(":ln", $ln);
        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw);

        // para debugear las conusltas sin ejecutarlas se utiliza este codigo
        // $query->execute();
        // var_dump($query->error_Info());

        return $query->execute();

        //y este tambien
        //return false;

    } // fin de funcion insertUserDetails


    /* ===============VALIDATEFIRSTNAME====================== */

    private function validateFirstName($fn){

        if(strlen($fn) < 2 || strlen($fn) > 30){

            array_push($this->errorArray, Constants::$firstNameCharacters);  // Guardo la constante del error en
            // este array
        }

    } // Fin de la funcion validateFirstName

    /* ===============VALIDATELASTNAME====================== */

    private function validateLastName($ln){

        if(strlen($ln) < 2 || strlen($ln) > 30){

            array_push($this->errorArray, Constants::$lastNameCharacters);
        }

    } // Fin de la funcion validateLastName

    /* ===============VALIDATEUSERNAME====================== */

    private function validateUsername($un){

        if(strlen($un) < 2 || strlen($un) > 30){

            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un", $un);

        $query->execute();

        if($query->rowCount() != 0){

            array_push($this->errorArray, Constants::$usernameTaken);

        }

    } // Fin de la funcion validateUsername



    /* ===============validateEmails====================== */

    public function validateEmails($em, $em2){

        // Si no es igual el email con el email de confirmacion
        if($em != $em2){

            array_push($this->errorArray, Constants::$emailsDontMatch);
            return;

        }

        // Filtro el email si no tiene .com o formato de correo
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){

            array_push($this->errorArray, Constants::$emailInvalid);
            return;

        }

        // Consulta para saber si el email ya esta registrado en la base de datos
        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();

        if($query->rowCount() != 0){

            array_push($this->errorArray, Constants::$emailTaken);

        }

    } // Fin de la funcion validateEmails

    public function validateNewEmails($em, $un){

        // Filtro el email si no tiene .com o formato de correo
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){

            array_push($this->errorArray, Constants::$emailInvalid);
            return;

        }

        // Consulta para saber si el email ya esta registrado en la base de datos
        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em AND username != :un");
        $query->bindValue(":em", $em);
        $query->bindValue(":un", $un);


        $query->execute();

        if($query->rowCount() != 0){

            array_push($this->errorArray, Constants::$emailTaken);

        }

    } // Fin de la funcion validateEmails




    /* ===============validatePassword====================== */

    private function validatePasswords($pw, $pw2){

        // Si no es igual el password con el password de confirmacion
        if($pw != $pw2){
            array_push($this->errorArray, Constants::$passwordsDontMatch);
            return;
        }

        if(strlen($pw) < 5 || strlen($pw) > 30){
            array_push($this->errorArray, Constants::$passwordLength);
            return;
        }

    } // Fin de la funcion validatePassword



    /* ===============getError====================== */

    public function getError($error){ // Esta funcion me permite mandar a solicitar el array en la pagina register

        if(in_array($error, $this->errorArray)){

            return "<span class='errorMessage'>$error</span>";

        }

    } // Fin de la funcion getError()

    public function getFirstError() {
        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }
    } // end the function getFirstError()

    public function updatePassword($oldPw, $pw, $pw2, $un) {
        $this->validateOldPassword($oldPw, $un);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)){
            $query = $this->con->prepare("UPDATE users SET password=:pw WHERE username=:un");
            $pw = hash("sha512", $pw);
            $query->bindValue(":pw", $pw);
            $query->bindValue(":un", $un);

            return $query->execute();

        }

        return false;
    } // end the function updatePassword()

    public function validateOldPassword($oldPw, $un) {
        $pw = hash("sha512", $oldPw);

        $query = $this->con->prepare("SELECT * FROM  users WHERE username=:un AND password=:pw");

        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 0){
            array_push($this->errorArray, Constants::$passwordIncorrect);
        }
    } // end the function validateOldPassword()

} // Fin de la clase Account

?>