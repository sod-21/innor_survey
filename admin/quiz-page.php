<?php
/**
 * This file handles the contents on the "Quizzes/Surveys" page.
 *
 * @package QSM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates the quizzes and surveys page
 *
 * @since 5.0
 */
function quiz_manage_page() {

	$delete_id = isset($_POST["quiz-delete"]) ? intval($_POST["quiz-delete"])  : 0;
	$duplicate_id = isset($_POST["quiz-duplicate"]) ? intval($_POST["quiz-duplicate"]) : 0;

	if ($duplicate_id > 0) {		
		MQZ_QUIZ::duplicate_quiz($duplicate_id);
	}

	if ($delete_id > 0) {
		MQZ_QUIZ::delete_quiz($delete_id);
	}
	
	require_once( MQA_PLUGIN_PATH . "includes/class-quiz-table-list.php");
	$table =new  Quiz_Table_List();
	?>
	
	<div class="wrap qsm-quizes-page">
		<input type="hidden" id="mqz_url" value="<?php echo site_url('quiz'); ?>" />
		<h1>
			<?php esc_html_e( 'Quiz', 'mqz' ); ?>
			<a id="new_quiz_button" href="<?php echo admin_url( 'post-new.php?post_type=quiz&id=new');?>" class="add-new-h2"><?php _e( 'Add New', 'mqz' ); ?></a>
		</h1>
		
		<div id="table-quiz-list">
			<?php
			$table->prepare_items();
			$table->display(); 
			?>
		</div>

		<div class="modal micromodal-slide" id="mqz-modal-1" aria-hidden="true">
	        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
	          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
	            <header class="modal__header">
	              <h2 class="modal__title" id="modal-1-title">
	                Save Settings
	              </h2>
	              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
	            </header>
	            <main class="modal__content" id="modal-1-content">
	              <p>
	              	<input type="text" class="mqz-permalink" value=""/>
	              </p>
	            </main>
	            <footer class="modal__footer">
	            	<?php wp_nonce_field( 'inlineeditnonce', '_inline_edit', false ); ?>
	              <button class="modal__btn button-primary modal__btn_save">Save</button>
	              <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
	            </footer>
	          </div>
	        </div>
      	</div>

		
	</div>

	
<?php
}
?>
