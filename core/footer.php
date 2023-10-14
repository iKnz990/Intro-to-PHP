</body>
<footer>
    <?php
    $profiles = getSocialMediaProfiles();

    echo '<div class="social-media-links">';
    foreach ($profiles as $platform => $link) {
        $safeLink = sanitizeOutput($link);
        switch ($platform) {
            case 'Facebook':
                echo "<a href='{$safeLink}' target='_blank'><i class='fab fa-facebook-f'></i></a> ";
                break;
            case 'Instagram':
                echo "<a href='{$safeLink}' target='_blank'><i class='fab fa-instagram'></i></a> ";
                break;
            case 'Discord':
                echo "<a href='{$safeLink}' target='_blank'><i class='fab fa-discord'></i></a> ";
                break;
            case 'GitHub':
                echo "<a href='{$safeLink}' target='_blank'><i class='fab fa-github'></i></a> ";
                break;
            case 'LinkedIn':
                echo "<a href='{$safeLink}' target='_blank'><i class='fab fa-linkedin-in'></i></a> ";
                break;
        }
    }

    // PayPal Donation Icon
    echo "<a href='https://www.paypal.com/donate?business=C2YUDHL5M8VUJ&no_recurring=0&item_name=Your+donation+allows+me+to+turn+code+into+life-changing+solutions.+Thank+you+for+powering+progress.&currency_code=USD' target='_blank'><i class='fab fa-paypal'></i></a> ";

    echo '</div>';

    ?>
</footer>

</html>