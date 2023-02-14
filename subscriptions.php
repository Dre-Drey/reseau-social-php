<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnements</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include 'header.php'
    ?>
    <div id="wrapper">
        <aside>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes dont
                    l'utilisatrice
                    n° <?php echo intval($_GET['user_id']) ?>
                    suit les messages
                </p>

            </section>
        </aside>
        <main class='contacts'>
            <?php
            $userId = intval($_GET['user_id']);
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            while ($user = $lesInformations->fetch_assoc()) {
            ?>
                <article>
                    <img src="user.jpg" alt="blason" />
                    <h3><a href="./wall.php?user_id=<?php echo $user["id"]?>"><?php echo $user["alias"] ?></a></h3>
                    <p><?php echo $user["id"] ?></p>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>