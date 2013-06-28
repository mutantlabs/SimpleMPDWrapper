<?php

    /*

    Example of an API using SimpleMPDWrapper and https://github.com/marcj/php-rest-service

    */

    include 'vendor/autoload.php';
    include 'helpers/BaseUrl.php';
    include 'MPDWrapper/SimpleMPDWrapper.php';

use MPDWrapper\SimpleMPDWrapper;
use RestService\Server;

    Server::create('/')
        ->addGetRoute('', function(){
            $baseUrl = new BaseUrl();
            $response = array(
                'message' => 'Welcome to the Mutant MPD API',
                'view this nicely' => 'http://jsonview.com/',
                'menu' => array(
                    'status' => $baseUrl->url()."status",
                    'add song' => $baseUrl->url()."add",
                    'control player' => $baseUrl->url()."control",
                    'current song' => $baseUrl->url()."current",
                    'volume to integer' => $baseUrl->url()."volume",
                    'volume up' => $baseUrl->url()."volUp",
                    'volume down' => $baseUrl->url()."volDown",
                    'clear playlist' => $baseUrl->url()."clear"
                )
            );
            return $response;
        })
        ->addGetRoute('status',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->status();
        })
        ->addGetRoute('add/(.*)', function($data){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            $response = array(
                'message' => 'track sent to mutant playlist',
                'track' => $data,
                'response' => $mp->add($data)
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
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send($action, "");
        })
        ->addGetRoute('current',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
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
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send('setvol', $volume);
        })
        ->addGetRoute('getVolume',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            $data = $mp->send('status');
            $volume = explode(":",$data['ret'][0]);
            return array('volume' => (int)$volume[1]);
        })
        ->addGetRoute('volUp',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            $data = $mp->status();
            $volume = explode(":",$data['ret'][0]);
            $newVolume = $volume[1] + 1;
            $mp->send('setvol', (int)$newVolume);
            return array(
                'volume' => (int)$newVolume
            );
        })
        
        ->addGetRoute('volDown',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            $data = $mp->send('status');
            $volume = explode(":",$data['ret'][0]);
            $newVolume = $volume[1] - 1;
            $mp->send('setvol', (int)$newVolume);
            return array(
                'volume' => (int)$newVolume
            );
        })
        ->addGetRoute('clear',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send('clear');
        })
        ->addGetRoute('random/(.*)',function($int){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send('random', $int);
        })
        ->addGetRoute('repeat/(.*)',function($int){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send('repeat', $int);
        })
        ->addGetRoute('playlist',function(){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->send('playlistinfo');
        })
        ->addGetRoute('move/(.*)/(.*)',function($from,$to){
            $mp = new SimpleMPDWrapper("","192.168.1.121",6600,0);
            return $mp->move($from,$to);
        })
        ->run();


