# Remove the languages/default.pot file and launch make to recreate the pot and merge it into po localized files

all: locales


locales: ./locales/en_US/LC_MESSAGES/default.mo ./locales/fr_FR/LC_MESSAGES/default.mo

./locales/en_US/LC_MESSAGES/default.mo: ./locales/en_US/LC_MESSAGES/default.po
	msgfmt ./locales/en_US/LC_MESSAGES/default.po -o ./locales/en_US/LC_MESSAGES/default.mo

./locales/fr_FR/LC_MESSAGES/default.mo: ./locales/fr_FR/LC_MESSAGES/default.po
	msgfmt ./locales/fr_FR/LC_MESSAGES/default.po -o ./locales/fr_FR/LC_MESSAGES/default.mo


pot: ./locales/default.pot

./locales/default.pot: controller/*.php view/*.php *.php
	[ -r $@ ] || touch $@
	xgettext --from-code=UTF-8 --force-po -o $@ --keyword=_ --keyword=__  -L PHP  -j `find -not -name '.*' -a \( -name '*.php' -o -name '*.phtml' \)`

./locales/%/LC_MESSAGES/default.po: ./locales/default.pot
	msgmerge -v -U $@ $^

*.phtml: 

*.php:
