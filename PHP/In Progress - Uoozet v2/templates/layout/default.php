<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
//$uoozet->config = $this->get('config');
//$uoozet->page = $this->get('page');
//$uoozet->user = $this->get('user');
//print_r($uoozet);
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>
    <?= $this->Html->script([
        'jquery-3.min', 'jquery-ui.min', 'jquery.form.min',
        'functions.alireza',
        'header', 'footer',
        'bootstrap.min', 'bootstrap-select.min', 'bootstrap-toggle.min',
        'lib/sweetalert2/dist/sweetalert2.js', 'lib/notifIt/notifIt/js/notifIt.min', 'lib/socket.io/socket.io.2.0.4.js',
        'owl.carousel.min', 'Fingerprintjs2/fingerprint2',
        ]); ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?= $this->fetch('player') ?>
</head>
<body>
<!--    <nav class="top-nav">-->
<!--        <div class="top-nav-title">-->
<!--            <a href="--><?php //echo $this->Url->build('/') ?><!--"><span>Cake</span>PHP</a>-->
<!--        </div>-->
<!--        <div class="top-nav-links">-->
<!--            <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Documentation</a>-->
<!--            <a target="_blank" rel="noopener" href="https://api.cakephp.org/">API</a>-->
<!--        </div>-->
<!--    </nav>-->
    <?= $this->element('header'); ?>

    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <?= $this->element('footer'); ?>
</body>
</html>
