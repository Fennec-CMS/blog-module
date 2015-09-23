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
use \Fennec\Services\Settings;

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
     * Default number of posts shown on listing
     *
     * @var integer
     */
    const DEFAULT_POSTS_PER_PAGE = 10;

    /**
     * Blog Model
     *
     * @var \Fennec\Modules\Blog\Model\Blog
     */
    private $model;
    
    /**
     * Settings object
     * @var Settings $settings
     */
    public $settings;

    /**
     * Defines $this->model
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->settings = new Settings('Blog');
        
        $this->model = new BlogModel();
    }

    /**
     * Default action
     */
    public function indexAction()
    {
        $title = "Blog module";
        if ($this->getParam('page')) {
            $page = intval($this->getParam('page'));
            $title .= " :: Page $page";
        } else {
            $page = 1;
        }

        $this->setTitle($title);
        
        $postsPerPage = $this->settings->getSetting('postsPerPage');
        if (! $postsPerPage) {
            $postsPerPage = self::DEFAULT_POSTS_PER_PAGE;
        }

        $this->posts = $this->model->getActivePosts($postsPerPage, $page);
        $this->totalPosts = $this->model->countArticles();
        $this->totalPages = ceil($this->totalPosts / $postsPerPage);
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
                $this->setTitle($this->post->getTitle());
            } else {
                $this->throwHttpError(404);
            }
        }
    }
}
