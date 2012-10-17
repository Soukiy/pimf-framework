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
 * Connection management to MySQL.
 *
 * @package Pimf_Pdo
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
class Pimf_Pdo_Mysql extends Pimf_Pdo_Connector
{
  /**
   * @param array $config
   * @return Pimf_Pdo
   */
  public function connect(array $config)
  {
    $dsn = "mysql:host={$config['host']};dbname={$config['database']}";

    // The developer has the freedom of specifying a port for the MySQL database
    // or the default port (3306) will be used to make the connection by PDO.
    // The Unix socket may also be specified if necessary.
    if (isset($config['port'])) {
      $dsn .= ";port={$config['port']}";
    }

    // The UNIX socket option allows the developer to indicate that the MySQL
    // instance must be connected to via a given socket. We'll just append
    // it to the DSN connection string if it is present.
    if (isset($config['unix_socket'])) {
      $dsn .= ";unix_socket={$config['unix_socket']}";
    }

    $connection = new Pimf_Pdo($dsn, $config['username'], $config['password'], $this->options($config));

    // If a character set has been specified, we'll execute a query against
    // the database to set the correct character set. By default, this is
    // set to UTF-8 which should be fine for most scenarios.
    if (isset($config['charset'])) {
      $connection->prepare("SET NAMES '{$config['charset']}'")->execute();
    }

    return $connection;
  }
}