<div class="header radius3">
    <div class="headerinner">
        <div class="headright">
            <div class="headercolumn" id="userPanel">
                <a class="userinfo radius2" href="#">
                    <span><strong><?=mb_ucfirst(Yii::app()->user->name); ?></strong></span>
                </a>
                <div class="userdrop">
                    <ul>
                        <li><a href="<?=Yii::app()->createUrl('home/logout'); ?>">Выход</a></li>
                    </ul>
                </div><!--userdrop-->
            </div><!--headercolumn-->
            <!--headercolumn-->
        </div><!--headright-->
    </div><!--headerinner-->
</div>