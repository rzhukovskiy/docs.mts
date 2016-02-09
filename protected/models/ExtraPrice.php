<?php

/**
 * This is the model class for table "{{price}}".
 *
 * The followings are the available columns in table '{{price}}':
 * @property int $id
 * @property int $price_id
 * @property int $inside
 * @property int $outside
 */
class ExtraPrice extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ExtraPrice the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{extra_price}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('price_id, inside, outside','required'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'inside' => 'Стоимость(внутр.)',
            'outside' => 'Стоимость(снар.)',
        );
    }

    public function relations()
    {
        return array(
            'price' => array(self::BELONGS_TO, 'Price', 'price_id'),
        );
    }
}