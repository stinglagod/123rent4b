#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

github_token=$(echo "$1")

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd /app
composer --no-progress --prefer-dist install

info "Init project"
./init --env=Development --overwrite=y

info "Apply migrations"
#./yii migrate --interactive=0
./yii_test migrate --interactive=0

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc

info "Restore mysql from backup"
cd /app
mysql -u root rent4b < sql/rent4b.sql
mysql -u root rent4b_test < sql/rent4b.sql

cd /app && mysql -uroot <<< "DROP DATABASE rent4b" && mysql -uroot <<< "CREATE DATABASE rent4b" && mysql -u root rent4b < sql/rent4b.sql
cd /app && mysql -uroot <<< "DROP DATABASE rent4b_test" && mysql -uroot <<< "CREATE DATABASE rent4b_test" && mysql -u root rent4b_test < sql/rent4b.sql

mysql -uroot rent4b <<< "UPDATE client_sites SET domain='rent4b.test' WHERE domain='rent4b.ru'"
mysql -uroot rent4b <<< "UPDATE client_sites SET domain='deco-rent.test' WHERE domain='deco-rent.ru'"
mysql -uroot rent4b <<< "UPDATE client_sites SET domain='fabrika-kukol.rent4b.test' WHERE domain='fabrika-kukol.rent4b.ru'"
mysql -uroot rent4b <<< "UPDATE client_sites SET domain='studio-white.rent4b.test' WHERE domain='studio-white.rent4b.ru'"

mysql -uroot rent4b_test <<< "UPDATE client_sites SET domain='rent4b.test' WHERE domain='rent4b.ru'"
mysql -uroot rent4b_test <<< "UPDATE client_sites SET domain='deco-rent.test' WHERE domain='deco-rent.ru'"
mysql -uroot rent4b_test <<< "UPDATE client_sites SET domain='fabrika-kukol.rent4b.test' WHERE domain='fabrika-kukol.rent4b.ru'"
mysql -uroot rent4b_test <<< "UPDATE client_sites SET domain='studio-white.rent4b.test' WHERE domain='studio-white.rent4b.ru'"
