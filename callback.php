<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_GET['state']) || !isset($_SESSION['spotify_auth_state']) || $_GET['state'] !== $_SESSION['spotify_auth_state']) {
    unset($_SESSION['spotify_auth_state']);
    exit('Erro: Estado inválido.');
}

// Já validado, pode remover o estado da sessão
unset($_SESSION['spotify_auth_state']);

$client_id = 'dbddd2f9d7b44029a0dd0f63168227c9';
$client_secret = 'e36a7f8684ae444fada96e66ae50b7b3';
$redirect_uri = 'http://127.0.0.1:8888/callback.php';

if (!isset($_GET['code'])) {
    die('Código não encontrado.');
}

$code = $_GET['code'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Erro cURL: " . curl_error($ch);
    curl_close($ch);
    exit;
}
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $data['expires_at'] = time() + $data['expires_in'];

    file_put_contents('token.json', json_encode($data, JSON_PRETTY_PRINT));

    header('Location: index.php');
    exit;
} else {
    echo "<h2>Erro ao obter token</h2>";
    echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
}
?>
