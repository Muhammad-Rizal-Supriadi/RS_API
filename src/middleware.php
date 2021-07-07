<?php

use Slim\App;
use Tuupola\Middleware\JwtAuthentication;
return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);
    
    //middleware untuk validasi token JWT 
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "path" => "/api", /* or ["/api", "/admin"] */
        "secure" => false,
        "attribute" => "decoded_token_data",
        "secret" => "supersecretkeyyoushouldnotcommittogithub",
        "algorithm" => ["HS256"],
        "error" => function ($req, $res, $args) {
        $data["status"] = "error";
        $data["message"] = $args["message"];
        return $res
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
        ]));

    // APY KEY
    // $app->add(function ($request, $response, $next) {
    
    //     $key = $request->getQueryParam("key");
    
    //     if(!isset($key)){
    //         return $response->withJson(["status" => "API Key required"], 401);
    //     }
        
    //     $sql = "SELECT * FROM api_users WHERE api_key=:api_key";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":api_key" => $key]);
        
    //     if($stmt->rowCount() > 0){
    //         $result = $stmt->fetch();
    //         if($key == $result["api_key"]){
            
    //             // update hit
    //             $sql = "UPDATE api_users SET hit=hit+1 WHERE api_key=:api_key";
    //             $stmt = $this->db->prepare($sql);
    //             $stmt->execute([":api_key" => $key]);
                
    //             return $response = $next($request, $response);
    //         }
    //     }
    
    //     return $response->withJson(["status" => "Unauthorized"], 401);
    
    // });
    
};


