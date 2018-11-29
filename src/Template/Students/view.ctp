<!-- File: src/Template/Articles/view.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h2>Student Record</h2>
<h3><?= "Student Name: ".h($student->firstname)."  " ?>  <?= h($student->lastname) ?></h3>
<h3><?=" IDN: ".h($student->studentnumber) ?></h3>
<table>
<tr>

    <th>Major</th>
    <th>Semester Credits</th>
    <th>Total Credits</th>
    <th>Semester GPA</th>
    <th>YTD GPA</th>
    <th>Action</th>
</tr>

<td>
    <p><?= h($student->major) ?></p>
</td>
<td>
    <p><?= h($student->semestercredits) ?></p>
</td>
<td>
    <p><?= h($student->totalcredits) ?></p>
</td>
<td>
    <p><?= h($student->semestergpa) ?></p>
</td>
<td>
    <p><?= h($student->gpa) ?></p>
</td>
<td>
    <?= $this->Html->link('<List>', ['action' => 'index', $student->studentnumber]) ?>
    <?= $this->Html->link('<Edit>', ['action' => 'edit', $student->studentnumber]) ?>
    <?= $this->Form->postLink('Delete',
        ['action' => 'delete', $student->studentnumber],
        ['confirm' => 'Are you sure?']) ?>
</td>
</table>

    <div class="related">
        <h4><?= __('Related Classes') ?></h4>
        <?php if (!empty($student->sections)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Year') ?></th>
                <th scope="col"><?= __('Semester') ?></th>
                <th scope="col"><?= __('Days') ?></th>
                <th scope="col"><?= __('Time') ?></th>
                <th scope="col"><?= __('Section') ?></th>
                <th scope="col"><?= __('Course name') ?></th>
                <th scope="col"><?= __('Instructor') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($student->sections as $section): ?>
            <tr>
                <td><?= h($section->year) ?></td>
                <td><?= h($section->semester->semestername) ?></td>
                <td><?= h($section->meetingdays) ?></td>
                <td><?= h($section->starttime.' - '.$section->endtime) ?></td>
                <td><?= h($section->sectionid) ?></td>
                <td><?= h($section->class->coursename) ?></td>
                <td><?= h($section->instructor->lastname) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SectionsStudents', 'action' => 'view', $section->_joinData->id]) ?>
                    <?= $this->Html->link(__('Edit Grade'), ['controller' => 'SectionsStudents', 'action' => 'edit', $section->_joinData->id]) ?>
                    <?= $this->Html->link(__('Delete'), ['controller' => 'SectionsStudents', 'action' => 'delete',  $section->_joinData->id], ['confirm' => __('Are you sure you want to delete this course for # {0}?', $student->studentnumber)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
