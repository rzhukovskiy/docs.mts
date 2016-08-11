<?php
/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property int $active
 * @property string $create_date
 */
class User extends CActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const PARTNER_ROLE = 'partner';
    const CLIENT_ROLE  = 'client';
    const ADMIN_ROLE   = 'admin';
    const GUEST_ROLE   = 'guest';

    public $oldpassword;
    public $companyType;

    public $userStatus = array(
        'Неактивен',
        'Активен',
        'Заблокирован'
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{user}}';
    }

    public function rules()
    {
        return array(
            array('password, email, company_id', 'required'),
            array('name, email, companyType', 'length', 'max' => 255),
            array('password, oldpassword', 'length', 'max' => 32),
            array('salt', 'length', 'max' => 5),
            array('active', 'length', 'max' => 1),
            array('email', 'unique'),
            array('id, name, password, salt, email, active, create_date, company_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
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
        $criteria->with = 'company';
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('active', $this->active);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('company.type', $this->companyType);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => "Имя",
            'password' => 'Пароль',
            'email' => 'Логин',
            'oldpassword' => 'Пароль',
            'company_id' => 'Компания',
            'active' => 'Active',
            'companyType' => 'Тип',
            'create_date' => 'Create Date',
        );
    }

    public function beforeSave()
    {
        if (isset($this->company) && $this->company->type == Company::COMPANY_TYPE) {
            $this->role = self::CLIENT_ROLE;
        }

        if ($this->isNewRecord || $this->password) {
            $salt = self::randomSalt();
            $this->password = self::hashPassword($this->password, $salt);
            $this->salt = $salt;
        }
        return true;
    }


    public function hashPassword($password, $salt)
    {
        return md5($salt . $password);
    }

    public function randomSalt($length = 5)
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime() * 1000000);
        $i = 1;
        $salt = '';

        while ($i <= $length) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $salt .= $tmp;
            $i++;
        }
        return $salt;
    }
}