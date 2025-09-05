<?php 
/* Retrieve the URL of the cookie page that is set in the customizer */
$privacy_cookie_url = get_theme_mod( 'open_privacy_cookies_url' );

if ( (!empty($privacy_cookie_url)) and (check_cookie_cookie() <> '1') ) {
?>


<div id="banner-cookie-bar">
<div id="cookie-message">
We use cookies to ensure that we give you the best experience on our website. If you continue without changing your settings, we’ll assume that you are happy to receive all cookies on the Open Inclusion website. However, if you would like to, you can change your cookie settings at any time. <a href="<?php echo $privacy_cookie_url; ?>">Read our cookie policy</a>.
</div>
<div id="cookie-button">
<p id="cookie-accept-nojs"><a class="button" href="<?php echo get_the_permalink().'?cookiedis=1'; ?>" >Continue</a></p>
<button id="cookie-accept-js">Continue</button>	
</div>
</div><!-- #banner-cookie-bar -->
<?php 
}
?>