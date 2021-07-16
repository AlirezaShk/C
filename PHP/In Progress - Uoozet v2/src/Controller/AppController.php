<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }


    /**
     * Getter  method
     *
     * @param array $cond
     * @param string $beautify 'object'|'array'|false; if set to false, it will return the raw fetched data
     * @return array|object|false
     */
    public function get(array $cond, $beautify = 'object', $fields = NULL)
    {
        $className = $this->modelClass;
        $options = ['conditions'=> $cond];
        if (!is_null($fields))
            $options['contain'] = $fields;
        $res = $this->$className
            ->find('all', $options)
            ->execute()
            ->fetch('assoc');
        if (!$res)
            throw new RecordNotFoundException("", 404);
        else
            return (!$beautify ? $res : $this->reshapeDBData($res, (
                is_null($fields) ? $beautify : (
                    (count($fields) == 1) ? 'array' : $beautify
                )
            )));
    }

    public function getAll(string $beautify = 'object', $fields = NULL)
    {
        $className = $this->modelClass;
        $options = [];
        if (!is_null($fields))
            $options['contain'] = $fields;
        $res = $this->$className
            ->find('all', $options)
            ->execute()
            ->fetch('assoc');
        if (!$res)
            throw new RecordNotFoundException("", 404);
        else
            return (!$beautify ? $res : $this->reshapeDBData($res, (
                is_null($fields) ? $beautify : (
                (count($fields) == 1) ? 'array' : $beautify
                )
            )));
    }

    public function update(array $cond, array $data)
    {
        $className = $this->modelClass;
        $this->$className
            ->query()
            ->update()
            ->where($cond)
            ->set($data)
            ->execute();
    }

    public function reshapeDBData($array, $type = "array")
    {
        $result = array();
        $array = (array) $array;
        $i = 0;
        foreach($array as $a) {
            $a = (array) $a;
            $keys = array_keys($a);
            if (count($keys) == 1) {
                /* If only one field/column is being called from each DB result */
                $result[$i++] = $a[$keys[0]];
            } else {
                /* If multiple fields/columns are being called from each DB result */
                $result[$i] = array();
                foreach ($keys as $k_) {
                    $k = explode("__", $k_)[1];
                    $result[$i][$k] = $a[$k_];
                }
                $result[$i] = $this->returnAs($result[$i], $type);
                $i++;
            }
        }
        if ($i === 1 AND is_array($result[0])) $result = $this->returnAs($result[0], $type);
        return $result;
    }

    public function returnAs($target, $type = 'array')
    {
        switch ($type){
            case "object":
                return (object) $target;
            case "array":
            default:
                return $target;
        }
    }
}
