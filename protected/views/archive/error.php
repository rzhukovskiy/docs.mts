<?php
    /**
     * @var $this ArchiveController
     * @var $model Act
     * @var $provider Act
     */

?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span><?=$title?></span></h2>
    </div>
<?php
    $this->renderPartial( 'error/_error', array(
        'model' => $model,
        'provider' => $provider,
    ) );
