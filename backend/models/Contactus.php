<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contactus".
 *
 * @property int $id ID
 * @property int $id_dep ID Департамента
 * @property string $phone Телефон
 * @property string $text Описание
 * @property string $date_create Дата создание заявки
 */
class Contactus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contactus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dep', 'phone', 'date_create'], 'required'],
            [['id_dep'], 'integer'],
            [['text'], 'string'],
            [['phone', 'date_create'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dep' => 'ID Департамента',
            'phone' => 'Телефон',
            'text' => 'Описание',
            'date_create' => 'Заявка создана',
        ];
    }
}
