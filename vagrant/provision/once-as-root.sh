#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Add PHP 7.2 repository"
add-apt-repository ppa:ondrej/php -y

info "Add Oracle JDK repository"
add-apt-repository ppa:webupd8team/java -y

info "Add ElasticSearch sources"
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
echo "deb https://artifacts.elastic.co/packages/5.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-5.x.list

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.2-curl php7.2-cli php7.2-intl php7.2-mysqlnd php7.2-gd php7.2-fpm php7.2-mbstring php7.2-xml unzip nginx mysql-server-5.7 php.xdebug php7.2-memcached memcached php7.2-zip

info "Install Oracle JDK"
#debconf-set-selections <<< "oracle-java11-installer shared/accepted-oracle-license-v1-1 select true"
#debconf-set-selections <<< "oracle-java11-installer shared/accepted-oracle-license-v1-1 seen true"
#apt-get install -y oracle-java11-installer
apt-get install -y default-jre

info "Install ElasticSearch"
apt-get install -y elasticsearch
sed -i 's/-Xms2g/-Xms64m/' /etc/elasticsearch/jvm.options
sed -i 's/-Xmx2g/-Xmx64m/' /etc/elasticsearch/jvm.options
service elasticsearch restart
systemctl enable elasticsearch

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.2/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.2/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Delete default nginx conf"
rm /etc/nginx/sites-enabled/default
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Configure php.ini"
#cli
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.2/cli/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 16M/g' /etc/php/7.2/cli/php.ini
#fpm
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.2/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 16M/g' /etc/php/7.2/fpm/php.ini

echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE rent4b"
mysql -uroot <<< "CREATE DATABASE rent4b_test"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer