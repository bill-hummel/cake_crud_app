<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionsStudent[]|\Cake\Collection\CollectionInterface $sectionsStudents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Instructors'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Classes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Add a Student to a Class'), ['controller' => 'SectionsStudents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>

    </ul>
</nav>
<div class="sectionsStudents index large-9 medium-8 columns content">
    <h3><?= __('Sections Students') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>

                <th scope="col"><?= $this->Paginator->sort('sectionid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('studentid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lettergrade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('numericgrade') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sectionsStudents as $sectionsStudent): ?>
            <tr>


                <td><?= h($sectionsStudent->section->class->coursename.' '.$sectionsStudent->section->sectionid.' '.$sectionsStudent->section->semester->semesterabr) ?></td>

                <td><?= h($sectionsStudent->student->firstname.' '.$sectionsStudent->student->lastname) ?></td>
                <td><?= h($sectionsStudent->lettergrade) ?></td>
                <td><?= $this->Number->format($sectionsStudent->numericgrade) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sectionsStudent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sectionsStudent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sectionsStudent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sectionsStudent->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
