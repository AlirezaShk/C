<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Session $session
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Session'), ['action' => 'edit', $session->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Session'), ['action' => 'delete', $session->id], ['confirm' => __('Are you sure you want to delete # {0}?', $session->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sessions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Session'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sessions view content">
            <h3><?= h($session->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Session Id') ?></th>
                    <td><?= h($session->session_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $session->has('user') ? $this->Html->link($session->user->id, ['controller' => 'Users', 'action' => 'view', $session->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Platform') ?></th>
                    <td><?= h($session->platform) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($session->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Time') ?></th>
                    <td><?= $this->Number->format($session->time) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Sessions') ?></h4>
                <?php if (!empty($session->sessions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Session Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Platform') ?></th>
                            <th><?= __('Time') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($session->sessions as $sessions) : ?>
                        <tr>
                            <td><?= h($sessions->id) ?></td>
                            <td><?= h($sessions->session_id) ?></td>
                            <td><?= h($sessions->user_id) ?></td>
                            <td><?= h($sessions->platform) ?></td>
                            <td><?= h($sessions->time) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Sessions', 'action' => 'view', $sessions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Sessions', 'action' => 'edit', $sessions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sessions', 'action' => 'delete', $sessions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sessions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
