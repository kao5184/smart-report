<?php

$app->get('/', function () {
    return view('index');
});

$app->group(['prefix' => 'reporter', 'namespace' => 'Reporter'], function () use ($app) {

    $app->group(['prefix' => 'config'], function () use ($app) {

        $app->post('/', 'ConfigurationController@configSQL');

        $app->get('/{id:\d+}', 'ConfigurationController@config');

        $app->get('/', 'ConfigurationController@configs');

        $app->delete('/{id:\d+}', 'ConfigurationController@deleteSQL');

        $app->put('/{id:\d+}', 'ConfigurationController@updateSQL');

        $app->post('/{drId:\d+}/map', 'ConfigurationController@configMapping');

        $app->put('/map/{id:\d+}', 'ConfigurationController@updateMapping');

        $app->delete('/map/{id:\d+}', 'ConfigurationController@deleteMapping');
    });

    $app->group(['prefix' => 'render'], function () use ($app) {

        // $app->get('/{key:[a-z\-]+}', 'RenderController@singleRender');

        $app->get('/', 'RenderController@dataRender');

        $app->get('/{id:\d+}', 'RenderController@singleRender');
    });

    $app->get('/', 'ReporterController@all');

    $app->get('/export', 'ReporterController@export');

    $app->delete('/{id:\d+}', 'ReporterController@delete');

    $app->put('/{id:\d+}', 'ReporterController@update');

    $app->post('/', 'ReporterController@create');

    $app->get('/{id:\d+}', 'ReporterController@allPage');

    $app->group(['prefix' => 'page'], function () use ($app) {

        $app->get('/', 'ReporterController@queryPage');

        $app->delete('/{id:\d+}', 'ReporterController@deletePage');

        $app->put('/{id:\d+}', 'ReporterController@updatePage');

        $app->post('/', 'ReporterController@createPage');
    });

    $app->group(['prefix' => 'source'], function () use ($app) {

        $app->get('/', 'ReporterController@allParameter');

        $app->get('/keys', 'ReporterController@parameters');

        $app->delete('/{id:\d+}', 'ReporterController@deleteParameter');

        $app->put('/{id:\d+}', 'ReporterController@updateParameter');

        $app->post('/', 'ReporterController@createParameter');
    });
});
