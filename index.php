<html>
<head>
<title>Suma Ref Report</title>
<style>
tbody td, td.number { text-align: right }
thead td, tfoot td { font-weight: bold } 
form { display: inline }
.highlight { background-color: yellow }
</style>
<script type="text/javascript"
         src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js">
</script>
<?php
include("/docs/lib/include/jquery/plugins.php");
jQueryPlugins("jqueryUI","flot","flot-time","flot-pie", "flot-tooltip");
?>
<script type="text/javascript" src="heatmap.js"></script>
<?php
require_once ("suma.activities.class.php");
include ("flottime.php");
include ("flotpie.php");
include ("crosstab.php");
include ("config.php");

$stats = new Stats($default_init, SUMASERVER_URL);

/* weekly breakdown */
$weeks = $stats->ListWeeks($stats->firstTime,date("Y-m-d"));
$weekly_chart_data = array();
$weekly_table = '';
$total = 0;
foreach ($weeks as $week) { 
    $filters = array ("start"=>$week['start'], "end"=>$week['end']);
    $sum = $stats->Sum($filters);
    $total += $sum;
    $weekly_table .=  "<tr><td>".$week['start_label']." to ".$week['end_label']."</td><td>$sum</td></tr>\n";
    $weekly_chart_data[$week['start']] = $sum;
}
$weekly_table = '<table id="questions-by-week-table"><thead><tr><td>Week of...</td><td>Ref Qs</td></tr></thead>'. $weekly_table . '<tfoot><tr><td>Total</td><td class="number">'.$total.'</td></tr></tfoot></table>'.PHP_EOL;


/* activity breakdown */
$groupinfo = $stats->GetGroupStats();
$pie_scripts = '';
$pie_html = '';

foreach ($groupinfo as $i => $group) {
    foreach ($group as $label => $array) {
        $pie = FlotPie($array, $i);
        $pie_scripts .= $pie['script'];
        $pie_html .= '<h2>'.$label.'</h2>'.PHP_EOL;
        $pie_html .= $pie['datatable'] . $pie['html'];
    }
}
?>

<link rel="stylesheet" type="text/css" href="style.css" type="text/css">

<script type="text/javascript">
     $(document).ready(function() {
             $('#questions-by-week-table').addClass('highlightable');
             $('#initiative-selector').change(function() {
                     var init = $(this).val();
                     window.location.replace('?set_init='+init);
                 });
             $('.highlightable tr').mousedown(function() {
                     $(this).parent().children().removeClass('highlight');
                     $(this).addClass('highlight');
                 });

<?php
                print $pie_scripts;
?>

                    /* Create tabs. This section should go last to avoid binding conflicts. Note: the delegate/prevent default section stops the page from jumping the vertical position around when tabs are clicked */

                    $('#tabs').tabs(); 
                    $("#tabs ul li").delegate('a', 'click', function(e){
                            e.preventDefault();
                            return false;
                        });


        });
</script>

</head>
<body>
<h1>Suma Ref Report</h1>
<?php

if (DEBUG === true) {
    var_dump($_REQUEST);
        print "<p></p>".PHP_EOL;
}



?>

<div id="tabs">
<ul>
<li><a href="#tabs-1">Questions Per Week</a></li>
    <li><a href="#tabs-2">Activity Groups (Who, How Long, Etc)</a></li>
    <li><a href="#tabs-3">Activity Crosstabs</a></li>
</ul>
<div id="tabs-1">
<?php
print (CreateFlotTimeChart($weekly_chart_data));
print ($weekly_table);
?>
</div>
<div id="tabs-2">
<?php
print $pie_html;
?>
</div><!--id=tabs-2-->

<div id="tabs-3">
<?php
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
</div><!--id=tabs-3-->

</div><!--id=tabs-->

<?php
    include("license.php");
?> 
</body>
