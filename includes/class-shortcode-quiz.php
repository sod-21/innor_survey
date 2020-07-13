<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Quiz_Shortcode {
    public static function output($atts = array()) {
        extract(shortcode_atts(array(
            'id' => ''
        ), $atts));

        if (!$id) {
            $id = get_the_ID();
        }

        if ($id) {
            $data = MQZ_QUIZ::load_quiz($id);
            
            if (isset($data["questions"])) {
                foreach ($data["questions"] as $key => &$qq) {
                    if ($qq["type"] == "HF") {
                        $key = $qq["key"];

                        $qq["text"] = isset($_GET[$key]) ? $_GET[$key] : "";
                    }
                }
            }
            
            $json_str = json_encode($data);
            $social = MQZ_Helpers::get_options()["social"];

            ob_start();
            ?>
            <div id="suvery_form"></div>            
            <script type="text/javascript">
                //window.survey_url = "<?php echo site_url('wp-json/magic-quiz/v1'); ?>";                
                
                window.survey_settings = <?php echo json_encode(
                    array(
                        "url" => site_url('wp-json/magic-quiz/v1'),
                        "facebook" => $social["facebook"],
                        "twitter" => $social["twitter"],
                        "instagram" => $social["instagram"],
                    )
                    ); ?>;
                window.suvery_data = <?php echo $json_str; ?>;
            </script>
            <link href="<?php echo MQA_PLUGIN_URL . 'shortcode/public/dist/bundle.css?ver=' . MQZ_Helpers::$version;?>" type="text/css" rel="stylesheet" />            
            <script type="text/javascript" src="<?php echo MQA_PLUGIN_URL . 'shortcode/public/dist/bundle.js?ver=' . MQZ_Helpers::$version;?>"></script>
            <?php
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        return false;
    }
}