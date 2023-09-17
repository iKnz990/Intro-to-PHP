<?php
function embedYouTubeVideo($videoID) {
    return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoID . '" frameborder="0" allowfullscreen></iframe>
    ';
}

function getYoutubeDescription() {
    return '
    <div class="content">
        <h2>Considerations for Placing Video on a Website:</h2>
            <p><strong>Bandwidth:</strong> High-quality videos can consume significant bandwidth, impacting user experience for those with slow connections.</p>
            <p><strong>Accessibility:</strong> Ensure videos have captions or subtitles for those with hearing impairments.</p>
            <p><strong>Autoplay:</strong> Avoid autoplaying videos as it can be intrusive and consume data without user consent.</p>
        

        <h2>Presentation Features of the YouTube Player:</h2>
            <p><strong>Customizable player controls (play, pause, volume, etc.)</strong></p>
            <p><strong>Ability to embed playlists.</strong></p>
            <p><strong>Annotations and end screens for additional interactivity.</strong></p>
    </div>
    ';
}
