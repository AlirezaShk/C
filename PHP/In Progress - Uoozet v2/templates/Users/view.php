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
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Password') ?></th>
                    <td><?= h($user->password) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fname') ?></th>
                    <td><?= h($user->fname) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lname') ?></th>
                    <td><?= h($user->lname) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mobile') ?></th>
                    <td><?= h($user->mobile) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('RegisterDate') ?></th>
                    <td><?= h($user->registerDate) ?></td>
                </tr>
                <tr>
                    <th><?= __('RegisterCode') ?></th>
                    <td><?= h($user->registerCode) ?></td>
                </tr>
                <tr>
                    <th><?= __('RegisteredCode') ?></th>
                    <td><?= h($user->registeredCode) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Vcode') ?></th>
                    <td><?= h($user->email_vcode) ?></td>
                </tr>
                <tr>
                    <th><?= __('Device Id') ?></th>
                    <td><?= h($user->device_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lang') ?></th>
                    <td><?= h($user->lang) ?></td>
                </tr>
                <tr>
                    <th><?= __('Thumbnail') ?></th>
                    <td><?= h($user->thumbnail) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cover') ?></th>
                    <td><?= h($user->cover) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country') ?></th>
                    <td><?= h($user->country) ?></td>
                </tr>
                <tr>
                    <th><?= __('Other Socials') ?></th>
                    <td><?= h($user->other_socials) ?></td>
                </tr>
                <tr>
                    <th><?= __('Wallet') ?></th>
                    <td><?= h($user->wallet) ?></td>
                </tr>
                <tr>
                    <th><?= __('Balance') ?></th>
                    <td><?= h($user->balance) ?></td>
                </tr>
                <tr>
                    <th><?= __('User Upload Limit') ?></th>
                    <td><?= h($user->user_upload_limit) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('RegisterPoints') ?></th>
                    <td><?= $this->Number->format($user->registerPoints) ?></td>
                </tr>
                <tr>
                    <th><?= __('Access') ?></th>
                    <td><?= $this->Number->format($user->access) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Active') ?></th>
                    <td><?= $this->Number->format($user->last_active) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active Time') ?></th>
                    <td><?= $this->Number->format($user->active_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active Expire') ?></th>
                    <td><?= $this->Number->format($user->active_expire) ?></td>
                </tr>
                <tr>
                    <th><?= __('Imports') ?></th>
                    <td><?= $this->Number->format($user->imports) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uploads') ?></th>
                    <td><?= $this->Number->format($user->uploads) ?></td>
                </tr>
                <tr>
                    <th><?= __('Two Factor') ?></th>
                    <td><?= $this->Number->format($user->two_factor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Age') ?></th>
                    <td><?= $user->age ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Active') ?></th>
                    <td><?= $user->active ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('CompleteSignup') ?></th>
                    <td><?= $user->completeSignup ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Gender') ?></th>
                    <td><?= $user->gender ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Allow Notify') ?></th>
                    <td><?= $user->allow_notify ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Verified') ?></th>
                    <td><?= $user->verified ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Pro') ?></th>
                    <td><?= $user->pro ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('About') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->about)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Last Month') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->last_month)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Announcement Views') ?></h4>
                <?php if (!empty($user->announcement_views)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Announcement Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->announcement_views as $announcementViews) : ?>
                        <tr>
                            <td><?= h($announcementViews->id) ?></td>
                            <td><?= h($announcementViews->announcement_id) ?></td>
                            <td><?= h($announcementViews->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'AnnouncementViews', 'action' => 'view', $announcementViews->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'AnnouncementViews', 'action' => 'edit', $announcementViews->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'AnnouncementViews', 'action' => 'delete', $announcementViews->id], ['confirm' => __('Are you sure you want to delete # {0}?', $announcementViews->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Cat Affiliation') ?></h4>
                <?php if (!empty($user->cat_affiliation)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Likes') ?></th>
                            <th><?= __('Dislikes') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->cat_affiliation as $catAffiliation) : ?>
                        <tr>
                            <td><?= h($catAffiliation->id) ?></td>
                            <td><?= h($catAffiliation->user_id) ?></td>
                            <td><?= h($catAffiliation->likes) ?></td>
                            <td><?= h($catAffiliation->dislikes) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'CatAffiliation', 'action' => 'view', $catAffiliation->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'CatAffiliation', 'action' => 'edit', $catAffiliation->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'CatAffiliation', 'action' => 'delete', $catAffiliation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $catAffiliation->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Comments') ?></h4>
                <?php if (!empty($user->comments)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Target Id') ?></th>
                            <th><?= __('Text') ?></th>
                            <th><?= __('Time') ?></th>
                            <th><?= __('Pinned') ?></th>
                            <th><?= __('Likes') ?></th>
                            <th><?= __('Dis Likes') ?></th>
                            <th><?= __('Active') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->comments as $comments) : ?>
                        <tr>
                            <td><?= h($comments->id) ?></td>
                            <td><?= h($comments->user_id) ?></td>
                            <td><?= h($comments->type) ?></td>
                            <td><?= h($comments->target_id) ?></td>
                            <td><?= h($comments->text) ?></td>
                            <td><?= h($comments->time) ?></td>
                            <td><?= h($comments->pinned) ?></td>
                            <td><?= h($comments->likes) ?></td>
                            <td><?= h($comments->dis_likes) ?></td>
                            <td><?= h($comments->active) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Comments', 'action' => 'view', $comments->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comments->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comments->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related History') ?></h4>
                <?php if (!empty($user->history)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Media Id') ?></th>
                            <th><?= __('Time') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->history as $history) : ?>
                        <tr>
                            <td><?= h($history->id) ?></td>
                            <td><?= h($history->user_id) ?></td>
                            <td><?= h($history->media_id) ?></td>
                            <td><?= h($history->time) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'History', 'action' => 'view', $history->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'History', 'action' => 'edit', $history->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'History', 'action' => 'delete', $history->id], ['confirm' => __('Are you sure you want to delete # {0}?', $history->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Likes Dislikes') ?></h4>
                <?php if (!empty($user->likes_dislikes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Comment Id') ?></th>
                            <th><?= __('List Id') ?></th>
                            <th><?= __('Media Id') ?></th>
                            <th><?= __('Time') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->likes_dislikes as $likesDislikes) : ?>
                        <tr>
                            <td><?= h($likesDislikes->id) ?></td>
                            <td><?= h($likesDislikes->user_id) ?></td>
                            <td><?= h($likesDislikes->comment_id) ?></td>
                            <td><?= h($likesDislikes->list_id) ?></td>
                            <td><?= h($likesDislikes->media_id) ?></td>
                            <td><?= h($likesDislikes->time) ?></td>
                            <td><?= h($likesDislikes->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'LikesDislikes', 'action' => 'view', $likesDislikes->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'LikesDislikes', 'action' => 'edit', $likesDislikes->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'LikesDislikes', 'action' => 'delete', $likesDislikes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $likesDislikes->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Lists') ?></h4>
                <?php if (!empty($user->lists)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Char Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Privacy') ?></th>
                            <th><?= __('Views') ?></th>
                            <th><?= __('Time') ?></th>
                            <th><?= __('Content Ids') ?></th>
                            <th><?= __('Duration') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->lists as $lists) : ?>
                        <tr>
                            <td><?= h($lists->id) ?></td>
                            <td><?= h($lists->char_id) ?></td>
                            <td><?= h($lists->user_id) ?></td>
                            <td><?= h($lists->type) ?></td>
                            <td><?= h($lists->privacy) ?></td>
                            <td><?= h($lists->views) ?></td>
                            <td><?= h($lists->time) ?></td>
                            <td><?= h($lists->content_ids) ?></td>
                            <td><?= h($lists->duration) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Lists', 'action' => 'view', $lists->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Lists', 'action' => 'edit', $lists->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Lists', 'action' => 'delete', $lists->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lists->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Media') ?></h4>
                <?php if (!empty($user->media)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Media Type') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Char Id') ?></th>
                            <th><?= __('Short Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Thumbnail') ?></th>
                            <th><?= __('File Location') ?></th>
                            <th><?= __('Other Socials') ?></th>
                            <th><?= __('Time') ?></th>
                            <th><?= __('Time Date') ?></th>
                            <th><?= __('Active') ?></th>
                            <th><?= __('Tags') ?></th>
                            <th><?= __('Duration') ?></th>
                            <th><?= __('Size') ?></th>
                            <th><?= __('Converted') ?></th>
                            <th><?= __('Visits') ?></th>
                            <th><?= __('Views') ?></th>
                            <th><?= __('Shared') ?></th>
                            <th><?= __('Featured') ?></th>
                            <th><?= __('Privacy') ?></th>
                            <th><?= __('Age Restriction') ?></th>
                            <th><?= __('Approved') ?></th>
                            <th><?= __('Sell Video') ?></th>
                            <th><?= __('Demo') ?></th>
                            <th><?= __('Rating') ?></th>
                            <th><?= __('Is Channel') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->media as $media) : ?>
                        <tr>
                            <td><?= h($media->id) ?></td>
                            <td><?= h($media->media_type) ?></td>
                            <td><?= h($media->user_id) ?></td>
                            <td><?= h($media->char_id) ?></td>
                            <td><?= h($media->short_id) ?></td>
                            <td><?= h($media->title) ?></td>
                            <td><?= h($media->description) ?></td>
                            <td><?= h($media->thumbnail) ?></td>
                            <td><?= h($media->file_location) ?></td>
                            <td><?= h($media->other_socials) ?></td>
                            <td><?= h($media->time) ?></td>
                            <td><?= h($media->time_date) ?></td>
                            <td><?= h($media->active) ?></td>
                            <td><?= h($media->tags) ?></td>
                            <td><?= h($media->duration) ?></td>
                            <td><?= h($media->size) ?></td>
                            <td><?= h($media->converted) ?></td>
                            <td><?= h($media->visits) ?></td>
                            <td><?= h($media->views) ?></td>
                            <td><?= h($media->shared) ?></td>
                            <td><?= h($media->featured) ?></td>
                            <td><?= h($media->privacy) ?></td>
                            <td><?= h($media->age_restriction) ?></td>
                            <td><?= h($media->approved) ?></td>
                            <td><?= h($media->sell_video) ?></td>
                            <td><?= h($media->demo) ?></td>
                            <td><?= h($media->rating) ?></td>
                            <td><?= h($media->is_channel) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Media', 'action' => 'view', $media->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Media', 'action' => 'edit', $media->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Media', 'action' => 'delete', $media->id], ['confirm' => __('Are you sure you want to delete # {0}?', $media->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Media Transactions') ?></h4>
                <?php if (!empty($user->media_transactions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Paid Id') ?></th>
                            <th><?= __('Media Id') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Admin Com') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Time') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->media_transactions as $mediaTransactions) : ?>
                        <tr>
                            <td><?= h($mediaTransactions->id) ?></td>
                            <td><?= h($mediaTransactions->user_id) ?></td>
                            <td><?= h($mediaTransactions->paid_id) ?></td>
                            <td><?= h($mediaTransactions->media_id) ?></td>
                            <td><?= h($mediaTransactions->amount) ?></td>
                            <td><?= h($mediaTransactions->admin_com) ?></td>
                            <td><?= h($mediaTransactions->currency) ?></td>
                            <td><?= h($mediaTransactions->time) ?></td>
                            <td><?= h($mediaTransactions->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'MediaTransactions', 'action' => 'view', $mediaTransactions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'MediaTransactions', 'action' => 'edit', $mediaTransactions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'MediaTransactions', 'action' => 'delete', $mediaTransactions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mediaTransactions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Payments') ?></h4>
                <?php if (!empty($user->payments)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Date') ?></th>
                            <th><?= __('Expire') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->payments as $payments) : ?>
                        <tr>
                            <td><?= h($payments->id) ?></td>
                            <td><?= h($payments->user_id) ?></td>
                            <td><?= h($payments->type) ?></td>
                            <td><?= h($payments->amount) ?></td>
                            <td><?= h($payments->date) ?></td>
                            <td><?= h($payments->expire) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Payments', 'action' => 'view', $payments->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Payments', 'action' => 'edit', $payments->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Payments', 'action' => 'delete', $payments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payments->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Register Code') ?></h4>
                <?php if (!empty($user->register_code)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Code') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Points') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Start Datetime') ?></th>
                            <th><?= __('Expiry Datetime') ?></th>
                            <th><?= __('Use Times') ?></th>
                            <th><?= __('Used Times') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->register_code as $registerCode) : ?>
                        <tr>
                            <td><?= h($registerCode->id) ?></td>
                            <td><?= h($registerCode->code) ?></td>
                            <td><?= h($registerCode->type) ?></td>
                            <td><?= h($registerCode->points) ?></td>
                            <td><?= h($registerCode->user_id) ?></td>
                            <td><?= h($registerCode->start_datetime) ?></td>
                            <td><?= h($registerCode->expiry_datetime) ?></td>
                            <td><?= h($registerCode->use_times) ?></td>
                            <td><?= h($registerCode->used_times) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'RegisterCode', 'action' => 'view', $registerCode->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'RegisterCode', 'action' => 'edit', $registerCode->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'RegisterCode', 'action' => 'delete', $registerCode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $registerCode->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Sessions') ?></h4>
                <?php if (!empty($user->sessions)) : ?>
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
                        <?php foreach ($user->sessions as $sessions) : ?>
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
            <div class="related">
                <h4><?= __('Related Subscriptions') ?></h4>
                <?php if (!empty($user->subscriptions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Users') ?></th>
                            <th><?= __('Lists') ?></th>
                            <th><?= __('Channels') ?></th>
                            <th><?= __('Series') ?></th>
                            <th><?= __('Tags') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->subscriptions as $subscriptions) : ?>
                        <tr>
                            <td><?= h($subscriptions->id) ?></td>
                            <td><?= h($subscriptions->user_id) ?></td>
                            <td><?= h($subscriptions->users) ?></td>
                            <td><?= h($subscriptions->lists) ?></td>
                            <td><?= h($subscriptions->channels) ?></td>
                            <td><?= h($subscriptions->series) ?></td>
                            <td><?= h($subscriptions->tags) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Subscriptions', 'action' => 'view', $subscriptions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Subscriptions', 'action' => 'edit', $subscriptions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Subscriptions', 'action' => 'delete', $subscriptions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subscriptions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related User Ads') ?></h4>
                <?php if (!empty($user->user_ads)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Results') ?></th>
                            <th><?= __('Spent') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Audience') ?></th>
                            <th><?= __('Category') ?></th>
                            <th><?= __('Media') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Placement') ?></th>
                            <th><?= __('Posted') ?></th>
                            <th><?= __('Headline') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Location') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->user_ads as $userAds) : ?>
                        <tr>
                            <td><?= h($userAds->id) ?></td>
                            <td><?= h($userAds->user_id) ?></td>
                            <td><?= h($userAds->name) ?></td>
                            <td><?= h($userAds->results) ?></td>
                            <td><?= h($userAds->spent) ?></td>
                            <td><?= h($userAds->status) ?></td>
                            <td><?= h($userAds->audience) ?></td>
                            <td><?= h($userAds->category) ?></td>
                            <td><?= h($userAds->media) ?></td>
                            <td><?= h($userAds->url) ?></td>
                            <td><?= h($userAds->placement) ?></td>
                            <td><?= h($userAds->posted) ?></td>
                            <td><?= h($userAds->headline) ?></td>
                            <td><?= h($userAds->description) ?></td>
                            <td><?= h($userAds->location) ?></td>
                            <td><?= h($userAds->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'UserAds', 'action' => 'view', $userAds->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'UserAds', 'action' => 'edit', $userAds->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserAds', 'action' => 'delete', $userAds->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userAds->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Usr Prof Fields') ?></h4>
                <?php if (!empty($user->usr_prof_fields)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Fid 2') ?></th>
                            <th><?= __('Fid 3') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->usr_prof_fields as $usrProfFields) : ?>
                        <tr>
                            <td><?= h($usrProfFields->id) ?></td>
                            <td><?= h($usrProfFields->user_id) ?></td>
                            <td><?= h($usrProfFields->fid_2) ?></td>
                            <td><?= h($usrProfFields->fid_3) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'UsrProfFields', 'action' => 'view', $usrProfFields->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'UsrProfFields', 'action' => 'edit', $usrProfFields->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'UsrProfFields', 'action' => 'delete', $usrProfFields->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usrProfFields->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Verification Requests') ?></h4>
                <?php if (!empty($user->verification_requests)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Message') ?></th>
                            <th><?= __('Media File') ?></th>
                            <th><?= __('Time') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->verification_requests as $verificationRequests) : ?>
                        <tr>
                            <td><?= h($verificationRequests->id) ?></td>
                            <td><?= h($verificationRequests->user_id) ?></td>
                            <td><?= h($verificationRequests->name) ?></td>
                            <td><?= h($verificationRequests->message) ?></td>
                            <td><?= h($verificationRequests->media_file) ?></td>
                            <td><?= h($verificationRequests->time) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'VerificationRequests', 'action' => 'view', $verificationRequests->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'VerificationRequests', 'action' => 'edit', $verificationRequests->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'VerificationRequests', 'action' => 'delete', $verificationRequests->id], ['confirm' => __('Are you sure you want to delete # {0}?', $verificationRequests->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Video Ads') ?></h4>
                <?php if (!empty($user->video_ads)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Media') ?></th>
                            <th><?= __('Image') ?></th>
                            <th><?= __('Skip Seconds') ?></th>
                            <th><?= __('Vast Type') ?></th>
                            <th><?= __('Vast Xml Link') ?></th>
                            <th><?= __('Views') ?></th>
                            <th><?= __('Clicks') ?></th>
                            <th><?= __('Active') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->video_ads as $videoAds) : ?>
                        <tr>
                            <td><?= h($videoAds->id) ?></td>
                            <td><?= h($videoAds->user_id) ?></td>
                            <td><?= h($videoAds->url) ?></td>
                            <td><?= h($videoAds->name) ?></td>
                            <td><?= h($videoAds->media) ?></td>
                            <td><?= h($videoAds->image) ?></td>
                            <td><?= h($videoAds->skip_seconds) ?></td>
                            <td><?= h($videoAds->vast_type) ?></td>
                            <td><?= h($videoAds->vast_xml_link) ?></td>
                            <td><?= h($videoAds->views) ?></td>
                            <td><?= h($videoAds->clicks) ?></td>
                            <td><?= h($videoAds->active) ?></td>
                            <td><?= h($videoAds->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'VideoAds', 'action' => 'view', $videoAds->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'VideoAds', 'action' => 'edit', $videoAds->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'VideoAds', 'action' => 'delete', $videoAds->id], ['confirm' => __('Are you sure you want to delete # {0}?', $videoAds->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Views') ?></h4>
                <?php if (!empty($user->views)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Media Id') ?></th>
                            <th><?= __('Fingerprint') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Time') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->views as $views) : ?>
                        <tr>
                            <td><?= h($views->id) ?></td>
                            <td><?= h($views->media_id) ?></td>
                            <td><?= h($views->fingerprint) ?></td>
                            <td><?= h($views->user_id) ?></td>
                            <td><?= h($views->time) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Views', 'action' => 'view', $views->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Views', 'action' => 'edit', $views->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Views', 'action' => 'delete', $views->id], ['confirm' => __('Are you sure you want to delete # {0}?', $views->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Withdrawal Requests') ?></h4>
                <?php if (!empty($user->withdrawal_requests)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Requested') ?></th>
                            <th><?= __('Status') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->withdrawal_requests as $withdrawalRequests) : ?>
                        <tr>
                            <td><?= h($withdrawalRequests->id) ?></td>
                            <td><?= h($withdrawalRequests->user_id) ?></td>
                            <td><?= h($withdrawalRequests->email) ?></td>
                            <td><?= h($withdrawalRequests->amount) ?></td>
                            <td><?= h($withdrawalRequests->currency) ?></td>
                            <td><?= h($withdrawalRequests->requested) ?></td>
                            <td><?= h($withdrawalRequests->status) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'WithdrawalRequests', 'action' => 'view', $withdrawalRequests->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'WithdrawalRequests', 'action' => 'edit', $withdrawalRequests->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'WithdrawalRequests', 'action' => 'delete', $withdrawalRequests->id], ['confirm' => __('Are you sure you want to delete # {0}?', $withdrawalRequests->id)]) ?>
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
