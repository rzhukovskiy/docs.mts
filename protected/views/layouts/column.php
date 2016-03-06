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
        <?php if (!empty($this->tabs)) { ?>
            <ul class="maintabmenu">
                <?php foreach ($this->tabs as $key => $value) { if(!isset($value['role']) || Yii::app()->user->checkAccess($value['role'])) { ?>
                    <li<?php if (Yii::app()->controller->action->id == $key) { ?> class="current"<?php } ?>><a href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></li>
                <?php } } ?>
            </ul><!--maintabmenu-->
        <?php } ?>
        <div class="content">
            <?=$content; ?>
        </div><!--content-->
    </div><!--maincontentinner-->
    <?php $this->widget('WFooter'); ?>
</div><!--maincontent-->
<?php $this->endContent(); ?>
        

