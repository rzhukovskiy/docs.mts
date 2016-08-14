<?php

/**
 * This is the model class for table "{{company_tires_service}}".
 *
 * The followings are the available columns in table '{{company_tires_service}}':
 * @property int $id
 * @property int $company_id
 * @property int $tires_service_id
 * @property int $type_id
 * @property int $price
 * @property Company $company
 * @property Type $type
 * @property TiresService $tiresService
 */
class CompanyTiresService extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CompanyTiresService the static model class
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
        return '{{company_tires_service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('company_id, tires_service_id, type_id, price','required'),

        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'tiresService' => array(self::BELONGS_TO, 'TiresService', 'tires_service_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'          => 'ID',
            'company_id'  => 'Компания',
            'tires_service_id' => 'Услуга',
            'type_id'     => 'Тип ТС',
            'price'       => 'Сумма',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('type_id', $this->type_id);

        $sort = new CSort;
        $sort->defaultOrder = 'type_id ASC';
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function byPrice()
    {
        $criteria = new CDbCriteria;

        $criteria->group = 'tires_service_id + price';

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    public function getSamePrices()
    {
        $samePrices = CompanyTiresService::model()->findAll([
            'condition' => 'company_id = :company_id AND price = :price AND tires_service_id = :service_id',
            'params' => [
                ':company_id' => $this->company_id,
                ':price'      => $this->price,
                ':service_id' => $this->tires_service_id,
            ],
        ]);

        $cnt = 0;
        $types = [];
        foreach($samePrices as $price) {
            $types[] = $price->type->name;
            $cnt++;
            if ($cnt == 3) {
                $types[] = '...';
                break;
            }
        }

        return implode(", ", $types);
    }

    public function beforeDelete()
    {
        CompanyTiresService::model()->deleteAll([
            'condition' => 'company_id = :company_id AND price = :price AND tires_service_id = :service_id',
            'params' => [
                ':company_id' => $this->company_id,
                ':price'      => $this->price,
                ':service_id' => $this->tires_service_id,
            ],
        ]);

        return false;
    }
}