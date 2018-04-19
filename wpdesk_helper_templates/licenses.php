<?php extract( $args ); ?>

<div class="wrap">
	<?php /* screen_icon(); */ ?>

	<h1><?php _e( 'WP Desk Licenses', 'wpdesk-helper' ); ?></h1>

    <p class="mb0">
        <?php
            if ( get_locale() === 'pl_PL' ) {
                $url = 'https://www.wpdesk.pl/moje-konto/';
            } else {
                $url = 'https://www.wpdesk.net/my-account/';
            }

            $link = sprintf( wp_kses( __( 'Get your license keys <a href="%s" target="_blank">here</a>.', 'wpdesk-helper' ), array(  'a' => array( 'href' => array(), 'target' => '_blank' ) ) ), esc_url( $url ) );
            echo $link;
        ?>
    </p>

	<?php settings_errors(); ?>

	<?php
		$list_table = new WPDesk_Helper_List_Table( );
		$list_table->data = $wpdesk_helper_plugins;
		$list_table->prepare_items();
		$list_table->display();
	?>
</div> <!-- class="wrap" -->
