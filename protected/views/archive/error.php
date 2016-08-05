<?php
    /**
     * @var $this ArchiveController
     * @var $model Act
     * @var $provider Act
     */

    // активная вкладка
    $currentType = Yii::app()->request->getParam( 'type' );
    $currentTitle = 'Все ошибочные';

    $this->tabs = array(
        'error' => array(
            'url' => Yii::app()->createUrl( 'archive/error' ),
            'name' => 'Все ошибочные акты',
            'active' => ( $currentType == 'error' ),
        ),
    );

    foreach ( Company::$listService as $service => $name ) {
        $this->tabs[ $model->companyType != $service ? $service : 'list' ] = array(
            'url' => Yii::app()->createUrl( "archive/error?type=$service" ),
            'name' => $name,
            'active' => ( $currentType == $service ),
        );
        $currentTitle = ($currentType == $service) ? $name : $currentTitle ;
    }
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span><?=$currentTitle?></span></h2>
    </div>
<?php
    $this->renderPartial( 'error/_error', array(
        'model' => $model,
        'provider' => $provider,
    ) );
