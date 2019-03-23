<?php
/**
 * Created by PhpStorm.
 * User: NIKITALIAN
 * Date: 03.03.2019
 * Time: 21:44
 */

namespace frontend\widgets;

use yii\bootstrap\Html;
use yii\bootstrap\NavBar;


class CustomNavBar extends NavBar
{
    public function renderToggleButton()
    {
        $bar = Html::tag('span', '', ['class' => 'fa fa-bars']);
//        $screenReader = "<span class=\"sr-only\">{$this->screenReaderToggleText}</span>";

        return Html::button("\n{$bar}", [
            'class' => 'navbar-toggler',
            'data-toggle' => 'collapse',
            'data-target' => "#{$this->containerOptions['id']}",
        ]);
    }
}