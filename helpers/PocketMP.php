<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mijahn
 * Date: 31/05/13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */

class PocketMP {
    private $fp;
    private $response;
    public function __construct($pass = "",$host = "192.168.1.120",$port = 6600,$refresh = 0) {

        if(!isset($srch)) {

        }
        $fp = fsockopen($host,$port,$errno,$errstr,30); //Connect-String
        if(!$fp) {
            echo "$errstr ($errno)<br>\n"; //Can we connect?
        }
        else {
            while(!feof($fp)) {
                $got = fgets($fp,1024);
                if(strncmp("OK",$got,strlen("OK"))==0) //MPD Ready...
                break;
                $this->response = "$got<br>";
                if(strncmp("ACK",$got,strlen("ACK"))==0) //What's going wrong?
                break;
            }
            if($pass != "") { //Password needed?
                fputs($fp,"password \"$pass\"\n"); //Check Password
                while(!feof($fp)) {
                    $got = fgets($fp,1024);
                    if(strncmp("OK",$got,strlen("OK"))==0) { //Password OK
                        #print "Login Succesful<br>\n";
                        break;
                    }
                    $this->response = "$got<br>";
                    if(strncmp("ACK",$got,strlen("ACK"))==0) //Password Wrong
                    break;
                    die("Wrong Password?");
                }
            }
        }

        $this->fp = $fp;

    }

    public function send($method,$string) { //Retrieve Status Information from MPD

        $command = ($method && $method == "add") ? "$method \"$string\"": $method;

        $fp = $this->fp;
        fputs($fp,"$command\n"); //Do desired action!
        $c = 0;
        while(!feof($fp)) {
            $got =  fgets($fp,1024);
            if(strncmp("OK",$got,strlen("OK"))==0)
                break;
            if(strncmp("ACK",$got,strlen("ACK"))==0)
                break;

            $ret[$c]=$got;
            $c++;
        }
        
        $sentResponse = array(
            'response' => $this->response,
            'got' => $got,
            'ret' => $ret
        );
        return $sentResponse;

    }

}