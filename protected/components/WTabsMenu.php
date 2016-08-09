<?php

    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 16:55
     *
     * Виджет меню в виде табов
     * На входе массив элементов
     */

    class WTabsMenu extends CWidget
    {
        /**
         * Элементы меню
         *
         * @var array $items {
         *      @var string $name required
         *      @var string $url required
         *      @var boolean $active
         * }
         */
        public $items;

        /**
         * Определяем активный элемент внутри виджета
         *
         * @var null
         */
        private $active = null;

        /**
         *
         */
        public function init()
        {
            if ( is_null( $this->active ) )
                $this->active = $this->getActiveElement();
        }

        /**
         * @throws CException
         */
        public function run()
        {
            $this->render( 'tabsmenu/index', array(
                'items' => $this->items,
                'active' => $this->active,
            ) );
        }

        /**
         * Поиск активного элемента в наборе табов.
         * По дефолту привязано к действию контроллера
         *
         * @return string
         */
        private function getActiveElement()
        {
            $active = Yii::app()->controller->action->id;

            foreach ( $this->items as $key => $item ) {
                if ( array_key_exists( 'active', $item ) )
                    if ($item['active']) {
                        $active = $key;

                        // выходим по первому попавшемуся! 2 активных не рассматриваем
                        break;
                    }
            }

            return $active;
        }
    }