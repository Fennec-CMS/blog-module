<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Modules\Blog\Model;

use \Fennec\Model\Base;
use \Fennec\Library\PHPImageWorkshop\ImageWorkshop;

/**
 * Blog model
 *
 * @author David Lima
 * @version b0.1
 */
class Blog extends Base
{
    use \Fennec\Library\Urls;

    /**
     * Path to send uploads (must have write permissions)
     *
     * @var string
     */
    const UPLOAD_DIR = parent::UPLOAD_BASE_DIR . 'blog';

    /**
     * Table to save data
     *
     * @var string
     */
    public static $table = "blog";

    /**
     * Post title
     *
     * @var string
     */
    public $title;

    /**
     * Post custom URL
     *
     * @var string
     */
    public $url;

    /**
     * Post tags
     *
     * @var string
     */
    public $tags;

    /**
     * Short description for SEO (max 150 characters)
     *
     * @var string
     */
    public $seodescription;

    /**
     * Post preview text
     *
     * @var string
     */
    public $preview;

    /**
     * Post complete text
     *
     * @var string
     */
    public $body;

    /**
     * Post author ID
     *
     * @var integer
     */
    public $author;

    /**
     * Post timestamp
     *
     * @var string
     */
    public $timestamp;

    /**
     * Post pubdate
     *
     * @var string
     */
    public $publishdate;

    /**
     * Post ID
     *
     * @var integer
     */
    public $id;
    
    /**
     * Post status
     *
     * @var boolean
     */
    public $status;

    /**
     * All image sizes to save
     *
     * @var array
     */
    protected $imageSizes = array(
        'small' => array(
            'width' => 150,
            'height' => 150
        ),
        'medium' => array(
            'width' => 400,
            'height' => 400
        ),
        'large' => array(
            'width' => 600,
            'height' => 600
        ),
        'small-wide' => array(
            'width' => 200,
            'height' => 100
        ),
        'medium-wide' => array(
            'width' => 400,
            'height' => 200
        ),
        'large-wide' => array(
            'width' => 600,
            'height' => 300
        )
    );

    /**
     * Creates a new post
     *
     * @return PDOStatement
     */
    public function create()
    {
        $data = $this->prepare();

        if (isset($data['valid']) && ! $data['valid']){
            return $data;
        } else {
            try {
                if ($this->id) {
                    $post = $this->getByColumn('id', $this->id)[0];
                    $query = $this->update(self::$table)
                        ->set($data)
                        ->where("id = '{$this->id}'")
                        ->execute();
                } else {
                    $query = $this->insert($data)
                        ->into(self::$table)
                        ->execute();
                    
                    $this->id = $query;
                }

                if ($this->id && isset($_FILES['image']) && ! empty($_FILES['image']['name'])) {
                    $image = ImageWorkshop::initFromPath($_FILES['image']['tmp_name']);

                    foreach ($this->imageSizes as $name => $dimensions) {
                        $resizedImage = clone $image;

                        if ($dimensions['width'] > $dimensions['height']) {
                            $resizedImage->resizeInPixel($dimensions['width'], false, true, 0, 0, 'MM');
                        } else {
                            $resizedImage->resizeInPixel(false, $dimensions['height'], true, 0, 0, 'MM');
                        }

                        $resizedImage->cropInPixel($dimensions['width'], $dimensions['height'], 0, 0, 'MM');

                        $resizedImage->save(self::UPLOAD_DIR, $this->id . '-' . $name . '.png', true);
                    }
                }

                if ($this->id && isset($_FILES['image-social']) && ! empty($_FILES['image-social']['name'])) {
                    $image = ImageWorkshop::initFromPath($_FILES['image-social']['tmp_name']);
                    $image->resizeInPixel(500, false, true, 0, 0, 'MM');
                    $image->cropInPixel(500, 500, 0, 0, 'MM');
                    $image->save(self::UPLOAD_DIR, $this->id . '-' . 'social.png', true);
                }

                return array(
                    'result' => (isset($post) ? 'Post updated!' : 'Post published!')
                );
            } catch (\Exception $e) {
                return array(
                    'result' => 'Failed to publish post!',
                    'errors' => array($e->getMessage())
                );
            }
        }
    }
    
    /**
     * Return all active posts (status == true and publishdate <- now)
     *
     * @param number $limit
     * @param number $page
     * @return array
     */
    public function getActivePosts($limit = 10, $page = 1)
    {
        $page--;

        $offset = $limit * $page;

        return $this->select('*')
                ->from(self::$table)
                ->where('status AND publishdate <= NOW()')
                ->limit($limit)
                ->offset($offset)
                ->order('publishdate', 'DESC')
                ->execute()
                ->fetchAll();
    }

    /**
     * Return the total of posts
     *
     * @param boolean $includeInactive
     * @return integer
     */
    public function countArticles($includeInactive = false)
    {
        $select = $this->select("COUNT(*) AS total")
                ->from(self::$table);

        if (! $includeInactive) {
            $select->where('status');
        }

        $total = $select->execute()->fetchColumn(0);
        return $total;
    }

    /**
     * (non-PHPdoc)
     * @see \Fennec\Model\Base::getAll()
     */
    public function getAll()
    {
        return $this->select("*")
            ->from(self::$table)
            ->order('publishdate', 'DESC')
            ->execute();
    }

    /**
     * Prepare data to create administrator
     *
     * @return multitype:string |multitype:\Fennec\Model\string \Fennec\Model\integer
     */
    private function prepare()
    {
        $errors = $this->validate();
        if (! $errors['valid']) {
            return $errors;
        }

        $this->title = filter_var($this->title, \FILTER_SANITIZE_STRING);
        $this->tags = filter_var($this->tags, \FILTER_SANITIZE_STRING);
        $this->author = $_SESSION['fennecAdmin']->getId();
        $this->publishdate = filter_var($this->publishdate, \FILTER_SANITIZE_STRING);
        $this->timestamp = (empty($this->timestamp) ? date("Y-m-d H:i:s") : $this->timestamp);
        $this->url = $this->toSlug(empty($this->url) ? $this->title : $this->url);
        $this->status = $this->status ? 1 : 0;
        $this->seodescription = strip_tags($this->seodescription);

        return array(
            'title' => $this->title,
            'tags' => $this->tags,
            'url' => $this->url,
            'seodescription' => $this->seodescription,
            'preview' => $this->preview,
            'body' => $this->body,
            'author' => $this->author,
            'publishdate' => $this->publishdate,
            'timestamp' => $this->timestamp,
            'status' => $this->status
        );
    }

    /**
     * Validate post data
     *
     * @return multitype:string
     */
    private function validate()
    {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if (! $this->title) {
            $validation['valid'] = false;
            $validation['errors']['title'] = "Title is a required field";
        }

        if (! $this->body) {
            $validation['valid'] = false;
            $validation['errors']['body'] = "Body is a required field";
        }

        return $validation;
    }
}
