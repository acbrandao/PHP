# php WebSockets Demo Tutorial Code
Example of of websockets code explained  on http://www.abrandao.com/2013/06/websockets-html5-php/

## Installation
Simply cloen or download the few files that make up this client/server.  Remember to change the server **$ip_address** on the client and the ws_server.php files to match, to get it to work on your box. Installation steps are as follows:

 * move  files into a folder on your web server
 * edit the ws_server.php changing the $server_ip address to match that of your server
 * on the client.html edit the default value <input id="host" type="textbox" size="35" value="echo.websocket.org"/> to point to your actual server_ip
 *  via the command line start-up your php server  ( php -q ws_server.php ) open your client.html page, if all goes well you should see the resulting command Welcome- Status 1


## Usage
Remember to change the server **$ip_address** on the *html client* and the *ws_server.php*

I also included  a simple test_socket_server (which IS NOT A web socket server) , but simply sets up a basic socket connection which is useful to confirm that your server is not blocked by firewall or other networking issues. You can talk to it via simple telnet server_ip port. Please feel free to share this link and leave comments if you found this helpful.

## Contributing
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
## History
TODO: Write history
## Credits
TODO: Write credits
## License
TODO: Write license
.
