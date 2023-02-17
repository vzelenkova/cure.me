<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
// use yii\bootstrap5\BootstrapAsset;

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

        $items = [
            ['label' => 'Специальности врачей', 'url' => ['/admin/specialty']],
            ['label' => 'Врачи', 'url' => ['/admin/doctor']],
            ['label' => 'Пользователи', 'url' => ['/admin/user']],
            ['label' => 'Клиники', 'url' => ['/admin/clinic']],
            ['label' => 'Владельцы клиник', 'url' => ['/admin/clinic-owner']],
            '<div style="display: flex; flex-grow: 1;"></div>',
        ];

        if (Yii::$app->user->isGuest) {
            $items[] = ['label' => 'Вход', 'url' => ['/auth/login']];
            $items[] = ['label' => 'Регистрация', 'url' => ['/auth/register']];
        } else {
            $items[] = ['label' => 'Личный кабинет', 'url' => [Yii::$app->user->identity->getHomeUrl()]];

            $items[] = '<li class="nav-item">'
                . Html::beginForm(['/auth/logout'])
                . Html::submitButton(
                    'Выйти',
                    ['class' => 'btn logout']
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
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])) : ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'homeLink' => [
                        'label' => 'Главная',
                        'url' => Yii::$app->homeUrl . 'admin',
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
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>