<!-- File: src/Template/Articles/add.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
<ul class="side-nav">
    <li class="heading"><?= __('Actions') ?></li>
    <li><?= $this->Html->link(__('Back to Student List'), ['controller' => 'Students', 'action' => 'index']) ?></li>
</ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h1>Add a new student</h1>
<?php
//begin form
echo $this->Form->create($student);

//add data for specific fields
echo $this->Form->control('studentnumber', ['label' => 'Student ID Number']);
echo $this->Form->control('firstname', ['label' => 'Student First Name']);
echo $this->Form->control('lastname', ['label' => 'Student Last Name']);
echo $this->Form->control('major', ['label' => 'Student Major']);

//save button
echo $this->Form->button(__('Save Student')); //submit back to current action

//end form
echo $this->Form->end();

?>
</div>
