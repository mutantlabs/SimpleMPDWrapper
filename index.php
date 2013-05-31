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
                    'spotify' => $baseUrl->url()."addTrackToPlaylist"
                )
            );
            return $response;
        })
        ->addGetRoute('addTrackToPlaylist/(.*)', function($data){
            $mp = new PocketMP("","192.168.1.120",6600,0);

            $response = array(
                'message' => 'track sent to mutant playlist',
                'track' => $data,
                'response' => $mp->send('add', $data)
            );
            return $response;
        })
        ->addGetRoute('addTrackToPlaylist', function(){
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
        ->addGetRoute('play',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            return $mp->send('play', "");
        })
        ->addGetRoute('pause',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            return $mp->send('pause', "");
        })
        ->run();


