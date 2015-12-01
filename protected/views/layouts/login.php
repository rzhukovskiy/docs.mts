<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <title><?php
echo CHtml::encode(Yii::app()->name);
if (!empty($this->pageTitle))
    echo ':' . CHtml::encode($this->pageTitle);
?></title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link href="/css/style.css" type="text/css" rel="stylesheet">
        <link href="/css/style.brightblue.css" type="text/css" rel="stylesheet">
    </head>
    <body class="login">
        <?=$content; ?>
        <?php $this->widget('WFooter'); ?>
    </body>
</html>
