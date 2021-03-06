<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Semester $semester
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Semester'), ['action' => 'edit', $semester->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Semester'), ['action' => 'delete', $semester->id], ['confirm' => __('Are you sure you want to delete # {0}?', $semester->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Semester'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Semester'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="semester view large-9 medium-8 columns content">
    <h3><?= h($semester->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Semestername') ?></th>
            <td><?= h($semester->semestername) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Semesterabr') ?></th>
            <td><?= h($semester->semesterabr) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($semester->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Semestercurrent') ?></th>
            <td><?= $this->Number->format($semester->semestercurrent) ?></td>
        </tr>
    </table>
</div>
