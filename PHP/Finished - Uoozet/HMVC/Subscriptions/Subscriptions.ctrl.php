<?php


class Subscriptions extends \lib\Controller
{
    const SUB_TYPE_TAGS = 'tags';
    const SUB_TYPE_USERS = 'users';
    const SUB_TYPE_CHANNELS = 'channels';
    const SUB_TYPE_SERIES = 'series';
    const SUB_TYPE_LISTS = 'lists';

    public function __construct()
    {
        parent::__construct();
    }

    public function addUserRecord(int $user_id)
    {
        return $this->model->addOne(['user_id' => $user_id]);
    }

    /**
     * @param int $user_id
     * @param string $type
     * @param string|array $raw_data
     * @param int|string|array|NULL $timestamp
     * @return TRUE|FALSE
     * @throws Exception
     */
    public function subscribe(int $user_id, string $type, $raw_data, $timestamp = NULL)
    {
        $cond = ['user_id' => $user_id];
        if ($this->model->exists($cond)) {
            $data = $this->returnData($this->model->getOne($cond), self::RETURN_ARRAY);
            $data = json_decode($data[$type]);
        } else {
            $this->addUserRecord($user_id);
            $data = [];
        }
        if (is_string($raw_data)) {
            if (is_null($timestamp)) $timestamp = current_time('timestamp');
            elseif (is_array($timestamp)) throw new Exception('Timestamp cannot be array if tags are not');
            else $timestamp = intval($timestamp);
            $data[] = $raw_data . ":" . $timestamp;
        } elseif (is_array($raw_data)) {
            if (!is_array($timestamp) and !is_null($timestamp))
                throw new Exception('Timestamp must be array if tags are more than one');
            for ($i = 0; $i < count($raw_data); $i++) {
                $data[] = $raw_data[$i] . ":" . (
                    (is_null($timestamp)) ?
                        (current_time('timestamp')) : ($timestamp[$i])
                    );
            }
        }
        $data = [$type => json_encode($data)];
        return $this->model->setOne($data, $cond);
    }

    /**
     * @param int $user_id
     * @param string $type
     * @param string|array $raw_data
     * @return TRUE|FALSE
     * @throws Exception
     */
    public function unsubscribe(int $user_id, string $type, $raw_data)
    {
        $cond = ['user_id' => $user_id];
        if (!$this->model->exists($cond)) return FALSE;
        $data = $this->returnData($this->model->getOne($cond), self::RETURN_ARRAY);
        $data = json_decode($data[$type]);
        if (is_string($raw_data)) {
            $target = preg_grep('/^' . $raw_data . ':(.*)/', $data);
            if (count($target) === 0) return FALSE;
            unset($data[$target[0]]);
        } elseif (is_array($raw_data)) {
            for ($i = 0; $i < count($raw_data); $i++) {
                $target = preg_grep('/^' . $raw_data[$i] . ':(.*)/', $data);
                if (count($target) === 0) continue;
                unset($data[$target[0]]);
            }
        }
        $data = [$type => json_encode($data)];
        return $this->model->setOne($data, $cond);
    }

    public function getOne(int $user_id, int $returnType = self::RETURN_ARRAY)
    {
        $cond = ['user_id'=>$user_id];
        if (!$this->exists($cond)) return FALSE;
        return $this->returnData($this->model->getOne($cond), self::RETURN_ARRAY);
    }

    public function subscribeCount($target, string $type)
    {
        $subscriptions = $this->returnData($this->model->getOne(NULL, [$type], NULL, NULL, [$type => "%'$target:%"]),
            self::RETURN_ARRAY);
        return count($subscriptions);
    }
}