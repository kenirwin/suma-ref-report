<?
include("/docs/lib/include/scripts.php");
require_once ("config.php");
require_once ("suma.activities.class.php");
include("crosstab.php");
?>

<script type="text/javascript" src="heatmap.js"></script>

<?
$stats = new Stats(3, SUMASERVER_URL);

foreach ($stats->ListCrosstabPairs() as $pair) {
    $a = $pair[0];
    $b = $pair[1];
    $items = $stats->Crosstab($a,$b);
    $matrix = $stats->CrosstabGroupMatrix($a,$b);
    $titles = $stats->activityTitles;
    print '<h3>'.$stats->activityGroupTitles[$a].' vs. '.$stats->activityGroupTitles[$b].'</h3>'.PHP_EOL;
    print (CrosstabTable ($items, $matrix, $titles, "crosstab-$a-$b"));
    
}
?>
