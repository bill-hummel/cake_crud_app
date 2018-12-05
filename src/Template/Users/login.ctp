<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Section[]|\Cake\Collection\CollectionInterface $sections
*/
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Course Information'), ['controller' => 'classes', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sections index large-9 medium-8 columns content">
    <h1>Login</h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('password') ?>
    <?= $this->Form->button('Login') ?>
    <?= $this->Form->end() ?>
</div>

