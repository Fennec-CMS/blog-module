<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Modules\Blog\Controller;

use \Fennec\Controller\Base;
use \Fennec\Modules\Blog\Model\Blog as BlogModel;

/**
 * Blog module
 *
 * @author David Lima
 * @version b0.1
 */
class Index extends Base
{
    use \Fennec\Library\Urls;

    /**
     * Blog Model
     *
     * @var \Fennec\Modules\Blog\Model\Blog
     */
    private $model;

    /**
     * Defines $this->model
     */
    public function __construct()
    {
        $this->model = new BlogModel();
    }

    /**
     * Default action
     */
    public function indexAction()
    {
        $this->posts = $this->model->getActivePosts();
    }

    /**
     * If $_GET['slug'] is a valid post, sets it to $this->post
     */
    public function readAction()
    {
        if ($this->getParam('slug')) {
            $slug = $this->toSlug($this->getParam('slug'));
            $post = $this->model->getByColumn('url', $slug);

            if (count($post)) {
                $this->post = $post[0];
            } else {
                $this->throwHttpError(404);
            }
        }
    }
}
