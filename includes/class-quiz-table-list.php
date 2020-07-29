<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Quiz_Table_List extends WP_List_Table {

    // function __construct() {
    //     parent::__construct( array(
    //     'singular'=> 'quiz', //Singular label
    //     'plural' => 'quiz', //plural label, also this well be one of the table css class
    //     'ajax'   => false //We won't support Ajax for this table
    //     ) );
    // }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['ID']
        );
    }

    public function column_Title($item) {
        return sprintf(
            '<a class="row-title" href="%s">%s</a>
            <a href="%s" target="_blank" class="btn-action">%s</a>',
            admin_url( 'post-new.php?post_type=quiz&id=' .$item['ID'] ), $item["Title"],
            get_permalink($item["ID"]), '<svg width="22" height="14" viewBox="0 0 22 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11 0C15.8644 0 20.0444 2.75 22 6.72222C20.0444 10.6944 15.8644 13.4444 11 13.4444C6.13556 13.4444 1.95556 10.6944 0 6.72222C1.95556 2.75 6.13556 0 11 0ZM13.8722 3.78862C13.8722 3.06751 13.3956 2.46862 12.7478 2.27306C12.5522 2.24862 12.3567 2.2364 12.1489 2.22418C11.3545 2.28529 10.7189 2.95751 10.7189 3.78862C10.7189 4.6564 11.4278 5.36529 12.2956 5.36529C13.1756 5.36529 13.8722 4.6564 13.8722 3.78862ZM11 11.9776C15.1067 11.9776 18.6389 9.67983 20.2889 6.72205C19.1523 4.68094 17.1111 3.2876 14.6056 2.6276C15.4245 3.5076 15.9378 4.69316 15.9378 6.00094C15.9378 8.72649 13.7256 10.9387 11 10.9387C8.27447 10.9387 6.06225 8.72649 6.06225 6.00094C6.06225 4.69316 6.57559 3.5076 7.39447 2.6276C4.88892 3.2876 2.84781 4.68094 1.71114 6.72205C3.36114 9.67983 6.89336 11.9776 11 11.9776Z" fill="#888888"/>
<mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="14">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11 0C15.8644 0 20.0444 2.75 22 6.72222C20.0444 10.6944 15.8644 13.4444 11 13.4444C6.13556 13.4444 1.95556 10.6944 0 6.72222C1.95556 2.75 6.13556 0 11 0ZM13.8722 3.78862C13.8722 3.06751 13.3956 2.46862 12.7478 2.27306C12.5522 2.24862 12.3567 2.2364 12.1489 2.22418C11.3545 2.28529 10.7189 2.95751 10.7189 3.78862C10.7189 4.6564 11.4278 5.36529 12.2956 5.36529C13.1756 5.36529 13.8722 4.6564 13.8722 3.78862ZM11 11.9776C15.1067 11.9776 18.6389 9.67983 20.2889 6.72205C19.1523 4.68094 17.1111 3.2876 14.6056 2.6276C15.4245 3.5076 15.9378 4.69316 15.9378 6.00094C15.9378 8.72649 13.7256 10.9387 11 10.9387C8.27447 10.9387 6.06225 8.72649 6.06225 6.00094C6.06225 4.69316 6.57559 3.5076 7.39447 2.6276C4.88892 3.2876 2.84781 4.68094 1.71114 6.72205C3.36114 9.67983 6.89336 11.9776 11 11.9776Z" fill="white"/>
</mask>
<g mask="url(#mask0)">
</g>
</svg>'
        );
    }
    
    public function column_Shortcode($item) {
        return sprintf(
            '[innovere-survey id="%s"]',
            $item['ID']
        );
    }

    public function column_Action($item) {

        return sprintf("<div class='quiz-actions-wrap'><form method='post' action=''>
            <a href='%s' class='btn-action' title='Edit'>%s</a>
            <a href='%s' target='_blank' data-view='%s' class='btn-action' title='View'>%s</a>
            <button type='submit' name='quiz-duplicate' value='%s' class='btn-action btn-duplicate' title='Duplicate'>%s</button>
            <a href='javascript:void(0);'  class='btn-action btn-copy' data-id='%s' title='Get Shortcode'>%s</a>
            <button type='submit' name='quiz-delete' value='%s' class='btn-action' title='Delete'>%s</button> </form></div>",
            admin_url( 'post-new.php?post_type=quiz&id=' .$item['ID'] ),
            // get_post_field('post_name', $item["ID"]), $item["ID"],
            '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M14.3827 1.75C14.9556 1.75 15.5048 1.97874 15.9058 2.38299L18.6191 5.09628C19.0231 5.50025 19.25 6.04815 19.25 6.61944C19.25 7.19074 19.0231 7.73864 18.6191 8.14261L8.71282 18.0458C8.10165 18.7508 7.23529 19.184 6.24277 19.252H1.75V18.377L1.75284 14.6889C1.82738 13.7662 2.25633 12.9083 2.91068 12.3317L12.8585 2.38405C13.2618 1.97823 13.8104 1.75 14.3827 1.75ZM6.18098 17.5042C6.64843 17.4711 7.08355 17.2536 7.43294 16.8539L14.0497 10.2372L10.7646 6.95198L4.10909 13.6059C3.75458 13.9194 3.53527 14.3581 3.5 14.7594V17.5026L6.18098 17.5042ZM12.0022 5.71469L15.2871 8.99975L17.3817 6.90517C17.4575 6.82939 17.5 6.72661 17.5 6.61944C17.5 6.51228 17.4575 6.4095 17.3817 6.33372L14.666 3.618C14.5911 3.54248 14.4891 3.5 14.3827 3.5C14.2763 3.5 14.1743 3.54248 14.0994 3.618L12.0022 5.71469Z" fill="#757575"/>
</svg>',
get_permalink($item["ID"]), $item["ID"],
'<svg width="22" height="14" viewBox="0 0 22 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11 0C15.8644 0 20.0444 2.75 22 6.72222C20.0444 10.6944 15.8644 13.4444 11 13.4444C6.13556 13.4444 1.95556 10.6944 0 6.72222C1.95556 2.75 6.13556 0 11 0ZM13.8722 3.78862C13.8722 3.06751 13.3956 2.46862 12.7478 2.27306C12.5522 2.24862 12.3567 2.2364 12.1489 2.22418C11.3545 2.28529 10.7189 2.95751 10.7189 3.78862C10.7189 4.6564 11.4278 5.36529 12.2956 5.36529C13.1756 5.36529 13.8722 4.6564 13.8722 3.78862ZM11 11.9776C15.1067 11.9776 18.6389 9.67983 20.2889 6.72205C19.1523 4.68094 17.1111 3.2876 14.6056 2.6276C15.4245 3.5076 15.9378 4.69316 15.9378 6.00094C15.9378 8.72649 13.7256 10.9387 11 10.9387C8.27447 10.9387 6.06225 8.72649 6.06225 6.00094C6.06225 4.69316 6.57559 3.5076 7.39447 2.6276C4.88892 3.2876 2.84781 4.68094 1.71114 6.72205C3.36114 9.67983 6.89336 11.9776 11 11.9776Z" fill="#888888"/>
<mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="14">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11 0C15.8644 0 20.0444 2.75 22 6.72222C20.0444 10.6944 15.8644 13.4444 11 13.4444C6.13556 13.4444 1.95556 10.6944 0 6.72222C1.95556 2.75 6.13556 0 11 0ZM13.8722 3.78862C13.8722 3.06751 13.3956 2.46862 12.7478 2.27306C12.5522 2.24862 12.3567 2.2364 12.1489 2.22418C11.3545 2.28529 10.7189 2.95751 10.7189 3.78862C10.7189 4.6564 11.4278 5.36529 12.2956 5.36529C13.1756 5.36529 13.8722 4.6564 13.8722 3.78862ZM11 11.9776C15.1067 11.9776 18.6389 9.67983 20.2889 6.72205C19.1523 4.68094 17.1111 3.2876 14.6056 2.6276C15.4245 3.5076 15.9378 4.69316 15.9378 6.00094C15.9378 8.72649 13.7256 10.9387 11 10.9387C8.27447 10.9387 6.06225 8.72649 6.06225 6.00094C6.06225 4.69316 6.57559 3.5076 7.39447 2.6276C4.88892 3.2876 2.84781 4.68094 1.71114 6.72205C3.36114 9.67983 6.89336 11.9776 11 11.9776Z" fill="white"/>
</mask>
<g mask="url(#mask0)">
</g>
</svg>
',$item['ID'], '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M14.2657 11.9653H5.7063V3.40588H14.2657V11.9653ZM9.27273 6.97231H6.41958V8.39889H9.27273V11.252H10.6993V8.39889H13.5524V6.97231H10.6993V4.11917H9.27273V6.97231Z" fill="black" fill-opacity="0.54"/>
<path d="M1.42657 14.8185C1.42657 15.6031 2.06853 16.245 2.85315 16.245H12.8392V14.8185H2.85315V4.83246H1.42657V14.8185Z" fill="black" fill-opacity="0.54"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.7063 1.97931H14.2657C15.0504 1.97931 15.6923 2.62127 15.6923 3.40588V11.9653C15.6923 12.7499 15.0504 13.3919 14.2657 13.3919H5.7063C4.92168 13.3919 4.27972 12.7499 4.27972 11.9653V3.40588C4.27972 2.62127 4.92168 1.97931 5.7063 1.97931ZM5.70629 11.9653H14.2657V3.40588H5.70629V11.9653Z" fill="black" fill-opacity="0.54"/>
<path d="M9.27273 11.252H10.6993V8.39889H13.5525V6.97232H10.6993V4.11917H9.27273V6.97232H6.41959V8.39889H9.27273V11.252Z" fill="black" fill-opacity="0.54"/>
</svg>
',
$item["ID"],
'<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M9.06161 9.38432L5.89161 12.491L8.9983 15.661L7.5882 17.0528L3.08987 12.4628L7.67977 7.97432L9.06161 9.38432ZM14.9384 15.7209L18.1084 12.6142L15.0017 9.44424L16.4118 8.0525L20.9101 12.6425L16.3202 17.1309L14.9384 15.7209Z" fill="black" fill-opacity="0.54"/>
</svg>
', $item["ID"], '<svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M6.8728 0H12.3272C13.3313 0 14.1453 0.813999 14.1453 1.81812V2.72623H16.8725C17.8766 2.72623 18.6906 3.54023 18.6906 4.54434V6.36246C18.6906 7.36658 17.8766 8.18058 16.8725 8.18058H16.7997L15.9634 18.1819C15.9634 19.186 15.1494 20 14.1453 20H5.05468C4.05056 20 3.23656 19.186 3.2397 18.2574L2.39997 8.18058H2.32752C1.3234 8.18058 0.509399 7.36658 0.509399 6.36246V4.54434C0.509399 3.54023 1.3234 2.72623 2.32752 2.72623H5.05468V1.81812C5.05468 0.813999 5.86868 0 6.8728 0ZM5.05468 4.5453V4.54503H2.32771V6.36314H16.8727V4.54503H14.1453V4.5453H5.05468ZM5.05471 18.1807L4.22427 8.18106H14.9754L14.1484 18.1052L14.1453 18.1807H5.05471ZM12.3265 1.8191V2.72815H6.87219V1.8191H12.3265ZM6.23058 10.6425L7.51619 9.35693L9.60056 11.4413L11.6849 9.35693L12.9705 10.6425L10.8862 12.7269L12.9705 14.8113L11.6849 16.0969L9.60056 14.0125L7.51619 16.0969L6.23058 14.8113L8.31496 12.7269L6.23058 10.6425Z" fill="#757575"/>
</svg>
' );

    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',            
            'Title' => __('Title', ''),
            'Shortcode' => __('Shortcode', ''),
            'Action' => __('Action', '')
        );
        return $columns;
    }

   

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'Title' => array('Title', true),
        );
        return $sortable_columns;
    }

    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'innovere_survey';
      
        $per_page = 10;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        
        // $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(quiz_id) FROM $table_name");
        
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT quiz_name as Title, quiz_id as ID  FROM $table_name ", $per_page, $paged), ARRAY_A);
        
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}   