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
                'view this nicely' => 'http://jsonview.com/',
                'menu' => array(
                    'add song' => $baseUrl->url()."add",
                    'control player' => $baseUrl->url()."control",
                    'current song' => $baseUrl->url()."current",
                    'volume to integer' => $baseUrl->url()."volume",
                    'volume up' => $baseUrl->url()."volUp",
                    'volume down' => $baseUrl->url()."volDown"
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
                'message' => 'play, pause etc',
                'options' => array(
                    'e.g.' => array(
                        "play" => $baseUrl->url()."/play",
                        "pause" => $baseUrl->url()."/pause"
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
        ->addGetRoute('volume',function(){
            $baseUrl = new BaseUrl();
            $response = array(
                'message' => 'set vol to integer',
                'options' => array(
                    'e.g.' => array(
                        "50%" => $baseUrl->url()."/50",
                        "100%" => $baseUrl->url()."/100"
                    )
                )
            );
            return $response;
        })
        ->addGetRoute('volume/(.*)',function($volume){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            return $mp->send('setvol', $volume);
        })
        ->addGetRoute('getVolume',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            $data = $mp->send('status', '');
            $volume = explode(":",$data['ret'][0]);
            return array('volume' => (int)$volume[1]);
        })
        ->addGetRoute('volUp',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            $data = $mp->send('status', '');
            $volume = explode(":",$data['ret'][0]);
            $newVolume = $volume[1] + 1;
            $mp->send('setvol', (int)$newVolume);
            return array(
                'volume' => (int)$newVolume
            );
        })
        ->addGetRoute('volDown',function(){
            $mp = new PocketMP("","192.168.1.120",6600,0);
            $data = $mp->send('status', '');
            $volume = explode(":",$data['ret'][0]);
            $newVolume = $volume[1] - 1;
            $mp->send('setvol', (int)$newVolume);
            return array(
                'volume' => (int)$newVolume
            );
        })
        ->run();


