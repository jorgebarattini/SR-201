<?php
/*Basic PHP code to control SR-201 relay based on information found in https://github.com/cryxli/sr201*/
/*
THE DEVICE: Factory Defaults
Default IP Address:        192.168.1.100
Port 6722:                 TCP control
Port 6723:                 UDP control
Port 5111:                 TCP Configuration
*/

/* port for the relay service. */
$service_port = "6722";

/* IP address for the target host. */
$address = "192.168.1.100";

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

/* Uncomment for socket debug
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}*/

/* Connect TCP/IP socket. */
$result = socket_connect($socket, $address, $service_port);

/* Uncomment for socket_connect debug
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}*/

/* Command to send to relay:
Commands are ASCII strings that must be sent in one packet:
  0R     No operation (but return status).
  1R*    Close relay if it's open, wait approx 1/2 a second, openrelay.
  1R     Close relay if it's open.
  1R:0   Close relay if it's open.
  1R:n   Close relay if it's open, then in n seconds (1 <= n <= 65535) open it.
  2R     Open relay if it's closed.

  Where:
  R      is the relay number, '1' .. '8'.

  If the command is sent over TCP (not UDP, TCP only), the relay will reply with a string of 8 0's and 1's,
  representing the "before" command was executed" state of relay's 1..8 in that order.
  A '0' is sent if the relay is open, '1' if closed.

Command to send to relay: */
$in = "11";
$out = '';

/* Write to socket */
socket_write($socket, $in, strlen($in));

/* Read response */
$out = socket_read($socket, 8);
echo "Output:\n";
echo $out;
echo "\n";

/* Close socket*/
echo "Closing socket...";
socket_close($socket);
?>
