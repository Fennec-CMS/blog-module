<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Modules\Blog\Controller\Admin;

use \Fennec\Controller\Admin\Index as AdminController;

/**
 * Sample custom module (Admin controller)
 *
 * @author David Lima
 * @version b0.1
 */
class Index extends AdminController
{

    /**
     * Default action
     */
    public function indexAction()
    {
        $this->text = "I'm a blog module";
    }
}
