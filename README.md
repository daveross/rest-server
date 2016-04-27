# rest-server
## Socket-based PHP Server Microframework.

So many PHP frameworks assume they're running behind a web server like Apache or Nginx. This framework functions more like Express from the node.js community. Code listens on a TCP port and handles HTTP requests directly. This allows for efficient processing and lightweight setup.

## Usage

All code uses `namespace DaveRoss\RestServer`.

### Spinning up a server

The restServer function starts a server listening on a port.

`restServer($port, callable $fn, $host = '127.0.0.1', $maxRequestSize = 9999)'`

* `$port` is the TCP port number to listen on (ex. port 80 for public HTTP, 8000 for a web app backend)
* `$fn` is a function to call when a request comes in, or an instance of `RestRouter`.
* `$host` is used when a server has more than one network interface available. It specifies which interface to listen on.
* `$maxRequestSize` is used to override the default payload size. Increase this number if you're expecting large POST requests.

If a function is passed for $fn, it'll receive two parameters: a `RestRequest` and a `RestResponse`:

```php
function handleRequest( RestRequest $request, RestResponse $response ) {
  // do something
  return $response;
}

restServer( 8000, 'handleRequest' );
```

### RestRequest

A `RestRequest` has five public variables with data from the request:

* `$method` contains the request's HTTP method, such as "GET" or "POST"
* `$path` contains the requested path, such as "/hello-world"
* `$protocol` contains the request's protocol (usually HTTP or HTTPS)
* `$headers` contains an associative array of the request's HTTP headers
* `$payload` contains the request's body (usually only applies to "POST" requests)

### RestResponse

A `RestResponse` has four public variables for sending a response back to the requester:

* `$statusCode` is the HTTP status code (defaults to 200)
* `$statusDescription` is the status code's description defaults to "OK"
* `$headers` is an array of HTTP headers sent with the response
* `$payload` is the body of the response

There are also sonme convenience methods defined on RestResponse:

* `setStatusCode*($code)` takes a status code (ex. 200) and sets both the code & its statusDescription
* `header($key, $value)` sets a new HTTP header on the response
* `setPayload($payload, $format)` sets the response body. If `$format` is `RestResponse::FORMAT_TEXT`, the `$payload` is used as-is. If it's `RestResponse::FORMAT_JSON`, the `$payload` is JSON-encoded first.

