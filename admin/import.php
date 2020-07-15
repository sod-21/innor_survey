<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mqz_import_page() {
?>
    <div class="wrap">
    	<h2><?php echo __( 'Export / Import Survey', 'mqz' ); ?></h2>
    	<div class="mqz-import-section">
    		<div class="mqz-row">

	    		<div class="mqz-row-title">
	    			Import:
	    		</div>
	    		<div class="mqz-form">
	    			<form action="<?php echo admin_url( 'options.php' ); ?>" method="post" enctype="multipart/form-data"
		    			>
		    			<?php settings_fields( 'mqz_import_file' ); ?>

		    			<input type="file" name="mqz_import_file" id="mqz_import_file" />

		    			<?php submit_button("Import"); ?>
		    		</form>
	    		</div>

    		</div>

    		<div class="mqz-row">

	    		<div class="mqz-row-title">
	    			Export:
	    		</div>
	    		<div class="mqz-form">
	    			<form method="post">
		    			<?php 
		    			$quiz = MQZ_Helpers::get_quizzes();
		    			if (count($quiz) > 0):
		    			?>
		    			<select class="mqz-quiz-select" name="mqz-export-id">
		    				<?php  foreach ($quiz as $q):?>
		    					<option value="<?php echo $q->quiz_id; ?>"><?php echo $q->quiz_name; ?></option>
		    				<?php endforeach; ?>
		    			</select>
		    			<?php else: ?>
		    			<p>No Quiz Data.</p>
		    			<?php endif; ?>
		    			<?php submit_button("Export"); ?>
		    		</form>
	    		</div>

    		</div>
    		

    	</div>
    	
    </div>
<?php
}

?>