<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Session[]|\Cake\Collection\CollectionInterface $sessions
 */
?>
<div class="sessions index content">
    <?= $this->Html->link(__('New Session'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Sessions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('session_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('platform') ?></th>
                    <th><?= $this->Paginator->sort('time') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?= $this->Number->format($session->id) ?></td>
                    <td><?= h($session->session_id) ?></td>
                    <td><?= $session->has('user') ? $this->Html->link($session->user->id, ['controller' => 'Users', 'action' => 'view', $session->user->id]) : '' ?></td>
                    <td><?= h($session->platform) ?></td>
                    <td><?= $this->Number->format($session->time) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $session->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $session->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $session->id], ['confirm' => __('Are you sure you want to delete # {0}?', $session->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
