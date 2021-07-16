<?php


namespace lib;


class Model
{
    protected $db;
    protected $tableName;

    public function __construct()
    {
        $this->db = \DB::connect();
    }

    public function __destruct()
    {
        try{
            \DB::disConnect($this->db);
        } catch(\PDOException $e) {
        }
    }

    public function getAll()
    {
        $getAll = $this->db->prepare("SELECT * FROM `" . $this->tableName . "`");
        $getAll->execute();
        return $getAll->fetchAll();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getCols()
    {
        $t = $this->db->prepare("SELECT DISTINCT `COLUMN_NAME` FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$this->getTableName()."'");
        $t->execute();
        $raw_cols = $t->fetchAll();
        $res = array();
        foreach($raw_cols as $raw){
            $res[] = $raw['COLUMN_NAME'];
        }
        return $res;
    }

    public function addOne($data)
    {
        $col_text = "(";
        $val_text = "(";
        $arrayKeys = array();
        $i = 0;
        foreach($data as $k => $v) {
            $col_text .= "`" . $k . "`";
            $val_text .= ":v$i";
            if($i !== (count($data) - 1)) {
                $col_text .= ", ";
                $val_text .= ", ";
            }
            $arrayKeys[] = $k;
            $i++;
        }
        $col_text .= ")";
        $val_text .= ")";
        $add = $this->db->prepare("INSERT INTO `" . $this->getTableName() . "`" . $col_text . " VALUES " . $val_text);
        $i = 0;
        foreach($data as $k => $d) {
            $add->bindValue(":v$i", $data[$arrayKeys[$i]]);
            $i++;
        }
        return $add->execute();
    }

    public function setOne($data, $cond)
    {
        $col_text = "";
        $arrayKeys = array();
        $i = 0;
        foreach($data as $k => $v) {
            $col_text .= "`" . $k . "` = :v$i";
            if($i !== (count($data) - 1)) {
                $col_text .= ", ";
            }
            $arrayKeys[] = $k;
            $i++;
        }
        $con_text = "";
        $j = 0;
        foreach($cond as $k => $v) {
            $con_text .= "`" . $k . "` = :v$i";
            if(($j) !== (count($cond) - 1)) {
                $con_text .= " AND ";
            }
            $arrayKeys[] = $k;
            $i++;
            $j++;
        }
        $add = $this->db->prepare("UPDATE `" . $this->getTableName() . "` SET " . $col_text . " WHERE " . $con_text);
        $k = 0;
        for($i = 0; $i < count($data); $i++) {
            $add->bindValue(":v$k", $data[$arrayKeys[$k]]);
            $k++;
        }
        for($i = 0; $i < count($cond); $i++) {
            $add->bindValue(":v$k", $cond[$arrayKeys[$k]]);
            $k++;
        }
        return $add->execute();
    }

    public function delOne($cond)
    {
        $arrayKeys = array();
        $con_text = "";
        $i = 0;
        foreach($cond as $k => $v) {
            $con_text .= "`" . $k . "` = :v$i";
            if(($i) !== (count($cond) - 1)) {
                $con_text .= " AND ";
            }
            $arrayKeys[] = $k;
            $i++;
        }
        $del = $this->db->prepare("DELETE FROM `". $this->getTableName() ."` WHERE " . $con_text);
        $i = 0;
        foreach($cond as $k => $d) {
            $del->bindValue(":v$i", $cond[$arrayKeys[$i]]);
            $i++;
        }
        return $del->execute();
    }

    public function getOne(array $binary_cond = NULL, array $cols = NULL, array $orders = NULL, $countLimit = NULL, array $like_cond = NULL, array $op = NULL)
    {
        $op_flag = ( (is_null($op))?("AND"):(FALSE) );
        $dbcols = $this->getCols();
        $col_text = $order_text = $count_text = "";
        $s = 0;
        do {
            if (!is_null($cols)) {
                foreach ($cols as $col) {
                    switch ($s) {
                        case 0:
                            if (array_search($col, $dbcols) === FALSE) return FALSE;
                            break;
                        case 1:
                            $col_text .= "`$col`, ";
                            break;
                    }
                }
            }
            if (!is_null($orders)) {
                $order_text = " ORDER BY ";
                foreach ($orders as $col => $dir) {
                    switch ($s) {
                        case 0:
                            if (array_search($col, $dbcols) === FALSE) return FALSE;
                            break;
                        case 1:
                            $order_text .= "`$col` $dir, ";
                            break;
                    }
                }
            }
        } while ($s++ < 1);
        if (strlen($col_text) === 0) $col_text = "*";
        else $col_text = substr($col_text, 0, -2);
        if (strlen($order_text) === 10) $order_text = "";
        else $order_text = substr($order_text, 0, -2);
        if (!is_null($countLimit)) {
            if (($countLimit > 0) AND (is_int($countLimit))) {
                $count_text = " LIMIT " . $countLimit;
            } else {
                return FALSE;
            }
        }
        $arrayKeys = array();
        $con_text = "(";
        $i = 0;
        if (!is_null($binary_cond) AND count($binary_cond) > 0) {
            foreach ($binary_cond as $k => $v) {
                if (is_array($v)) {
                    $string = "";
                    foreach ($v as $k_ => $v_) {
                        if (is_string($v_)) $string .= "'$v_', ";
                        else $string .= $v_ . ", ";
                    }
                    $string = substr($string, 0, -2);
                    $con_text .= "`" . $k . "` IN ($string)";
                } else $con_text .= "`" . $k . "` = :v$i";
                if (($i++) !== (count($binary_cond) - 1)) {
                    $con_text .= " ". (($op_flag)?($op_flag):($op[$k])) ." ";
                }
                $arrayKeys[] = $k;
            }
        }
        if (!is_null($like_cond) AND count($like_cond) > 0) {
            $j = 0;
            if (strlen($con_text) > 1) $con_text .= ") AND (";
            foreach ($like_cond as $k => $v) {
                if (is_array($v)) {
                    $jj = 0;
                    $con_text .= "(";
                    foreach ($v as $k_ => $v_) {
                        $con_text .= "`" . $k . "` LIKE '$v_'";
                        if(($jj++) !== (count($v) - 1)) {
                            $con_text .= " OR ";
                        }
                    }
                    $con_text .= ")";
                } else $con_text .= "`" . $k . "` LIKE :v" . ($i + $j);
                if(($j++) !== (count($like_cond) - 1)) {
                    $con_text .= " ". (($op_flag)?($op_flag):($op[$k])) ." ";
                }
                $arrayKeys[] = $k;
            }
        }
        if (strlen($con_text) === 1) $con_text = "1";
        else $con_text .= ")";
        echo "SELECT $col_text FROM `". $this->getTableName() ."` WHERE " . $con_text . $order_text . $count_text;
        $get = $this->db->prepare("SELECT $col_text FROM `". $this->getTableName() ."` WHERE " . $con_text . $order_text . $count_text);
        $i = 0;
        if (!is_null($binary_cond)) {
            foreach ($binary_cond as $k => $d) {
                if (!is_array($binary_cond[$arrayKeys[$i]])) {
                    $get->bindValue(":v$i", $binary_cond[$arrayKeys[$i]]);
                }
                $i++;
            }
        }
        if (!is_null($like_cond)) {
            $j = 0;
            foreach ($like_cond as $k => $d) {
                if (!is_array($like_cond[$arrayKeys[($i + $j)]])) {
                    $get->bindValue(":v" . ($i + $j), $like_cond[$arrayKeys[($i + $j)]]);
                }
                $j++;
            }
        }
        if ($get->execute())
            return $get->fetchAll();
        else
            return FALSE;
    }
}