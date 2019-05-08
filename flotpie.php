<?php
function FlotPie ($data, $divlabel='') {
    if ($divlabel == '') { $divlabel = rand(0,100000000); }
    $json_data = array();
    $data_table_rows = "";
    foreach ($data as $label => $datum) {
        $json_data[] = array("label" => $label, "data" => $datum);
        $data_table_rows .= "<tr><th>$label</th><td>$datum</td></tr>\n";
    }
    $json_data = json_encode($json_data);
    $data_table = '<table id="datatable-'.$divlabel.'" class="flotpie-datatable">'.$data_table_rows.'</table>'.PHP_EOL;
$script = <<<EOF
            var data = $json_data;

function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>"
        + label + "<br/>" + Math.round(series.percent) + "%</div>";
}

$.plot("#placeholder-$divlabel", data, {

            
    series: {
        pie: { 
            show: true,
                    radius: 1,
                    label: {
                show: true,
                        radius: 3/4,
                        formatter: labelFormatter,

                        background:  {
                    opacity: 0.5,
                            color: '#000'
                    }
                }
             }
        },
            grid: { hoverable: true },

            tooltip: {
        show: true,
                content: "%p.0%, %s"
        }
    });
EOF;

$html = <<<EOF
    <div class="demo-container">
     <div id="placeholder-$divlabel" class="demo-placeholder"></div>
    </div>
EOF;

$return = array("html"=>$html, "script"=>$script, "datatable"=>$data_table);
return $return;
  } //end FlotPie
?>

