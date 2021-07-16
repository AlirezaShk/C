<?php


namespace lib;


class Controller
{
    protected $model;
    protected $theme;
    protected $debugMode;

    const DEBUG_OFF = FALSE;
    const DEBUG_ON = TRUE;

    const RETURN_RAW = 0;
    const RETURN_ARRAY = 1;
    const RETURN_OBJECT = 2;
    const RETURN_JSON = 3;
    const RETURN_TABLE_DATA = 4;

    public function __construct()
    {
        if(get_class($this) !== "Controller") {
            $modelName = "\Model\\" . get_class($this);
            $this->model = new $modelName();
            $this->theme = DEFAULT_THEME;
            $this->debugMode = (bool) DEBUG_STATUS;
        }
    }

    protected function returnData($result, int $returnType, bool $isItPDO = TRUE, bool $reduceSingleResult = TRUE)
    {
        if (is_object($result)) $result = (array) $result;
        if (is_array($result) AND $reduceSingleResult)
            if (count($result) === 1 AND key_exists(0, $result)) $result = $result[0];
        if(is_bool($result)) {
            return $result;
        }
        if($returnType === self::RETURN_RAW) {
            return $result;
        }
        //Since we use PDO, we should delete the integer keys that come along with fetching the results:
        if($isItPDO) {
            foreach ($result as $k => $res) {
                if (is_array($res)) {
                    foreach ($res as $k2 => $v) {
                        if (is_int($k2) && !is_string($k2))
                            unset($result[$k][$k2]);
                    }
                } else {
                    if (is_int($k) && !is_string($k))
                        unset($result[$k]);
                }
            }
        }
        switch ($returnType) {
            case self::RETURN_OBJECT:
                if (count($result) === 1) {
                    return (object)$result;
                } else {
                    $result = (object) $result;
                    foreach ($result as $k => $res) {
                        if (is_array($res)) $result->$k = (object) $res;
                    }
                    return $result;
                }
            case self::RETURN_TABLE_DATA:
                return $this->toTableData($result);
            case self::RETURN_JSON:
                return json_encode($result);
            case self::RETURN_ARRAY:
            default:
                return $result;
        }
    }

    protected function toTableData(array $a)
    {
        $result = "";
        for ($i = 0; $i < count($a); $i++) {
            $r = $a[$i];
            $result .= "<tr>";
            foreach ($r as $k => $v) {
                $result .= "<td data-col='$k'>$v</td>";
            }
            $result .= "</tr>";
        }
        return $result;
    }

    protected function exists(array $binary_cond)
    {
        return (($this->model->getOne($binary_cond) === FALSE OR count($this->model->getOne($binary_cond)) === 0) ? (FALSE) : (TRUE));
    }

    public function setDebug(bool $toggle = TRUE)
    {
        $this->debugMode = $toggle;
    }
}