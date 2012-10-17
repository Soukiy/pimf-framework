<?php
require_once 'autoloader.php';

// load the app configuration.
require_once 'config.php';

if ($config['environment'] == 'testing') {

  error_reporting(E_ALL | E_STRICT);
  ini_set('display_errors', 'on');

  $dbConf = $config['testing']['db'];

} else {

  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 'off');

  $dbConf = $config['production']['db'];
}

// start checking the dependencies.
$problems = array();

// check php-version.
if (version_compare(PHP_VERSION, $config['bootstrap']['expected']['php_version']) == -1) {
  $problems[] = 'You have PHP '.PHP_VERSION
               .' and you need PHP '.$config['bootstrap']['expected']['php_version'].' or higher!';
}

// check expected extensions.
foreach ($config['bootstrap']['expected']['extensions'] as $extension) {
  if (!extension_loaded($extension)) {
    $problems[] = 'No ' . $extension . ' extension loaded!';
  }
}

// configure necessary things for the application.
$registry = new Pimf_Registry();

try {

  $db = Pimf_Pdo_Factory::get($dbConf);

  $registry->em     = new Pimf_EntityManager($db, $config['app']['name']);

  $registry->logger = new Pimf_Logger($config['bootstrap']['local_temp_directory']);
  $registry->logger->init();

  $registry->env  = new Pimf_Environment($_SERVER);
  $registry->conf = $config;

} catch (Exception $e) {
  $problems[] = $e->getMessage();
}

if (!empty($problems)) {
  die(print_r($problems, true));
}

unset($dbDsn, $dbUser, $dbPwd, $db, $extension, $problems, $config);
