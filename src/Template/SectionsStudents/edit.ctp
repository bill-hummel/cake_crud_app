<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionsStudent $sectionsStudent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Classes/Students'), ['controller' => 'SectionsStudents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Add a student to a class'), ['controller' => 'SectionsStudents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sectionsStudents form large-9 medium-8 columns content">
    <?= $this->Form->create($sectionsStudent) ?>
    <fieldset>
        <legend><?= __('Edit Grade for '.$sectionsStudent->student->firstname.'  '.
            $sectionsStudent->student->lastname.'  '.$sectionsStudent->student->studentnumber) ?></legend>
        <h4><?= $sectionsStudent->section->sectionid.'  '.$sectionsStudent->section->class->coursename; ?></h4>
        <?php

            //echo $this->Form->control('sectionid');
            ///echo $this->Form->control('studentid');
            echo $this->Form->control('lettergrade');
            //echo $this->Form->control('numericgrade');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
