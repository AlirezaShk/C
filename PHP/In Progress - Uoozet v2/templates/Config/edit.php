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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $config->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $config->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Config'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="config form content">
            <?= $this->Form->create($config) ?>
            <fieldset>
                <legend><?= __('Edit Config') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('value');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
