
    </body>
<footer>
<?php 
$profiles = getSocialMediaProfiles();

echo '<div class="social-media-links">';
foreach ($profiles as $platform => $link) {
    switch ($platform) {
        case 'Facebook':
            echo "<a href='{$link}' target='_blank'><i class='fab fa-facebook-f'></i></a> ";
            break;
        case 'Instagram':
            echo "<a href='{$link}' target='_blank'><i class='fab fa-instagram'></i></a> ";
            break;
        case 'Discord':
            echo "<a href='{$link}' target='_blank'><i class='fab fa-discord'></i></a> ";
            break;
        case 'GitHub':
            echo "<a href='{$link}' target='_blank'><i class='fab fa-github'></i></a> ";
            break;
        case 'LinkedIn':
            echo "<a href='{$link}' target='_blank'><i class='fab fa-linkedin-in'></i></a> ";
            break;
    }
}
echo '</div>';
?>
</footer>
</html>
