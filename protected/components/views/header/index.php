<div class="header radius3">
    <div class="headerinner">
        <div class="headright">
            <div class="headercolumn" id="userPanel">
                <?php if(!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && isset(Yii::app()->request->cookies['is_admin']->value)) { ?>
                    <a class="btn btn-danger" href="/user/login?id=1">Стать админом</a>
                <? } ?>
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