<?php
session_start();

$client_id = 'dbddd2f9d7b44029a0dd0f63168227c9';
$redirect_uri = 'http://127.0.0.1:8888/callback.php';
$scope = 'user-read-currently-playing user-read-playback-state';

// Gera state e salva na sessão
$state = bin2hex(random_bytes(16));
$_SESSION['spotify_auth_state'] = $state;

$url = "https://accounts.spotify.com/authorize"
    . "?response_type=code"
    . "&client_id=" . urlencode($client_id)
    . "&scope=" . urlencode($scope)
    . "&redirect_uri=" . urlencode($redirect_uri)
    . "&state=" . urlencode($state);

header("Location: $url");
exit;