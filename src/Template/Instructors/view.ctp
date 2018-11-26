<!-- File: src/Template/Articles/view.ctp -->
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Instructors'), ['controller' => 'Instructors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Instructor'), ['controller' => 'Instructors', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
<h2>Instructor Information</h2>

<table>
    <tr>
        <td><h3><?= "Instructor Name: ".h($instructor->firstname)." ".h($instructor->lastname) ?></h3></td>
        <td><h3><?= "Department: ".h($instructor->department) ?></h3></td>
    </tr>
</table>

<table>
<tr>

    <th>Semester class total</th>
    <th>Year class total</th>
    <th>Action</th>

</tr>

<td>
    <p><?= h($instructor->semesterclasses) ?></p>
</td>
<td>
    <p><?= h($instructor->totalclasses) ?></p>
</td>

<td>
    <?= $this->Html->link('List', ['action' => 'index', $instructor->id]) ?>
    <?= $this->Html->link('Edit', ['action' => 'edit', $instructor->id]) ?>
    <?= $this->Form->postLink('Delete',
        ['action' => 'delete', $instructor->id],
        ['confirm' => 'Are you sure?']) ?>
</td>
</table>

    <div class="related">
        <h4><?= __('Related Sections') ?></h4>
        <?php if (!empty($instructor->sections)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Year') ?></th>
                <th scope="col"><?= __('Semester') ?></th>
                <th scope="col"><?= __('Course Name') ?></th>
                <th scope="col"><?= __('Section') ?></th>
                <th scope="col"><?= __('Time') ?></th>
                <th scope="col"><?= __('Student Totals') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($instructor->sections as $section): ?>
            <tr>
                <td><?= h($section->year) ?></td>
                <td><?= h($section->semester->semesterabr) ?></td>
                <td><?= h($section->class->coursename) ?></td>
                <td><?= h($section->sectionid) ?></td>
                <td><?= h($section->starttime.' - '.$section->endtime) ?></td>
                <td><?= h($section->totalstudents) ?></td>

                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Sections', 'action' => 'view', $section->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Sections', 'action' => 'edit', $section->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sections', 'action' => 'delete', $section->id], ['confirm' => __('Are you sure you want to delete # {0}?',$section->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
