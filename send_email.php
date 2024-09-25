<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    // Vérification que tous les champs sont remplis
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Destinataire
        $to = "nicolas.saint-leger@universite-paris-saclay.fr";

        // Sujet du mail
        $subject = "Message de " . $name;

        // Corps du mail
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // En-têtes de l'e-mail
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Envoyer l'e-mail et vérifier le résultat
        if (mail($to, $subject, $body, $headers)) {
            echo "Votre message a été envoyé avec succès.";
        } else {
            // Récupérer et afficher la dernière erreur PHP
            $error = error_get_last();
            if ($error !== null) {
                echo "Erreur lors de l'envoi du message. Détails de l'erreur : " . $error['message'];
            } else {
                echo "Erreur lors de l'envoi du message. Aucune information d'erreur disponible.";
            }
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
} else {
    echo "Méthode d'envoi invalide.";
}
?>