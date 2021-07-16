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
            <?= $this->Html->link(__('List Sessions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sessions form content">
            <?= $this->Form->create($session) ?>
            <fieldset>
                <legend><?= __('Add Session') ?></legend>
                <?php
                    echo $this->Form->control('session_id');
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('platform');
                    echo $this->Form->control('time');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
