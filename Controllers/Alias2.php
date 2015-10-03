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
class Alias
{
    public function __construct(){
        $this->route    = Resources\Config::router();
    }

    /*
    * This is method to receive alias requests
    */
    public function index()
    {
        $args = func_get_args();

        if ( empty($args) )
            $args = ['dashboard','index'];

        if (!isset($args[1]) && !empty($args[0])) 
            $args[1] = 'index';

        // die(var_dump($args));

        $route = [
            'admin' => [
                'class'  => '\\Controllers\Admin',
                'method' => 'index'
            ],
            'mahasiswa' => [
                'class'  => '\\Controllers\Admin\Mahasiswa',
                'method' => $args[1]
            ],
            'dashboard' => [
                'class'  => '\\Controllers\Admin\Home',
                'method' => $args[1]
            ],
        ];

        if( in_array($args[0], array_keys($route) ) ) {

            try {
                throw new \Resources\HttpException('Page not found!');
            }
            catch(\Exception $e) {

                $route[$args[0]]['class'] = new $route[$args[0]]['class'];
                // die( var_dump( $route[$args[0]]['class'] ) );
                // die(var_dump(array_slice($args, 2)));
                
                if( $this->is_methods( $route[$args[0]]['class'], $args[1]) ){
                    call_user_func_array(
                        array_values($route[$args[0]]),
                        array_slice($args, 2)
                    );
                }
            }
            return;
        }

        throw new \Resources\HttpException('Page not found!');
    }

    private function segments($seg=3, $key=0,$segment=true){
        $pathInfo  = isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO']:'';
        $selfArray = explode('/', rtrim($_SERVER['PHP_SELF'].$pathInfo, '/'));
        $selfKey   = array_search(INDEX_FILE, $selfArray);
        $segments  = array_slice($selfArray, ($selfKey + $seg));
        return ($segment)? $segments : $selfArray[$key] ;
    }

    private function is_methods($instance, $method){
        if (!method_exists($instance, $method)) 
            throw new Resources\HttpException('Page not found!');
        return $method;
    }

}