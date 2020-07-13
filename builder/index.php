<?php
	remove_action('wp_head', '_admin_bar_bump_cb');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Quiz Builder</title>
  <style type="text/css">
    #wpadminbar {
      display: none;
    }
  </style>
  
  <?php
  function load_admin_media_styles(){
    // list all style libs the post page of wp-admin loaded, but we may only need to load 2 of them
      $ary_libs = array(
          'common','forms',
          // 'dashicons', 'admin-bar','buttons','media-views',
          // 'admin-menu','dashboard','list-tables','edit','revisions','media','themes','about','nav-menu',
          // 's','wp-pointer','widgets','site-icon','l10n','wp-auth-check','wp-color-picker'
      );
      $admin_media_styles_url = add_query_arg(
          array(
              'c' => 0,
              'dir' => 'ltr',
              'load[]' => implode(',', $ary_libs),
              'ver' => '5.4'
          ),
          admin_url().'load-styles.php'
      );
      echo "<link rel='stylesheet' id='admin_styles_for_media-css' href='".$admin_media_styles_url."' type='text/css' media='all' />";
  }
  load_admin_media_styles();
?>
  <link href="<?php echo MQA_PLUGIN_URL . 'builder/dist/bundle.css?ver=' . MQZ_Helpers::$version;?>" rel="stylesheet" />  
</head>

<body>
  <div id="root"></div>
  <?php
	wp_footer();
	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_print_footer_scripts' );
?>
  <script type="text/javascript">
	window.quiz_url = "<?php echo site_url('wp-json/magic-quiz/v1/'); ?>";
  window.redirect_page = "<?php echo admin_url('admin.php?page=innovere-survey%2Fmagicquiz.php'); ?>";
  window.img = "<?php echo MQA_PLUGIN_URL . "assets/img/"; ?>";
	<?php
  $id = isset($_GET["id"]) ? $_GET["id"] : 0;
  if ($id == "new") {
    $id = 0;
  }
	?>
    window.quiz_id = "<?php echo $id;?>";
  </script>
  <script type="text/javascript" src="<?php echo MQA_PLUGIN_URL . "builder/dist/bundle.js?ver=" . MQZ_Helpers::$version; ?>"></script>
</body>
</html>
