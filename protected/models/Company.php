<?php

/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $contact
 * @property string $type
 * @property string $contract
 * @property string $act_header
 * @property int $is_demo
 */
class Company extends CActiveRecord
{
    const COMPANY_TYPE = 'company',
          CARWASH_TYPE = 'carwash',
          SERVICE_TYPE = 'service',
          TIRES_TYPE   = 'tires';

    public $cardList;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Company the static model class
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
        return '{{company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name','required'),
            array('is_demo, address, phone, contact, contract, act_header, type, cardList','safe'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Город',
            'phone' => 'Телефон',
            'cardList' => 'Карты',
            'contact' => 'Директор',
            'contract' => 'Договор',
            'act_header' => 'Заголовок акта',
            'is_demo' => 'Демо компания',
        );
    }

    public function relations()
    {
        return array(
            'users' => array(self::HAS_MANY, 'User', 'company_id'),
            'cards' => array(self::HAS_MANY, 'Card', 'company_id'),
            'cars' => array(self::HAS_MANY, 'Car', 'company_id'),
        );
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('type', $this->type, true);

        $sort = new CSort;
        $sort->attributes = array('id', 'name', 'address', 'phone', 'contact');
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

    public function afterSave()
    {
        if ($this->cardList) {
            $numPointList = explode('-', $this->cardList);
            if(count($numPointList) > 1) {
                for ($num = intval($numPointList[0]); $num <= intval($numPointList[1]); $num++) {
                    $card = new Card();
                    $card->company_id = $this->id;
                    $card->num = $num;
                    $card->save();
                }
            }
        }
    }
}