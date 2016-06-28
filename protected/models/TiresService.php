<?php

/**
 * This is the model class for table "{{tires_service}}".
 *
 * The followings are the available columns in table '{{tires_service}}':
 * @property int $id
 * @property int $pos
 * @property string $description
 * @property string $is_fixed
 */
class TiresService extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TiresService the static model class
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
        return '{{tires_service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('description','required'),
            array('is_fixed','safe'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'id'          => 'ID',
            'description' => 'Описание',
            'is_fixed'    => 'Фиксировано',
        );
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->pos = $this->id;
        }
        return true;
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
        $criteria->compare('description', $this->description, true);

        $sort = new CSort;
        $sort->defaultOrder = 'pos ASC';
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