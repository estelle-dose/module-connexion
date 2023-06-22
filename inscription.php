<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $login = $_POST["login"];
    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    
    // Vérifier si les mots de passe correspondent
    if ($password === $confirmPassword) {
        // Connexion à la base de données
        $host = "localhost";
        $dbname = "moduleconnexion";
        $username = "root";
        $passwordDB = "";
        
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Insérer les données dans la table utilisateurs
            $query = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$login, $prenom, $nom, $password]);
            
            // Redirection vers la page de connexion
            header("Location: connexion.php");
            exit;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link href="css/inscription.css" rel="stylesheet"/>
    <title>Inscription</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <h2>Inscription</h2>
                    
                    <div class="inputbox">
                        <ion-icon name="sparkles-outline"></ion-icon>
                        <input type="text" id="login" name="login" required>
                        <label for="login">Login :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" id="prenom" name="prenom" required>
                        <label for="prenom">Prénom :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="home-outline"></ion-icon>
                        <input type="text" id="nom" name="nom" required>
                        <label for="nom">Nom :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="password" required>
                        <label for="password">Mot de passe :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <label for="confirmPassword">Confirmer le mot de passe :</label>
                    </div>
                    
                    <div class="button">
                        <input type="submit" value="S'inscrire">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
