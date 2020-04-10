#!/bin/sh

# Importation des fichiers Binaires
flagAnalyseAccess='/tmp/.flagBoilerBoxAnalyseModulesAccess'
boilerDir='/var/www/html/BoilerBox.fr/BoilerBox/'
consoleDir=$boilerDir'/bin/console'
cacheDir=$boilerDir'/var/cache'


# Vérification qu'un flag d'importation de fichiers binaires n'existe pas
if [ -e "$flagAnalyseAccess" ]; then
		# Si le flag existe mais que le process ne fonctionne pas : suppression du flag
		#php /var/www/html/BoilerBox.fr/BoilerBox/src/Lci/BoilerBoxBundle/Resources/views/Utils/../../../../../../bin/console boilerbox:utils
		processActif=`ps -ef | grep 'boilerbox:modulesutils' | grep -v 'grep'`
		if [ -z "$processActif" ]; then
			rm "$flagAnalyseAccess"
		else
			echo "L'analyse de la disponibilité des sites est déjà en cours d'execution"
		fi
        exit 1
else
    # Création du flag
    touch "$flagAnalyseAccess"
    # Appel de la commande qui importe en base la liste des fichiers présents dans le dossier fichiers_binaires
    retour=`nice -0 php $consoleDir boilerbox:modulesutils`
    # Libération du flag
    rm "$flagAnalyseAccess"
fi
chmod -R 777 $cacheDir
exit 0
