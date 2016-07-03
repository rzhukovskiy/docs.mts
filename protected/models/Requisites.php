<?php

/**
 * This is the model class for table "{{requisites}}".
 *
 * The followings are the available columns in table '{{requisites}}':
 * @property int $id
 * @property int $company_id
 * @property string $contract
 * @property string $header
 * @property string $service_type
 */
class Requisites extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Requisites the static model class
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
        return '{{requisites}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('company_id, contract','required'),

        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'       => 'ID',
            'contract' => 'Договор',
            'header'   => 'Заголовок',
        );
    }
}