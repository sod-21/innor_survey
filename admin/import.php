<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mqz_import_page() {
?>
    <div class="wrap">
    	<h2><?php echo __( 'Import', 'mqz' ); ?></h2>
    	<div class="mqz-import-section">
    		<div class="mqz-row">

	    		<div class="mqz-row-title">
	    			Import Quiz Data:
	    		</div>
	    		<div class="mqz-form">
	    			<form action="<?php echo admin_url( 'options.php' ); ?>" method="post" enctype="multipart/form-data"
		    			>
		    			<input type="file" name="mqz_import_file" id="mqz_import_file" />

		    			<?php submit_button("Import"); ?>
		    		</form>
	    		</div>

    		</div>

    		<div class="mqz-row">

	    		<div class="mqz-row-title">
	    			Export Quiz Data:
	    		</div>
	    		<div class="mqz-form">
	    			<form action="<?php echo admin_url( 'options.php' ); ?>" method="post" enctype="multipart/form-data"
		    			>
		    			<select class="mqz-quiz-select">
		    					
		    			</select>

		    			<?php submit_button("Export"); ?>
		    		</form>
	    		</div>

    		</div>
    		

    	</div>
    	
    </div>
<?php
}

?>