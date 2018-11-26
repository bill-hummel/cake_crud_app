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
<div class="sectionsStudents view large-9 medium-8 columns content">
    <h3><?= h($sectionsStudent->student->studentnumber.'  '.$sectionsStudent->student->firstname.'  '.$sectionsStudent->student->lastname.'  ') ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Class') ?></th>
            <td><?= $sectionsStudent->section->class->coursename.' Section '.$sectionsStudent->section->sectionid; ?></td>
        </tr>

        <tr>
            <th scope="row"><?= __('Lettergrade') ?></th>
            <td><?= h($sectionsStudent->lettergrade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Numericgrade') ?></th>
            <td><?= h($sectionsStudent->numericgrade) ?></td>
        </tr>
    </table>
</div>
