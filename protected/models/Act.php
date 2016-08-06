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
 * @property string $extra_number
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
 * @property string $month
 * @property string $day
 * @property int $period
 * @property int $amount
 * @property string $from_date
 * @property string $to_date
 * @property string $clientName
 * @property string $service
 * @property string $cardNumber
 * @property string $sign
 */
class Act extends CActiveRecord
{
    public $clientName;

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
    public $cardNumber;
    public $fixMode = false;

    public static $periodList = array('все время', 'месяц', 'квартал', 'полгода', 'год');
    public static $carwashList = array(
        0 => 'снаружи',
        1 => 'внутри',
        2 => 'снаружи+внутри',
        6 => 'снаружи+двигатель',
        7 => 'внутри+двигатель',
        8 => 'снаружи+внутри+двигатель',
    );
    public static $serviceList = array(
        3 => 'сервис',
        4 => 'шиномонатж',
        5 => 'дезинфекция',
    );
    public static $fullList = array(
        'снаружи',
        'внутри',
        'снаружи+внутри',
        'сервис',
        'шиномонтаж',
        'дезинфекция',
        'снаружи+внутри',
        'снаружи+двигатель',
        'внутри+двигатель',
        'снаружи+внутри+двигатель',
    );
    public static $shortList = array(
        'мойка снаружи',
        'мойка внутри',
        'мойка снаружи+внутри',
        'сервис',
        'шиномонтаж',
        'дезинфекция',
        'снаружи+внутри',
        'снаружи+двигатель',
        'внутри+двигатель',
        'снаружи+внутри+двигатель',
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

    function __set($name, $value)
    {
        if ($name == 'month') {
            list($month, $year) = explode('-', $value);
            if(strlen($month) == 2) {
                $this->month = $year . '-' . $month;
            } else {
                $this->month = $value;
            }
        } elseif ($name == 'service_date') {
            if (count(explode('-', $value)) > 2) {
                list($year, $month, $day) = explode('-', $value);
                if(strlen($year) == 2) {
                    $this->service_date = $day . '-' . $month . '-' . $year;
                } else {
                    $this->service_date = $value;
                }
            } else {
                $this->service_date = $value;
            }
        } else {
            parent::__set($name, $value);
        }
    }

    public function tableName()
    {
        return '{{act}}';
    }

    public function rules()
    {
        return array(
            array('number', 'required'),
            array('check', 'unique'),
            array('sign, type_id, mark_id, service, card_id, cardNumber, extra_number, is_fixed, from_date, to_date, period, month, day, check, old_expense, old_income, month, partner_id, client_id, partner_service, client_service, service_date, profit, income, expense, check_image', 'safe'),
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
            'truck' => array(self::BELONGS_TO, 'Car', array('extra_number' => 'number')),
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
        $this->number = mb_strtoupper(str_replace(' ', '', $this->number), 'UTF-8');

        //подставляем тип и марку из машины, если нашли по номеру
        $car = Car::model()->find('number = :number', array(':number' => $this->number));
        if ($car) {
            $this->mark_id = $car->mark_id;
            $this->type_id = $car->type_id;
        }

        //подставляем карту, теперь по номеру
        if ($this->cardNumber) {
            $card = Card::model()->find('number = :number', array(':number' => $this->cardNumber));
            if ($card) {
                $this->card_id = $card->id;
            } else {
                return false;
            }
        }

        switch ($this->service) {
            case Company::SERVICE_TYPE:
                $this->client_service = $this->partner_service = 3;
                break;
            case Company::TIRES_TYPE:
                $this->client_service = $this->partner_service = 4;
                break;
            case Company::DISINFECTION_TYPE:
                $this->client_service = $this->partner_service = 5;
                break;
        }

        if ($this->isNewRecord) {
            $this->client_service = $this->partner_service;
        }

        $this->client_id = isset($this->card) ? $this->card->company_id : $this->client_id;

        if (($this->isNewRecord && !$this->income)
            || (
                !$this->is_closed
                && ($this->service == Company::CARWASH_TYPE || $this->service == Company::DISINFECTION_TYPE)
                && $this->old_income == $this->income
            )
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
                    $washPrice->fullOutside,
                    $washPrice->fullInside,
                    $washPrice->fullOutside + $washPrice->fullInside,
                    0,
                    0,
                    $washPrice->disinfection,
                    $washPrice->fullOutside + $washPrice->engine,
                    $washPrice->fullInside + $washPrice->engine,
                    $washPrice->fullOutside + $washPrice->fullInside + $washPrice->engine,
                );

                $this->income = $servicePrice[$this->client_service];
            }
        }

        if (($this->isNewRecord && !$this->expense)
            || (
                !$this->is_closed
                && ($this->service == Company::CARWASH_TYPE || $this->service == Company::DISINFECTION_TYPE)
                && $this->old_expense == $this->expense
            )
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
                    $washPrice->fullOutside,
                    $washPrice->fullInside,
                    $washPrice->fullOutside + $washPrice->fullInside,
                    0,
                    0,
                    $washPrice->disinfection,
                    $washPrice->fullOutside + $washPrice->engine,
                    $washPrice->fullInside + $washPrice->engine,
                    $washPrice->fullOutside + $washPrice->fullInside + $washPrice->engine,
                );

                $this->expense = $servicePrice[$this->partner_service];
            }
        }

        if ($this->fixMode && $this->service == Company::TIRES_TYPE && !$this->is_closed) {
            if ($this->showCompany) {
                $total = 0;
                /** @var ActScope $scope */
                foreach ($this->scope as $scope) {
                    $tiresService = CompanyTiresService::model()->with('tiresService')->find('type_id = :type_id AND company_id = :company_id AND tiresService.description = :description', [
                        ':type_id' => $this->type_id,
                        ':company_id' => $this->client_id,
                        ':description' => $scope->description,
                    ]);
                    if ($tiresService) {
                        $scope->income = $tiresService->price;
                        $scope->save();
                    }
                    $total += $scope->income * $scope->amount;
                }
                $this->income = $total;
            } else {
                $total = 0;
                /** @var ActScope $scope */
                foreach ($this->scope as $scope) {
                    $tiresService = CompanyTiresService::model()->with('tiresService')->find('type_id = :type_id AND company_id = :company_id AND tiresService.description = :description', [
                        ':type_id' => $this->type_id,
                        ':company_id' => $this->partner_id,
                        ':description' => $scope->description,
                    ]);
                    if ($tiresService) {
                        $scope->expense = $tiresService->price;
                        $scope->save();
                    }
                    $total += $scope->expense * $scope->amount;
                }
                $this->expense = $total;
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
            $this->showCompany = 1;
        }

        //на всякий случай для партнеров и клиентов показываем только их акты
        if (Yii::app()->user->model->role == User::PARTNER_ROLE && $this->partner_id != Yii::app()->user->model->company_id) {
            $this->partner_id = Yii::app()->user->model->company_id;
        }
        if (Yii::app()->user->model->role == User::CLIENT_ROLE
            && $this->client_id != Yii::app()->user->model->company_id
            && !$this->hasChild($this->client_id)
        ) {
            $this->client_id = Yii::app()->user->model->company_id;
        }

        $sort = new CSort;

        if (!$this->getDbCriteria()->order) {
            if ($this->number) {
                $sort->defaultOrder = 't.service_date';
            } else {
                if ($this->showCompany) {
                    $sort->defaultOrder = 'clientParent.id, client_id, t.service_date, profit DESC';
                } else {
                    $sort->defaultOrder = 'partner_id, t.service_date, profit DESC';
                }
            }
        }

        $criteria->with = ['partner', 'client', 'client.parent' => ['alias'=>'clientParent'], 'card', 'type', 'mark'];
        $criteria->compare('partner_id', $this->partner_id);

        if ($this->client_id && count(Company::model()->findByPk($this->client_id)->children) > 0) {
            $criteria->addCondition("clientParent.id = $this->client_id OR client_id = $this->client_id");
        } else {
            $criteria->compare('client_id', $this->client_id);
        }
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.card_id', $this->card_id);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.mark_id', $this->mark_id);
        $criteria->compare('service', $this->companyType);
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

    public function without($service)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('service != "' . $service . '"');

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Выбор ошибочных актов
     *
     * @return $this
     */
    public function withErrors()
    {
        $criteria = new CDbCriteria();

        $criteria->with = ['car', 'truck'];
        $criteria->order = 'service_date DESC';

        $criteria->compare('income', 0);
        $criteria->compare('expense', 0, false, 'OR');
        $criteria->addCondition('`check` is NULL AND partner_service IN(0,1,2)', 'OR');
        $criteria->addCondition('`check` = "" AND partner_service IN(0,1,2)', 'OR');
        $criteria->addCondition('card.company_id is NULL', 'OR');
        $criteria->addCondition('card.company_id != car.company_id', 'OR');
        $criteria->addCondition('client.is_split = 1 AND truck.company_id is NULL', 'OR');
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

        $criteria->group = 'service';

        $criteria->select = [
            'service',
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
            'extra_number' => 'Госномер п/прицепа',
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
                $hasError = (!$this->check || !$this->check_image) && $this->service == Company::CARWASH_TYPE;
                break;
            case 'card':
                $hasError = !isset($this->card) || (isset($this->car->company_id) && $this->card->company_id != $this->car->company_id);
                break;
            case 'car':
                $hasError = !isset($this->car->company_id);
                break;
            case 'truck':
                $hasError = (isset($this->client) && $this->client->is_split && !$this->extra_number) ||
                    (isset($this->client) && $this->client->is_split && $this->extra_number && !Car::model()->find('number = :number', [':number' => $this->extra_number]));
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

    public function getClientName()
    {
        return $this->client->name;
    }

    public function hasChild($childId)
    {
        $child = Company::model()->findByPk($childId);
        return $child && $child->parent_id == Yii::app()->user->model->company_id;
    }

    public function disinfectCar(Car $car)
    {
        $this->partner_id = Yii::app()->user->model->company->id;
        $this->client_id = $car->company_id;
        $this->number = $car->number;
        $this->partner_service = 5;
        $this->service = Company::DISINFECTION_TYPE;

        $this->service_date = $this->month . '-01';

        $this->save();
    }

    /**
     * @param string $type
     * @return Act
     */
    public function getPartnersByType($type) {
        return Act::model()->findAll([
            'condition' => 'service = :service AND date_format(t.service_date, "%Y-%m") = :month',
            'params'    => [
                ':service' => $type,
                ':month'   => $this->month,
            ],
            'order'     => 'type DESC',
            'group'     => 'partner_id',
            'with'      => [
                'partner'
            ]
        ]);
    }

    /**
     * @param string $type
     * @return Act
     */
    public function getClientsByType($type) {
        return Act::model()->findAll([
            'condition' => 'service = :service AND date_format(t.service_date, "%Y-%m") = :month',
            'params'    => [
                ':service' => $type,
                ':month'   => $this->month,
            ],
            'order'     => 'type DESC',
            'group'     => 'client_id',
            'with'      => [
                'client'
            ]
        ]);
    }

    public function getMonthAsString()
    {
        list($year, $month) = explode('-', $this->month);
        return $month . '-' . $year;
    }
}
