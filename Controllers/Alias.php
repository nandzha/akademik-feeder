<?php
namespace Controllers;

use Resources;

/*
 * Expected url:
 *
 * http://website.com/katalog ==> http://website.com/opac/pencarian
 * http://website.com/koleksi ==> http://website.com/detail/buku
 *
 * assuming controllers opac and pencarian exists! with it each method.
 *
 * @author kandar <k4ndar@yahoo.com>
 */

/*
 * Ini alias class
 */
class Alias extends Resources\Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * This is method to receive alias requests
     */
    public function index()
    {
        $args = func_get_args();

        if (empty($args)) {
            $args = ['dashboard', 'index'];
        }

        if (!isset($args[1]) && !empty($args[0])) {
            $args[1] = 'index';
        }

        if (!isset($args[2]) && !empty($args[1])) {
            $args[2] = 'index';
        }

        // die(var_dump($args));

        $route = [
            'signout' => [
                'class' => '\\Controllers\Login',
                'method' => 'signout',
            ],
            'testing' => [
                'class' => '\\Controllers\Test',
                'method' => $args[1],
            ],
            'admin' => [
                'class' => '\\Controllers\Login',
                'method' => 'index',
                'sub' => [
                    'signout' => [
                        'class' => '\\Controllers\Login',
                        'method' => 'signout',
                    ],
                    'mahasiswa' => [
                        'class' => '\\Controllers\Mahasiswa',
                        'method' => $args[2],
                    ],
                    'dashboard' => [
                        'class' => '\\Controllers\Dashboard',
                        'method' => $args[2],
                    ],
                    'dosen' => [
                        'class' => '\\Controllers\Dosen',
                        'method' => $args[2],
                    ],
                    'kuliah' => [
                        'class' => '\\Controllers\Kuliah',
                        'method' => $args[2],
                    ],
                    'prodi' => [
                        'class' => '\\Controllers\Prodi',
                        'method' => $args[2],
                    ],
                    'tools' => [
                        'class' => '\\Controllers\Tools',
                        'method' => $args[2],
                    ],
                ],
            ],
        ];

        if (in_array($args[0], array_keys($route))) {
            try {
                throw new \Resources\HttpException('Page not found! 1');
            } catch (\Exception $e) {

                if (isset($route[$args[0]]['sub']) && in_array($args[1], array_keys($route[$args[0]]['sub']))) {

                    $route[$args[0]]['sub'][$args[1]]['class'] = new $route[$args[0]]['sub'][$args[1]]['class'];
                    // unset($route[$args[0]]['sub']);

                    // die( var_dump( $route[$args[0]] ['sub'] [$args[1]] ) ) ;
                    // die( var_dump(array_slice($args, 3)));
                    // die( var_dump( array_values( $route[$args[0]] ['sub'] [$args[1]] ) ) );

                    if ($this->is_methods($route[$args[0]]['sub'][$args[1]]['class'], $args[2])) {
                        call_user_func_array(
                            array_values($route[$args[0]]['sub'][$args[1]]),
                            array_slice($args, 3)
                        );
                    }
                    return;
                }

                $route[$args[0]]['class'] = new $route[$args[0]]['class'];

                // die( var_dump( array_values( array_slice($route[$args[0]], 0,2 ) )));
                // die( var_dump( array_slice($args, 2)));

                if ($this->is_methods($route[$args[0]]['class'], $args[1])) {
                    call_user_func_array(
                        array_values(array_slice($route[$args[0]], 0, 2)),
                        array_slice($args, 2)
                    );
                }
            }
            return;
        }

        throw new \Resources\HttpException('Page not found! 2');
    }

    private function segments($seg = 3, $key = 0, $segment = true)
    {
        $pathInfo = isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '';
        $selfArray = explode('/', rtrim($_SERVER['PHP_SELF'] . $pathInfo, '/'));
        $selfKey = array_search(INDEX_FILE, $selfArray);
        $segments = array_slice($selfArray, ($selfKey + $seg));
        return ($segment) ? $segments : $selfArray[$key];
    }

    private function is_methods($instance, $method)
    {
        if (!method_exists($instance, $method)) {
            throw new Resources\HttpException('Page not found! 3');
        }

        return $method;
    }

}
