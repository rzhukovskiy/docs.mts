<div id="chart_div" style="width:100%;height:500px;"></div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {
        'packages':['corechart'],
        'language' : 'ru'
    });

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Партнер');
        data.addColumn('number', 'Прибыль');
        data.addColumn({type: 'string', role: 'annotation'});

        $('.data-table tr').each(function(id, value) {
            data.addRow([
                $(this).find('.value_0').text(),
                parseInt($(this).find('.value_2').text().replace(" ", "")),
                $(this).find('.value_2').text().replace(" ", ""),
            ]);
        });

        // Set chart options
        var options = {
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>