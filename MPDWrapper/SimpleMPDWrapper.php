<?php
/**
 * Created by Mutant Labs
 * User: mijahn
 * Date: 31/05/13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */

/*
    SimpleMPDWrapper Class useage

    // Construct a new PocketMp instance
    // Required parameters: Password, MPD Server address, Port, Refresh interval
    $mp = new PocketMP("","192.168.0.1",6600,0);

    // Send a command using the send method:
    echo json_encode($mp->send("add", "spotify:track:48mZ0CGCffjH49h5lAPTIe"));

    // Or utilise the quick method wrappers
    echo json_encode($mp->add("spotify:track:48mZ0CGCffjH49h5lAPTIe"));

*/
namespace MPDWrapper;

class SimpleMPDWrapper {
    private $fp;
    private $response;
    public function __construct($pass = "",$host = "",$port = 6600,$refresh = 0) {

        if(!isset($srch)) {

        }
        $this->fp = fsockopen($host,$port,$errno,$errstr,30); //Connect-String
        if(!$this->fp) {
            echo "$errstr ($errno)<br>\n"; //Can we connect?
        }
        else {
            while(!feof($this->fp)) {
                $got = fgets($this->fp,1024);
                if(strncmp("OK",$got,strlen("OK"))==0) //MPD Ready...
                break;
                $this->response = "$got<br>";
                if(strncmp("ACK",$got,strlen("ACK"))==0) //What"s going wrong?
                break;
            }
            if($pass != "") { //Password needed?
                fputs($this->fp,"password \"$pass\"\n"); //Check Password
                while(!feof($this->fp)) {
                    $got = fgets($this->fp,1024);
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

    }

    /*
     Our send method handles all commands and responses, you can use this directly or the quick method wrappers below
    */

    public function send($method,$arg1="",$arg2="") {

        // if we have a string, send it as well
        if($arg1 != "" && $arg2 != "")
        {
            $command = "$method \"$arg1\" \"$arg2\"";
        }
        elseif($arg1 != "") {
            $command = "$method \"$arg1\"";
        }
        else {
            $command = $method;
        }

        
        fputs($this->fp,"$command\n"); //Do desired action!
        $c = 0;
        while(!feof($this->fp)) {
            $got =  fgets($this->fp,1024);
            if(strncmp("OK",$got,strlen("OK"))==0)
                break;
            if(strncmp("ACK",$got,strlen("ACK"))==0)
                break;

            $ret[$c]=$got;
            $c++;
        }

        $sentResponse = array(
            "response" => $this->response,
            "got" => $got,
            "ret" => $ret
        );
        return $sentResponse;

    }

    /*
    Quick method wrappers

    // Construct a new PocketMp instance
    // Required parameters: Password, MPD Server address, Port, Refresh interval
    $mp = new PocketMP("","192.168.0.1",6600,0);

    // Call a quick method

    echo json_encode($mp->add("spotify:track:48mZ0CGCffjH49h5lAPTIe"));

    Extend this to make your own quick method wrappers
    e.g.

    public function status() {
        return $this->send("status",""); // second parameter null as status requires no args
    }



    */

    public function add($string) {
        return $this->send("add",$string);
    }

    public function status() {
        return $this->send("status"); // no second parameter null as status requires no args
    }

    public function clear() {
        return $this->send("clear");
    }

    public function currentSong() {
        return $this->send("currentsong");
    }

    public function move($from,$to) {
        return $this->send("move", $from,$to);
    }
}