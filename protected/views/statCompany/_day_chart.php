<div id="chart_div" style="width:100%;height:550px;"></div>
<script type="text/javascript">
    var it = 1;
    var days = <?=date("t", strtotime("$model->month-01 00:00:00"))?>;
    var dataTable = [];
    CanvasJS.addColorSet("blue",
        [//colorSet Array
            "#428bca"
        ]);

    $('.data-table tbody tr').each(function(id, value) {
        var day = parseInt($(this).find('.value_0').text());
        for (var i = it; i < day; i++) {
            dataTable.push({
                x: i,
                y: 0
            });
        }
        dataTable.push({
            x: parseInt($(this).find('.value_0').text()),
            y: parseInt($(this).find('.value_2').text().replace(" ", ""))
        });
        it = day;
    });

    for (var i = it + 1; i <= days; i++) {
        dataTable.push({
            x: i,
            y: 0
        });
    }

    var options = {
        colorSet: "blue",
        title: {
            text: "<?=StringNum::getMonthName(strtotime("$model->month-01 00:00:00"))[0]?> <?=explode('-',$model->month)[0]?>",
            fontColor: '#069',
            fontSize: 22
        },
        dataPointMaxWidth: 30,
        subtitles:[
            {
                text: "<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 'Прибыль' : 'Расход'?>",
                horizontalAlign: "left",
                fontSize: 14,
                fontColor: '#069',
                margin: 20
            }
        ],
        data: [
            {
                type: "column",
                dataPoints: dataTable
            }
        ],
        axisX:{
            title: "Дни месяца",
            titleFontSize: 14,
            titleFontColor: '#069',
            titleFontWeight: 'bold',
            labelFontColor: '#069',
            labelFontWeight: 'bold',
            interval: 1,
            lineThickness: 1,
            labelFontSize: 14,
            lineColor: 'black',
            margin: 20
        },

        axisY:{
            labelFontColor: '#069',
            labelFontWeight: 'bold',
            tickThickness: 1,
            gridThickness: 1,
            lineThickness: 1,
            labelFontSize: 14,
            lineColor: 'black',
            valueFormatString: "### ### ###",
            stripLines:[
                {
                    thickness: 1,
                    value:0,
                    color:"#000"
                }
            ]
        }
    };

    $("#chart_div").CanvasJSChart(options);
</script>