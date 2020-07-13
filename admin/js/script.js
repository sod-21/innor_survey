jQuery(document).ready(function() {

	jQuery("#table-quiz-list .btn-copy").off("click").on("click", function(e) {

		const el = document.createElement('textarea');
		el.value = '[innovere-survey id="' + jQuery(this).attr('data-id') + '"]';
		document.body.appendChild(el);
		el.select();
		document.execCommand('copy');
		document.body.removeChild(el);

	});

	jQuery("#table-quiz-list .btn-setting-edit").off("click").on("click", function(e) {
		jQuery("#mqz-modal-1").find(".mqz-permalink").val(jQuery(this).attr('data-href'));
		jQuery(".modal__btn_save").attr('data-id', jQuery(this).attr('data-edit'));
		MicroModal.show( 'mqz-modal-1' );
	});
	
	jQuery(".modal__btn_save").on("click", function(e) {

		var post_id =  jQuery(this).attr('data-id');
		 jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "inline-save",
                post_name: jQuery("#mqz-modal-1").find(".mqz-permalink").val(),
                post_ID: post_id,
                _inline_edit: jQuery('#_inline_edit').val(),
                post_type: "quiz"
            },

            success: function( data ) {
                jQuery('[data-view="' + post_id+ '"]').attr('href', jQuery('#mqz_url').val() + "/" + jQuery("#mqz-modal-1").find(".mqz-permalink").val());                
                jQuery('[data-edit="' + post_id+ '"]').attr('data-href', jQuery("#mqz-modal-1").find(".mqz-permalink").val());
            },

            error: function(jqXHR, exception) {
                
            }
        });

		MicroModal.close( 'mqz-modal-1' );
	});
});