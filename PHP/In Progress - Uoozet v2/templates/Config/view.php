<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Config $config
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Config'), ['action' => 'edit', $config->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Config'), ['action' => 'delete', $config->id], ['confirm' => __('Are you sure you want to delete # {0}?', $config->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Config'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Config'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="config view content">
            <h3><?= h($config->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($config->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value') ?></th>
                    <td><?= h($config->value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($config->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
