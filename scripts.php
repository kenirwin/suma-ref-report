<?php
/*
These scripts are deprecated -- use the suma.activities.class.php instead
 */


GetWeeks("2014-09-28", date("Y-m-d")); 

function GetWeeks ($start, $end) {
    $graph_array = array();
    $total_trans = 0;
    $starttime = strtotime($start);
    $endtime = strtotime($end);
    $oneday = 60*60*24; 
    for ($date = $starttime; $date <= $endtime; $date+=$oneday) {
        if (date("l",$date) == "Sunday") {
            $week_end = date("Y-m-d", $date+($oneday*6));
            $this_sunday = date("Y-m-d",$date);
            $next_sunday = date("Y-m-d",$date+($oneday*7));
            $trans = GetRefTrans ($this_sunday, $next_sunday);
            $rows .= "<tr><td>".$this_sunday."..".$week_end."</td>";
            $rows .= "<td>$trans</td></tr>";
            $total_trans += $trans;
            $graph_array[$this_sunday] = $trans;
        }
    }             
    $rows .= "<tr><th>Total</td> <th>$total_trans</th></tr>\n";
    print '<div id="placeholder" style="height: 400;width:800"></div>'.PHP_EOL;

    print "<table>\n";
    print "<thead><tr><th>Week of...</th> <th>Ref Qs</th></tr></thead>\n";
    print "<tbody>$rows</tbody>\n";
    print "</table>\n";
    print '<script>'.PHP_EOL;
    print '$(document).ready(function() {'.PHP_EOL;
    print 'var d1 = '.PrepFlotArray($graph_array).';'.PHP_EOL;
    print ' $.plot("#placeholder", [d1], { xaxis: { mode: "time", minTickSize: [1, "month"], timeformat: "%b<br>%Y"}, grid: { hoverable: true }, series: {lines: { show: true }, points: {show: true} }, tooltip: {show: true, xDateFormat: "%d %b %Y", content: "%y transactions during week beginning %x"} });';
    print '});'.PHP_EOL;
    print '</script>'.PHP_EOL;
} //end GetWeeks 

function GetRefTrans ($start,$end, $activity="") {
    if ($activity != "") { 
        $q = "SELECT sum(number) FROM `count`,`count_activity_join` WHERE `fk_location` = '9' AND `occurrence` > '$start' AND `occurrence` < '$end' AND `count_activity_join`.`fk_count` and `count_activity_join`.`fk_activity` = $activity";
    }
    else {
        $q = "SELECT sum(number) FROM `count` WHERE `fk_location` = '9' AND `occurrence` > '$start' AND `occurrence` < '$end'";
    }
    $r = mysql_query($q);
    while ($myrow = mysql_fetch_row($r)) {
        $sum = $myrow[0];
    }
    return $sum;
}

function PrepFlotArray ($data) {
    $js_array = array();
    foreach ($data as $date => $count) {
        $ts = strtotime($date) *1000;
        $entry = "[$ts, $count]";
        array_push($js_array, $entry);
    }
    $js_string = "[" . implode (",", $js_array) . "]";
    return ($js_string);
}
?>