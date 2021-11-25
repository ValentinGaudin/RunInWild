window.onSpotifyWebPlaybackSDKReady = () => {
  const token = "BQDVb11PQv8g0zn8jTIKLpvdZVe5MSWZC450o38pRwq01HauAUjSBuRe_FMkMr1dUbPMQvOETjfpFzoVzrf8JJNKgqUhtFHJA7MDauQfxtJziqYXFHuSVzRnBBHqSFTZ42nQAtRC7vwJ0QAcOYGdE_7E4grwBIX9LQ";
  const player = new Spotify.Player({
    name: 'Valentin Gaudin',
    getOAuthToken: (cb) => {
      cb(token);
    },
    volume: 0.5,
  });
  document.getElementById('togglePlay').onclick = function () {
    player.togglePlay();
  };
  // Ready
  player.addListener('ready', ({ device_id }) => {
    console.log('Ready with Device ID', device_id);
  });
  // Not Ready
  player.addListener('not_ready', ({ device_id }) => {
    console.log('Device ID has gone offline', device_id);
  });
  player.addListener('initialization_error', ({ message }) => {
    console.error(message);
  });
  player.addListener('authentication_error', ({ message }) => {
    console.error(message);
  });
  player.addListener('account_error', ({ message }) => {
    console.error(message);
  });
  player.connect();
};
