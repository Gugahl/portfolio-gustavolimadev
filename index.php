<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <meta name="description" content="Portfólio de Gustavo Lima, desenvolvedor web full-stack." />
    <meta name="keywords" content="Gustavo Lima, Desenvolvedor Web, Full-Stack, Portfólio, GitHub" />
    <title>Gustavo Lima Dev</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/g.png" alt="Logo pessoal de Gustavo Lima" />
            <h2>Gustavo</h2>
            <p><strong>Desenvolvedor Web</strong></p>
        </div>
        <nav class="menu">
            <ul class="menu">
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#skils">Minhas Skills</a></li>
                <li><a href="#trabalhos">Trabalhos</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>
            <div class="social-media">
                <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="#" target="_blank"><i class="fab fa-github"></i></a>
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </nav>
    </header>

    <main>
        <section id="sobre" class="intro">
            <div class="text">
                <h1>Olá,</h1>
                <h1>Eu sou <span style="color: #ff8229;">Gustavo</span></h1>
                <h1>web developer</h1>
                <p>Desenvolvedor full-stack</p>
                <div class="button-contact">
                    <button class="contact">Entre em contato</button>
                </div>
            </div>

            <div class="image">
                <img class="foto" src="assets/gustavo.jpeg" alt="Foto de Gustavo Lima" />
            </div>

            <div class="button">
                <button class="sound">
                    <p>
                        <img src="" alt="" />
                        <span>Som</span>
                        <span class="on-off" style="color: red;">Desligado</span>
                    </p>
                </button>
            </div>

            <!-- Spotify Now Playing Widget -->
            <div class="spotify-widget" id="spotify-widget">
                <img src="assets/placeholder-album.png" alt="Capa do álbum" class="spotify-album-art" />
                <div class="spotify-info">
                    <strong class="spotify-song" title="Nome da música">Carregando música...</strong>
                    <small class="spotify-artist" title="Nome do artista">&nbsp;</small>
                </div>
                <div class="spotify-icon">
                    <i class="fab fa-spotify"></i>
                </div>
                <div class="spotify-progress-container">
                    <div class="spotify-progress-bar"></div>
                </div>
            </div>
        </section>
    </main>
<script src="js/spotify-now-playing.js"></script>
</body>
</html>
