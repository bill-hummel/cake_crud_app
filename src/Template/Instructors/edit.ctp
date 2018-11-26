<!-- File: src/Template/Articles/add.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Instructors'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Instructor'), ['controller' => 'Instructors', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h1>Update Instructor Information</h1>
<?php
//begin form
echo $this->Form->create($instructor);

///add data for specific fields

echo $this->Form->control('id', ['type' => 'hidden']);
echo $this->Form->control('firstname', ['label' => 'First Name']);
echo $this->Form->control('lastname', ['label' => 'Last Name']);
echo $this->Form->control('department', ['label' => 'Department']);

//save button
echo $this->Form->button(__('Update Instructor')); //submit back to current action
?>
</div>
