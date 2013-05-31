<?php
#PocketMP V0.3.3 by Andreas Böhler
#Simple Front-End for MPD in PHP; I use it to control MPD from my Palm Tungsten!
#andy.boehler@gmx.at
#April 2007; Uses Code from phpMP, available at www.musicpd.org!
## OPTIONS ##

$pass = ""; //Leave empty if you don't need a password!
$host = "192.168.1.120"; //Host to connect to
$port = 6600; //Port MPD is listening
$refresh = 0; //Refresh-Interval; 0 to disable http-refresh

## No Changes below this line should be necessary!! ##

$srch = $_POST['srch'];
if(!isset($srch)) {
    $command = $_GET['command'];
}

function getStatus($fp) { //Retrieve Status Information from MPD
    $c = 0;
    fputs($fp,"status\n");
    while(!feof($fp)) {
        $got = fgets($fp,1024);
        if(strncmp("OK",$got,strlen("OK"))==0)
            break;
        $ret[$c]=$got;
        $c++;
    }
    return $ret;
}

function getSong($fp,$song) { //Retrieve Information on Current Song
    $c = 0;
    fputs($fp,"playlistinfo $song\n");
    while(!feof($fp)) {
        $got = fgets($fp,1024);
        if(strncmp("OK",$got,strlen("OK"))==0)
            break;
        $ret[$c]=$got;
        $c++;
    }
    return $ret;
}
?>
<html><head><meta http-equiv="expires" content="0">
    <?php
    if($refresh != 0) { //Is refresh enabled?
        echo "<meta http-equiv=\"refresh\" content=\"$refresh; URL=$PHP_SELF\">";
    }
    ?>
    <title>PocketMP</title><link rel="stylesheet" type="text/css" href="design.css"></head><body>

<?php
ob_start();
$fp = fsockopen($host,$port,$errno,$errstr,30); //Connect-String
if(!$fp) {
    echo "$errstr ($errno)<br>\n"; //Can we connect?
}
else {
    while(!feof($fp)) {
        $got = fgets($fp,1024);
        if(strncmp("OK",$got,strlen("OK"))==0) //MPD Ready...
        break;
        print "$got<br>";
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
            print "$got<br>";
            if(strncmp("ACK",$got,strlen("ACK"))==0) //Password Wrong
            break;
            die("Wrong Password?");
        }
    }
}
if(isset($srch)) { //Searching for Song
    $srch = utf8_encode($srch);
    fputs($fp,"playlist\n"); //Give complete Playlist
    while(!feof($fp)) {
        $got = fgets($fp,1024);
        if(strncmp("OK",$got,strlen("OK"))==0)
            break;
        if(stristr($got,$srch)) { //Filter Search-Pattern
            $cc = explode(":",$got);
            $str = base64_encode("play ".$cc[0]);
            echo "<a href=\"$PHP_SELF?command=".$str."\">".htmlentities(utf8_decode($cc[1]))."</a><br>"; //Give us a Link
        }
    }

}
if(isset($command)) { //Was a command sent to PocketMP?
    $command = base64_decode($command);
    fputs($fp,"$command\n"); //Do desired action!
    while(!feof($fp)) {
        $got =  fgets($fp,1024);
        if(strncmp("OK",$got,strlen("OK"))==0)
            break;
        if(strncmp("ACK",$got,strlen("ACK"))==0)
            break;
    }
}


$ret = getStatus($fp);
$state = explode(": ",$ret[6]);
$state = $state[1]; //Filter necessary Status Information
$song = explode(": ",$ret[7]);
$song = $song[1]; //Filter currently played Song
#print $state."<br>"; //Uncomment for debugging
#print $song."<br>"; //Uncomment for debugging

if(strncmp("play",$state,strlen("play"))==0) { //Are we playing?
    $dis = getSong($fp,$song);
#  print $dis; //Uncomment for debugging
    for($x=0;$x<count($dis);$x++) {
        $dc = explode(": ",$dis[$x]);
        if($dc[0] == "Title"){ //Get Title-Information
            $title = htmlentities(utf8_decode($dc[1])); //Decode UTF8 and Encode HTML Characters
        }
        if($dc[0] == "Artist"){ //Get Artist-Information
            $artist = htmlentities(utf8_decode($dc[1])); //Decode UTF8 and Encode HTML Characters
        }
    }
    print "<div id=\"headline\">Now Playing:</div><br>";
    print "<div id=\"Info\">".$artist."<br>";
    print $title."</div><br>";
}


if(strncmp("stop",$state,strlen("stop"))==0) { //State is stopped
    print "<div id=\"headline\">Status: Stopped</div>";
}

if(strncmp("pause",$state,strlen("pause"))==0) { //State is paused
    print "<div id=\"headline\">Status: Paused</div>";
}



?>
<table cellspacing="10">
    <tr>
        <td><a href="<?php echo $PHP_SELF; ?>?command=cHJldmlvdXM=">&lt;&lt;</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=cGxheQ==">&gt;</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=cGF1c2U=">||</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=c3RvcA==">Stop</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=bmV4dA==">&gt;&gt;</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=dm9sdW1lICsxMA==">Vol+</a></td>
        <td><a href="<?php echo $PHP_SELF; ?>?command=dm9sdW1lIC0xMA==">Vol-</a></td>
    </tr></table><table><tr>
        <td><form method="POST" action="<?php echo $PHP_SELF; ?>"><input type="text" name="srch"></td>
        <td><input type="submit" value="Search"></td>
    </tr>
</table>
</body></html>