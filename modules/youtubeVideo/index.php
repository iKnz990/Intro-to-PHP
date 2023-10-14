<?php
include '../../core/header.php';
include 'core/functions.php';
?>



<div class="video-container">
    <h1 class="video-title">YouTube Video Player</h1>
    <div class="youtube-video">
        <?php echo embedYouTubeVideo('a7_WFUlFS94'); ?>
        <!-- This is the video embed - YouTube Video URLs are .../watch?v=a7_WFU1FS94 \\ test with  -->
    </div>

    <div class="video-description">
        <!-- This is the video description - I will use this to answer the assignment questions -->
        <?php echo getYoutubeDescription(); ?>
    </div>
</div>

<?php
include '../../core/footer.php';
?>