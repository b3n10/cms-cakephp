<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

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
        $this->belongsToMany('Tags');
    }

    /**
     * Populate slug before saving
     *
     * @param object $event check if new entity
     * @param object $entity get slug
     * @param array $options some options
     *
     * @return void
     */
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    /**
     * Build tags
     *
     * @param string $tagString tag string
     *
     * @return array
     */
    protected function _buildTags($tagString)
    {
        $newTags = array_map('trim', explode(',', $tagString));
        $newTags = array_filter($newTags);
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags]);

        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }

        foreach ($query as $tag) {
            $out[] = $tag;
        }

        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
    }

    /**
     * Validate input
     *
     * @param object $validator validator object
     *
     * @return object
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->notEmpty('body')
            ->minLength('body', 10);

        return $validator;
    }

    /**
     * Find distinct articles that have a ‘matching’ tag.
     *
     * @param object $query Query builder
     * @param array $options parameters for $query
     *
     * @return object
     */
    public function findTagged(Query $query, array $options)
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug'
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            $query
                ->leftJoinWith('Tags')
                ->where(['Tags.title IS' => null]);
        } else {
            $query
                ->innerJoinWith('Tags')
                ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['Articles.id']);
    }
}
