<?php
/**
 * @name API Google Analytics Exemple
 * @author Emeric Fevre
 * @link http://www.emeric.me
 * @example Ressource supplémentaire :
 * 		Dimensions & Metrics Reference : 		https://developers.google.com/analytics/devguides/reporting/core/dimsmets
 *		Google APIs Client Library for PHP :	https://code.google.com/p/google-api-php-client/
 *		APIs Explorer :							https://developers.google.com/apis-explorer/#p/analytics/v3/analytics.data.ga.get
 *		Core Reporting API - Reference Guide :	https://developers.google.com/analytics/devguides/reporting/core/v3/reference
 * 		Obtenir son ProfilID :					https://developers.google.com/analytics/images/profile_id_report_url.png
 **/


//On importe la class GAPI et nos identifiants
include 'identifiants.php';
include 'gapi.class.php';

//Fonction de connexion a Google Analytics
$ga = new gapi(ga_email,ga_password);


/*
 * La fonction requestReportData prend comme paramettre:
 * $ga->requestReportData($profileId, $dimensions, $metrics, $sortMetric, $filter,$startDate, $endDate, $startIndex, $maxResults);
 */
?>

<!-- On importe les librairies jQuery et Flot -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script src="js/jquery.flot.min.js"></script>
<script src="js/jquery.flot.pie.min.js"></script>


<?php
/*
 * Exemple avec les navigateurs
 * On recupere le nombre de page vues (pageviews) pour chaque navigateur, 
 * puis on les additionnes pour ensuite faire le pourcentage de chaque navigateur et 
 * afficher une petite image suivant le navigateur.
 */

//On filtre les navigateurs ou on veut recuperer les donnees
$filter = 'browser == Chrome || browser == Firefox || browser == Internet Explorer || browser == Safari || browser == Opera';
//On fait notre requete sur l'API Google Analytics
$ga->requestReportData(ga_profile_id,array('browser'),array('pageviews','visits'),'-visits',$filter);

//On compte le nombre totale de page vues pour tout les navigateurs
foreach($ga->getResults() as $pagesviews)
{
	$total += $pagesviews->getPageviews();
}
?>
<div class="navigateurs">
	<?php
	//On ecris le resultat pour chaque navigateur
	foreach($ga->getResults() as $browser)
	{
		/*On fait le pourcentage par rapport au nombre de vues totales 
		et au nombre de vues pour chaque navigateur*/
		$poucent = ($browser->getPageviews()*100)/$total;
		?>
		<div style="display: inline-block;">
			<img src="img/browser/browser-<?php echo $browser; ?>.png" alt="<?php echo $browser; ?>">
			<!-- On arrondi le pourcentage -->
			<span><?php echo round($poucent)."%"; ?></span>
		</div>
		<?php
	}
	?>
</div>


<?php
/*
 * Exemple avec les statistiques
 * On recupere :
 * 		Le nombre de pages vues
 *		Le nombre de pages par visite
 *		La duree moyenne de la visite
 *		Le toux de rebond
 *		Le nombre de visites
 *		Le nombre de visiteurs uniques
 */						
$ga->requestReportData(ga_profile_id, NULL,array('visitors','visits','pageviews','pageviewsPerVisit','avgtimeOnSite','visitBounceRate','percentNewVisits'),NULL,NULL,'2012-11-30',(date('Y-m-d',time())));
?>
<div class="statistiques">
	<ul>
		<li>
			<?php echo "Pages Vues:"; ?>
			<span class="number"><?php echo $ga->getPageViews(); ?></span>
		</li>
		<li>
			<?php echo "Pages par Visite:"; ?>
			<span class="number"><?php echo round($ga->getPageViewsPerVisit(), 2); ?></span>
		</li>
		<li>
			<?php echo "Duree moyenne de la visite:"; ?>
			<span class="number"><?php echo gmdate("H:i:s", $ga->getavgTimeOnSite()); ?></span>
		</li>
		<li>
			<?php echo "Taux de rebond:"; ?>
			<span class="number"><?php echo round($ga->getVisitBounceRate(), 2); ?>%</span>
		</li>
		<li>
			<?php echo "Visites:"; ?>
			<span class="number"><?php echo $ga->getVisits(); ?></span>
		</li>
		<li>
			<?php echo "Visiteurs Uniques:"; ?>
			<span class="number"><?php echo $ga->getVisitors(); ?></span>
		</li>
	</ul>
</div>


<?php
/*
 * Exemple avec les continents
 * On recupere le nombre de page vues (pageviews) pour chaque continent, 
 * puis on les additionnes pour ensuite faire le pourcentage de chaque continent et 
 * stocker le resultat dans un tableau puis le mettre en format JSON pour le JavaScript.
 */

//On filtre les continents ou on veut recuperer les donnees
$filter_c = 'continent == Africa || continent == Americas || continent == Asia || continent == Europe || continent == Oceania';

$ga->requestReportData(ga_profile_id,array('continent'),array('visitors'),NULL,$filter_c,'2012-11-30',(date('Y-m-d',time())));

//On compte le nombre totale de pages vues pour tout les continents
foreach($ga->getResults() as $continent)
{
	$total_continent += $continent->getVisitors();
}
$dataset_continent = array();

//On ecris le resultat pour chaque contient dans un tableau
foreach($ga->getResults() as $continents)
{
	/*On fait le pourcentage par rapport au nombre de vues totales 
	et au nombre de vues pour chaque navigateur*/
	$pourcent = ($continents->getVisitors()*100)/$total_continent;
	
	//On met les valeurs dans le tableau 
	$dataset_continent["$continents"] = round($pourcent);
	
}
//On met les donnees en format Json et on les stocks dans une variable
$continent_donnee = '[
						{ label: "Afrique", data: '.$dataset_continent['Africa'].', color: "#f26645"},
						{ label: "Amerique", data: '.$dataset_continent['Americas'].', color: "#fecd64"},
						{ label: "Asie", data: '.$dataset_continent['Asia'].', color: "#40befb" },
						{ label: "Europe", data: '.$dataset_continent['Europe'].', color: "#99cc65" },
						{ label: "Oceanie", data: '.$dataset_continent['Oceania'].', color: "#ab65cc" }
					]';

?>
<script type="text/javascript">
$(function () {
	//On remplis la variable data
	var data = <?php echo $continent_donnee; ?>;
	
	//On parametre le flot
	$.plot('#flot-continents', data, {
		series: {
			pie: {
				show: true,
				label: {
					show: true,
					formatter: function (label, series) {
						return '<div style="font-size:8pt;text-align:center;padding:5px;color:' + series.color + ';">' + Math.round(series.percent) + '%</div>';
					}
				}
			}
		}
	});
});
</script>
<!-- Div pour afficher les balises canvas du Flot -->
<div id="flot-continents" style="height:218px;width:400px"></div>