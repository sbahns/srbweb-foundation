<?php
/* Privacy */
define("_OURPROMISE", "You have our promise not to sell or share your email address &mdash; ever!");
// POP UP WINDOW
define("_PRIVACYPOLICY", '<p class="mouse">We hate spam as much as you do. '. _OURPROMISE . ' Please read our <a href="/account/privacy-policy/" onclick="newWindow(\'' . get_bloginfo("template_directory") . '/popup_privacy_policy.php\', \'Privacy\', \'600\', \'200\', \'yes\'); return false;" title="">privacy policy</a>.</p>');
// NOLINK
define("_PRIVACYPOLICY_NOLINK",'<p class="mouse">We hate spam as much as you do. Your name and email address will not be sold or shared with any other organization unless required by law.</p>');
?>
