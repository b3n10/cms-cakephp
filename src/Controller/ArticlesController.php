<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    /**
     * Load index page
     *
     * @return void
     */
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(['articles' => $articles]);
    }

    /**
     * Init
     *
     * @return void
     *
     public function initialize()
     {
         $this->Auth->allow(['tags']);
     }
     */

    /**
     * Load view page
     *
     * @param string $slug URL of selected slug
     * @return void
     */
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * Create new articles
     *
     * @return redirect
     */
    public function add()
    {
        // create Articles object to save new data
        $article = $this->Articles->newEntity();

        if ($this->request->is('post')) {
            // patchEntity updates $article using fields from $this->request->getData()
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            $article->user_id = $this->Auth->user('id');

            // save the changes of patchEntity
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to add your article!'));
        }
        // not find a list, but return a list of tags
        $tags = $this->Articles->Tags->find('list');

        // if not POST, show view passing $article and $tags
        $this->set(compact('article', 'tags'));
    }

    /**
     * Edit an article
     *
     * @param string $slug slug of article
     *
     * @return redirect
     */
    public function edit($slug)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            // patchEntity updates $article using fields from $this->request->getData()
            $this->Articles->patchEntity($article, $this->request->getData(), [
                'accessbileFields' => ['user_id' => false]
            ]);

            // save the changes of patchEntity
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to update your article!'));
        }
        // not find a list, but return a list of tags
        $tags = $this->Articles->Tags->find('list');

        // if not POST, show view passing $article and $tags
        $this->set(compact('article', 'tags'));
    }

    /**
     * Delete $article
     *
     * @param string $slug slug of article
     *
     * @return redirect
     */
    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();

        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('Your article "{0}" has been deleted!', $article->title));

            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error(__('Unable to delete your article!'));
    }

    /**
     * Get articles by tag
     *
     * @param object $tags tags
     *
     * @return void
     */
    public function tags(...$tags)
    {
        // The 'pass' key is provided by CakePHP and contains all
        // the passed URL path segments in the request.
        // $tags = $this->request->getParam('pass');

        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ]);

        $this->set(compact('tags', 'articles'));
    }

    /**
     * Check user login
     *
     * @param object $user user
     *
     * @return bool
     */
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        if (in_array($action, ['add', 'tags'])) {
            return true;
        }

        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }

        $article = $this->Articles->findBySlug($slug)->first();

        return $article->user_id === $user['id'];
    }
}
