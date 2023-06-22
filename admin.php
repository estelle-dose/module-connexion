<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["login"] !== "admin") {
    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}

// Récupérer les informations des utilisateurs depuis la base de données
$host = "localhost";
$dbname = "moduleconnexion";
$username = "root";
$passwordDB = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les utilisateurs de la table utilisateurs
    $query = "SELECT * FROM utilisateurs";
    $stmt = $conn->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	<link href="css/admin.css" rel="stylesheet"/>
    <title>Administration</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <h1>Administration</h1>

                <h2>Liste des utilisateurs</h2>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Login</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Mot de passe</th>
                    </tr>
                    <div class="inputbox">
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['login']; ?></td>
                        <td><?php echo $user['prenom']; ?></td>
                        <td><?php echo $user['nom']; ?></td>
                        <td><?php echo $user['password']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </div>
                </table>

                <br>
                <form method="GET" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" name="logout" value="true">
                    <input type="submit" value="Se déconnecter">
                </form>
            </div>
        </div>
    </section>
</body>
</html>
