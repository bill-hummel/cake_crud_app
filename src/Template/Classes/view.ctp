<!-- File: src/Template/Articles/view.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Classes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Course'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h2>Course Information</h2>
<h3><?= "Course Name: ".h($class->coursename) ?></h3>
<h3><?= "Course Number: ".h($class->coursenumber) ?></h3>
<table>
<tr>

    <th>subject</th>
    <th>Credits</th>
    <th>Course Description</th>
    <th>Action</th>

</tr>

<td>
    <p><?= h($class->subject) ?></p>
</td>
<td>
    <p><?= h($class->credits) ?></p>
</td>
<td>
    <p><?= h($class->coursedescription) ?></p>
</td>

<td>
    <?= $this->Html->link('<List>', ['action' => 'index', $class->coursenumber]) ?>
    <?= $this->Html->link('<Edit>', ['action' => 'edit', $class->coursenumber]) ?>
    <?= $this->Form->postLink('Delete',
        ['action' => 'delete', $class->coursenumber],
        ['confirm' => 'Are you sure?']) ?>
</td>
</table>

    <div class="related">
        <h4><?= __('Related Sections') ?></h4>
        <?php if (!empty($class->sections)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Section') ?></th>
                <th scope="col"><?= __('Semester') ?></th>
                <th scope="col"><?= __('Days') ?></th>
                <th scope="col"><?= __('Meeting Time') ?></th>
                <th scope="col"><?= __('Instructor') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($class->sections as $section): ?>
            <tr>
                <td><?= h($section->sectionid) ?></td>
                <td><?= h($section->semester->semestername) ?></td>
                <td><?= h($section->meetingdays) ?></td>
                <td><?= h($section->starttime.' - '.$section->endtime) ?></td>
                <td><?php if($section->instructor==null){
                    echo 'No instructor';
                    }
                    else {
                    echo h($section->instructor->lastname);
                    }

                    ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Sections', 'action' => 'view', $section->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SectionsStudents', 'action' => 'edit', $section->id]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
