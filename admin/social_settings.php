<?php
    $social = MQZ_Helpers::get_options()["social"];
    
    $twitter = isset($social["twitter"]) ? $social["twitter"] : "";
    $facebook = isset($social["facebook"]) ? $social["facebook"] : "";
    $instagram = isset($social["instagram"]) ? $social["instagram"] : "";
?>

<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __( 'Social settings', '' ); ?></h2>

    <div class="metabox-holder has-right-sidebar">
		
        <div id="post-body">
            <div id="post-body-content" class="social-activate">
            <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
                <?php settings_fields( 'mqz_social_settings' ); ?>
                <table class="form-table">
                    <tr valign="top">
						<th scope="row">
							<?php _e( 'Twitter', 'mqz' ); ?>
						</th>
						<td>
                            <input type="text" name="mqz[social][twitter]" value="<?php echo $twitter; ?>">						
						</td>
                    </tr>
                    <tr valign="top">
						<th scope="row">
							<?php _e( 'Facebook', 'mqz' ); ?>
						</th>
						<td>
                            <input type="text" name="mqz[social][facebook]" value="<?php echo $facebook; ?>">						
						</td>
                    </tr>
                    <tr valign="top">
						<th scope="row">
							<?php _e( 'Instagram', 'mqz' ); ?>
						</th>
						<td>
                            <input type="text" name="mqz[social][instagram]" value="<?php echo $instagram; ?>">						
						</td>
					</tr>
               
                <tr>
                    <td><input type="submit" name="submit" id="submit" class="button button-primary"
                        value="<?php echo __( 'Save', 'mailerlite' ); ?>">
                    </tr>
                 </table>
            </form>
               
            </div>
        </div>
    </div>
</div>