<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Semester[]|\Cake\Collection\CollectionInterface $semester
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Semester'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="semester index large-9 medium-8 columns content">
    <h3><?= __('Semester') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('semestername') ?></th>
                <th scope="col"><?= $this->Paginator->sort('semesterabr') ?></th>
                <th scope="col"><?= $this->Paginator->sort('semestercurrent') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($semester as $semester): ?>
            <tr>
                <td><?= $this->Number->format($semester->id) ?></td>
                <td><?= h($semester->semestername) ?></td>
                <td><?= h($semester->semesterabr) ?></td>
                <td><?= $this->Number->format($semester->semestercurrent) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $semester->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $semester->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $semester->id], ['confirm' => __('Are you sure you want to delete # {0}?', $semester->id)]) ?>
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
