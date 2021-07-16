<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Config[]|\Cake\Collection\CollectionInterface $config
 */
?>
<div class="config index content">
    <?= $this->Html->link(__('New Config'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Config') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('value') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($config as $config): ?>
                <tr>
                    <td><?= $this->Number->format($config->id) ?></td>
                    <td><?= h($config->name) ?></td>
                    <td><?= h($config->value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $config->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $config->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $config->id], ['confirm' => __('Are you sure you want to delete # {0}?', $config->id)]) ?>
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
