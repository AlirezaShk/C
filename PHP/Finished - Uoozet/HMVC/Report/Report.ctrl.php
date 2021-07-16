<?php


class Report extends \lib\Controller
{
    const TYPE_REPORT = 'Report';
    const TYPE_CONTACT = 'Contact';
    const TYPE_VREQ = 'Verification Request';
    const TYPE_CHREQ = 'Channel Request';
    const TYPE_OTHER = 'Other';

    const STATUS = [
        0 => 'Not Approved',
        1 => 'New',
        2 => 'Under Process',
        3 => 'c'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function get(array $cond, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne($cond), $returnType);
    }

    public function getAll(int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAll(), $returnType);
    }

    public function getAllByType(string $type, int $returnType = self::RETURN_ARRAY)
    {
        if (
            $type === self::TYPE_REPORT OR
            $type === self::TYPE_CONTACT OR
            $type === self::TYPE_VREQ OR
            $type === self::TYPE_CHREQ OR
            $type === self::TYPE_OTHER
        ) return $this->returnData($this->model->getOne(['type'=>$type]), $returnType);
        else {
            if ($this->debugMode) throw new Exception('Type is not recognized. Use Class Constants.');
            else return FALSE;
        }
    }

    public function setOne(array $data, array $cond)
    {
        $this->model->setOne($data, $cond);
    }

    public function addOne(array $data)
    {
        $this->model->addOne($data);
    }
}