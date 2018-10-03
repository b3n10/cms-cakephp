<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ArticlesTable extends Table
{
    /**
     * Initialize the config
     *
     * @param array $config config
     *
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
}
