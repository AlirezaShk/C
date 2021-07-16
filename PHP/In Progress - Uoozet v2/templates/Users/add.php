<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('username');
                    echo $this->Form->control('password');
                    echo $this->Form->control('fname');
                    echo $this->Form->control('lname');
                    echo $this->Form->control('age');
                    echo $this->Form->control('mobile');
                    echo $this->Form->control('email');
                    echo $this->Form->control('active');
                    echo $this->Form->control('completeSignup');
                    echo $this->Form->control('registerDate');
                    echo $this->Form->control('registerCode');
                    echo $this->Form->control('registeredCode');
                    echo $this->Form->control('registerPoints');
                    echo $this->Form->control('access');
                    echo $this->Form->control('gender');
                    echo $this->Form->control('email_vcode');
                    echo $this->Form->control('device_id');
                    echo $this->Form->control('lang');
                    echo $this->Form->control('thumbnail');
                    echo $this->Form->control('cover');
                    echo $this->Form->control('country');
                    echo $this->Form->control('about');
                    echo $this->Form->control('other_socials');
                    echo $this->Form->control('allow_notify');
                    echo $this->Form->control('verified');
                    echo $this->Form->control('last_active');
                    echo $this->Form->control('active_time');
                    echo $this->Form->control('active_expire');
                    echo $this->Form->control('pro');
                    echo $this->Form->control('imports');
                    echo $this->Form->control('uploads');
                    echo $this->Form->control('wallet');
                    echo $this->Form->control('balance');
                    echo $this->Form->control('user_upload_limit');
                    echo $this->Form->control('two_factor');
                    echo $this->Form->control('last_month');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
