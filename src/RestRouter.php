<?php 

namespace DaveRoss\RestServer;

class RestRouter {
    
    private $routes;
    
    function __construct() {
        $this->routes = array();
    }
    
    public function get($path, callable $fn) {
        $this->routes['get'][$path] = $fn;
    }
    
    public function post($path, callable $fn) {
        $this->routes['post'][$path] = $fn;
    }
    
    public function __invoke(RestRequest $request, RestResponse $response) {
        
        if(isset($this->routes[strtolower($request->method)][$request->path])) {
            return $this->routes[strtolower($request->method)][$request->path]($request, $response);
        }

        return $response;
                
    }
}