<?php
/**
 * This is the model class for table "{{car}}".
 *
 * The followings are the available columns in table '{{car}}':
 * @property int $id
 * @property int $company_id
 * @property string $number
 * @property int $mark_id
 * @property int $type_id
 * @property int $service_count
 * @property int $client_id
 * @property int $is_infected
 * @property string $from_date
 * @property string $to_date
 * @property CUploadedFile $external
 */
class Car extends CActiveRecord
{
    public $from_date;
    public $to_date;
    public $client_id;
    public $service_count;
    public $period;
    public $month;
    public $external;

    public static $periodList = array('все время', 'месяц', 'квартал', 'полгода', 'год');
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Car the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{car}}';
    }

    public function rules()
    {
        return array(
            array('company_id, number, type_id', 'required'),
            array('number', 'unique'),
            array('is_infected, month, external, mark_id, client_id, from_date, to_date', 'safe'),
            array('service_count, id, number, mark_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'mark' => array(self::BELONGS_TO, 'Mark', 'mark_id'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'act' => array(self::HAS_MANY, 'Act', '', 'on'=>'act.number = t.number AND act.client_id = t.company_id', 'joinType' => 'LEFT JOIN', 'alias' => 'act'),
        );
    }

    public function after($id)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', ' >=' . $id);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.company_id', $this->company_id);
        $criteria->compare('t.mark_id', $this->mark_id);
        $criteria->compare('t.type_id', $this->type_id);

        $this->getDbCriteria()->mergeWith($criteria);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'pagination' => false,
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function services()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        if (!$this->company_id && !Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }
        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $criteria->with = ['cardCompany'];
            $criteria->compare('company_id', Yii::app()->user->model->company_id);
            $criteria->compare('company.parent_id', Yii::app()->user->model->company_id, false, 'OR');
        }

        $criteria->with = ['act', 'company'];
        $criteria->select = '*, count(act.id) as service_count';
        $criteria->group = 't.id';
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->addCondition("NOT act.service = '" . Company::DISINFECTION_TYPE . "'");
        if (isset($this->from_date)) {
            $criteria->addBetweenCondition('act.service_date', $this->from_date, $this->to_date);
        }

        $sort = new CSort();
        $sort->defaultOrder = 't.company_id, service_count DESC';
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'sort' => $sort,
            'pagination' => false,
        ));

        return $provider;
    }

    /**
     * @return CActiveDataProvider
     */
    public function infected()
    {
        $criteria = new CDbCriteria;

        $criteria->addCondition('NOT EXISTS (SELECT * FROM mts_act WHERE mts_act.partner_service = 5
            AND mts_act.number = t.number AND date_format(mts_act.service_date, "%Y-%m") = "' . $this->month . '")');
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('is_infected', 1);
        $criteria->addCondition('type_id != 7');
        $criteria->group = 't.id';

        $sort = new CSort();
        $sort->defaultOrder = 't.number DESC';
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'sort' => $sort,
            'pagination' => false,
        ));

        return $provider;
    }

    /**
     * @return CActiveDataProvider
     */
    public function dirty()
    {
        $criteria = new CDbCriteria;

        if (!$this->company_id && !Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }

        $criteria->with = ['company'];
        if ($this->from_date) {
            $criteria->addCondition('NOT EXISTS (SELECT * FROM mts_act WHERE mts_act.number = t.number AND mts_act.service_date BETWEEN "' . $this->from_date . '" AND "' . $this->to_date .'")');
        } else {
            $criteria->addCondition('NOT EXISTS (SELECT * FROM mts_act WHERE mts_act.number = t.number)');
        }
        $criteria->group = 't.id';
        $criteria->compare('t.id', $this->id);
        $criteria->compare('company.is_infected', 1);
        if ($this->client_id) {
            $criteria->compare('clientParent.id', $this->client_id);
        } else {
            $criteria->compare('company_id', $this->company_id);
        }

        $sort = new CSort();
        $sort->defaultOrder = 't.company_id, t.number DESC';
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'sort' => $sort,
            'pagination' => false,
        ));

        return $provider;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'number' => 'Номер',
            'company_id' => 'Компания',
            'mark_id' => 'Марка ТС',
            'type_id' => 'Тип ТС',
            'service_count' => 'Обслуживаний',
            'external' => 'Файл',
            'is_infected' => 'Дизенфицировать',
        );
    }

    public function beforeSave()
    {
        $this->number = mb_strtoupper(str_replace(' ', '', $this->number), 'UTF-8');
        return true;
    }

    public function getActCount()
    {
        return count($this->act);
    }

    public function saveFromExternal()
    {
        if($this->external) {
            $res = [];
            spl_autoload_unregister(array('YiiBase','autoload'));
            Yii::import("ext.PHPExcel.Classes.PHPExcel", true);
            spl_autoload_register(array('YiiBase','autoload'));

            $objPHPExcel = PHPExcel_IOFactory::load($this->external->getTempName());

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow(); // e.g. 10

                for ($row = 1; $row <= $highestRow; ++ $row) {
                    $name   = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $type   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                    if (
                        PHPExcel_Cell_DefaultValueBinder::dataTypeForValue($name) == PHPExcel_Cell_DataType::TYPE_STRING
                        && PHPExcel_Cell_DefaultValueBinder::dataTypeForValue($number) == PHPExcel_Cell_DataType::TYPE_NULL
                        && PHPExcel_Cell_DefaultValueBinder::dataTypeForValue($type) == PHPExcel_Cell_DataType::TYPE_NULL
                    ) {
                        if ($newCompany = Company::model()->find('name = :name', [':name' => $name])) {
                            $this->company_id = $newCompany->id;
                        }
                        continue;
                    }

                    $car = new Car();
                    $car->attributes = $this->attributes;

                    if ($type = Type::model()->find('name = :name', [':name' => $type])) {
                        $car->type_id = $type->id;
                    }

                    $name = explode('-', explode(' ', $name)[0])[0];
                    if ($mark = Mark::model()->find('name = :name', [':name' => $name])) {
                        $car->mark_id = $mark->id;
                    }

                    $number = mb_strtoupper(str_replace(' ', '', $number), 'UTF-8');
                    $number = strtr($number, Translit::$rules);
                    if ($existed = Car::model()->find('number = :number', [':number' => $number])) {
                        continue;
                    }
                    $car->number = $number;

                    $car->save();
                    $res[] = $car;
                }
            }

            return $res;
        }

        return $this;
    }
}
