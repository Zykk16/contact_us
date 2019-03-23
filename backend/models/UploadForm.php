<?php
/**
 * Created by PhpStorm.
 * User: NIKITALIAN
 * Date: 22.03.2019
 * Time: 3:11
 */

namespace backend\models;


use Yii;
use yii\base\Model;

class UploadForm extends Model
{
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('img/uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        Yii::$app->db->createCommand()->insert('slider', [
            'name' => $this->imageFile->baseName,
            'format' => $this->imageFile->extension,
            'path' => 'uploads',
            'flag' => 1
        ])->execute();
    }
}