userinfo - http://user@example.com/
===================================

    curl -X GET http://byoung@bigbluehat.com/

I've been typing email addresses into browser
address bars for over a decade. Now, I want
something back.

## A User URI Scheme

`mailto` and (more recently) `acct` provide
methods of identifying an entity (person, bot,
etc) by an email address.

The `userinfo` portion of a URI
(per [RFC3986](http://tools.ietf.org/html/rfc3986#section-3.2.1))
provides for such a format in HTTP URLs:

> The userinfo subcomponent may consist of a user name and, **optionally**,
  scheme-specific information about how to gain authorization to access
  the resource.

There's more in that spec and in the more recent
`httpbis` that runs away from the use of this
`userinfo` URI component, but there is untapped value here.

Additionally, the [`userinfo` portion of the URL is mentioned in the WHATWG URL spec](http://url.spec.whatwg.org/#concept-url-userinfo) as follows:

> Userinfo must be a username, **optionally** followed by a ":" and a password.

Note: emphasis added to both specs...'cause that's the important bit.

## Initial Tests

HTTP clients will send a URL containing a `userinfo`
component as an HTTP Authorization header. For the
URL above, the outbound request looks like:

    GET / HTTP/1.1
    Authorization: Basic YnlvdW5nOg==
    User-Agent: curl/7.26.0
    Host: bigbluehat.com
    Accept: */*

The server will treat this as an HTTP Basic
Authentication request. That Base64 encoded
bit after the word "Basic" above is the username
`byoung` without a colon. Most clients will also include
a `:` in Base64 portion. This can be stripped
server-side of course, but should not be sent in
the first place (re:
[RFC3986](http://tools.ietf.org/html/rfc3986#section-3.2.1)).

## Examples

```
curl http://byoung@bigbluehat.com/
```
Returns HTML + microformat hCard markup (at the moment).

```
curl -H "Accept: text/turtle" http://byoung@bigbluehat.com/
```
Returns a [WebID](http://www.w3.org/2005/Incubator/webid/spec/identity/) document.

```
curl -H "Accept: image/jpeg" http://byoung@bigbluehat.com/ > byoung.jpg
```
Returns a photo.

## Yeah, so?

For me, this means a RESTful endpoint for myself.
I intend to use
[content negotiation](http://en.wikipedia.org/wiki/Content_negotiation)
to deliver my profile pic (in various formats) and
social or authentication related markup: FOAF, XRDS,
and hCard + OpenID discovery meta tags (done, but
needs further testing and exploration).

For the web, my hope is this will prove a useful
pattern that could be adopted to more simply
locate a person's public content. It's easy to
imagine profile URLs such as `http://bigbluehat@twitter.com/`
which at the very least could [302](http://httpstatus.es/302) to my current
Twitter profile: http://twitter.com/bigbluehat

## Much more

There's much more to explore and explain, but
this idea has sat in my head far too long and
I needed it out in the wild to make room for
new ones.

My hope is to work this toward an
[Internet-Draft](http://www.ietf.org/id-info/)
at the IETF where I'd love to see it become an
RFC that people find useful, interesting, and
at least inspring.

## Contribute

The biggest need right now is client testing,
reporting, and patching.

I'll be documenting this idea further in this
repo in the coming weeks. For now, please use
the issues on this repo to send me your thoughts.

## License

All code (forthcoming) will be licensed under the
Apache Foundation License 2.0.
