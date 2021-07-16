<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="users index content">
    <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('password') ?></th>
                    <th><?= $this->Paginator->sort('fname') ?></th>
                    <th><?= $this->Paginator->sort('lname') ?></th>
                    <th><?= $this->Paginator->sort('age') ?></th>
                    <th><?= $this->Paginator->sort('mobile') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('active') ?></th>
                    <th><?= $this->Paginator->sort('completeSignup') ?></th>
                    <th><?= $this->Paginator->sort('registerDate') ?></th>
                    <th><?= $this->Paginator->sort('registerCode') ?></th>
                    <th><?= $this->Paginator->sort('registeredCode') ?></th>
                    <th><?= $this->Paginator->sort('registerPoints') ?></th>
                    <th><?= $this->Paginator->sort('access') ?></th>
                    <th><?= $this->Paginator->sort('gender') ?></th>
                    <th><?= $this->Paginator->sort('email_vcode') ?></th>
                    <th><?= $this->Paginator->sort('device_id') ?></th>
                    <th><?= $this->Paginator->sort('lang') ?></th>
                    <th><?= $this->Paginator->sort('thumbnail') ?></th>
                    <th><?= $this->Paginator->sort('cover') ?></th>
                    <th><?= $this->Paginator->sort('country') ?></th>
                    <th><?= $this->Paginator->sort('other_socials') ?></th>
                    <th><?= $this->Paginator->sort('allow_notify') ?></th>
                    <th><?= $this->Paginator->sort('verified') ?></th>
                    <th><?= $this->Paginator->sort('last_active') ?></th>
                    <th><?= $this->Paginator->sort('active_time') ?></th>
                    <th><?= $this->Paginator->sort('active_expire') ?></th>
                    <th><?= $this->Paginator->sort('pro') ?></th>
                    <th><?= $this->Paginator->sort('imports') ?></th>
                    <th><?= $this->Paginator->sort('uploads') ?></th>
                    <th><?= $this->Paginator->sort('wallet') ?></th>
                    <th><?= $this->Paginator->sort('balance') ?></th>
                    <th><?= $this->Paginator->sort('user_upload_limit') ?></th>
                    <th><?= $this->Paginator->sort('two_factor') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->username) ?></td>
                    <td><?= h($user->password) ?></td>
                    <td><?= h($user->fname) ?></td>
                    <td><?= h($user->lname) ?></td>
                    <td><?= h($user->age) ?></td>
                    <td><?= h($user->mobile) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->active) ?></td>
                    <td><?= h($user->completeSignup) ?></td>
                    <td><?= h($user->registerDate) ?></td>
                    <td><?= h($user->registerCode) ?></td>
                    <td><?= h($user->registeredCode) ?></td>
                    <td><?= $this->Number->format($user->registerPoints) ?></td>
                    <td><?= $this->Number->format($user->access) ?></td>
                    <td><?= h($user->gender) ?></td>
                    <td><?= h($user->email_vcode) ?></td>
                    <td><?= h($user->device_id) ?></td>
                    <td><?= h($user->lang) ?></td>
                    <td><?= h($user->thumbnail) ?></td>
                    <td><?= h($user->cover) ?></td>
                    <td><?= h($user->country) ?></td>
                    <td><?= h($user->other_socials) ?></td>
                    <td><?= h($user->allow_notify) ?></td>
                    <td><?= h($user->verified) ?></td>
                    <td><?= $this->Number->format($user->last_active) ?></td>
                    <td><?= $this->Number->format($user->active_time) ?></td>
                    <td><?= $this->Number->format($user->active_expire) ?></td>
                    <td><?= h($user->pro) ?></td>
                    <td><?= $this->Number->format($user->imports) ?></td>
                    <td><?= $this->Number->format($user->uploads) ?></td>
                    <td><?= h($user->wallet) ?></td>
                    <td><?= h($user->balance) ?></td>
                    <td><?= h($user->user_upload_limit) ?></td>
                    <td><?= $this->Number->format($user->two_factor) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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
