<?php
$socials              = lebe_get_option('lebe_header_social');
$socials_content      = lebe_get_option('user_all_social');
?>
<?php if (!empty($socials)) : ?>
    <div class="menu-social">
        <h3 class="social-title"><?php echo esc_html__('Follow Us','lebe')?></h3>
        <ul class="social-list">
            <?php foreach ($socials as $social) :
                if (isset($socials_content[$social])):
                    $content = $socials_content[$social]; ?>
                    <li>
                        <a href="<?php echo esc_url($content['link_social']); ?>">
                            <span class="<?php echo esc_attr($content['icon_social']); ?>"></span>
                        </a>
                    </li>
                    <?php
                endif;
            endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
