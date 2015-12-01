<?php
/**
 * This is the model class for table "{{act_scope}}".
 *
 * The followings are the available columns in table '{{act_scope}}':
 * @property int $id
 * @property int $act_id
 * @property string $description
 * @property int $sum
 * @property int $amount
 */
class ActScope extends CActiveRecord
{
    public $oldSum;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ActScope the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{act_scope}}';
    }

    public function rules()
    {
        return array(
            array('sum, description, act_id, amount', 'required'),
            array('sum, description, act_id, amount', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'act' => array(self::BELONGS_TO, 'Act', 'act_id'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('act_id', $this->act_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('sum', $this->sum);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'act_id' => 'Акт',
            'description' => 'Вид работ',
            'sum' => 'Сумма',
            'amount' => 'Количество',
        );
    }
}
