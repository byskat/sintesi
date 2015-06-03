<?php
    require('../includes/php/db.inc.php');
    require('./includes/php/functions.inc.php');

    if (!empty($_POST['name']) && !empty($_POST['lastName']) && !empty($_POST['birthDay']) && !empty($_POST['email']) && !empty($_POST['username']) && 
    	!empty($_POST['password']) && !empty($_POST['password1']) && !empty($_POST['startYear']) && !empty($_POST['endYear'])){
          
        //Rols dels professors        

        $role = null;
        $orderNumber = null;
        $inserirCentre = false;
        $centerValue = false; 
            
        if(!empty($_POST['orderNumber'])){

            $orderNumber = $_POST['orderNumber'];
            $result = executePreparedQuery($conn, "SELECT * FROM teachersValidations WHERE orderNum = :orderNum", array(':orderNum'=>$_POST['orderNumber']), false);

            //Si ha trobat el nombre
            if ($result != false){           
                
                //Comprobar que conicideixi amb el nom
                
                if($_POST['name'] == $result->name && $_POST['lastName'] == $result->lastName){
                    //Finalment comprovo que el professor no estigui registrat.
                    if($result->used == "no"){

                        executeInsertUpdateQuery($conn, "UPDATE teachersValidations SET used='si' WHERE orderNum =" . $result->orderNum);                        
                        $role = 2;

                        if($_POST['centers'] == "startOption"){
                        	$inserirCentre = true; 
                        }

                    }else{
                        $msg = "Aquest professor ja esta registrat.";
                        $msgColor = 2;
                    }
                    
                }else{
                    $msg = "El nom, cognoms o nombre d'ordre incorrectes.";
                    $msgColor = 2;
                }                
            }else{
                $msg = "El nombre d'ordre no existeix.";
                $msgColor = 2;                          
            }            
        }else{
            //Si no s'ha omplet la casella de num d'ordre de professor el rol es dusuari (1)
            $role = 1;
            //Tambe evaluo la possibilitat que s'hagi descudat aquest camp si s'ha intentat inserir algun camp al centre

            if($_POST['nameCenter'] != "" || $_POST['cityCenter'] != "" || $_POST['zipCenter'] != "" || $_POST['addressCenter'] != ""){
                $msg = "T'has deixat el num de profe";
                $msgColor = 2;              
            }        
        }

        //Role nomes sera diferent a null si no s'ha definit el num d'ordre (valdra 1 alumne) o tot es correcte valdra 2(professor). D'aquesta manera evito insercions si el professor s'equivoca en alguna dada. 
        if(!is_null($role)){

            if($_POST['startYear'] < $_POST['endYear']){

                if($_POST['password'] == $_POST['password1']){                

                    $result = executePreparedQuery($conn, "SELECT * FROM users WHERE username = :userName", array(':userName'=>$_POST['username']), false);
                 
                    //Si count es igual a false vol dir no s'ha trobar l'usuari per tant  l'usuari esta disponible
                        if($result == false){
                            $result = executePreparedQuery($conn, "SELECT * FROM users WHERE email = :email", array(':email'=>$_POST['email']), false);    
                            
                            //Si no existeix el correu electronic insereixo usuari
                            if($result == false){
                                
                                    //Insereixo usuari                                    

                                    $sql = "INSERT INTO `users` (name, lastName, email, role, birthDay, profileImg, password, orderNum, username)
                                            VALUES (:name, :lastName, :email, :role, :birthDay, :profileImg, md5(:pass), :orderNum, :user)";            
                                                  
                                    $arr = array(':name'=>strip_tags(trim($_POST['name'])),
                                                 ':lastName'=>strip_tags(trim($_POST['lastName'])),
                                                 ':email'=>strip_tags(trim($_POST['email'])),
                                                 ':role'=>strip_tags(trim($role)),
                                                 ':birthDay'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $_POST['birthDay']))),
                                                 ':profileImg'=>"./images/profileImages/default.png",
                                                 ':pass'=>$_POST['password'],
                                                 ':orderNum'=>$orderNumber,
                                                 ':user'=>strip_tags(trim($_POST['username']))
                                    );

                                    executeInsertUpdateQuery($conn, $sql, $arr, false); 
                                    
                                    if($inserirCentre == true){
                                        
                                        //Comprovo que el centre que intento inserir no existeixi ja a la base de dades
                              
                                        $result = executePreparedQuery($conn, "SELECT * FROM centers WHERE name = :namecenter", array(':namecenter'=>$_POST['nameCenter']), false);

                                        if($count != false){                                            

                                            //TODO: Comprovar que el centre no existeix a la base de dades.
                                     
                                            $sql = "INSERT INTO `centers` (name, city, zipCode, address) VALUES (:name, :city, :zipCode, :address)";            
                                                              
                                            $arr = array(
                                                  ':name'=>strip_tags(trim($_POST['nameCenter'])),
                                                  ':city'=>strip_tags(trim($_POST['cityCenter'])),
                                                  ':zipCode'=>strip_tags(trim($_POST['zipCenter'])),
                                                  ':address'=>strip_tags(trim($_POST['addressCenter']))
                                            );

                                            executeInsertUpdateQuery($conn, $sql, $arr, false);
                                            $centerValue = strip_tags(trim($_POST['nameCenter']));
                                        }else{

                                            $msg = "El centre ja existeix.";
                                            $msgColor = 2;
                                        }                              

                                    } else {
                                    	$centerValue = $_POST['centers'];
                                    }                                        

                                $msg = "L'usuari s'ha inserit correctement.";
                                $msgColor = 1;                      

                                //Obtinc la del usuari  
                                
                                $result = executeQuery($conn, "SELECT id FROM users WHERE username ='" . $_POST['username'] . "'");
                                $user = $result->id; 

                                //Obtinc la id del centre
                                                       
                                $result = executeQuery($conn, "SELECT id FROM centers WHERE name ='" . $centerValue . "'");
                                $center = $result->id;                        

                                //Insereixo inscripcio
                                
                                $sql = "INSERT INTO `inscriptions` (users_id, centers_id, startYear, endYear) VALUES (:users_id, :centers_id, :startYear, :endYear)";
                                $arr = array(
                                             ':users_id'=> intval($user),
                                             ':centers_id'=> intval($center),
                                             ':startYear'=> strip_tags(trim($_POST['startYear'])),
                                             ':endYear'=> strip_tags(trim($_POST['endYear'])) 
                                             );
                                executeInsertUpdateQuery($conn, $sql, $arr, false);
                                
                            }else{
                               $msg = "El mail ja existeix.";
                               $msgColor = 2;
                            }
                        }else{
                            $msg = "L'usuari ja existeix.";
                            $msgColor = 2;
                        }
                }else{
                    $msg = "Les contrassenyes no coincideixen.";
                    $msgColor = 2;
                }
            }else{
                $msg = "Revisa les dates d'inici i fi";
                $msgColor = 2;
            } 
        }

    }else if(isset($_POST['submit']) ){

        $msg = "Falta algun camp.";
        $msgColor = 2;

    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registre</title>  
        <?php require('./includes/php/header.inc.php'); ?>
    </head>
    <body>
        <div class="imgBg">
          <div class="wrapper">
          </div>
        </div>
        <div class="paddingTop"></div>
        <div class="outerCenter">
            <div id="register" class="container">
                <h1>Registre</h1>
                <div class="subcontainer first">
                    
                    <form action="" method="POST">
                    <p>
                       <input id="name" class="text" type="text" name="name" required placeholder="nom"  />
                    </p>
                    <p>
                       <input id="lastName" class="text" type="text" name="lastName" required placeholder="cognoms" />
                    </p>

                    <p>
                       <input id="datetimepicker" class="text" type="text" name="birthDay" required placeholder="data de naixement" />
                    </p>
                    
                    <p>
                       <input id="password" class="text" type="email" name="email" required placeholder="email" />
                    </p>

                    <p>
                       <input id="username" class="text" type="text" name="username" required placeholder="nom d'usuari" />
                    </p>

                    <p>
                       <input id="startYear" class="text half" type="text" name="startYear" required placeholder="any d'inici" />
                    </p>

                    <p>
                       <input id="endYear" class="text half" type="text" name="endYear" required placeholder="any de fi" />
                    </p>
                 
                    <p>
                       <input id="password" class="text" type="password" name="password" required placeholder="contrasenya" />
                    </p>
                    <p>
                       <input id="password1" class="text" type="password" name="password1" required placeholder="Repetir contrasenya" />
                    </p>
                    <p class="center roleButtons">
                        <input id="teacher" type="radio" name="role" value="teacher">
                        <label for="teacher">Professor</label>
                        <input id="student" type="radio" name="role" value="student">
                        <label for="student">Alumne</label>
                    </p>
                </div>
                <div class="subcontainer second">
                        
                    <p>
                       <input id="orderNumber" class="text teacherInput orderInput" type="text" name="orderNumber" placeholder="nombre d'ordre (nomes professors)" />
                    </p>

                    <p>                   
                        <select class="text addCenterCamp" name="centers" id="centers">
                            <?php echo fillDropDownCenters($conn) ?>
                        </select>                   
                        <span class="addCenterButton">
                            <a><i class="fa fa-plus"></i></a>
                        </span>
                    </p>

                    <p>
                       <input id="nameCenter" class="text teacherInput" type="text" name="nameCenter" placeholder="nom del nou centre" />
                    </p>

                    <p>
                       <input id="cityCenter" class="text teacherInput" type="text" name="cityCenter" placeholder="ciutat del nou centre" />
                    </p>

                    <p>
                       <input id="zipCenter" class="text teacherInput" type="text" name="zipCenter" placeholder="codi postal nou centre" />
                    </p>

                    <p>
                       <input id="adressCenter" class="text teacherInput" type="text" name="addressCenter" placeholder="adressa del nou centre" />
                    </p>             

                    <p class="center">
                        <input class="redButton" type="submit" name="submit" value="Registre" /><br />
                    </p>
                    </form>

                </div>
                
                <div style="clear:both"></div>

                <p class="center loginLink">
                    <a class="link" href="login.php">Login</a>
                </p>
                
                <?php require('./includes/php/showMessage.inc.php'); ?>       

            </div>
            <div style="clear:both"></div>

            <div class="paddingTop"></div>

            <script src="./includes/js/register.js"> </script>              

        </div>
    </body>
</html>
