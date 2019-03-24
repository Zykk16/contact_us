<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id ID Департамента
 * @property string $division Подразделение
 * @property string $specialist ФИО
 * @property string $position Должность
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['division', 'specialist', 'position'], 'required'],
            ['id', 'safe'],
            [['division', 'specialist', 'position'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'division' => 'Подразделение',
            'specialist' => 'ФИО',
            'position' => 'Должность',
        ];
    }
}
