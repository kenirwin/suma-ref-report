<?php
/*
  NOTE: You MUST include jquery, flot, and the flot.time plugin
  AND: you must define the style for .demo-placeholder, which can be done
  by including the style.css stylesheet in this directory.
 */

function CreateFlotTimeChart($data, $divLabel='placeholder-timechart') {
    //takes data as ($date=>$count) and returns as flot chart code
    $flot_chart = '<script>'.PHP_EOL;
    $flot_chart.= '$(document).ready(function() {'.PHP_EOL;
    $flot_chart.= 'var d1 = '.PrepFlotTimeArray($data).';'.PHP_EOL;
    $flot_chart.= ' $.plot("#'.$divLabel.'", [d1], { xaxis: { mode: "time", minTickSize: [1, "month"], timeformat: "%b<br>%Y"}, grid: { hoverable: true }, series: {lines: { show: true }, points: {show: true} }, tooltip: {show: true, xDateFormat: "%d %b %Y", content: "%y transactions during week beginning %x"} });';
    $flot_chart.= '});'.PHP_EOL;
    $flot_chart.= '</script>'.PHP_EOL;
    $flot_chart.= '<div id="'.$divLabel.'" style="width:600px; height: 200px"></div>'.PHP_EOL;
    return $flot_chart;
}

function PrepFlotTimeArray ($data) {
    //takes data as ($date=>$count) and returns as (x,y) javascript array
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