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
 * @property string $month
 * @property int $period
 */
class Car extends CActiveRecord
{
    public $service_count;
    public $period;
    public $month;

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
            array('company_id, number, mark_id, type_id', 'required'),
            array('number', 'unique'),
            array('period, month', 'safe'),
            array('service_count, id, number, mark_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'mark' => array(self::BELONGS_TO, 'Mark', 'mark_id'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'act' => array(self::HAS_MANY, 'Act', '', 'on'=>'act.number = t.number', 'joinType' => 'INNER JOIN', 'alias' => 'act'),
        );
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

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
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

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }

        $criteria->with = 'act';
        $criteria->together = true;
        $criteria->select = '*, count(act.id) as service_count';
        $criteria->group = 't.id';
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.company_id', $this->company_id);
        $criteria->compare('t.mark_id', $this->mark_id);
        $criteria->compare('t.type_id', $this->type_id);
        switch ($this->period) {
            case 1:
                if($this->month) {
                    $criteria->compare('date_format(act.service_date, "%Y-%m")', $this->month);
                }
                break;
            case 2:
                $criteria->addBetweenCondition('act.service_date', date('Y-m-d', strtotime("-3 month", time())), date('Y-m-d', time()));
                break;
            case 3:
                $criteria->addBetweenCondition('act.service_date', date('Y-m-d', strtotime("-6 month", time())), date('Y-m-d', time()));
                break;
            case 4:
                $criteria->addBetweenCondition('act.service_date', date('Y-m-d', strtotime("-12 month", time())), date('Y-m-d', time()));
                break;
            default:
        }

        $sort = new CSort();
        $sort->defaultOrder = 't.company_id, service_count DESC';
        $sort->applyOrder($criteria);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => false,
        ));
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
        );
    }

    public function beforeSave()
    {
        $this->number = mb_strtoupper(preg_replace('/\s+/', '', $this->number), 'UTF-8');
        return true;
    }

    public function getActCount()
    {
        return count($this->act);
    }
}
