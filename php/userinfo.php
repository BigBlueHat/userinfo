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

require 'vendor/autoload.php';

/**
 * Simple array holding various media type definitions of the same #me
 * TODO: ...share the #me data among them
 **/
$provided = array();
$provided['text/html'] = <<<EOD
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
EOD;

$provided['text/turtle'] = <<<EOD
@prefix foaf: <http://xmlns.com/foaf/0.1/> .

<> a foaf:PersonalProfileDocument ;
  foaf:maker <#me> ;
  foaf:primaryTopic <#me> .

<#me> a foaf:Person ;
  foaf:name "Benjamin Young" ;
  foaf:givenname "Benjamin" ;
  foaf:family_name "Young" ;
  foaf:mbox_sha1sum "61229e8c81dd3f98aa0d516c2ea1244659d57669" ;
  foaf:homepage <http://bigbluehat.com/> .
EOD;
// Used http://service.simile-widgets.org/babel/ to convert between these
$provided['application/rdf+xml'] = <<<EOD
<rdf:RDF
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      xmlns:foaf="http://xmlns.com/foaf/0.1/"
      xmlns:admin="http://webns.net/mvcb/">
<foaf:PersonalProfileDocument rdf:about="">
  <foaf:maker rdf:resource="#me"/>
  <foaf:primaryTopic rdf:resource="#me"/>
  <admin:generatorAgent rdf:resource="http://www.ldodds.com/foaf/foaf-a-matic"/>
  <admin:errorReportsTo rdf:resource="mailto:leigh@ldodds.com"/>
</foaf:PersonalProfileDocument>
<foaf:Person rdf:ID="me">
<foaf:name>Benjamin Young</foaf:name>
<foaf:givenname>Benjamin</foaf:givenname>
<foaf:family_name>Young</foaf:family_name>
<foaf:mbox_sha1sum>61229e8c81dd3f98aa0d516c2ea1244659d57669</foaf:mbox_sha1sum>
<foaf:homepage rdf:resource="http://bigbluehat.com/"/></foaf:Person>
</rdf:RDF>
EOD;

/**
 * Picks from the above $provided array, and returns the "best match" by doing
 * some simple content negotiation (conneg).
 **/
function respond($provided) {
  $type = \Bitworking\Mimeparse::bestMatch(array_keys($provided),
            $_SERVER['HTTP_ACCEPT']);

  if (isset($provided[$type])) {
    header("Content-Type: {$type}");
    return $provided[$type];
  } else {
    header('HTTP/1.1 406 Not Acceptable');
  }
}

/**
 * userinfo.me "bit"
 * TODO: make this work for more than just the example `byoung` #me
 **/
if (!empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW'])) {
  if ($_SERVER['PHP_AUTH_USER'] === 'byoung') {
    header('X-XRDS-Location: http://bigbluehat.myopenid.com/?xrds=1');
    echo respond($provided);
  }
}
