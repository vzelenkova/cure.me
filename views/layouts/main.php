<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => '<img style="height: 48px" src="/web/logo.svg" alt="logo" />',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md fixed-top shadow']
        ]);

        $isGuest = Yii::$app->user->isGuest;
        $isDoctor = !$isGuest && Yii::$app->user->identity->doctor;

        $items = [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Клиники', 'url' => ['/site/clinics']],
        ];

        if ($isDoctor) {
            $items[] =  ['label' => 'Записи ко мне', 'url' => ['/site/doctor-appointments']];
        } else {
            $items[] =  ['label' => 'Записи', 'url' => ['/site/appointments']];
        }

        $items[] = '<div style="display: flex; flex-grow: 1;"></div>';

        if ($isGuest) {
            $items[] = ['label' => 'Вход', 'url' => ['/auth/login']];
            $items[] = ['label' => 'Регистрация', 'url' => ['/auth/register']];
        } else {
            if (Yii::$app->user->identity->isAdmin()) {
                $items[] = ['label' => 'Панель администратора', 'url' => ["/admin/"]];
            }

            // if ($isDoctor) {
            //     $items[] = ['label' => 'Панель администратора', 'url' => ["/admin/"]];
            // }

            if (Yii::$app->user->identity->isOwner()) {
                $items[] = ['label' => 'Панель владельца клиники', 'url' => ["/owner/"]];
            }

            $items[] = '<li class="nav-item">'
                . Html::beginForm(['/auth/logout'])
                . Html::submitButton(
                    'Выйти',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav', 'style' => 'width: 100%'],
            'items' => $items
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container mt-2">
            <?php if (!empty($this->params['breadcrumbs'])) : ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'homeLink' => [
                        'label' => 'Главная',
                        'url' => Yii::$app->homeUrl,
                    ],
                ]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; cure.me <?= date('Y') ?></div>
                <div class="col-md-2 text-center text-md-start">
                    <h5>Телефоны</h5>
                    <span>+7 (800) 123-45-67</span>
                </div>
                <div class="col-md-2 text-center text-md-start">
                    <h5>Почты</h5>
                    <span>contact@cure.me</span>
                </div>
                <div class="col-md-2 text-center text-md-start">
                    <h5>Социальные сети</h5>
                    <div class="d-flex flex-row">
                        <a href="https://vk.com/cure_me" alt="Сообщество в ВКонтакте" target="_blank" rel="noreferrer noopener">
                            <?= Html::img("@web/vk.svg", ['style' => 'width: 32px; height: 32px;']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>