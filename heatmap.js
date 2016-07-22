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
 
             // Define the starting colour #76f644
             // Alternate color options at: http://www.designchemical.com/lab/jquery/demo/jquery_data_heat_map_demo.htm
             yr = 68, //118;
             yg = 163, //246;
             yb = 64, //68;

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