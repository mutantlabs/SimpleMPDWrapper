<?php

    include 'vendor/autoload.php';
    include 'helpers/BaseUrl.php';
    include 'helpers/PocketMP.php';

    use RestService\Server;

    Server::create('/')
        ->addGetRoute('', function(){
            $baseUrl = new BaseUrl();
            $response = array(
                'message' => 'Welcome to the Mutant Pi API',
                'menu' => array(
                    'add song' => $baseUrl->url()."add",
                    'control player' => $baseUrl->url()."control",
                    'current song' => $baseUrl->url()."current"
                )
            );
            return $response;
        })
        ->addGetRoute('add/(.*)', function($data){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            $response = array(
                'message' => 'track sent to mutant playlist',
                'track' => $data,
                'response' => $mp->send('add', $data)
            );
            return $response;
        })
        ->addGetRoute('add', function(){
            $baseUrl = new BaseUrl();
            $response = array(
                'message' => 'Requires a valid spotify URI',
                'options' => array(
                    'default' => array(
                        "description" => "spotify URI",
                        "example" => $baseUrl->url()."/spotify:track:48mZ0CGCffjH49h5lAPTIe"
                    )
                )
            );
            return $response;
        })
        ->addGetRoute('control',function(){
            $baseUrl = new BaseUrl();
            $response = array(
                'message' => 'Requires a valid spotify URI',
                'options' => array(
                    'default' => array(
                        "play" => $baseUrl->url()."/control/play",
                        "pause" => $baseUrl->url()."/control/pause"
                    )
                )
            );
            return $response;
        })
        ->addGetRoute('control/(.*)',function($action){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            return $mp->send($action, "");
        })
        ->addGetRoute('current',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            return $mp->send('currentsong', "");
        })
        ->run();


