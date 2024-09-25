<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Inclure les fichiers PHPMailer manuellement
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Inclure le fichier de configuration
$config = require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Valider les données
    if (empty($name) || empty($email) || empty($message)) {
        echo "Tous les champs sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    // Configurer PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $config['smtp_port'];

        // Destinataires
        $mail->setFrom($email, $name);
        $mail->addAddress("nicolas.saint-leger@universite-paris-saclay.fr"); // Remplacez par votre adresse email

        // Contenu de l'email
        $mail->isHTML(false);
        $mail->Subject = "[CONTACT] $name";
        $mail->Body    = $message;

        // Envoyer l'email
        $mail->send();
        
        // Rediriger vers la racine du site
        header('Location: /');
        exit("Message envoyé avec succès.");
    } catch (Exception $e) {
        echo "Échec de l'envoi du message. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode de requête non valide.";
}
?>