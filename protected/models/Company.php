<?php

/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property int $id
 * @property int $parent_id
 * @property int $is_split
 * @property int $is_infected
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $contact
 * @property string $type
 * @property string $contract
 * @property string $act_header
 * @property string $month
 */
class Company extends CActiveRecord
{
    const COMPANY_TYPE      = 'company',
          CARWASH_TYPE      = 'carwash',
          SERVICE_TYPE      = 'service',
          DISINFECTION_TYPE = 'disinfection',
          TIRES_TYPE        = 'tires',
          UNIVERSAL_TYPE    = 'universal';

    public $cardList;
    public $month;

    static $listType = [
        self::COMPANY_TYPE => 'Компания',
        self::CARWASH_TYPE => 'Мойка',
        self::SERVICE_TYPE => 'Сервис',
        self::TIRES_TYPE => 'Шиномонтаж',
        self::DISINFECTION_TYPE => 'Дезинфекция',
        self::UNIVERSAL_TYPE => 'Универсальная',
    ];

    static $listService = [
        self::CARWASH_TYPE => 'Мойка',
        self::SERVICE_TYPE => 'Сервис',
        self::TIRES_TYPE => 'Шиномонтаж',
        self::DISINFECTION_TYPE => 'Дезинфекция',
    ];

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
            array('name','unique'),
            array('is_infected, parent_id, is_split, address, phone, contact, contract, act_header, type, cardList','safe'),
            array('carwash', 'safe'),
            array('remont', 'safe'),
            array('tires', 'safe'),
            array('disinfection', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'is_split' => 'Разделять тягач и п/п',
            'name' => 'Название',
            'address' => 'Город',
            'phone' => 'Телефон',
            'cardList' => 'Карты',
            'contact' => 'Директор',
            'contract' => 'Договор',
            'act_header' => 'Заголовок акта',
            'parent_id' => 'Родительская компания',
            'carwash' => 'Мойка',
            'remont' => 'Сервис',
            'tires' => 'Шиномонтаж',
            'disinfection' => 'Дезинфекция',
        );
    }

    public function relations()
    {
        return array(
            'users' => array(self::HAS_MANY, 'User', 'company_id'),
            'cards' => array(self::HAS_MANY, 'Card', 'company_id'),
            'cars' => array(self::HAS_MANY, 'Car', 'company_id'),
            'acts' => array(self::HAS_MANY, 'Act', 'client_id'),
            'parent' => array(self::BELONGS_TO, 'Company', 'parent_id'),
            'children' => array(self::HAS_MANY, 'Company', 'parent_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the  models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.parent_id', $this->parent_id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.address', $this->address, true);
        $criteria->compare('t.phone', $this->phone, true);
        $criteria->compare('t.contact', $this->contact, true);
        $criteria->compare('t.type', $this->type, true);

        $sort = new CSort;
        $sort->attributes = array('id', 'name', 'address', 'phone', 'contact');
        $sort->defaultOrder = 'id DESC';
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

    /**
     * @return Company
     */
    public function withEmptyActs() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->addCondition('NOT EXISTS (SELECT * FROM mts_act WHERE mts_act.client_id = t.id AND date_format(mts_act.service_date, "%Y-%m") = "' . $this->month . '")');

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    public function afterSave()
    {
        if ($this->cardList) {
            $numPointList = explode('-', $this->cardList);
            if(count($numPointList) > 1) {
                for ($num = intval($numPointList[0]); $num <= intval($numPointList[1]); $num++) {
                    $card = new Card();
                    $card->company_id = $this->id;
                    $card->number = $num;
                    $card->save();
                }
            }
        }
    }
}