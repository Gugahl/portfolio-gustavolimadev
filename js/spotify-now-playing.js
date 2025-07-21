// Variáveis globais para controle do progresso
let progressInterval = null;
let trackDuration = 0;  // em ms
let trackProgress = 0;  // em ms

// Função para buscar os dados da música atual no servidor
async function fetchCurrentTrack() {
  try {
    const response = await fetch('now-playing.php');
    if (!response.ok) throw new Error('Erro ao buscar dados do Spotify');

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Erro ao buscar música:', error);
    return null;
  }
}

// Função para atualizar o DOM com os dados recebidos
function updateSpotifyWidget(data) {
  const albumArt = document.querySelector('.spotify-album-art');
  const songTitle = document.querySelector('.spotify-song');
  const artistName = document.querySelector('.spotify-artist');
  const progressBar = document.querySelector('.spotify-progress-bar');

  if (!data || !data.is_playing || !data.title) {
    // Nenhuma música tocando
    albumArt.src = 'assets/placeholder-album.png';
    albumArt.alt = 'Nenhuma música tocando';
    songTitle.textContent = 'Nenhuma música tocando agora.';
    songTitle.title = '';
    artistName.textContent = '';
    artistName.title = '';

    // Remove animação e zera barra de progresso
    albumArt.classList.remove('album-playing');
    if (progressBar) progressBar.style.width = '0%';

    clearInterval(progressInterval);
    progressInterval = null;
    return;
  }

  // Música tocando — atualiza dados
  albumArt.src = data.albumImageUrl;
  albumArt.alt = `Capa do álbum: ${data.title}`;
  songTitle.textContent = data.title;
  songTitle.title = data.title;
  artistName.textContent = data.artist;
  artistName.title = data.artist;

  // Adiciona animação de pulse
  albumArt.classList.add('album-playing');

  // Inicia ou atualiza barra de progresso
  trackDuration = data.duration_ms || 0;
  trackProgress = data.progress_ms || 0;

  if (progressBar) {
    updateProgressBar(progressBar, trackProgress, trackDuration);

    // Limpa intervalo anterior para não duplicar
    if (progressInterval) clearInterval(progressInterval);

    // Inicia intervalo para atualizar progresso a cada segundo
    progressInterval = setInterval(() => {
      trackProgress += 1000; // incrementa 1 segundo em ms
      if (trackProgress > trackDuration) {
        trackProgress = trackDuration; // evita ultrapassar duração
        clearInterval(progressInterval);
      }
      updateProgressBar(progressBar, trackProgress, trackDuration);
    }, 1000);
  }
}

// Atualiza a barra de progresso visual
function updateProgressBar(progressBar, progress, duration) {
  if (duration === 0) {
    progressBar.style.width = '0%';
    return;
  }
  const percent = (progress / duration) * 100;
  progressBar.style.width = `${percent}%`;
}

// Função principal para buscar e atualizar widget
async function carregarMusicaAtual() {
  const data = await fetchCurrentTrack();

  // Seu backend agora deve enviar: is_playing, title, artist, albumImageUrl, duration_ms, progress_ms
  updateSpotifyWidget(data);
}

// Inicializa no carregamento da página e atualiza a cada 30s
window.addEventListener('DOMContentLoaded', () => {
  carregarMusicaAtual();
  setInterval(carregarMusicaAtual, 30000);
});
