// fetch('http://localhost:8000/spotify/tracking/Playlist')
//     .then(response => response.json())
//     .then(data => {
//         console.log(data)
//         console.log(data.playlists.items[2].id)

//         document.getElementById('playlist').src = "https://open.spotify.com/embed/playlist/"+data.playlists.items[2].id+"?utm_source=generator"
//     })
//     .catch(() => alert('Not Good'))

// fetch('http://localhost:8000/spotify/tracking/Track')
//     .then (response => response.json())
//     .then (data => {
//         const randomElement = data.tracks.items[Math.floor(Math.random() * data.tracks.items.length)];

//         console.log(randomElement.id)
//         document.getElementById('track').src = "https://open.spotify.com/embed/track/"+randomElement.id+"?utm_source=generator"

//     })
//     .catch(() => alert('Not'))