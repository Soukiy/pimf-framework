<?php
/**
 * Pimf_Pdo
 *
 * PHP Version 5
 *
 * A comprehensive collection of PHP utility classes and functions
 * that developers find themselves using regularly when writing web applications.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

/**
 * Connection management to PostgreSQL
 *
 * @package Pimf_Pdo
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
class Pimf_Pdo_Postgre extends Pimf_Pdo_Connector
{
  protected $options = array(
    PDO::ATTR_CASE              => PDO::CASE_LOWER,
    PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
    PDO::ATTR_STRINGIFY_FETCHES => false,
  );

  /**
   * @param array $config
   * @return Pimf_Pdo
   */
  public function connect(array $config)
  {
    $dsn = "pgsql:host={$config['host']};dbname={$config['database']}";

    // The developer has the freedom of specifying a port for the PostgresSQL
    // database or the default port (5432) will be used by PDO to create the
    // connection to the database for the developer.
    if (isset($config['port'])) {
      $dsn .= ";port={$config['port']}";
    }

    $connection = new Pimf_Pdo($dsn, $config['username'], $config['password'], $this->options($config));

    // If a character set has been specified, we'll execute a query against
    // the database to set the correct character set. By default, this is
    // set to UTF-8 which should be fine for most scenarios.
    if (isset($config['charset'])) {
      $connection->prepare("SET NAMES '{$config['charset']}'")->execute();
    }

    // If a schema has been specified, we'll execute a query against
    // the database to set the search path.
    if (isset($config['schema'])) {
      $connection->prepare("SET search_path TO '{$config['schema']}'")->execute();
    }

    return $connection;
  }
}