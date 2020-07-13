<?php

if ( !class_exists('MQZ_API_Resource_Not_Found_Exception') ) :
class MQZ_API_Resource_Not_Found_Exception extends MQZ_API_Exception {

	// Thrown when a requested resource does not exist in Mailchimp
}

endif;