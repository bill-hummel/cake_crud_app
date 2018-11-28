<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Section $section
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
    <?= $this->Form->create($section) ?>
    <fieldset>
        <legend><?= __('Edit Section '.$section->sectionid.' of class '.$section->class->coursename) ?></legend>
        <?php
             echo $this->Form->control('year',['label'=>'Year','disabled'=>'true']);
        echo $this->Form->control('semesterid', ['type'=>'select','options' => $semester,'label'=>'Semester','disabled'=>'true']);
        echo $this->Form->control('meetingdays',['label'=>'Meeting Days','disabled'=>'true']);
        echo $this->Form->control('starttime',['label'=>'Start Time','disabled'=>'true']);
        echo $this->Form->control('endtime',['label'=>'End Time','disabled'=>'true']);
        echo $this->Form->control('totalstudents',['label'=>'Max Class Size','disabled'=>'true']);
        echo $this->Form->control('instructorid', ['type'=>'select','label'=>'Instructor','options' => $instructors]);

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
