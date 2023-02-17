<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

/** @var app\models\Doctor $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиники', 'url' => Url::to("clinics")];
$this->params['breadcrumbs'][] = $this->title;

$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css']);
$this->registerJsFile('https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js', ['position' => $this::POS_HEAD]);
?>
<div class="site-doctor d-flex flex-column gap-2">
    <h1><?= $this->title ?></h1>


    <h1><?= $model->renderAvatar() ?></h1>

    <div class="card p-2 shadow-sm d-flex flex-column">
        <span>Адрес: <?= $model->address ?></span>
        <span>Телефон: <?= $model->phone ?></span>
    </div>

    <?php if ($model->geo) : ?>
        <?php
        list($lat, $lon) = array_map('trim', explode(",", $model->geo));
        ?>
        <style>
            #map {
                position: relative;
                width: 100%;
                height: 300px;
            }
        </style>
        <div id="map"></div>

        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoic2Rlc3lhNzQiLCJhIjoiY2wwb3I4c3RtMW5yejNqcHd2cXN4eHVpYSJ9.gHaZruoJYsyW2F4_BGzOdw';
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v12',
                center: [<?= $lon ?>, <?= $lat ?>],
                zoom: 15
            });

            // Create a default Marker and add it to the map.
            const marker1 = new mapboxgl.Marker()
                .setLngLat([<?= $lon ?>, <?= $lat ?>])
                .addTo(map);
        </script>
    <?php endif; ?>

    <div class="d-flex flex-column gap-2">
        <h2>Врачи этой клиники <spaт class="text-muted">(<?php
                                                            $count = count($model->doctors) % 100;
                                                            $suffix = 'врачей';
                                                            if ($count % 10 == 1 && $count !== 11) $suffix = 'врач';
                                                            else if ($count % 10 < 5 && $count % 10 > 0 && ($count < 10 || $count > 20)) $suffix = "врача";

                                                            echo $count . ' ' . $suffix
                                                            ?>)</span></h2>
        <div class="d-flex justify-content-center col flex-wrap gap-2">
            <? foreach ($model->doctors as $model) : ?>
                <a href="<?= Url::to(["doctor", 'id' => $model->id]) ?>" class="card w-100 shadow-sm" style="padding-left: 16px; text-decoration: none; color: inherit;">
                    <div class="d-flex row flex-nowrap align-items-center ">
                        <img src="/web/doctor.svg" style="width: 64px; height: 64px">
                        <div><?= $model->user->full_name ?></div>
                    </div>
                </a>
            <? endforeach ?>
        </div>
    </div>
</div>