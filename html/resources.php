<?php
    require('../includes/php/db.inc.php');
    require('./includes/php/userValidation.inc.php');
    require('./includes/php/functions.inc.php');

    if(!isset($_GET["hiddenTeamId1"])){    
        header('Location: ./login.php');
    }

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equips</title>        
    <?php require('./includes/php/header.inc.php'); ?>
</head>
<body>

    <?php
        require('./includes/php/topBar.inc.php');
        require('./includes/php/leftMenu.inc.php');        
    ?>    

    <div class="panel">

        <div class="itemHeader">
            <div class="itemTitle">
                <h1>Equip <?php echo "#" . $_GET['hiddenTeamId1']. " " .$_GET['hiddenTeamName'] ?></h1>
            </div>
        </div>

        <div class="itemList">

            <div class="itemIcon">
                <form action="forum/main_forum.php" method="GET">
                    <button class="shadowBox">
                        <input name="teamId" type="hidden" value="<?php echo $_GET['hiddenTeamId1'] ?>">
                        <input name="teamName" type="hidden" value="<?php echo $_GET['hiddenTeamName'] ?>">
                        <div class="iconImg"><i class="fa fa-comments"></i></div>
                        <div>Forum</div>
                    </button>
                </form>
            </div>

        </div>

        <div class="paddingTop"></div>
        <form action="teams.php" method="GET">
            <input name="toTeamsProjId" id="toTeamsProjId" type="hidden" value="<?php echo $_SESSION['toTeamsProjId'] ?>">
            <input name="toTeamsDesc" id="toTeamsDesc" type="hidden" value="<?php echo $_SESSION['toTeamsDesc'] ?>">
            <button class="backButton"><i class="fa fa-arrow-left"></i></button>
        </form>
    </div>
    
    <script type="text/javascript" src="./includes/js/functions.inc.js"></script>
    
</body>
</html>