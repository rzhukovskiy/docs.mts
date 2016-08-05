<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <?php $this->widget('WHTMLHead'); ?>
        <title><?php
        echo CHtml::encode(Yii::app()->name);
        if (!empty($this->pageTitle))
            echo ':' . CHtml::encode($this->pageTitle);
        ?></title>
    </head>
    <body>
        <?php $this->widget('WHeader'); ?>
        <div class="mainwrapper">
            <div class="mainwrapperinner">
                <?php echo $content; ?>	
            </div><!--mainwrapperinner-->
        </div>
        <div class="overlay"></div>
        <a href="#" class="go-to-bottom">Вниз</a>
        <a href="#" class="back-to-top">Наверх</a>
    </body>
</html>