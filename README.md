SimpleMPDWrapper
================

Simple PHP Wrapper for MDP is a PHP class for interfacing with the MPD Music Daemon. It allows you to develop an API to interface with MPD

SimpleMPDWrapper Class useage

// Construct a new PocketMp instance
// Required parameters: Password, MPD Server address, Port, Refresh interval
$mp = new PocketMP("","192.168.0.1",6600,0);

// Send a command using the send method:
echo json_encode($mp->send("add", "spotify:track:48mZ0CGCffjH49h5lAPTIe"));

// Or utilise the quick method wrappers
echo json_encode($mp->add("spotify:track:48mZ0CGCffjH49h5lAPTIe"));
