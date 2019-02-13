<?php
if ( function_exists('my_get_setting') ) {
    define('WP_SERVER', my_get_setting('WP_SERVER'));
    define('POST_EXCERPT_LENGTH_HOME', my_get_setting('POST_EXCERPT_LENGTH_HOME'));
    define('POST_HOMEPAGE_NUMBER_OF_POSTS', my_get_setting('POST_HOMEPAGE_NUMBER_OF_POSTS'));
    define('TEXT_READMORE', my_get_setting('TEXT_READMORE'));
    define('CS_EMAIL', my_get_setting('CS_EMAIL'));
    define('RETURN_EMAIL', my_get_setting('RETURN_EMAIL'));
    define('BYPASS_OPENX', my_get_setting('BYPASS_OPENX'));
	  define('USE_CATEGORY_IMAGES', my_get_setting('USE_CATEGORY_IMAGES'));
    define('PRIVACY_POLICY', my_get_setting('PRIVACY_POLICY'));
}
?>
