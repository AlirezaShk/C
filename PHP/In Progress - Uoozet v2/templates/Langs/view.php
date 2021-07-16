<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lang $lang
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Lang'), ['action' => 'edit', $lang->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Lang'), ['action' => 'delete', $lang->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lang->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Langs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Lang'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="langs view content">
            <h3><?= h($lang->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Lang Key') ?></th>
                    <td><?= h($lang->lang_key) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= h($lang->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($lang->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('English') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($lang->english)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Arabic') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($lang->arabic)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Farsi') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($lang->farsi)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
