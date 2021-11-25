window.onSpotifyWebPlaybackSDKReady = () => {
  const token = "BQD8sASTO3uJQ96F08DmAq99hhoBulYdNOxd-DaTVilmnYsvdEgmbmKV_z7hvVCeIavIdmmVRapO73fdw350C4C8LcpYmjzkBF6JJpqNpJsBiW-PJgQrOG1zvlG1VG5VyAnOrmbkzaIMBeqdGraA2SAcrpWgV5F5oA";
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
