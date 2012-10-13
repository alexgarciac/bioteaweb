<?php

/**
 * Biotea API
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 * @license Florida State University - All Rights Reserved
 */

/**
 * @file Bootstrap File
 */

// ------------------------------------------------------------------

/*
 * Uses
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Bioteawebapi\Services\SolrClient;
use Bioteawebapi\Services\MySQLClient;
use Silex\Application;

// ------------------------------------------------------------------

/*
 * Bootstrap
 */

//BASEPATH
define('BASEPATH', realpath(__DIR__ . '/../'));

//Autoloader
require(BASEPATH . '/vendor/autoload.php');

//Setup Classloader
$loader = new UniversalClassLoader();
$loader->registerNamespace('Bioteawebapi', BASEPATH . '/src');
$loader->registerNamespace('TaskTracker', BASEPATH . '/src');

$loader->registerPrefix('EasyRdf', BASEPATH . '/vendor/njh/easyrdf/lib');
$loader->register();

//EasyRDF Lunacy
set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . '/vendor/njh/easyrdf/lib');

//Solarium 

// ------------------------------------------------------------------

/*
 * Load Silex
 */

//Silex
$app = new Application();

/*
 * Common Libraries
 */

//Configuration
$app['config'] = new Configula\Config(BASEPATH . '/config/');

//Monolog Logger (more complex than the default logger)
$app['monolog'] = $app->share(function($app) {
    
    $logger = new Monolog\Logger('biotealog');

    //Register handlers
    if ($app['config']->logs['file']) {
        $level = constant('Monolog\Logger::' . strtoupper($app['config']->logs['file']['level']));
        $path  = $app['config']->logs['file']['path'] . DIRECTORY_SEPARATOR . 'biotea.log';
        $logger->pushHandler(new Monolog\Handler\StreamHandler($path, $level));
    }

    if ($app['config']->logs['email']) {
        $level   = constant('Monolog\Logger::' . strtoupper($app['config']->logs['email']['level']));
        $from    = 'bioteaapi@' . (gethostname() ?: 'bioteaserver');
        $to      = $app['config']->logs['email']['to'];
        $subject = "Biotea Message from " . (gethostname() ?: 'bioteaserver');
        $logger->pushHandler(new Monolog\Handler\NativeMailerHandler($to, $subject, $from, $level));
    }

    return $logger;
});

//SOLR Client for Docs
$app['solr_config'] = array('adapteroptions' => $app['config']->solr['terms']);
$app['solr_client'] = $app->share(function($app) {
    return new SolrClient(new Solarium_Client($app['solr_config']));
});

//MySQL DBAL Connection ($app['db'])
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => 'pdo_mysql',
        'host'      => $app['config']->mysql['host'],
        'dbname'    => $app['config']->mysql['name'],
        'user'      => $app['config']->mysql['user'],
        'password'  => $app['config']->mysql['pass'],
    )
));

//MySQL ORM ($app['db.orm'])
$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.proxies_dir' => ($app['config']->mysql['cachedir'] == 'AUTO')
        ? sys_get_temp_dir()
        : $app['config']->mysql['cachedir'],
    'db.orm.proxies_namespace'     => 'DoctrineProxy',
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation',
        'path'      => BASEPATH .'/src/Bioteawebapi/Entities',
        'namespace' => 'Bioteawebapi\Entities',
    ))
));

//MySQL Client for Docs -- @TODO: Remove when ORM is working!
$app['mysql_client'] = $app->share(function($app) { 
    return new MySQLClient($app['db']);
});

//Doc Builder
$app['builder'] = $app->share(function($app) {
    return new Bioteawebapi\Services\IndexBuilder($app['config']->vocabularies);
});

//Doc Indexer
$app['indexer'] = $app->share(function($app) {
    return new Bioteawebapi\Services\Indexer($app['builder'], $app['db.orm.em']);
});

// ------------------------------------------------------------------

/*
 * Return it
 */
return $app;

/* EOF: bootstrap.php */