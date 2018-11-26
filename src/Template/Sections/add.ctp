<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Section $section
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Return to Sections List'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
    <?= $this->Form->create($section) ?>
    <fieldset>
        <legend><?= __('Add Section') ?></legend>
        <?php
            echo $this->Form->control('year');
            echo $this->Form->control('semesterid', ['type'=>'select','options' => $semester]);
            echo $this->Form->control('meetingdays');
            echo $this->Form->control('starttime');
            echo $this->Form->control('endtime');
            echo $this->Form->control('totalstudents');
            echo $this->Form->control('sectionid');
            echo $this->Form->control('instructorid', ['type'=>'select','options' => $instructors]);
            echo $this->Form->control('classid', ['type'=>'select','options' => $classes]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
