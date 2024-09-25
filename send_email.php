<?php
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
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];

        // Destinataires
        $mail->setFrom($email, $name);
        $mail->addAddress($config['smtp_username']); // Remplacez par votre adresse email

        // Contenu de l'email
        $mail->isHTML(false);
        $mail->Subject = "Nouveau message de $name";
        $mail->Body    = $message;

        // Envoyer l'email
        $mail->send();
        echo "Message envoyé avec succès.";
    } catch (Exception $e) {
        echo "Échec de l'envoi du message. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode de requête non valide.";
}
?>