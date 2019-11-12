#!/bin/sh

# Importation des fichiers Binaires
flagAnalyseAccess='/tmp/.flagBoilerBoxAnalyseAccess'

# Vérification qu'un flag d'importation de fichiers binaires n'existe pas
if [ -e "$flagAnalyseAccess" ]; then
        echo "L'analyse de la disponibilité des sites est déjà en cours d'execution"
        exit 1
else
    # Création du flag
    touch "$flagAnalyseAccess"
    # Appel de la commande qui importe en base la liste des fichiers présents dans le dossier fichiers_binaires
    retour=`nice -0 php /var/www/vhosts/boiler-box.fr/httpdocs/BoilerBox/app/console boilerbox:utils`
    # Libération du flag
    rm "$flagAnalyseAccess"
fi
chmod -R 777 /var/www/vhosts/boiler-box.fr/httpdocs/BoilerBox/app/cache
exit 0
