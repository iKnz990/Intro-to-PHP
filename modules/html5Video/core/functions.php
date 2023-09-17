<?php
function embedHTML5Video($videoPath) {
    return '
    <video onloadedmetadata="this.volume=0.15" width="560" height="315" controls>
        <source src="' . $videoPath . '" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    ';
}

function getHtml5Description() {
    return '
    <div class="content">
        <h2>Purpose and General Use of the Video Element:</h2>
        <p>The HTML5 video element allows for the embedding of video files that can be played directly in modern web browsers without requiring third-party plugins. It is also customizable with CSS and Javascript.</p></br>
        <p><strong>In this example we set the border, border radius, and we lower the volume.</strong></p>

        <h2>Presentation Features of the Video Element:</h2>
            <p>Native controls for play, pause, volume, and fullscreen.</p>
            <p>Support for multiple video formats through the source element.</p>
            <p>Customizable with CSS and JavaScript for a tailored user experience.</p>
        

        <h2>Supported Video Formats:</h2>
            <p><strong>MP4 (MPEG4 files with H264 VCodec and AAC ACodec)</strong></p>
            <p><strong>WebM (WebM files with VP8 VCodec and Vorbis ACodec)</strong></p>
            <p><strong>Ogg (Ogg files with Theora VCodec and Vorbis ACodec)</strong></p>
        
    </div>
    ';
}
