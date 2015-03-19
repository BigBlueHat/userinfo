from urlparse import urlparse

import requests


def userinfo(url, accept="text/html"):
    o = urlparse(url)

    r = requests.get('{}://{}'.format(o.scheme, o.netloc),
                     headers={'User-Info': o.username,
                              'Accept': accept})
    return r
