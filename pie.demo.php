<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <title>Flot Examples: Basic Usage</title>
     <link href="/lib/include/jquery/flot/examples/examples.css" rel="stylesheet" type="text/css">
<?php
     include("/docs/lib/include/scripts.php");
jQueryPlugins("flot","flot-time","flot-pie","flot-tooltip");
include("flotpie.php");

$data = array ("Monkeys" => 34, "Rabbits" => 12, "Llamas" => 29, "Tigers" => 1);
$data2 = array ("Ken"=> 18, "Kristen" => 32, "Alisa" => 45);

$animal_pie = FlotPie($data);
$libn_pie = FlotPie($data2);
?>

<script type="text/javascript">
    $(function() {
                       <?php echo $animal_pie['script'];?>
                       <?php echo $libn_pie['script'];?>
        });
</script>

<div>
<?php echo $animal_pie['html']; ?>
</div>
<div>
<?php echo $libn_pie['html']; ?>
</div>
</body>
</html>
