<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\mysql\QueryBuilder;
use yii\db\Query;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $department;
    public $phone;
    public $text;
    public $body;
//    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required', 'message' => 'Полностью заполните телефон'],
            ['phone', 'string'],
            [['department', 'phone', 'text'], 'safe']
            // verifyCode needs to be entered correctly
//            ['verifyCode', 'captcha'],
        ];
    }

    public function getDep4Cont()
    {
        $query = (new \yii\db\Query())
            ->select(['*'])
            ->from('department')->all();

        foreach ($query as $item) {
            $res[$item['id']] = $item['division'] . '/' . $item['specialist'] . '/' . $item['position'];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department' => 'Департамент',
            'phone' => 'Телефон',
            'text' => 'Текст',
            'verifyCode' => 'Verification Code',
        ];
    }

    public function saveContact()
    {
        Yii::$app->db->createCommand()->insert('contactus', [
            'id_dep' => $this->department,
            'phone' => $this->phone,
            'text' => $this->text,
            'date_create' => date("Y-m-d"),
        ])->execute();
    }

    public function getContent4Google()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('department d')
            ->join('join', 'contactus c', 'd.id = c.id_dep')
            ->orderBy(['c.id' => SORT_DESC])->limit(1)
            ->one();
        return $query;
    }
}
