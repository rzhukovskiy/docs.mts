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

        var it = 1;
        $('.data-table tbody tr').each(function(id, value) {
            var day = parseInt($(this).find('.value_0').text());
            for (var i = it + 1; i < day; i++) {
                data.addRow([
                    i.toString(),
                    0,
                ]);
            }
            data.addRow([
                parseInt($(this).find('.value_0').text()).toString(),
                parseInt($(this).find('.value_2').text().replace(" ", "")),
            ]);
            it = day;
        });

        // Set chart options
        var options = {
            title: '<?=StringNum::getMonthName(strtotime("$model->month-01 00:00:00"))[0]?> <?=explode('-',$model->month)[0]?>',
            legend: { position: 'none' },
            chartArea: {'width': '90%', 'height': '80%'},
            hAxis: {showTextEvery:1},
            fontSize: 14
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>