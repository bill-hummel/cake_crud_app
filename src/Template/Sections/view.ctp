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
<div class="sections view large-9 medium-8 columns content">
    <h3><?= h($section->class->coursename." - Section: ".$section->sectionid) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Semester') ?></th>
            <td><?= $section->semester->semestername ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Meetingdays') ?></th>
            <td><?= h($section->meetingdays) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Starttime') ?></th>
            <td><?= h($section->starttime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Endtime') ?></th>
            <td><?= h($section->endtime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sectionid') ?></th>
            <td><?= h($section->sectionid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Year') ?></th>
            <td><?= h($section->year) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Totalstudents') ?></th>
            <td><?= $this->Number->format($section->totalstudents) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Instructorid') ?></th>
            <td><?= $section->instructor->firstname." ".$section->instructor->lastname ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Students') ?></h4>
        <?php if (!empty($section->students)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                 <th scope="col"><?= __('Studentnumber') ?></th>
                <th scope="col"><?= __('Firstname') ?></th>
                <th scope="col"><?= __('Lastname') ?></th>
                <th scope="col"><?= __('Major') ?></th>
                <th scope="col"><?= __('Semestercredits') ?></th>
                <th scope="col"><?= __('Totalcredits') ?></th>
                <th scope="col"><?= __('Semestergpa') ?></th>
                <th scope="col"><?= __('Gpa') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($section->students as $students): ?>
            <tr>
                <td><?= h($students->studentnumber) ?></td>
                <td><?= h($students->firstname) ?></td>
                <td><?= h($students->lastname) ?></td>
                <td><?= h($students->major) ?></td>
                <td><?= h($students->semestercredits) ?></td>
                <td><?= h($students->totalcredits) ?></td>
                <td><?= h($students->semestergpa) ?></td>
                <td><?= h($students->gpa) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->studentnumber]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->studentnumber]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->studentnumber)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
