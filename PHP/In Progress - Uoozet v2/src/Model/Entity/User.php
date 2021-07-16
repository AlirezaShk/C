<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $fname
 * @property string $lname
 * @property bool $age
 * @property string $mobile
 * @property string|null $email
 * @property bool $active
 * @property bool $completeSignup
 * @property string $registerDate
 * @property string|null $registerCode
 * @property string|null $registeredCode
 * @property int $registerPoints
 * @property int $access
 * @property bool|null $gender
 * @property string|null $email_vcode
 * @property string|null $device_id
 * @property string|null $lang
 * @property string|null $thumbnail
 * @property string|null $cover
 * @property string|null $country
 * @property string|null $about
 * @property string|null $other_socials
 * @property bool $allow_notify
 * @property bool $verified
 * @property int|null $last_active
 * @property int|null $active_time
 * @property int|null $active_expire
 * @property bool|null $pro
 * @property int|null $imports
 * @property int|null $uploads
 * @property string|null $wallet
 * @property string|null $balance
 * @property string $user_upload_limit
 * @property int $two_factor
 * @property string|null $last_month
 *
 * @property \App\Model\Entity\Device $device
 * @property \App\Model\Entity\AnnouncementView[] $announcement_views
 * @property \App\Model\Entity\CatAffiliation[] $cat_affiliation
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\History[] $history
 * @property \App\Model\Entity\LikesDislike[] $likes_dislikes
 * @property \App\Model\Entity\List[] $lists
 * @property \App\Model\Entity\Media[] $media
 * @property \App\Model\Entity\MediaTransaction[] $media_transactions
 * @property \App\Model\Entity\Payment[] $payments
 * @property \App\Model\Entity\RegisterCode[] $register_code
 * @property \App\Model\Entity\Session[] $sessions
 * @property \App\Model\Entity\Subscription[] $subscriptions
 * @property \App\Model\Entity\UserAd[] $user_ads
 * @property \App\Model\Entity\UsrProfField[] $usr_prof_fields
 * @property \App\Model\Entity\VerificationRequest[] $verification_requests
 * @property \App\Model\Entity\VideoAd[] $video_ads
 * @property \App\Model\Entity\View[] $views
 * @property \App\Model\Entity\WithdrawalRequest[] $withdrawal_requests
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'password' => true,
        'fname' => true,
        'lname' => true,
        'age' => true,
        'mobile' => true,
        'email' => true,
        'active' => true,
        'completeSignup' => true,
        'registerDate' => true,
        'registerCode' => true,
        'registeredCode' => true,
        'registerPoints' => true,
        'access' => true,
        'gender' => true,
        'email_vcode' => true,
        'device_id' => true,
        'lang' => true,
        'thumbnail' => true,
        'cover' => true,
        'country' => true,
        'about' => true,
        'other_socials' => true,
        'allow_notify' => true,
        'verified' => true,
        'last_active' => true,
        'active_time' => true,
        'active_expire' => true,
        'pro' => true,
        'imports' => true,
        'uploads' => true,
        'wallet' => true,
        'balance' => true,
        'user_upload_limit' => true,
        'two_factor' => true,
        'last_month' => true,
        'device' => true,
        'announcement_views' => true,
        'cat_affiliation' => true,
        'comments' => true,
        'history' => true,
        'likes_dislikes' => true,
        'lists' => true,
        'media' => true,
        'media_transactions' => true,
        'payments' => true,
        'register_code' => true,
        'sessions' => true,
        'subscriptions' => true,
        'user_ads' => true,
        'usr_prof_fields' => true,
        'verification_requests' => true,
        'video_ads' => true,
        'views' => true,
        'withdrawal_requests' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];
}
