<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Semester $semester
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>

        <li><?= $this->Html->link(__('List Semester'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="semester form large-9 medium-8 columns content">
    <?= $this->Form->create($semester) ?>
    <fieldset>
        <legend><?= __('Edit Semester') ?></legend>
        <?php
            echo h('Current Semester: '.$semester->semestername);
            echo $this->Form->control('semestercurrent');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
