<div id="chart_div" style="width:100%;height:500px;"></div>
<script type="text/javascript">
    CanvasJS.addColorSet("blue",
        [//colorSet Array

            "#428bca"
        ]);

    var dataTable = [];
    $('.data-table tbody tr').each(function(id, value) {
        dataTable.push({
            label: $(this).find('.value_0').text(),
            y: parseInt($(this).find('.value_2').text().replace(" ", "")),
            indexLabel: '{y}'
        });
    });
    var options = {
        colorSet: "blue",
        title: {
            text: 'По месяцам',
            fontColor: '#069',
            fontSize: 22
        },
        subtitles:[
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
        axisX:{
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

        axisY:{
            labelFontColor: '#069',
            labelFontWeight: 'bold',
            tickThickness: 1,
            gridThickness: 1,
            lineThickness: 1,
            labelFontSize: 14,
            lineColor: 'black',
            valueFormatString: "### ### ###"
        }
    };

    $("#chart_div").CanvasJSChart(options);
</script>