<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_key = $_ENV['api_key']; // habe ihn gerade offline gestellt, gehe auf die webseite um es wieder zu aktivieren
    $url = 'https://router.huggingface.co/cohere/compatibility/v1/chat/completions';

    // Die Nutzereingabe
    $user_message = $_POST['message'];  // wenn per Formular gesendet
    // $user_answer = $_POST['response'];

    // JSON-Daten für den API-Call
    $data = json_encode([
        "messages" => [
            [
                "role" => "user",
                "content" => $user_message
            ]
        ],
        "model" => "c4ai-aya-expanse-8b",
        "stream" => false
    ]);

    // cURL-Anfrage vorbereiten
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]);

    // Anfrage ausführen & Antwort holen
    $response = curl_exec($ch);
    curl_close($ch);

    // Antwort dekodieren und anzeigen
    $result = json_decode($response, true);

    $antwort_text = '';

    if (isset($result['choices'][0]['message']['content'])) {
        $antwort_text = htmlspecialchars($result['choices'][0]['message']['content']);
    } else {
        $antwort_text = "Keine Antwort vom Server erhalten.";
    }

    // if
}
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KI API</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <form method="post">
        <label for="message">Deine Frage hier unten</label><br>
        <input type="text" id="message" name="message"><br><br>
        <button id="enter" type="submit">Enter</button>

        <br>
        <?php if (!empty($antwort_text)): ?>
            <div style="margin-top: 20px; padding: 10px; background-color: grey;">
                <strong>Antwort der KI:</strong><br>
                <?php echo nl2br($antwort_text); ?>
            </div>
        <?php endif; ?>

    </form>
</body>

</html>