#GAPI Google Analytics Exemple

Exemple d'utilisation de la GAPI Google Analytics.

##Dépendence
* [Google APIs Client Library for PHP](https://code.google.com/p/google-api-php-client/)
* [FlotCharts](http://www.flotcharts.org/) (Pour la représentation des contients sous forme d'un graphisme)

##Installation
Vous devez renseigner dans le fichier **identifiants.php** vos informations de connexion à votre compte Google Analytics et votre *Profil ID*.


###Obtenir son Profil ID
![ProfilID](https://developers.google.com/analytics/images/profile_id_report_url.png)

Votre *Profil ID* se situe après la lettre **p**.

###Resultat final
![Resultat](http://i.imgur.com/150CqZY.png)

Libre à vous de personnaliser/améliorer ces scripts qui sont la base de l'utilisation de la GAPI Google Analytics.

**Attention**: Si votre compte Google Analytics est nouveau, il se peut que les continents ne s'affiche pas avant 3/4jours.

##Ressources
* Dimensions & Metrics Reference : https://developers.google.com/analytics/devguides/reporting/core/dimsmets
* Google APIs Client Library for PHP : https://code.google.com/p/google-api-php-client/
* APIs Explorer : https://developers.google.com/apis-explorer/#p/analytics/v3/analytics.data.ga.get
* Core Reporting API - Reference Guide : https://developers.google.com/analytics/devguides/reporting/core/v3/reference
