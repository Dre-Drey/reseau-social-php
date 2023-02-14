<?php
session_start();
if (isset($_SESSION['connected_id'])) {
$userId = intval($_SESSION['connected_id']);
} else {header("location:login.php");
    exit();
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Flux</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include 'header.php'
    ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        ?>

        <aside>
            <?php
            
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
          
            ?>
            <img src="./licorne-arc-en-ciel-licorne.jpeg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les post des utilisatrices
                    auxquel est abonnée l'utilisatrice <?php echo $user["alias"] ?>
                </p>

            </section>
        </aside>

        <main>

        <!-- Formulaire des likes -->
        <?php $enCoursDeTraitement = isset($_POST['like']);
                if ($enCoursDeTraitement) {
                    // on ne fait ce qui suit que si un formulaire a été soumis.
                    // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                    // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                    echo "<pre>" . print_r($_POST, 1) . "</pre>";
                    // et complétez le code ci dessous en remplaçant les ???
                    $new_like = $_POST['like'];
                }
                $new_like = $mysqli->real_escape_string($new_like);
                $lInstructionSql = "INSERT INTO likes (id, user_id, post_id) "
                        . "VALUES (NULL, "
                        . "'" . $userId . "', "
                        . "'" . $new_like . "'"
                        . ");";
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                echo "L'inscription a échouée : " . $mysqli->error;
                        } else {
                            echo "Votre inscription est un succès : " . $new_alias;
                            echo " <a href='login.php'>Connectez-vous.</a>";
                        }
        ?>
            <?php
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.user_id,
                    posts.id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()) {
            ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post["created"] ?></time>
                    </h3>
                    <address>par <a href ="./wall.php?user_id=<?php echo $post["user_id"]?>"><?php echo $post["author_name"] ?></a></address>
                    <div>
                        <p><?php echo $post["content"] ?></p>
                    </div>
                    <footer>
                        <small>
                        <form action="feed.php" method="post">
                            <input type="hidden" name="like" value="<?php echo $post["id"] ?>">
                            <input type="submit" name="submit" value="♥ <?php echo $post["like_number"] ?>">
                        </form>
                        </small>
                        <?php print_r($post); ?>
                        <a href=""><?php echo "#" . $post["taglist"] ?></a>
                    </footer>
                </article>
            <?php
            } ?>


        </main>
    </div>
</body>

</html>