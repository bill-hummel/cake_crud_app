<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Semester[]|\Cake\Collection\CollectionInterface $semester
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Instructors'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Classes/Students'), ['controller' => 'SectionsStudents', 'action' => 'index']) ?></li>
         <li><?= $this->Html->link(__('Edit Semester Information'), ['controller' => 'Semester', 'action' => 'edit']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>

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
