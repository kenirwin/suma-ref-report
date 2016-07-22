<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <title>Flot Examples: Basic Usage</title>
     <link href="/lib/include/jquery/flot/examples/examples.css" rel="stylesheet" type="text/css">
<?
     include("/docs/lib/include/scripts.php");
jQueryPlugins("flot","flot-time","flot-pie");
require_once ("config.php");
require_once ("suma.activities.class.php");

include ("flotpie.php");

$stats = new Stats(3, SUMASERVER_URL);
$groupinfo = $stats->GetGroupStats();
$pie_scripts = '';
$pie_html = '';

foreach ($groupinfo as $i => $group) {
    foreach ($group as $label => $array) {
        $pie = FlotPie($array, $i);
        //        print_r($pie);
        $pie_scripts .= $pie['script'];
        $pie_html .= '<h2>'.$label.'</h2>'.PHP_EOL;
        $pie_html .= $pie['html'];
    }
}
?>
<script type="text/javascript">
    $(function() {
            <?php
                print $pie_scripts;
                ?>
        });
</script>

<?
print $pie_html;
?>

