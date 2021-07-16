<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\Controller\ComponentRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventManagerInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

/**
 * Config Controller
 *
 * @property \App\Model\Table\ConfigTable $Config
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $config = $this->paginate($this->Config);

        $this->set(compact('config'));
    }

    /**
     * View method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $config = $this->Config->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('config'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $config = $this->Config->newEmptyEntity();
        if ($this->request->is('post')) {
            $config = $this->Config->patchEntity($config, $this->request->getData());
            if ($this->Config->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
        $this->set(compact('config'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $config = $this->Config->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Config->patchEntity($config, $this->request->getData());
            if ($this->Config->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
        $this->set(compact('config'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $config = $this->Config->get($id);
        if ($this->Config->delete($config)) {
            $this->Flash->success(__('The config has been deleted.'));
        } else {
            $this->Flash->error(__('The config could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getAll(string $returnType = 'object', $fields = NULL)
    {
        $result = parent::getAll($returnType, $fields);
        if (!$result) $result = array();
        return $result;
    }
}
