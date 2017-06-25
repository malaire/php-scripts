#!/usr/bin/env php
<?php


# 
# DESCRIPTION
#   Trivial web server which responds to every request with 'Hello World!'
#
# USAGE
#   Start this script with 'php server-hello.php' and then connect to
#   http://127.0.0.1:12345 with e.g. web-browser
#
# LICENSE
#   Copyright (c) 2017 Markus Laire
#
#   Permission is hereby granted, free of charge, to any person obtaining a copy
#   of this software and associated documentation files (the "Software"), to
#   deal in the Software without restriction, including without limitation the
#   rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
#   sell copies of the Software, and to permit persons to whom the Software is
#   furnished to do so, subject to the following conditions:
#
#   The above copyright notice and this permission notice shall be included in
#   all copies or substantial portions of the Software.
#
#   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
#   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
#   IN THE SOFTWARE.
#


# ======================================================================
# CONSTANTS

const SERVER_PORT = 12345;
const SERVER_HOST = '127.0.0.1';

# ======================================================================
# FUNCTIONS

# -------===========----
function SocketError() {
# -------===========----
  throw new Exception(socket_strerror(socket_last_error()));
}

# ======================================================================
# MAIN

$socket = socket_create(AF_INET, SOCK_STREAM, 0);
if ($socket === FALSE) SocketError();

if (! socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)) SocketError();

if (! socket_bind($socket, SERVER_HOST, SERVER_PORT)) SocketError();

while (1) {
  if (! socket_listen($socket)) SocketError(); 
  
  $client = socket_accept($socket);
  if ($client === FALSE) SocketError();
  
  $request = socket_read($client, 1000);
  if ($request === FALSE) SocketError();

  echo str_repeat("=", 70) . "\n";
  echo $request;

  $response  = "HTTP/1.1 200 OK\r\n";
  $response .= "Server: HelloServer\r\n";
  $response .= "Content-Type: text/plain\r\n";
  $response .= "\r\n";
  $response .= "Hello World!";

  if (socket_write($client, $response) === FALSE) SocketError();

  socket_close($client);  
}
