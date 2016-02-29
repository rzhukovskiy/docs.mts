<?php
/**
 * @var $this StatController
 * @var $model Act
 */

$ts1 = strtotime($model->from_date);
$ts2 = strtotime($model->to_date);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
?>
<div id="chart_div" style="width:100%;height:500px;"></div>
<script type="text/javascript">
    var dataTable = [];
    $('.data-table tbody tr').each(function (id, value) {
        dataTable.push({
            label: $(this).find('.value_0').text(),
            y: parseInt($(this).find('.value_2').text().replace(" ", "")),
        });
    });
    var options = {
        title: {
            text: 'По компаниям',
            fontColor: '#069',
            fontSize: 22
        },
        data: [
            {
                type: "pie", //change it to line, area, bar, pie, etc
                dataPoints: dataTable,
                yValueFormatString: "### ### ###",
                toolTipContent: "{label}: <strong>{y}</strong>",
                indexLabel: "{label} - {y}",
                indexLabelFontSize: 14,
                indexLabelFontColor: '#069',
                indexLabelFontWeight: 'bold'
            }
        ]
    };

    $("#chart_div").CanvasJSChart(options);
</script>