<?php

return [
    // Pages web classiques
    ['GET', '/', 'HomeController@index'],
    ['GET', '/about', 'PageController@about'],
    ['GET', '/docs', 'PageController@docs'],
    ['GET', '/post/{id}/{slug}', 'PostController@view'],
    ['GET', '/users', 'UserController@index'],
    ['POST', '/set-lang', 'LangController@switch'],
    // API REST
    ['GET', '/api/posts', 'Api\PostController@index'],
    ['GET', '/api/posts/{id}', 'Api\PostController@show'],
    ['POST', '/api/posts', 'Api\PostController@store'],
    ['PUT', '/api/posts/{id}', 'Api\PostController@update'],
    ['DELETE', '/api/posts/{id}', 'Api\PostController@destroy'],
];
