# calender017

https://zusann123.blogspot.com/2024/12/blog-post_12.html

# Steps required to start and run the application

Install php8.3, mysql or mariadb, sqlite3

Set up Mysql or Mariadb

setting php.ini

# Set up user information with a script

$ php myuser01.php

# Start by specifying the IP address or port

Specifying a specific IP address
To make it accessible within your local network, for example, specify the IP address 192.168.1.100:

$ php -S 192.168.1.100:1505

This will make it accessible at http://192.168.1.100:1505.
To bind to all interfaces
To make it accessible on all network interfaces (local and external), use 0.0.0.0:

$ php -S 0.0.0.0:1505

This will make it accessible at http://[host IP address]:1505 from any device in your network.
Things to check
Firewall settings:

Make sure that the IP address and port you specified are not blocked by your firewall.
Checking the IP address:

To check the IP address used by the server, use the following command:

$ ip a

Security:

When using in a public environment, consider that the built-in server is for development, not production.

$ php -S localhost:1505
