<!-- File: src/Template/Articles/add.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Classes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Course'), ['controller' => 'Students', 'action' => 'add']) ?></li>    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h1>Update Course Information</h1>
<?php
//begin form
echo $this->Form->create($class);

//add data for specific fields
echo $this->Form->control('subject', ['label' => 'Subject or Department']);
echo $this->Form->control('coursenumber', ['label' => 'Course Number']);
echo $this->Form->control('coursename', ['label' => 'Course Name']);
echo $this->Form->control('coursedescription', ['label' => 'Course Description', 'rows' => '3']);
echo $this->Form->control('credits', ['label' => 'Credits']);

//save button
echo $this->Form->button(__('Save Course')); //submit back to current action

//end form
echo $this->Form->end();
?>
</div>
