<?php 
include("/docs/lib/include/scripts.php");
?>

<script type="text/JavaScript">
      /*
Heatmap code from: 
http://www.designchemical.com/blog/index.php/jquery/jquery-tutorial-create-a-flexible-data-heat-map/
       */

     $(document).ready(function(){
 
             $("table.heat-map").each(function () {
                     var current_table_id = $(this).attr('id');
             // Function to get the max value in an Array
             Array.max = function(array){
                 return Math.max.apply(Math,array);
             };
 
             // Get all data values from our table cells making sure to ignore the first column of text
             // Use the parseInt function to convert the text string to a number
 
             // Original code, before ken modified it to handle multiple tables
             // var counts= $('.heat-map tbody td').not('.stats-title').map(function() {
             var counts= $('#' +current_table_id + ' tbody td').not('.stats-title').map(function() {
                     return parseInt($(this).text());
                 }).get();
 
             // run max value function and store in variable
             var max = Array.max(counts);
             n = 100; // Declare the number of groups
 
             // Define the ending colour, which is white
             xr = 255; // Red value
             xg = 255; // Green value
             xb = 255; // Blue value
 
             // Define the starting colour #f32075
             yr = 243; // Red value
             yg = 32; // Green value
             yb = 117; // Blue value

             
             // Loop through each data point and calculate its % value
             $('#'+current_table_id + ' tbody td').not('.stats-title').each(function(){
                     var val = parseInt($(this).text());
                     var pos = parseInt((Math.round((val/max)*100)).toFixed(0));

                     red = parseInt((xr + (( pos * (yr - xr)) / (n-1))).toFixed(0));
                     green = parseInt((xg + (( pos * (yg - xg)) / (n-1))).toFixed(0));
                     blue = parseInt((xb + (( pos * (yb - xb)) / (n-1))).toFixed(0));
                     clr = 'rgb('+red+','+green+','+blue+')';
                     $(this).css({backgroundColor:clr});
                 });
                 }); //end each table

         });
</script>


<h3>Who answered question vs. Type</h3>
<table id="crosstab-5-6" class="crosstab heat-map"><thead><tr><td>&nbsp</td><td>Reference</td><td>Directional</td><td>Copy/Scan/Print</td><td>Computing</td><td>Archives</td><td>Other</td></tr>
</thead>
<tbody><tr class="stats-row"><td class="stats-title">Librarian</td><td>237</td><td>15</td><td>146</td><td>48</td><td>4</td><td>16</td> </tr>
<tr class="stats-row"><td class="stats-title">Student</td><td>81</td><td>69</td><td>139</td><td>40</td><td>1</td><td>40</td> </tr>
<tr class="stats-row"><td class="stats-title">Other</td><td>1</td><td>1</td><td>3</td><td>5</td><td>0</td><td>0</td> </tr>
</tbody>
</table>
<h3>Who answered question vs. Medium</h3>
<table id="crosstab-5-7" class="crosstab heat-map"><thead><tr><td>&nbsp</td><td>In Person</td><td>Email</td><td>Phone</td></tr>
</thead>
<tbody><tr class="stats-row"><td class="stats-title">Librarian</td><td>358</td><td>45</td><td>63</td> </tr>
<tr class="stats-row"><td class="stats-title">Student</td><td>291</td><td>0</td><td>79</td> </tr>
<tr class="stats-row"><td class="stats-title">Other</td><td>8</td><td>0</td><td>2</td> </tr>
</tbody>