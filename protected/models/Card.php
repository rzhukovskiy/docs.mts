<?php
/**
 * This is the model class for table "{{card}}".
 *
 * The followings are the available columns in table '{{card}}':
 * @property int $id
 * @property int $company_id
 * @property int $number
 * @property int $active
 * @property string $create_date
 */
class Card extends CActiveRecord
{

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $cardStatus = array(
        'Активен',
        'Заблокирована'
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Card the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{card}}';
    }

    public function rules()
    {
        return array(
            array('company_id', 'required'),
            array('number', 'unique'),
            array('id, number, type, active, create_date', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'cardCompany' => array(self::BELONGS_TO, 'Company', 'company_id'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('active', $this->active);

        $sort = new CSort;
        $sort->defaultOrder = 'company_id, number';
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
            'number' => 'Номер',
            'company_id' => 'Компания',
            'active' => 'Active',
            'create_date' => 'Create Date',
        );
    }

    public function beforeSave()
    {
        if ($this->isNewRecord && !$this->number) {
            $salt = self::randomSalt();
            $this->number = $salt . str_pad($this->company_id, 4, "0", STR_PAD_LEFT);
        } else {
            $numPointList = explode('-', $this->number);
            if(count($numPointList) > 1) {
                for ($num = intval($numPointList[0]); $num < intval($numPointList[1]); $num++) {
                    $card = clone $this;
                    $card->number = $num;
                    $card->save();
                }
                $this->number = intval($numPointList[1]);
            }
        }
        return true;
    }

    public function randomSalt()
    {
        return str_pad(rand(1, 9999), 4, "0", STR_PAD_RIGHT);
    }
}