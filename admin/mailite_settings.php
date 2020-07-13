<?php


    $mail_lite = MQZ_Helpers::get_mailite_options();
// var_dump ($mail_lite);    
    $mail_lite_connected = false;
    $mail_lite_selcted = "";
    $mail_lite_list = array();
    if ($mail_lite) {
        $mail_lite_connected = true;
        
        // var_dump($mail_lite["api_key"]);
        
        if ($mail_lite["api_key"] && isset($mail_lite["account_id"]) && $mail_lite["account_id"]) {
            $mail_lite_key = "****************";
            $mail_lite_list = MQZ_Helpers::mailite_form_group();
        }
            
        else
            $mail_lite_key = "";
        
        $mail_lite_selcted = isset($mail_lite["list"]) ? $mail_lite["list"] : "";
    } else {
        $mail_lite_key = "";
    }

?>
<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __( 'Mailite settings', 'mailerlite' ); ?></h2>

    <div class="metabox-holder has-right-sidebar">
		
        <div id="post-body">
            <div id="post-body-content" class="mailerlite-activate">
            <form action="<?php echo admin_url( 'options.php' ); ?>" method="post" id="enter-mailerlite-key">
                <?php settings_fields( 'mqz_maillite_settings' ); ?>
                <table class="form-table">
                    <tr valign="top">
						<th scope="row">
							<?php _e( 'Status', 'mqz' ); ?>
						</th>
						<td>
							<?php
							if ( $mail_lite_key ) {
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
                    <tr>
                        <th valign="top">
                            <label for="mailerlite-api-key"><?php echo __( 'Enter an API key',
									'mailerlite' ); ?></label>
                        </th>
                        <td>
                                <input type="text" name="mqz[mailite][api_key]" class="regular-text" placeholder="API-key"
                                       value="<?php echo $mail_lite_key; ?>" id="mailerlite-api-key"/>

                                


                            <p class="description"><?php echo __( "Don't know where to find it?", 'mailerlite' ); ?>
                                <a
                                        href="https://kb.mailerlite.com/does-mailerlite-offer-an-api/"
                                        target="_blank"><?php echo __( "Check it here!", 'mailerlite' ); ?></a></p>
                        </td>
                    </tr>
                    <tr>
                    <th scope="row"><label>Lists:</label></th>
                    <td>
                        <?php
                        $option_select = sprintf( '<select name="mqz[mailite][list]">');
                            
                        foreach ( $mail_lite_list as $list ) {
                            if ($mail_lite_selcted == $list["id"]) {
                                $option_select .= sprintf( '<option value="%s" selected="selected">%s</option>', $list["id"], $list["name"]);
                            } else {
                                $option_select .= sprintf( '<option value="%s">%s</option>', $list["id"], $list["name"]);
                            }
                            
                        }
                        $option_select .= '</select>';

                        echo $option_select;
                        ?>
                    </td>
                        </tr>
                </table>
                <tr>
                    <td><input type="submit" name="submit" id="submit" class="button button-primary"
                        value="<?php echo __( 'Save this key', 'mailerlite' ); ?>">
                
                    </tr>
                

            </form>
               
            </div>
        </div>
    </div>
</div>