Import Lists of MEP into the PiPhone
====================================

To facilitate the import of MEPs from [parltrack](http://parltrack.euwiki.org/) to the [Piphone](https://piphone.lqdn.fr), we created that import/ folder

To use it, proceed as follow : 

* download a JSON file of all the MEP you want (for example wget "http://parltrack.euwiki.org/committee/LIBE?format=json" -O LIBE.json )
* launch the json to csv convert script on it. using "php json2csv.php LIBE.json >LIBE.csv"
* this script not only convert the json file to csv, extracting only relevant fields, but also download the JSON of each MEP in /var/www/parltrack.
* then upload this CSV file into the PiPhone administrative interface for your campaign.

