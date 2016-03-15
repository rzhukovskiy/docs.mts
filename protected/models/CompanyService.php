<?php

/**
 * Created by PhpStorm.
 * User: Valery
 * Date: 15.03.2016
 * Time: 15:48
 */
class CompanyService extends CActiveRecord
{


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{company_service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('company_id, service','required'),
            array('company_id','integer'),
            array('service', 'string'),
            array('service', 'unique'),
            array('service', 'in', 'range' => array_keys(Company::$listService)),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'company_id' => 'ID компании',
            'service' => 'сервис',
        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'id'),
        );
    }

}