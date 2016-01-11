<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
<script>
    $(function(){
        var data = <?php
            $result = array();
            foreach (Card::model()->findAll() as $row)
            {
                $result[] = array($row->id, htmlentities($row->number));
            }

            echo json_encode($result);
        ?>;

        function setToPid(pid)
        {
            var sites = $('#Act_card_id');
            var selected = sites.val();
            var html = '';
            $.each(data, function(i,e){
                html += '<option value="'+e[0]+'">'+e[1]+'</option>';
            });

            sites.html(html);
            if (selected)
            {
                sites.select2('val',selected);
            }
        }

        $('#Act_card_id').select2();
        $('#Act_card_id').change(function(){
            setToPid($(this).val());
        });

        setToPid($('#Act_card_id').val());
    })
</script>