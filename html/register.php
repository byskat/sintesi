<?php
    require('../includes/php/db.inc.php');
<<<<<<< HEAD
    require('../includes/php/validation.inc.php');                        
    
    //Generar select de centres
    
    $options = "";
    $options .= "<option value='startOption' selected disabled >Selecciona un centre</option>";
    $sql = "SELECT name FROM centers";
    $query = $conn->query($sql);
    $results = $query->fetchAll(PDO::FETCH_OBJ);                            

    foreach ($results as $result) {                                
        $options .= "<option value='" . $result->name . "'>". $result->name . "</option>";
    } 

    

    if (!empty($_POST['name']) && !empty($_POST['lastName']) && !empty($_POST['birthDay']) && !empty($_POST['email']) && 
        !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['password1']) && !empty($_POST['startYear']) && 
        !empty($_POST['endYear']) && $_POST['centers'] != "startOption"){
       
        /* Array amb les dades del registre. Per poder recuperar les dades.
        $updateValues = array(
            "connId" => $formId,
            "connName" => $connName,
            "connCenters"  => $nameCenter1 . " & " . $connCenter2Name,
            "endDate" => $connEndDate,
            "nameCenter2" => $connCenter2Name
        );
        */

        function formatDate($dateString){
            $newDate = DateTime::createFromFormat('d/m/Y', $dateString);    
            return $newDate->format('Y-m-d');
        }
        
=======
    require('./includes/php/functions.inc.php');

    if (!empty($_POST['name']) && !empty($_POST['lastName']) && !empty($_POST['birthDay']) && !empty($_POST['email']) && !empty($_POST['username']) && 
    	!empty($_POST['password']) && !empty($_POST['password1']) && !empty($_POST['startYear']) && !empty($_POST['endYear'])){
          
>>>>>>> origin/victor-forum
        //Rols dels professors        

        $role = null;
        $orderNumber = null;
        $inserirCentre = false;
        $centerValue = false; 
            
        if(!empty($_POST['orderNumber'])){

            $orderNumber = $_POST['orderNumber'];
            $result = executePreparedQuery($conn, "SELECT * FROM teachersValidations WHERE orderNum = :orderNum", array(':orderNum'=>$_POST['orderNumber']));

            //Si ha trobat el nombre
            if ($result != false){           
                
                //Comprobar que conicideixi amb el nom
                
                if($_POST['name'] == $result->name && $_POST['lastName'] == $result->lastName){
                    //Finalment comprovo que el professor no estigui registrat.
                    if($result->used == "no"){

                        executeUpdateQuery($conn, "UPDATE teachersValidations SET used='si' WHERE orderNum =" . $result->orderNum);                        
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

                    $result = executePreparedQuery($conn, "SELECT * FROM users WHERE username = :userName", array(':userName'=>$_POST['username']));
                 
                    //Si count es igual a false vol dir no s'ha trobar l'usuari per tant  l'usuari esta disponible
                        if($result != false){
                            $result = executePreparedQuery($conn, "SELECT * FROM users WHERE email = :email", array(':email'=>$_POST['email']));    

                            //Si no existeix el correu electronic insereixo usuari
                            if($count == false){
                                
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

                                    executeInsertQuery($conn, $sql, $arr); 
                                    
                                    if($inserirCentre == true){
                                        
                                        //Comprovo que el centre que intento inserir o existeixi ja a la base de dades
                              
                                        $result = executeQuery($conn, "SELECT * FROM centers WHERE name ='" . $_POST['nameCenter'] . "'");

                                        if($count == false){                                            

                                            //TODO: Comprovar que el centre no existeix a la base de dades.
                                     
                                            $sql = "INSERT INTO `centers` (name, city, zipCode, address) VALUES (:name, :city, :zipCode, :address)";            
                                                              
                                            $arr = array(
                                                  ':name'=>strip_tags(trim($_POST['nameCenter'])),
                                                  ':city'=>strip_tags(trim($_POST['cityCenter'])),
                                                  ':zipCode'=>strip_tags(trim($_POST['zipCenter'])),
                                                  ':address'=>strip_tags(trim($_POST['addressCenter']))
                                            );

                                            executeInsertQuery($conn, $sql, $arr);
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
                                executeInsertQuery($conn, $sql, $arr);
                                
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
<<<<<<< HEAD
        <title>Registre</title>        

        <link rel="stylesheet" type="text/css" href="./includes/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./includes/css/jquery-ui-1.11.4/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="./includes/css/style.css" />
        <link rel="stylesheet" type="text/css" href="./includes/fonts/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="./includes/datetimepicker/jquery.datetimepicker.css"/>

        <script src="./includes/js/jquery-2.1.4/jquery-2.1.4.js"></script>
        <script src="./includes/js/jquery-ui-1.11.4/jquery-ui.js"></script>
        <script src="./includes/datetimepicker/jquery.datetimepicker.js"></script>

        
=======
        <title>Registre</title>  
        <?php require('./includes/php/header.inc.php'); ?>
>>>>>>> origin/victor-forum
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
<<<<<<< HEAD
                       <input id="name" class="text" type="text" name="name" required placeholder="nom" value="<?php echo $_POST['name'] ?>" />
=======
                       <input id="name" class="text" type="text" name="name" required placeholder="nom"  />
>>>>>>> origin/victor-forum
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
                        
<<<<<<< HEAD
                    <p>                   
                        <select class="text addCenterCamp" name="centers" id="centers">
                            <?php echo $options ?>
=======
                    <p>
                       <input id="orderNumber" class="text teacherInput orderInput" type="text" name="orderNumber" placeholder="nombre d'ordre (nomes professors)" />
                    </p>

                    <p>                   
                        <select class="text addCenterCamp" name="centers" id="centers">
                            <?php echo fillDropDownCenters($conn) ?>
>>>>>>> origin/victor-forum
                        </select>                   
                        <span class="addCenterButton">
                            <a><i class="fa fa-plus"></i></a>
                        </span>
                    </p>

                    <p>
<<<<<<< HEAD
                       <input id="orderNumber" class="text teacherInput" type="text" name="orderNumber" placeholder="nombre d'ordre (nomes professors)" />
                    </p>

                    <p>
=======
>>>>>>> origin/victor-forum
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
<<<<<<< HEAD
                    </p>

                    <!--<p class="center">
                        <input type="button" class="btnFoto" name="btnFoto" value="sel·lcciona la foto" onclick="getFile()">
                        <input type="file" id="profileImg" name="profileImg" class="selectFile">
                    </p>-->
=======
                    </p>             
>>>>>>> origin/victor-forum

                    <p class="center">
                        <input class="redButton" type="submit" name="submit" value="Registre" /><br />
                    </p>
                    </form>
<<<<<<< HEAD
=======

>>>>>>> origin/victor-forum
                </div>
                
                <div style="clear:both"></div>

                <p class="center loginLink">
                    <a class="link" href="login.php">Login</a>
                </p>
<<<<<<< HEAD

            </div>
            <div style="clear:both"></div>

            <div class="paddingTop"></div>

            <script>

            var flagAddCenter=true;

                $( document ).ready(function() {
                    $(".teacherInput").hide();
                    $("input[name='role']").click(function(){
                        
                        $(".teacherInput").hide();
                        $(".addCenterButton").html("<a><i class='fa fa-plus'></i></a>");
                        $(".addCenterCamp").prop('disabled', false);
                        flagAddCenter=true;

                        if($(this).val()=="teacher"){
                            $(".addCenterButton").show();
                            $(".text#centers").addClass("addCenterCamp");

                        } else {
                            $(".addCenterButton").hide();
                            $(".text#centers").removeClass("addCenterCamp");
                        }

                        $(".second").css({
                            "margin-left": "0",
                            "z-index":"0"
                        });
                    });
                    
                    $(".addCenterButton").click(function(){
                        if(flagAddCenter){
                            $(".addCenterButton").html("<a><i class='fa fa-chevron-down'></i></a>");
                            $(".addCenterCamp").val('startOption');
                            $(".teacherInput").show();
                            $(".addCenterCamp").prop('disabled', true);
                            flagAddCenter=false;
                        } else {
                            $(".addCenterButton").html("<a><i class='fa fa-plus'></i></a>");
                            $(".teacherInput").hide();
                            $(".addCenterCamp").prop('disabled', false);
                            flagAddCenter=true;
                        }
                        
                    });

                });

                jQuery('#datetimepicker').datetimepicker({
                    timepicker:false,
                    format:'d/m/Y',
                    lang:'ca'
                });

                /*function getFile(){
                    document.getElementById("profileImg").click();
                }*/

            </script>

            <div class="msgBox">
                <p><?php if(isset($msg) & !empty($msg)){ 
                    echo $msg; ?> 
                    <script> 
                        $('.msgBox').addClass('activeMsg', 1000, "easeOutBounce"); 
                    <?php 
                    if($msgColor==1){ ?> $('.msgBox').addClass('green'); $('.msgBox').removeClass('red'); <?php }
                    if($msgColor==2){ ?> $('.msgBox').addClass('red'); $('.msgBox').removeClass('green'); <?php }
                    ?> </script> <?php } ?>
                </p>
            </div>

=======
                
                <?php require('./includes/php/showMessage.inc.php'); ?>       

            </div>
            <div style="clear:both"></div>

            <div class="paddingTop"></div>

            <script src="./includes/js/register.js"> </script>

               

>>>>>>> origin/victor-forum
        </div>
    </body>
</html>
