# User-Info JavaScript Client

The implementation...

- takes a [userinfo.me](http://userinfo.me/) style URL
- parses out the username
- puts that in the `User-Info` header
- (optionally) sends and `Accept` header
- and returns a simple promise function for dealing with the result

### Details

The XMLHttpRequest code is based on [pegasus](https://github.com/typicode/pegasus)
(which is MIT licensed).

The customizations are to allow a customized XHR object to be used
since we need to add request headers.

## Try it!

Open `index.html` in your browser. No web server needed.

The endpoint you test must support CORS--in particular the
`Access-Control-Allow-Headers: User-Info` header.

Otherwise, you're good to go.

### License

Apache License 2.0
