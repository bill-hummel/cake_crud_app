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

                <th scope="col">Semester</th>
                <th scope="col">Abbreviation</th>
                <th scope="col">ID</th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>

            <tr>

                <td><?= h($semester->semestername) ?></td>
                <td><?= h($semester->semesterabr) ?></td>
                <td><?= $this->Number->format($semester->semestercurrent) ?></td>
                <td class="actions">

                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $semester->id]) ?>

            </tr>

        </tbody>
    </table>

</div>
