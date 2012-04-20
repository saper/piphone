#!/bin/bash
# This shell script fill a MySQL db with the MEP name/phone at Strasbourg
# into a piphone campaign
# TODO: make it a direct-db-connection to MEMOPOL2 (https://mempol.lqdn.fr)

# USAGE: change the mysql user/pass at the bottom and also the campaign=2 by your campaign-id

(
    echo "SET NAMES UTF8;" 
    wget -q -O - https://memopol.lqdn.fr/europe/parliament/committee/INTA |
    egrep "(/europe/parliament/deputy/|callto://\+32|/europe/parliament/country/|/europe/parliament/group/)" |
    grep -v "More information" | 
    grep -v "By country" | 
    grep -v "By political group" | 
    sed -e 's/^.*"\/europe\/parliament\/deputy\/\([^"]*\)">\([^<]*\)<.*$/INSERT INTO lists SET campaign=4, name="\2", url="https:\/\/memopol.lqdn.fr\/europe\/parliament\/deputy\/\1";/' \
	-e 's/^.*callto:\/\/.\([0-9]*\).*callto:\/\/.\([0-9]*\).*$/UPDATE lists SET phone="00\1" WHERE phone="";/' \
	-e 's/^.*country\/\(..\)\/.*$/UPDATE lists SET country="\1" WHERE country="";/' \
	-e 's/^.*\/group\/\([^\/]*\)\/.*$/UPDATE lists SET name=CONCAT(name," (\1)"), callduration=1 WHERE callduration=0;/'
) | mysql piphone # if necesssary : -u<user> -p<password>

(
    echo "SET NAMES UTF8;" 
    wget -q -O - https://memopol.lqdn.fr/europe/parliament/committee/ITRE |
    egrep "(/europe/parliament/deputy/|callto://\+32|/europe/parliament/country/|/europe/parliament/group/)" |
    grep -v "More information" | 
    grep -v "By country" | 
    grep -v "By political group" | 
    sed -e 's/^.*"\/europe\/parliament\/deputy\/\([^"]*\)">\([^<]*\)<.*$/INSERT INTO lists SET campaign=5, name="\2", url="https:\/\/memopol.lqdn.fr\/europe\/parliament\/deputy\/\1";/' \
	-e 's/^.*callto:\/\/.\([0-9]*\).*callto:\/\/.\([0-9]*\).*$/UPDATE lists SET phone="00\1" WHERE phone="";/' \
	-e 's/^.*country\/\(..\)\/.*$/UPDATE lists SET country="\1" WHERE country="";/' \
	-e 's/^.*\/group\/\([^\/]*\)\/.*$/UPDATE lists SET name=CONCAT(name," (\1)"), callduration=1 WHERE callduration=0;/'
) | mysql piphone # if necesssary : -u<user> -p<password>

(
    echo "SET NAMES UTF8;" 
    wget -q -O - https://memopol.lqdn.fr/europe/parliament/committee/JURI |
    egrep "(/europe/parliament/deputy/|callto://\+32|/europe/parliament/country/|/europe/parliament/group/)" |
    grep -v "More information" | 
    grep -v "By country" | 
    grep -v "By political group" | 
    sed -e 's/^.*"\/europe\/parliament\/deputy\/\([^"]*\)">\([^<]*\)<.*$/INSERT INTO lists SET campaign=6, name="\2", url="https:\/\/memopol.lqdn.fr\/europe\/parliament\/deputy\/\1";/' \
	-e 's/^.*callto:\/\/.\([0-9]*\).*callto:\/\/.\([0-9]*\).*$/UPDATE lists SET phone="00\1" WHERE phone="";/' \
	-e 's/^.*country\/\(..\)\/.*$/UPDATE lists SET country="\1" WHERE country="";/' \
	-e 's/^.*\/group\/\([^\/]*\)\/.*$/UPDATE lists SET name=CONCAT(name," (\1)"), callduration=1 WHERE callduration=0;/'
) | mysql piphone # if necesssary : -u<user> -p<password>



(
    echo "SET NAMES UTF8;" 
    wget -q -O - https://memopol.lqdn.fr/europe/parliament/committee/DEVE |
    egrep "(/europe/parliament/deputy/|callto://\+32|/europe/parliament/country/|/europe/parliament/group/)" |
    grep -v "More information" | 
    grep -v "By country" | 
    grep -v "By political group" | 
    sed -e 's/^.*"\/europe\/parliament\/deputy\/\([^"]*\)">\([^<]*\)<.*$/INSERT INTO lists SET campaign=7, name="\2", url="https:\/\/memopol.lqdn.fr\/europe\/parliament\/deputy\/\1";/' \
	-e 's/^.*callto:\/\/.\([0-9]*\).*callto:\/\/.\([0-9]*\).*$/UPDATE lists SET phone="00\1" WHERE phone="";/' \
	-e 's/^.*country\/\(..\)\/.*$/UPDATE lists SET country="\1" WHERE country="";/' \
	-e 's/^.*\/group\/\([^\/]*\)\/.*$/UPDATE lists SET name=CONCAT(name," (\1)"), callduration=1 WHERE callduration=0;/'
) | mysql piphone # if necesssary : -u<user> -p<password>



(
    echo "SET NAMES UTF8;" 
    wget -q -O - https://memopol.lqdn.fr/europe/parliament/committee/LIBE |
    egrep "(/europe/parliament/deputy/|callto://\+32|/europe/parliament/country/|/europe/parliament/group/)" |
    grep -v "More information" | 
    grep -v "By country" | 
    grep -v "By political group" | 
    sed -e 's/^.*"\/europe\/parliament\/deputy\/\([^"]*\)">\([^<]*\)<.*$/INSERT INTO lists SET campaign=8, name="\2", url="https:\/\/memopol.lqdn.fr\/europe\/parliament\/deputy\/\1";/' \
	-e 's/^.*callto:\/\/.\([0-9]*\).*callto:\/\/.\([0-9]*\).*$/UPDATE lists SET phone="00\1" WHERE phone="";/' \
	-e 's/^.*country\/\(..\)\/.*$/UPDATE lists SET country="\1" WHERE country="";/' \
	-e 's/^.*\/group\/\([^\/]*\)\/.*$/UPDATE lists SET name=CONCAT(name," (\1)"), callduration=1 WHERE callduration=0;/'
) | mysql piphone # if necesssary : -u<user> -p<password>




