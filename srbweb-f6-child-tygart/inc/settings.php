<?php
    /**
     * Adds Or Removes Settings Constants
     * @param $mySettingList
     * @param $mySettings
     *
     * @return array
     */
	if(class_exists('mySettings')) {
		function mySettingListChildTheme( $mySettingList, $mySettings ) {
			// Add the new settings
			$mySettingList[] = 'GOOGLE_CSE_CODE';
		  //	$mySettingList[] = 'WM_EMAIL_ADDRESS';
			//$mySettingList[] = 'EMBARGO_DAYS';

			// Remove a setting called "testRemove" if it exists
			/*$exists = array_search('testRemove', $mySettingList);
			if ( $exists !== false ) {
				unset($mySettingList[$exists]);
			}*/

			return $mySettingList;
		}

		add_filter( 'mySettingList', 'mySettingListChildTheme', null, 2 );

		//Proper way to get and use settings values: set to variable and define as constant
		//$google_cse = my_get_setting( 'GOOGLE_CSE_CODE' );
		//define( "GOOGLE_CSE_CODE", my_get_setting( 'GOOGLE_CSE_CODE' ) );

		//$unsuburl = my_get_setting( 'UNSUBSCRIBE_SCRIPT_URL' );
		//define( "UNSUBSCRIBE_SCRIPT_URL", my_get_setting( 'UNSUBSCRIBE_SCRIPT_URL' ) );

		//$wmemail = my_get_setting( 'WM_EMAIL_ADDRESS' );
		//define( "WM_EMAIL_ADDRESS", my_get_setting( 'WM_EMAIL_ADDRESS' ) );

		//$embargodays = my_get_setting( 'EMBARGO_DAYS' );
		//define( "EMBARGO_DAYS", my_get_setting( 'EMBARGO_DAYS' ) );
	}
?>
