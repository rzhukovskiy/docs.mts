<div class="loginbox radius3">
    <div class="loginboxinner radius3">
        <div class="loginform">
            <div class="loginerror"><p>Invalid username or password</p></div>
            <form id="login" action="<?php echo Yii::app()->createUrl('home/login') ?>" method="post">
                <p>
                    <input id="LoginForm_username" name="LoginForm[username]" type="text" class="radius2" placeholder="login" />
                </p>
                <p>
                    <input id="LoginForm_password" name="LoginForm[password]" type="password" class="radius2" placeholder="password"  />
                </p>
                <p>
                    <button id="formBtn" type="submit" class="radius3 bebas">Войти</button>
                </p>
            </form>
        </div><!--loginform-->
    </div><!--loginboxinner-->
</div><!--loginbox-->