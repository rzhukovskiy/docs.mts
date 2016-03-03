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
    CanvasJS.addColorSet("blue",
        [//colorSet Array
            "#428bca"
        ]);
    var dataTable = [];
    <?php if(!$diff || $diff == 12) {
        for ($i = 1; $i <= 12; $i++) {?>
            if ($('.data-table .month_<?=$i?>').length) {
                dataTable.push({
                    label: '<?=StringNum::getMonthNameByNum($i)[0]?>',
                    y: parseInt($('.data-table .month_<?=$i?>').parent().find('.value_2').text().replace(" ", "")),
                });
            } else {
                dataTable.push({
                    label: '<?=StringNum::getMonthNameByNum($i)[0]?>',
                    y: 0
                });
            }
        <?php }
    } else { ?>
    $('.data-table tbody tr').each(function (id, value) {
        dataTable.push({
            label: $(this).find('.value_0').text(),
            y: parseInt($(this).find('.value_2').text().replace(" ", "")),
        });
    });
    <?php } ?>
    var max = 0;
    dataTable.forEach(function (value) {
        if (value.y > max) max = value.y;
    });
    var options = {
        colorSet: "blue",
        dataPointMaxWidth: 40,
        title: {
            text: 'По месяцам',
            fontColor: '#069',
            fontSize: 22
        },
        subtitles: [
            {
                text: "Прибыль",
                horizontalAlign: "left",
                fontSize: 14,
                fontColor: '#069',
                margin: 20
            }
        ],
        data: [
            {
                type: "column", //change it to line, area, bar, pie, etc
                dataPoints: dataTable
            }
        ],
        axisX: {
            title: "Месяц",
            titleFontSize: 14,
            titleFontColor: '#069',
            titleFontWeight: 'bold',
            labelFontColor: '#069',
            labelFontWeight: 'bold',
            interval: 1,
            lineThickness: 1,
            labelFontSize: 14,
            lineColor: 'black'
        },

        axisY: {
            labelFontColor: '#069',
            labelFontWeight: 'bold',
            tickThickness: 1,
            gridThickness: 1,
            lineThickness: 1,
            labelFontSize: 14,
            lineColor: 'black',
            valueFormatString: "### ### ###",
            maximum: max + 0.1 * max
        }
    };

    $("#chart_div").CanvasJSChart(options);
</script>