window.onSpotifyWebPlaybackSDKReady = () => {
    const token = 'BQBp-1IEa772fGWZFbZg-kEDYOhrzLDZ0DsnUik4m80eCDa-5yT9cjht94Otl6PmyrtM9pNyvmIVm6yBjDEnjXuRc-d0HVVnryOViO2V_jXie-pB4LHGBawLZxZVFoNjWhMyiR0jt4NhJ8GsdyGzWlzQA7-F';
    const player = new Spotify.Player({
        name: 'Valentin Gaudin',
        getOAuthToken: cb => { cb(
            token
            ); },
        volume: 0.5,
    });
    document.getElementById('togglePlay').onclick = function() {
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
}
