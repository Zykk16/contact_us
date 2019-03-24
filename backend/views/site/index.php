<?php

/* @var $this yii\web\View */

$this->title = 'Админ-панель';

use yii\helpers\Url; ?>

<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Галерея</h2>
                <p>Просмотр/Загрузка/Удаление картинок для слайдера.</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute('slider/index') ?>">Перейти</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Просмотр заявок "Связаться с нами"</h2>
                <p>Отображение с базы заявок</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute('contactus/index') ?>">Перейти</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Департаменты</h2>
                <p>Хранение данных ФИО, должности, подразделения</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute('department/index') ?>">Перейти</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Прочие настройки</h2>
                <p>Описание, лого, можно еще в конфиги что-нибудь добавить.</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute('settings/index') ?>">Перейти</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Пользователи</h2>

                <p>Управление пользователями.</p>
                <ul class="list-inline">
                    <li><a class="btn btn-default" href="<?= Url::toRoute('user-management/user/index') ?>">Юзеры</a>
                    </li>
                    <li><a class="btn btn-default" href="<?= Url::toRoute('user-management/role/index') ?>">Роли</a>
                    </li>
                    <li><a class="btn btn-default" href="<?= Url::toRoute('user-management/permission/index') ?>">Права
                            доступа</a></li>
                    <li><a class="btn btn-default" href="<?= Url::toRoute('user-management/auth-item-group/index') ?>">Группы
                            разрешений</a></li>
                    <li><a class="btn btn-default" href="<?= Url::toRoute('user-management/user-visit-log/index') ?>">Логи</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>
