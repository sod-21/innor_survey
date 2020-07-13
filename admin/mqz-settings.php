<?php
/**
 * This file handles the contents on the "Quizzes/Surveys" page.
 *
 * @package QSM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mqz_settings_page() {

    $api_key = MQZ_Helpers::get_options()["api_key"];
    $connected = false;
    if ($api_key != "") {
        $connected = true;
        $lists = MQZ_Helpers::get_lists($instance);
        $api_key = "****************";        
    }

    
?>
    <div class="wrap">
        <h1>Settings</h1>

		<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'mailchimp'; ?>

		<h2 class="nav-tab-wrapper">
            <a href="?page=mqz_options&tab=mailchimp" class="nav-tab <?php echo $active_tab == 'mailchimp' ? 'nav-tab-active' : ''; ?>">Mailchimp Settings</a>
			<a href="?page=mqz_options&tab=mailer" class="nav-tab <?php echo $active_tab == 'mailer' ? 
			'nav-tab-active' : ''; ?>">Mailerlite Settings</a>
			<a href="?page=mqz_options&tab=social" class="nav-tab <?php echo $active_tab == 'social' ? 
			'nav-tab-active' : ''; ?>">Social Settings</a>
		</h2>
		
		<?php if ($active_tab == "mailchimp"): ?>

		<h2>Mailchimp Settings</h2>
        <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
				<?php settings_fields( 'mqz_settings' ); ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row">
							<?php _e( 'Status', 'mqz' ); ?>
						</th>
						<td>
							<?php
							if ( $connected ) {
								?>
								<span class="status positive"><?php _e( 'CONNECTED', 'mqz' ); ?></span>
								<?php
							} else {
								?>
								<span class="status neutral"><?php _e( 'NOT CONNECTED', 'mqz' ); ?></span>
								<?php
							}
							?>
						</td>
					</tr>


					<tr valign="top">
						<th scope="row"><label for="mailchimp_api_key"><?php _e( 'API Key', 'mqz' ); ?></label></th>
						<td>
							<input type="text" class="widefat" placeholder="<?php _e( 'Your Mailchimp API key', 'mqz' ); ?>" id="mailchimp_api_key" name="mqz[api_key]" value="<?php echo esc_attr( $api_key ); ?>" />
							<p class="help">
								<?php _e( 'The API key for connecting with your Mailchimp account.', 'mqz' ); ?>
								<a target="_blank" href="https://admin.mailchimp.com/account/api"><?php _e( 'Get your API key here.', 'mqz' ); ?></a>
							</p>
						</td>

					</tr>
                    <tr>
                    <th scope="row"><label>Lists:</label></th>
                    <td>
                        <?php

                        $option_select = sprintf( '<select name="mqz[list]">');
                       	if (isset($lists)) {
	                        foreach ( $lists as $list ) {
	                            $option_select .= sprintf( '<option value="%s">%s</option>', $list->id, $list->name );
	                        }
                        }
                        $option_select .= '</select>';

                        echo $option_select;
                        ?>
                    </td>
                        </tr>
				</table>

				<?php submit_button(); ?>

			</form>
		
		<?php endif; ?>

		<?php if ($active_tab == "mailer"): ?>
			<?php require_once(MQA_PLUGIN_PATH . "/admin/mailite_settings.php"); ?>
		<?php endif; ?>
		
		<?php if ($active_tab == "social"): ?>
			<?php require_once(MQA_PLUGIN_PATH . "/admin/social_settings.php"); ?>
		<?php endif; ?>
	</div>
	
<?php
}
?>
