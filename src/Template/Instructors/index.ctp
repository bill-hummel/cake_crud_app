<!-- File: src/Template/Articles/index.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Classes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Classes/Students'), ['controller' => 'SectionsStudents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Instructor'), ['controller' => 'Instructors', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('View/Edit Semester Information'), ['controller' => 'Semester', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>

    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h1>Instructor List</h1>
<table>
    <tr>
        <th>Instructor ID</th>
        <th>Instructor Name</th>
        <th>Department</th>

        <th>Action</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($instructors as $instructor): ?>
        <tr>
            <td>
                <?= $instructor->id ?>
            </td>
            <td>
                <?= $instructor->firstname." ".$instructor->lastname ?>
            </td>
            <td>
                <?= $instructor->department ?>
            </td>

            <td>
                <?= $this->Html->link('View', ['action' => 'view', $instructor->id]) ?>
                <?= $this->Html->link('Edit', ['action' => 'edit', $instructor->id]) ?>
                <?= $this->Form->postLink('Delete',
                                            ['action' => 'delete', $instructor->id],
                                            ['confirm' => 'Are you sure?']) ?>
            </td>



        </tr>
    <?php endforeach; ?>

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
</table>
</div>
