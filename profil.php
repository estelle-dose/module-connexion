<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}

// Récupérer les informations de l'utilisateur connecté depuis la base de données
$host = "localhost";
$dbname = "moduleconnexion";
$username = "root";
$passwordDB = "Etoile19*";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur connecté
    $query = "SELECT login, prenom, nom, password FROM utilisateurs WHERE login = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$_SESSION["login"]]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le formulaire de mise à jour a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les nouvelles données du formulaire
        $newLogin = $_POST["login"];
        $newPrenom = $_POST["prenom"];
        $newNom = $_POST["nom"];
        $newPassword = $_POST["password"];

        // Mettre à jour les informations de l'utilisateur dans la base de données
        $updateQuery = "UPDATE utilisateurs SET login = ?, prenom = ?, nom = ?, password = ? WHERE login = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$newLogin, $newPrenom, $newNom, $newPassword, $_SESSION["login"]]);

        // Mettre à jour le login de l'utilisateur dans la variable de session
        $_SESSION["login"] = $newLogin;

        // Redirection vers la page de profil mise à jour
        header("Location: profil.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Traitement de la déconnexion
if (isset($_GET["logout"])) {
    // Supprimer toutes les variables de session
    session_unset();

    // Détruire la session
    session_destroy();

    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link href="css/inscription.css" rel="stylesheet"/>
    <title>Profil</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                
                    <h2>Profil</h2>

                    <div class="inputbox">
                        <ion-icon name="sparkles-outline"></ion-icon>
                        <input type="text" id="login" name="login" value="<?php echo $row["login"]; ?>" required>
                        <label for="login">Login :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" id="prenom" name="prenom" value="<?php echo $row["prenom"]; ?>" required>
                        <label for="prenom">Prénom :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="home-outline"></ion-icon>
                        <input type="text" id="nom" name="nom" value="<?php echo $row["nom"]; ?>" required>
                        <label for="nom">Nom :</label>
                    </div>

                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="password" value="<?php echo $row["password"]; ?>" required>
                        <label for="password">Nouveau mot de passe :</label>
                    </div>

                    <div class="button">
                        <input type="submit" value="Enregistrer les modifications">
                    </div>

                </form>
                <br>
                <form method="GET" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <div class="button">
                        <input type="hidden" name="logout" value="true">
                        <input type="submit" value="Se déconnecter">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>      
            
</body>
</html>

