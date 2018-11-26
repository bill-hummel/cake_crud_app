<!-- File: src/Template/Articles/add.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
       <li><?= $this->Html->link(__('Return to Instructors List'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h1>Add a new Instructor</h1>
<?php
//begin form
echo $this->Form->create($instructor);

//add data for specific fields
echo $this->Form->control('firstname', ['label' => 'First Name']);
echo $this->Form->control('lastname', ['label' => 'Last Name']);
echo $this->Form->control('department', ['label' => 'Department']);

//save button
echo $this->Form->button(__('Add Instructor')); //submit back to current action

//end form
echo $this->Form->end();
?>
</div>
