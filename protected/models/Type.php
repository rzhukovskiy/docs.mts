<?php

/**
 * This is the model class for table "{{type}}".
 *
 * The followings are the available columns in table '{{type}}':
 * @property int $id
 * @property string $name
 * @property string $image
 */
class Type extends CActiveRecord
{
    public $screen;
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
        return '{{type}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name','required'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Тип ТС',
            'screen' => 'Загрузка изображения',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        $sort = new CSort;
        $sort->attributes = array('id', 'name');
        $sort->defaultOrder = 'id ASC';
        $sort->applyOrder($criteria);


        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }
}