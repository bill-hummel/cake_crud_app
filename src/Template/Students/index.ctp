
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Instructors'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Classes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Classes/Students'), ['controller' => 'SectionsStudents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>

    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h3>Student List</h3>
<table>
    <tr>
        <th>Student ID Number</th>
        <th>Student Name</th>
        <th>Major</th>

        <th>Action</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($students as $student): ?>
        <tr>
            <td>
                <?= $this->Html->link($student->studentnumber, ['action' => 'view', $student->studentnumber]) ?>
            </td>
            <td>
                <?= $student->firstname. " ".$student->lastname ?>
            </td>
            <td>
                <?= $student->major ?>
            </td>

            <td>
                <?= $this->Html->link('View', ['action' => 'view', $student->studentnumber]) ?>
                <?= $this->Html->link('Edit', ['action' => 'edit', $student->studentnumber]) ?>
                <?= $this->Form->postLink('Delete',
                                            ['action' => 'delete', $student->studentnumber],
                                            ['confirm' => 'Are you sure?']) ?>
            </td>



        </tr>
    <?php endforeach; ?>
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
