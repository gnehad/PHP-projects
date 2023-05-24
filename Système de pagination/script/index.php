<?php
    $booksPerPage = 5;
    $serverName = "localhost";
    $user = "root";
    $password = "";
    $database = "bdsystempagination"; 

    $connection = new mysqli($serverName, $user, $password, $database);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    //Déterminons combien d'enregistrements de livre contient la table Livre
    $numberOfBooksQuery = "SELECT COUNT(*) AS count FROM Livre";
    $result = $connection->query($numberOfBooksQuery);
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $numberOfBooks = $row["count"];
        $numberOfPages = ceil($numberOfBooks / $booksPerPage);
        
        //Initilisation du numéro de page : la page courante par défaut la première page
    $currentPage = 1;
    $lastPage = $numberOfPages;
    if(isset($_GET["page"])){
        $currentPage = intval($_GET["page"]);
        if($currentPage > $numberOfPages){
            $currentPage = $lastPage;
            }
        }
    for($index = 1; $index < $numberOfPages; $index++){
        echo "<a href=\"index.php?page=$index\">Page$index|</a>";
    }
    echo "<br>";
    $start = ($currentPage - 1) * $booksPerPage;
    $selectQuery = "SELECT * FROM Livre LIMIT $start, $booksPerPage";
    $resultBooks= $connection->query($selectQuery );
    echo "Page actuelle : $currentPage<br>";
    echo "Numéro du premier enregistrement : $start<br>";
    echo"<table>";
    echo"<tr>";
    echo"<th>Titre</th>";
    echo"<th>Auteur</th>";
    echo"</tr>";
    
    while($row = $resultBooks->fetch_assoc()){
        $title = $row["titre"];
        $author = $row["auteur"];
        echo "<tr>";
        echo "<td>$title</td>";
        echo"<td>$author</td>";
        echo"</tr>";
        //echo "Titre : ".."\n<br>"."Auteur : ".$row["auteur"]."\n<br>...................\n<br>";
    }  
    echo "</table>";
    }else{
        echo "Aucun résultat n'est trouvé";
    }
    $connection->close();