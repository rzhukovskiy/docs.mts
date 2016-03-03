<?php

/**
 * This is the model class for table "{{price}}".
 *
 * The followings are the available columns in table '{{price}}':
 * @property int $id
 * @property int $type_id
 * @property int $company_id
 * @property int $inside
 * @property int $outside
 * @property int $disinfection
 */
class Price extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Price the static model class
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
        return '{{price}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            ['type_id, company_id','required'],
            ['inside, outside, disinfection','safe'],
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type_id' => 'Тип ТС',
            'company_id' => 'Компания',
            'inside' => 'Стоимость(внутр.)',
            'outside' => 'Стоимость(снар.)',
            'disinfection' => 'Дезинфекция',
        );
    }

    public function relations()
    {
        return array(
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'extra' => array(self::HAS_ONE, 'ExtraPrice', 'price_id'),
        );
    }

    public function afterSave()
    {
        if ($this->disinfection && $this->company->type == Company::COMPANY_TYPE) {
            $this->company->is_infected = 1;
            $this->company->save();
        }
        parent::afterSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('inside', $this->inside);
        $criteria->compare('outside', $this->outside);

        $sort = new CSort;
        $sort->attributes = array('id', 'name');
        $sort->defaultOrder = 'id DESC';
        $sort->applyOrder($criteria);


        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function getFullOutside()
    {
        if (isset($this->extra)) {
            return $this->outside + $this->extra->outside;
        } else {
            return $this->outside;
        }
    }

    public function getFullInside()
    {
        if (isset($this->extra)) {
            return $this->inside + $this->extra->inside;
        } else {
            return $this->inside;
        }
    }
}