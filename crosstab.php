<?php 
function CrosstabTable ($items, $matrix, $titles, $divLabel) {
    $array_a = $matrix[0];
    $array_b = $matrix[1];
    foreach ($array_a as $y) {
        $rows .= '<tr class="stats-row"><td class="stats-title">'.$titles[$y].'</td>';
        foreach ($array_b as $x) {
            if (empty($items[$y][$x])) { 
                $items[$y][$x] = 0;
            }
            $rows .= "<td>".$items[$y][$x]."</td>";
        }
        $rows .=" </tr>\n";
    }
    $headers = '<tr><td>&nbsp</td>';
    foreach ($array_b as $x) {
        $headers .= '<td>'.$titles[$x].'</td>';
    }
    $headers .= '</tr>'.PHP_EOL;
    $table = '<table id="'.$divLabel.'" class="crosstab heat-map"><thead>'.$headers.'</thead>'.PHP_EOL.'<tbody>'.$rows.'</tbody>'.PHP_EOL.'</table>'.PHP_EOL;
    return $table; 
} //end CrosstabTable
?>

