<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Redirection vers la page de profil de l'utilisateur connecté
    header("Location: profil.php");
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $login = $_POST["login"];
    $password = $_POST["password"];
    
    // Connexion à la base de données
    $host = "localhost";
    $dbname = "moduleconnexion";
    $username = "root";
    $passwordDB = "";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Vérifier les informations de connexion dans la table utilisateurs
        $query = "SELECT id FROM utilisateurs WHERE login = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$login, $password]);
        
        if ($stmt->rowCount() == 1) {
            // L'utilisateur est connecté avec succès
            $_SESSION["loggedin"] = true;
            $_SESSION["login"] = $login;
            
            // Redirection vers la page admin
            if ($login== 'admin' && $password== 'admin'){
                header("Location: admin.php");
            }
            // Redirection vers la page de profil de l'utilisateur connecté
            else{
                header("Location: profil.php");
            }
            
            exit;
        } else {
            // Identifiants de connexion invalides
            $loginError = "Identifiants de connexion invalides.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link href="css/connexion.css" rel="stylesheet"/>
    <title>Connexion</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
    
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <h2>Connexion</h2>
                    
                    <div class="inputbox">
                        <ion-icon name="sparkles-outline"></ion-icon>
                        <input type="text" id="login" name="login" required>
                        <label for="login">Login :</label>
                    </div>
                        
                    <div class="inputbox">  
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="password" required>
                        <label for="password">Mot de passe :</label>
                    </div>
                    
                    <div class="button">
                        <input type="submit" value="Se connecter">
                    </div>

                    <div class="register">
                        <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous</a></p>
                    </div>

                </form>
                <?php
                // Afficher un message d'erreur si les identifiants de connexion sont invalides
                if (isset($loginError)) {
                    echo "<p style='color: #ae0606;'>$loginError</p>";
                }
                ?>
            </div>
        </div>
        
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    
</body>
</html>
