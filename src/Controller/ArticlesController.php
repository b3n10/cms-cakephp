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

            // temp user_id
            $article->user_id = 1;

            // save the changes of patchEntity
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to add your article!'));
        }

        // if not POST, show view passing $article
        $this->set(compact('article'));
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
        $article = $this->Articles->findBySlug($slug)->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            // patchEntity updates $article using fields from $this->request->getData()
            $this->Articles->patchEntity($article, $this->request->getData());

            // save the changes of patchEntity
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to update your article!'));
        }

        // if not POST, show view passing $article
        $this->set(compact('article'));
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
}
