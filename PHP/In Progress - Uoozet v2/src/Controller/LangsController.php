<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Langs Controller
 *
 * @property \App\Model\Table\LangsTable $Langs
 * @method \App\Model\Entity\Lang[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LangsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $langs = $this->paginate($this->Langs);

        $this->set(compact('langs'));
    }

    /**
     * View method
     *
     * @param string|null $id Lang id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lang = $this->Langs->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('lang'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lang = $this->Langs->newEmptyEntity();
        if ($this->request->is('post')) {
            $lang = $this->Langs->patchEntity($lang, $this->request->getData());
            if ($this->Langs->save($lang)) {
                $this->Flash->success(__('The lang has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lang could not be saved. Please, try again.'));
        }
        $this->set(compact('lang'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lang id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lang = $this->Langs->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lang = $this->Langs->patchEntity($lang, $this->request->getData());
            if ($this->Langs->save($lang)) {
                $this->Flash->success(__('The lang has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lang could not be saved. Please, try again.'));
        }
        $this->set(compact('lang'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lang id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lang = $this->Langs->get($id);
        if ($this->Langs->delete($lang)) {
            $this->Flash->success(__('The lang has been deleted.'));
        } else {
            $this->Flash->error(__('The lang could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getAvailableLangs()
    {
        $cols = $this->Langs->getSchema()->columns();
        unset($cols[0]);
        unset($cols[1]);
        unset($cols[2]);
        $langs = array();
        foreach ($cols as $col) {
            $langs[] = $col;
        }
        return $langs;
    }

    public function getAllCats(string $returnType = 'object', bool $info = false)
    {
        $contain = NULL;
        if (!$info)
            $contain = ['id'];
        try {
            return $this->get(['type'=>'category'], $returnType, $contain);
        } catch (RecordNotFoundException $e) {
            return array();
        }
    }

    public function getAllSubCats(string $returnType = 'object', bool $info = false)
    {
        $sub_cats = array();
        $cats = $this->getAllCats('array', $info);
        foreach($cats as $k => $cat) {
            $catId = intval($cat['id']);
            $data = $this->getSubCatsOf($catId, $returnType, $info);
            if (count($data) > 0) $sub_cats[$catId] = $data;
        }
        return $this->returnAs($sub_cats, $returnType);
    }

    public function getAllUCats(string $returnType = 'object', bool $info = false)
    {
        $contain = NULL;
        if (!$info)
            $contain = ['id'];
        try {
            return $this->get(['type LIKE'=>'%cat-%'], $returnType, $contain);
        } catch (RecordNotFoundException $e) {
            return array();
        }
    }

    public function getAllSubUCats(string $returnType = 'object', bool $info = false)
    {
        $sub_cats = array();
        $cats = $this->getAllUCats('array', $info);
        foreach($cats as $k => $cat) {
            $catId = intval($cat['id']);
            $data = $this->getSubCatsOf($catId, $returnType, $info);
            if (count($data) > 0) $sub_cats[$catId] = $data;
        }
        return $this->returnAs($sub_cats, $returnType);
    }


    public function getSubCatsOf(int $catId, string $returnType = 'object', bool $info = false)
    {
        $contain = NULL;
        if (!$info)
            $contain = ['id'];
        try {
            return $this->get(['type'=>$catId], $returnType, $contain);
        } catch (RecordNotFoundException $e) {
            return array();
        }
    }

    public function getAllUCatsOf(int $channel_id, string $returnType = 'object', bool $info = false)
    {
        $contain = NULL;
        if (!$info)
            $contain = ['id'];
        try {
            return $this->get(['type'=>'cat-'.$channel_id], $returnType, $contain);
        } catch (RecordNotFoundException $e) {
            return array();
        }
    }

    public function getFullUCatsOf(int $channel_id,  string $returnType = 'object', bool $info = false)
    {
        $result = array();
        $ucats = $this->getAllUCatsOf($channel_id, 'array', $info);
        foreach($ucats as $ucat) {
            $ucatId = $ucat['id'];
            $subcats = $this->getSubCatsOf($ucatId, $returnType, $info);
            if (count($subcats) === 0) continue;
            if (key_exists('id', $subcats)) $subcats = array($subcats);
            $data = [];
            foreach($subcats as $subcat) $data[] = $subcat;
            $result[] = array_merge($ucat, ["subcats"=>$data]);
        }
        return $result;
    }
}
