<?php
include '../../core/header.php';
include 'core/functions.php';
?>


<div class="video-container">
    <h1 class="video-title">HTML5 Video Player</h1>
    <div class="html5-video">
        <?php echo embedHTML5Video('./assets/vids/nature.mp4'); ?> <!--You need to go up a level for folders due to core/functions.php  -->
    </div>
    <div class="video-description">
        <?php echo getHtml5Description(); ?>
    </div>
</div>

<?php
include '../../core/footer.php';
?>
