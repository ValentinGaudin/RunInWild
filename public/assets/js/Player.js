// fetch('http://localhost:8000/spotify/tracking')
//     .then(response => response.json())
//     .then(data => {
//         // document.getElementById('playlist').src="https://open.spotify.com/track/"+data.item.id+"?utm_source=generator"
//         console.log(data.context.external_urls)
//     })
//     .catch(() => alert('error'))


fetch('http://localhost:8000/spotify/tracking')
    .then(response => response.json())
    .then(data => {
        console.log(data)
        console.log(data.playlists.items[2].id)

        document.getElementById('playlist').src = "https://open.spotify.com/embed/playlist/"+data.playlists.items[2].id+"?utm_source=generator"
        // console.log(data.tracks.items[0].id)
        // document.getElementById('track').src = "https://open.spotify.com/embed/track/"+data.tracks.items[0].id+"?utm_source=generator"
    })
    .catch(() => alert('Not Good'))

fetch("")
.then (response => response.json)
.then (data => {
    console.log(data)
})
.catch(() => alert('Not'))