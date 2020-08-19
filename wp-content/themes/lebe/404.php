<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link       https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage Lebe
 * @since      1.0
 * @version    1.0
 */

get_header(); ?>
    <div class="container">
        <div class="text-center page-404">
            <h1 class="heading">
				<?php esc_html_e( '404', 'lebe' ); ?>
            </h1>
            <h2 class="title"><?php esc_html_e( 'We are sorry, the page you\'ve requested is not available', 'lebe' ); ?></h2>
			<?php get_search_form(); ?>
            <a class="button"
               href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back To Home Page', 'lebe' ); ?></a>
        </div>
    </div>
<?php get_footer();
