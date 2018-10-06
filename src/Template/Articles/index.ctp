<h1>Articles</h1>

<?= $this->Html->link('Add New Article', ['action' =>  'add']); ?> |
<?= $this->Html->link('View Tags', ['controller' => 'Tags']); ?>

<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
        <th>Action</th>
    </tr>
    <?php foreach($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' =>  'edit',$article->slug]); ?>
            |
            <?= $this->Form->postLink('Delete',
                [ 'action' =>  'delete', $article->slug ],
                [ 'confirm' => "Are you sure you want to delete '$article->title'?" ]);
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']); ?>
