<?php
/**
 * This is the model class for table "{{act}}".
 *
 * The followings are the available columns in table '{{act}}':
 * @property int $id
 * @property int $partner_id
 * @property int $client_id
 * @property int $type_id
 * @property int $card_id
 * @property string $number
 * @property int $mark_id
 * @property string $create_date
 * @property string $service_date
 * @property int $is_closed
 * @property int $is_fixed
 * @property int $partner_service
 * @property int $client_service
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
 * @property string $from_date
 * @property string $to_date
 */
class Act extends CActiveRecord
{
    public $from_date;
    public $to_date;
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
        3 => 'сервис',
        4 => 'шиномонатж',
    );
    public static $fullList = array(
        'снаружи',
        'внутри',
        'снаружи+внутри',
        'сервис',
        'шиномонтаж',
    );
    public static $shortList = array(
        'мойка снаружи',
        'мойка внутри',
        'мойка снаружи+внутри',
        'сервис',
        'шиномонтаж',
    );

    public $month;
    public $day;

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
            array('is_fixed, from_date, to_date, period, month, day, check, old_expense, old_income, month, partner_id, client_id, partner_service, client_service, service_date, profit, income, expense, check_image', 'safe'),
            array('company_id, id, number, mark_id, type_id, card_id, service_date', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'partner' => array(self::BELONGS_TO, 'Company', 'partner_id'),
            'client' => array(self::BELONGS_TO, 'Company', 'client_id'),
            'card' => array(self::BELONGS_TO, 'Card', 'card_id'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'mark' => array(self::BELONGS_TO, 'Mark', 'mark_id'),
            'scope' => array(self::HAS_MANY, 'ActScope', 'act_id'),
            'car' => array(self::BELONGS_TO, 'Car', array('number' => 'number')),
        );
    }

    public function beforeSave()
    {
        //запрет на редактирование
        //для неадминов и не партнеров
        if(!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && !Yii::app()->user->checkAccess(User::PARTNER_ROLE)) {
            return false;
        }

        //для чужих актов для партнеров
        if (Yii::app()->user->model->role == User::PARTNER_ROLE && $this->partner_id != Yii::app()->user->model->company_id) {
            return false;
        }

        //для партнеров закрытых актов
        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $this->is_closed) {
            return false;
        }

        //номер в верхний регистр
        $this->number = mb_strtoupper(preg_replace('/\s+/', '', $this->number), 'UTF-8');

        //подставляем тип и марку из машины, если нашли по номеру
        $car = Car::model()->find('number = :number', array(':number' => $this->number));
        if ($car) {
            $this->mark_id = $car->mark_id;
            $this->type_id = $car->type_id;
        }

        if ($this->partner->type == Company::SERVICE_TYPE) {
            $this->client_service = $this->partner_service = 3;
        }
        if ($this->partner->type == Company::TIRES_TYPE) {
            $this->client_service = $this->partner_service = 4;
        }

        if ($this->isNewRecord) {
            $this->client_service = $this->partner_service;
        }

        $this->client_id = $this->card->company_id;

        if (($this->isNewRecord && !$this->income)
            || (!$this->is_closed && $this->partner->type == Company::CARWASH_TYPE && $this->old_income == $this->income)
        ) {
            $washPrice = Price::model()->find('company_id = :company_id AND type_id = :type_id',
                array(
                    ':company_id' => $this->client_id,
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

                $this->income = $servicePrice[$this->client_service];
            }
        }

        if (($this->isNewRecord && !$this->expense)
            || (!$this->is_closed && $this->partner->type == Company::CARWASH_TYPE && $this->old_expense == $this->expense)
        ) {
            $washPrice = Price::model()->find('company_id = :company_id AND type_id = :type_id',
                array(
                    ':company_id' => $this->partner_id,
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

                $this->expense = $servicePrice[$this->partner_service];
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

        //клиентам всегда показываем только закрытые акты
        if (Yii::app()->user->model->role == User::CLIENT_ROLE) {
            $this->is_closed = 1;
            $this->showCompany = 1;
        }

        //на всякий случай для партнеров и клиентов показываем только их акты
        if (Yii::app()->user->model->role == User::PARTNER_ROLE) {
            $this->partner_id = Yii::app()->user->model->company_id;
        }
        if (Yii::app()->user->model->role == User::CLIENT_ROLE) {
            $this->client_id = Yii::app()->user->model->company_id;
        }

        $sort = new CSort;

        if (!$this->getDbCriteria()->order) {
            if ($this->number) {
                $sort->defaultOrder = 't.service_date';
            } else {
                if ($this->showCompany) {
                    $sort->defaultOrder = 'client_id, t.service_date, profit DESC';
                } else {
                    $sort->defaultOrder = 'partner_id, t.service_date, profit DESC';
                }
            }
        }

        $criteria->with = array('partner', 'client', 'card', 'type', 'mark');
        $criteria->compare('partner.type', $this->companyType);
        $criteria->compare('partner_id', $this->partner_id);
        $criteria->compare('client_id', $this->client_id);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.card_id', $this->card_id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        if ($this->is_closed) {
            $criteria->compare('t.is_closed', $this->is_closed);
        }
        if (isset($this->from_date)) {
            $criteria->addCondition('service_date >= "' . $this->from_date . '"');
            $criteria->addCondition('service_date < "' . $this->to_date . '"');
        }
        if (isset($this->month)) {
            $criteria->compare('date_format(t.service_date, "%Y-%m")', $this->month);
        }
        if (isset($this->day)) {
            $criteria->compare('date_format(t.service_date, "%Y-%m-%d")', $this->day);
        }
        $criteria->compare('date_format(t.create_date, "%Y-%m-%d")', $this->create_date);
        $sort->applyOrder($criteria);

        $this->getDbCriteria()->mergeWith($criteria);

        $provider = new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->getDbCriteria(),
            'pagination' => false,
        ));

        $this->setDbCriteria(new CDbCriteria());

        return $provider;
    }

    public function withErrors()
    {
        $criteria = new CDbCriteria();

        $criteria->with = ['car'];
        $criteria->order = 'service_date DESC';

        $criteria->compare('income', 0);
        $criteria->compare('expense', 0, false, 'OR');
        $criteria->addCondition('`check` is NULL AND partner_service IN(0,1,2)', 'OR');
        $criteria->addCondition('card.company_id != car.company_id', 'OR');
        $criteria->addCondition('car.company_id is NULL', 'OR');

        $this->getDbCriteria()->mergeWith($criteria);

        $criteria = new CDbCriteria();
        $criteria->compare('is_fixed', 0);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    public function byDays()
    {
        $criteria = $this->getDbCriteria();

        $criteria->group = 'day';
        $criteria->select = [
            'date_format(service_date, "%Y-%m-%d") as day',
            'COUNT(t.id) as amount',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function byMonths()
    {
        $criteria = $this->getDbCriteria();

        $criteria->group = 'month';
        $criteria->select = [
            'date_format(service_date, "%Y-%m") as month',
            'COUNT(t.id) as amount',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function byCompanies()
    {
        $criteria = $this->getDbCriteria();

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            if ($this->showCompany) {
                $criteria->group = 'client_id';
            } else {
                $criteria->group = 'partner_id';
            }
        }

        $criteria->order = 'profit DESC';

        $criteria->select = [
            'partner_id',
            'client_id',
            'COUNT(t.id) as amount',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function byTypes()
    {
        $criteria = $this->getDbCriteria();

        $criteria->group = 'partnerType';

        $criteria->select = [
            'partner.type as partnerType',
            'COUNT(t.id) as amount',
            'SUM(expense) as expense',
            'SUM(income) as income',
            'SUM(profit) as profit'
        ];

        $criteria->order = 'profit DESC';

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
            'partner_id' => 'Партнер',
            'client_id' => 'Клиент',
            'card_id' => 'Карта',
            'number' => 'Госномер',
            'mark_id' => 'Марка',
            'service_date' => 'Дата',
            'partner_service' => 'Услуга',
            'client_service' => 'Услуга',
            'month' => 'Месяц',
            'is_closed' => 'Закрыта',
            'profit' => 'Итого',
            'income' => 'Сумма',
            'expense' => 'Сумма',
            'day' => 'День',
        );
    }

    public function hasError($error)
    {
        $hasError = false;
        switch ($error) {
            case 'expense':
                $hasError = !$this->expense;
                break;
            case 'income':
                $hasError = !$this->income;
                break;
            case 'check':
                $hasError = !$this->check && $this->partner->type == Company::CARWASH_TYPE;
                break;
            case 'card':
                $hasError = isset($this->car->company_id) && $this->card->company_id != $this->car->company_id;
                break;
            case 'car':
                $hasError = !isset($this->car->company_id);
                break;
        }

        return !$this->is_fixed && $hasError;
    }

    public function getFormattedField($field)
    {
        return number_format($this->$field, 0, ".", " ");
    }

    /**
     * @param $provider CActiveDataProvider
     * @param $field string
     * @return string
     */
    public function totalField($provider, $field)
    {
        $total = 0;

        foreach ($provider->getData() as $row) {
            $total += $row->$field;
        }

        return number_format($total, 0, ".", " ");
    }
}
