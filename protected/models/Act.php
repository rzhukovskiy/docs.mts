<?php
/**
 * This is the model class for table "{{act}}".
 *
 * The followings are the available columns in table '{{act}}':
 * @property int $id
 * @property int $company_id
 * @property int $type_id
 * @property int $card_id
 * @property string $number
 * @property int $mark_id
 * @property string $create_date
 * @property string $service_date
 * @property int $is_closed
 * @property int $service
 * @property int $company_service
 * @property string $check
 * @property string $check_image
 * @property int $expense
 * @property int $income
 * @property int $profit
 * @property int $old_expense
 * @property int $old_income
 * @property string $day
 * @property string $month
 * @property int $period
 */
class Act extends CActiveRecord
{
    public $screen;
    public $old_expense;
    public $old_income;
    public $companyType;
    public $showCompany;
    public $period = 0;

    public static $periodList = array('месяц', 'квартал', 'полгода', 'год');
    public static $carwashList = array(
        'снаружи',
        'внутри',
        'снаружи+внутри',
    );
    public static $serviceList = array(
        3 => 'ремонт',
        4 => 'шиномонатж',
    );
    public static $fullList = array(
        'снаружи',
        'внутри',
        'снаружи+внутри',
        'ремонт',
        'шиномонтаж',
    );
    public static $shortList = array(
        'мойка',
        'мойка',
        'мойка',
        'ремонт',
        'шиномонтаж',
    );

    protected $month;
    protected $day;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Act the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{act}}';
    }

    public function rules()
    {
        return array(
            array('type_id, card_id, number, mark_id', 'required'),
            array('check', 'unique'),
            array('period, month, day, check, company_service, old_expense, old_income, month, company_id, service, company_service, service_date, profit, income, expense, check_image', 'safe'),
            array('company_id, id, number, mark_id, type_id, card_id, service_date', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'card' => array(self::BELONGS_TO, 'Card', 'card_id'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'mark' => array(self::BELONGS_TO, 'Mark', 'mark_id'),
            'scope' => array(self::HAS_MANY, 'ActScope', 'act_id'),
        );
    }

    public function beforeSave()
    {
        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $this->company_id != Yii::app()->user->model->company_id) {
            return false;
        }

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $this->is_closed) {
            return false;
        }

        $this->number = mb_strtoupper(preg_replace('/\s+/', '', $this->number), 'UTF-8');

        $car = Car::model()->find('number = :number', array(':number' => $this->number));
        if ($car) {
            $this->mark_id = $car->mark_id;
            $this->type_id = $car->type_id;
        }

        if ($this->company->type == Company::SERVICE_TYPE) {
            $this->company_service = $this->service = 3;
        }
        if ($this->company->type == Company::TIRES_TYPE) {
            $this->company_service = $this->service = 4;
        }

        if ($this->isNewRecord) {
            $this->company_service = $this->service;
        }

        if (!$this->income
            || ($this->company->type == Company::CARWASH_TYPE && $this->old_income == $this->income)
        ) {
            $washPrice = Price::model()->find('company_id = :company_id AND type_id = :type_id',
                array(
                    ':company_id' => $this->card->company_id,
                    ':type_id' => $this->type_id
                )
            );
            if (!$washPrice) {
                $this->income = 0;
            } else {
                $servicePrice = array(
                    $washPrice->outside,
                    $washPrice->inside,
                    $washPrice->outside + $washPrice->inside,
                );

                $this->income = $servicePrice[$this->company_service];
            }
        }

        if (!$this->expense
            || ($this->company->type == Company::CARWASH_TYPE && $this->old_expense == $this->expense)
        ) {
            $washPrice = Price::model()->find('company_id = :company_id AND type_id = :type_id',
                array(
                    ':company_id' => $this->company_id,
                    ':type_id' => $this->type_id
                )
            );
            if (!$washPrice) {
                $this->expense = 0;
            } else {
                $servicePrice = array(
                    $washPrice->outside,
                    $washPrice->inside,
                    $washPrice->outside + $washPrice->inside,
                );

                $this->expense = $servicePrice[$this->service];
            }
        }

        $this->profit = $this->income - $this->expense;

        return true;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && Yii::app()->user->model->company->type == Company::COMPANY_TYPE) {
            $this->is_closed = 1;
            $this->showCompany = 1;
        }

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }

        $sort = new CSort;

        if ($this->showCompany) {
            $criteria->compare('card.company_id', $this->company_id);
            $criteria->together = 1;
            $sort->defaultOrder = 'card.company_id';
        } else {
            $criteria->compare('t.company_id', $this->company_id);
            $sort->defaultOrder = 't.company_id, t.service_date';
        }

        $criteria->with = array('company' , 'card', 'type', 'mark');
        $criteria->compare('company.type', $this->companyType);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.card_id', $this->card_id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        $criteria->compare('t.is_closed', $this->is_closed);
        if($this->day) {
            $criteria->compare('date_format(t.service_date, "%Y-%m-%d")', "$this->month-$this->day");
        } elseif($this->month) {
            $criteria->compare('date_format(t.service_date, "%Y-%m")', $this->month);
        }
        if($this->create_date) {
            $criteria->compare('date_format(t.create_date, "%Y-%m-%d")', $this->create_date);
        }
        $sort->applyOrder($criteria);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function cars()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('number', $this->number);
        switch ($this->period) {
            case 1:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-3 month", time())), date('Y-m-d', time()));
                break;
            case 2:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-6 month", time())), date('Y-m-d', time()));
                break;
            case 3:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-12 month", time())), date('Y-m-d', time()));
                break;
            default:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-1 month", time())), date('Y-m-d', time()));
        }

        $sort = new CSort;
        $sort->defaultOrder = 'service_date';
        $sort->applyOrder($criteria);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'check' => 'Номер чека',
            'check_image' => 'Чек',
            'screen' => 'Загрузка чека',
            'type_id' => 'Тип ТС',
            'company_id' => 'Мойка',
            'card_id' => 'Карта',
            'number' => 'Госномер',
            'mark_id' => 'Марка',
            'service_date' => 'Дата',
            'service' => 'Услуга',
            'company_service' => 'Услуга',
            'month' => 'Месяц',
            'is_closed' => 'Закрыта',
            'profit' => 'Итого',
            'income' => 'Цена компании',
            'expense' => 'Цена мойки',
        );
    }

    public function getMonth()
    {
        if (!$this->month) {
           $this->month = date('Y-m', strtotime($this->service_date));
        }

        return $this->month;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function totalExpense()
    {
        $total = 0;
        foreach ($this->search()->getData() as $row) {
            $total += $row->expense;
        }

        return $total;
    }

    public function totalIncome()
    {
        $total = 0;
        foreach ($this->search()->getData() as $row) {
            $total += $row->income;
        }

        return $total;
    }

    public function totalProfit()
    {
        $total = 0;
        foreach ($this->search()->getData() as $row) {
            $total += $row->profit;
        }

        return $total;
    }
}
