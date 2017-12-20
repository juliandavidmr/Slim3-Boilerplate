<?php

class RegisterEventMiddleware
{

    /**
     * Middleware invokable class
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *            PSR7 request
     * @param \Psr\Http\Message\ResponseInterface $response
     *            PSR7 response
     * @param callable $next
     *            Next middleware
     *            
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);
        
        $browser = new BrowserDetection();
        $agent = sprintf("(%s)-(%s)-OS(%s)-vOS(%s)", $browser->getName(), $browser->getVersion(), $browser->getPlatform(), $browser->getPlatformVersion());
        $host = $browser->getClientIP();
        
        // Get user from token
        $http_authorization = $request->getHeader('HTTP_AUTHORIZATION');        
    
        /********************
         * Write your magic
         ********************/

        // $response->getBody()->write("BEFORE $host");
        
        return $response;
    }
}