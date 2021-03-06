<?php
/**
 * View
 *
 * @copyright Copyright (c)  Gjero Krsteski (http://krsteski.de)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Pimf\View;

use Pimf\Contracts\Reunitable;
use Pimf\View;
use Pimf\Config;

/**
 * A view for HAANGA template engine that uses Django syntax - fast and secure template engine for PHP.
 *
 * For use please add the following code to the end of the config.app.php file:
 *
 * <code>
 *
 * 'view' => array(
 *
 *   'haanga' => array(
 *     'cache'       => true,  // if compilation caching should be used
 *     'debug'       => false, // if set to true, you can display the generated nodes
 *     'auto_reload' => true,  // useful to recompile the template whenever the source code changes
 *  ),
 *
 * ),
 *
 * </code>
 *
 * @link    http://haanga.org/documentation
 * @package View
 * @author  Gjero Krsteski <gjero@krsteski.de>
 * @codeCoverageIgnore
 */
class Haanga extends View implements Reunitable
{
    /**
     * @param string $template
     * @param array  $data
     */
    public function __construct($template, array $data = array())
    {
        parent::__construct($template, $data);

        $conf = Config::get('view.haanga');

        $options = [
            'debug'        => $conf['debug'],
            'template_dir' => $this->path,
            'autoload'     => $conf['auto_reload'],

        ];

        if ($conf['cache'] === true) {
            $options['cache_dir'] = $this->path . '/haanga_cache';
        }

        require_once BASE_PATH . "Haanga/lib/Haanga.php";

        \Haanga::configure($options);
    }

    /**
     * Puts the template an the variables together.
     *
     * @return NULL|string|void
     */
    public function reunite()
    {
        return \Haanga::Load(
            $this->template, $this->data->getArrayCopy()
        );
    }
}
