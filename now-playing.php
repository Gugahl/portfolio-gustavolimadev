<?php
$client_id = 'dbddd2f9d7b44029a0dd0f63168227c9';
$client_secret = 'e36a7f8684ae444fada96e66ae50b7b3';

// Função para renovar o token usando o refresh_token
function refreshAccessToken($refresh_token, $client_id, $client_secret) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Carrega token salvo
$tokenData = json_decode(file_get_contents('token.json'), true);

if (!$tokenData || !isset($tokenData['access_token']) || !isset($tokenData['expires_at'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Token de acesso não encontrado.']);
    exit;
}

// Verifica se o token expirou
if (time() >= $tokenData['expires_at']) {
    // Tenta renovar o token
    $refreshResponse = refreshAccessToken($tokenData['refresh_token'], $client_id, $client_secret);

    if (!$refreshResponse || !isset($refreshResponse['access_token'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Falha ao renovar token.']);
        exit;
    }

    // Atualiza token e tempo de expiração
    $tokenData['access_token'] = $refreshResponse['access_token'];

    if (isset($refreshResponse['refresh_token'])) {
        // Atualiza refresh_token se retornado
        $tokenData['refresh_token'] = $refreshResponse['refresh_token'];
    }

    $tokenData['expires_in'] = $refreshResponse['expires_in'];
    $tokenData['expires_at'] = time() + $refreshResponse['expires_in'];

    // Salva os dados atualizados
    file_put_contents('token.json', json_encode($tokenData, JSON_PRETTY_PRINT));
}

$access_token = $tokenData['access_token'];

// Consulta a API Spotify para música atual
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/player/currently-playing');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');

if ($httpCode == 204 || $httpCode >= 400) {
    // Nenhuma música tocando ou erro
    echo json_encode(['is_playing' => false]);
    exit;
}

$data = json_decode($response, true);

if (!$data || !isset($data['item'])) {
    echo json_encode(['is_playing' => false]);
    exit;
}

echo json_encode([
    'is_playing' => $data['is_playing'],
    'title' => $data['item']['name'],
    'artist' => implode(', ', array_map(fn($a) => $a['name'], $data['item']['artists'])),
    'albumImageUrl' => $data['item']['album']['images'][0]['url'],
    'duration_ms' => $data['item']['duration_ms'],
    'progress_ms' => $data['progress_ms'],
]);
