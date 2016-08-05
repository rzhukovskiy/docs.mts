<?php
/** @var Controller $this */
?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="mainleft">
    <div class="mainleftinner">
        <div class="leftmenu">
            <?php $this->widget('WMenu'); ?>
        </div><!--leftmenu-->
    </div><!--mainleftinner-->
</div><!--mainleft-->
<div class="maincontent noright">
    <div class="maincontentinner">
        <?php if (!empty($this->tabs))
            $this->widget('WTabsMenu', array('items' => $this->tabs));
        ?>
        <div class="content">
            <?=$content; ?>
        </div><!--content-->
    </div><!--maincontentinner-->
    <?php $this->widget('WFooter'); ?>
</div><!--maincontent-->
<?php $this->endContent(); ?>
        

