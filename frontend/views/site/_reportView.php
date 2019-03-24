<?php

/**
 * Created by PhpStorm.
 * User: NIKITALIAN
 * Date: 22.03.2019
 * Time: 23:34
 */

/* @var $this \yii\web\View */
/** @var array $content */
$date = $content[0]['date_create'];
$date = explode('-', $date);

?>
<p style="text-align: right;">Генеральному директору</p>
<p style="text-align: right;"><span style="text-decoration: underline;"><?= $content[0]['specialist'] ?></span></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p style="text-align: center;"><strong>ЗАЯВЛЕНИЕ</strong></p>
<p>&nbsp;</p>
<p style="text-align: center;">Прошу перевести меня в&nbsp;<span
            style="text-decoration: underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?= $content[0]['division'] ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>
</p>
<p style="text-align: center;">на должность <span style="text-decoration: underline;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?= $content[0]['position'] ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</span>
</p>
<p>&nbsp;</p>
<p style="text-align: right;">&nbsp;&laquo;<?= $date[2] ?>&raquo; <?= $date[1] ?> <?= $date[0] ?> г.</p>
<p style="text-align: right;">Телефон для связи: <?= $content[0]['phone'] ?></p>
<p>&nbsp;</p>
<p style="text-align: center;">О себе:</p>
<br>
<?= $content[0]['text'] ?>



