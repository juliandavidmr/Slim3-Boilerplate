<?php

class AuthMiddleware
{

    /**
     * Example middleware invokable class
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
        
        /********************
         * Write your magic
         ********************/
        
        // $response->getBody()->write('BEFORE');
        
        return $response;
    }
}