<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 17:06
     *
     * @var $this WTabsMenu;
     * @var $value array;
     * @var $key int;
     */

?>
<li class="<?php echo isset($value['class']) ? $value['class'] : ''; ?>">
    <a href="<?php echo $value[ 'url' ]; ?>"><?php echo $value[ 'name' ]; ?></a>
</li>
