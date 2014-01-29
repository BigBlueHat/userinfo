<?php
/**
 * Responds to http://user@example.com/ requests when hosted at a location
 * that can route "root" URL requests to this PHP file (likely with the aid
 * of `.htaccess` or similar.
 *
 * NOTE: this is currently only setup for a single user at a single domain.
 * Some simple PHP for loading the representation(s) for the user based on
 * requested content types (i.e. read the `Accept` header, load the rep)
 * should be trivial to add.
 * 
 * Hopefully this shows the simplicity of the concept and implementation
 * regardless.
 **/

if (!empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW'])) {
  if ($_SERVER['PHP_AUTH_USER'] === 'byoung') {
    header('X-XRDS-Location: http://bigbluehat.myopenid.com/?xrds=1');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Benjamin Young</title>
  <link rel="openid.server" href="http://www.myopenid.com/server" />
  <link rel="openid.delegate" href="http://bigbluehat.openid.com/" />
  <link rel="openid2.local_id"
        href="http://bigbluehat.myopenid.com" />
  <link rel="openid2.provider"
        href="http://www.myopenid.com/server" />
</head>
<body>
<div id="hcard-Benjamin-Young" class="vcard">
 <a class="url fn" href="http://linkedin.com/in/benjaminyoung/">Benjamin Young</a>
 <div class="adr">
  <span class="locality">Greenville</span>,
  <span class="region">SC</span>,
  <span class="postal-code">29609</span>
 </div>
</div>
</body>
</html>
<?php
exit;
  }
}
