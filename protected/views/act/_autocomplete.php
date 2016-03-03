<script>
    $(function() {
        var availableTags = <?php
            $result = array();
            foreach (Car::model()->findAll() as $row)
            {
                $result[] = htmlentities($row->number);
            }

            echo json_encode($result);
        ?>;

        $( ".number_fill" ).autocomplete({
            source: availableTags
        });
    });
</script>