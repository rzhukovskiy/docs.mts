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
 * @property int $amount
 */
class Act extends CActiveRecord
{
    public $screen;
    public $old_expense;
    public $old_income;
    public $companyType;
    public $showCompany;
    public $cardCompany;
    public $period;
    public $amount;
    public $fixMode = false;

    public static $periodList = array('все время', 'месяц', 'квартал', 'полгода', 'год');
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
        'мойка снаружи',
        'мойка внутри',
        'мойка снаружи+внутри',
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
            array('cardCompany, period, month, day, check, company_service, old_expense, old_income, month, company_id, service, company_service, service_date, profit, income, expense, check_image', 'safe'),
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

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $this->companyType == Company::CARWASH_TYPE) {
            $closedList = self::model()->find('is_closed = 1 AND date_format(service_date, "%Y-%m") = :month', array(
                ':month' => date('Y-m', strtotime($this->service_date)),
            ));
            if ($closedList) {
                return false;
            }
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

        if (($this->isNewRecord && !$this->income)
            || (!$this->is_closed && $this->company->type == Company::CARWASH_TYPE && $this->old_income == $this->income)
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

        if (($this->isNewRecord && !$this->expense)
            || (!$this->is_closed && $this->company->type == Company::CARWASH_TYPE && $this->old_expense == $this->expense)
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
        $criteria = new CDbCriteria();

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
            $sort->defaultOrder = 'card.company_id, t.service_date';
        } else {
            $criteria->compare('t.company_id', $this->company_id);
            $sort->defaultOrder = 't.company_id, t.service_date';
        }

        $criteria->with = array('company', 'card', 'type', 'mark', 'card.cardCompany');
        $criteria->compare('company.type', $this->companyType);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.card_id', $this->card_id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        if($this->create_date) {
            $criteria->compare('date_format(t.create_date, "%Y-%m-%d")', $this->create_date);
        } else {
            if($this->day) {
                $criteria->compare('date_format(t.service_date, "%Y-%m-%d")', "$this->month-$this->day");
            } else {
                $criteria->compare('date_format(t.service_date, "%Y-%m")', $this->getMonth());
            }
        }
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'pagination' => false,
        ));

        $this->setDbCriteria(new CDbCriteria());

        return $provider;
    }

    public function stat()
    {
        $criteria = new CDbCriteria();

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && Yii::app()->user->model->company->type == Company::COMPANY_TYPE) {
            $this->is_closed = 1;
            $this->showCompany = 1;
        }

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->company_id = Yii::app()->user->model->company_id;
        }

        if ($this->showCompany) {
            $criteria->compare('card.company_id', $this->company_id);
        } else {
            $criteria->compare('t.company_id', $this->company_id);
        }

        $criteria->with = array('company', 'card', 'type', 'mark', 'card.cardCompany');
        $criteria->compare('card.company_id', $this->cardCompany);
        $criteria->compare('company.type', $this->companyType);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.card_id', $this->card_id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        switch ($this->period) {
            case 1:
                if($this->month) {
                    $criteria->compare('date_format(service_date, "%Y-%m")', $this->month);
                }
                break;
            case 2:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-3 month", time())), date('Y-m-d', time()));
                break;
            case 3:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-6 month", time())), date('Y-m-d', time()));
                break;
            case 4:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-12 month", time())), date('Y-m-d', time()));
                break;
            default:
        }

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'pagination' => false,
        ));

        $this->setDbCriteria(new CDbCriteria());

        return $provider;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function cars()
    {
        $criteria = new CDbCriteria();

        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->showCompany = 1;
            $this->is_closed = 1;
        }

        $criteria->with = array('card.cardCompany');
        $criteria->compare('number', $this->number);
        switch ($this->period) {
            case 1:
                if($this->month) {
                    $criteria->compare('date_format(service_date, "%Y-%m")', $this->month);
                }
                break;
            case 2:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-3 month", time())), date('Y-m-d', time()));
                break;
            case 3:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-6 month", time())), date('Y-m-d', time()));
                break;
            case 4:
                $criteria->addBetweenCondition('service_date', date('Y-m-d', strtotime("-12 month", time())), date('Y-m-d', time()));
                break;
            default:
        }

        $sort = new CSort;
        $sort->defaultOrder = 'service_date';
        $sort->applyOrder($criteria);
        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'pagination' => false,
        ));

        $this->setDbCriteria(new CDbCriteria());

        return $provider;
    }

    public function byDays()
    {
        $criteria = $this->getDbCriteria();

        $criteria->group = 'day';
        $criteria->select = [
            'COUNT(t.id) as amount',
            'date_format(service_date, "%Y-%m-%d") as day',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $sort = new CSort;
        $sort->defaultOrder = 'service_date';
        $sort->applyOrder($criteria);
        $sort->attributes = [
            'income',
            'expense',
            'profit',
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function byMonths()
    {
        $criteria = $this->getDbCriteria();

        $criteria->group = 'month';
        $criteria->select = [
            'COUNT(t.id) as amount',
            'date_format(service_date, "%Y-%m") as month',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $sort = new CSort;
        $sort->defaultOrder = 'service_date';
        $sort->applyOrder($criteria);
        $sort->attributes = [
            'income',
            'expense',
            'profit',
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function byCompanies()
    {
        $criteria = $this->getDbCriteria();

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $criteria->group = 't.company_id';
        }
        $criteria->select = [
            'COUNT(t.id) as amount',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit',
        ];

        $sort = new CSort;
        $sort->defaultOrder = 'profit DESC';
        $sort->applyOrder($criteria);
        $sort->attributes = [
            'income',
            'expense',
            'profit',
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'check' => 'Номер чека',
            'check_image' => 'Чек',
            'screen' => 'Загрузка чека',
            'type_id' => 'Тип ТС',
            'company_id' => 'Сервис',
            'card_id' => 'Карта',
            'number' => 'Госномер',
            'mark_id' => 'Марка',
            'service_date' => 'Дата',
            'service' => 'Услуга',
            'company_service' => 'Услуга',
            'month' => 'Месяц',
            'is_closed' => 'Закрыта',
            'profit' => 'Итого',
            'income' => 'Сумма',
            'expense' => 'Сумма',
            'day' => 'День',
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
            $total += isset($row->expense) ? $row->expense : 0;
        }

        return $total;
    }

    public function totalIncome()
    {
        $total = 0;
        foreach ($this->search()->getData() as $row) {
            $total += isset($row->income) ? $row->income : 0;
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
