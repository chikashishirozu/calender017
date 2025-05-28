# calender017

https://zusann123.blogspot.com/2024/12/blog-post_12.html

# Steps required to start and run the application

Install php8.3, mysql or mariadb, sqlite3

Set up Mysql or Mariadb

setting php.ini (timezone language extension)

# Windows11 setting

I was able to safely develop in a local environment on Windows 11 by building a PHP8+Apache+MySQL+phpMyAdmin development environment without XAMPP, following the instructions at https://qiita.com/Limitex/items/098d2be431f1f031b1b9.

Hint: I copied and pasted libcual.dll from Apache2/bin into php, and libsqlite3 from php into Apache2/bin, and it started working.

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

# Security:

When using in a public environment, consider that the built-in server is for development, not production.

$ php -S localhost:1505

# Settings for Linux and Apache2

(For reference, see How to build a PHP8+Apache+MySQL+phpMyAdmin development environment on Windows without XAMPP
https://qiita.com/Limitex/items/098d2be431f1f031b1b9
.)

$ sudo dnf install php php-fpm php-gd php-xml php-sqlite3 php-json php-mysqlnd mysql-server httpd php-mbstring php-intl php-pdo php-curl php-zip php-xmlwriter php-opcache php-mysqli php-pecl-xdebug (fedora)

--- Depending on the environment (Debian) 

$ sudo apt install apache2 libapache2-mod-php php-mysql mysql-server php-gd php-sqlite3 php-fpm php-mbstring php-intl php-pdo php-curl php-zip php-xmlwriter php-opcache php-mysqli php-xdebug ---

$ sudo cp -r calender017 /var/www/html

$ sudo chown -R apache:apache /var/www/html/calender017

$ sudo chown apache:apache /var/www/html/calender017/calendar.db

--- Depending on the environment (Debian)

$ sudo chown -R www-data:www-data /var/www/html/calender017

$ sudo chown www-data:www-data /var/www/html/calender017/calendar.db ---

$ sudo find /var/www/html/calender017 -type d -exec chmod 755 {} \\;

$ sudo find /var/www/html/calender017 -type f -exec chmod 644 {} \\;

--- SELinux settings (fedora)

$ sudo chcon -R -t httpd_sys_rw_content_t /var/www/html/calender017

$ sudo chcon -R -t httpd_sys_rw_content_t /var/www/html/calender017/calendar.db ---

$ sudo chmod 775 /var/www/html/calender017

$ sudo chmod 664 /var/www/html/calender017/calendar.db

$ sudo systemctl enable httpd

$ sudo systemctl start httpd

--- Depending on the environment (Debian)

$ sudo systemctl enable apache2

$ sudo systemctl start apache2 ---

$ sudo systemctl enable mysql
 
$ sudo systemctl start mysql

$ sudo systemctl enable php-fpm

$ sudo systemctl start php-fpm

$ sudo systemctl restart httpd

--- Depending on the environment (Debian)

$ sudo systemctl restart apache2 ---
