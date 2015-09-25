<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Modules\Blog\Model;

use \Fennec\Model\Base;

/**
 * Tags model
 *
 * @author David Lima
 * @version 1.0
 */
class Tags extends Base
{
    /**
     * Table to save data
     *
     * @var string
     */
    public static $table = "tags";
    
    /**
     * Tag title
     *
     * @var string
     */
    public $title;
    
    /**
     * Tag ID
     *
     * @var integer
     */
    public $id;
    
    /**
    
    * Saves a new array of tags
    *
    * @return array
    */
    public function learnTags(array $tags)
    {
        
        $newTags = $this->filter($tags);
        
        if (empty($newTags)) {
            return array(
                'result' => 'No new tags learned'
            );
        }
        
        try {
            foreach ($newTags as $tag) {
                $data = array(
                    'title' => $tag
                );
                
                $this->insert($data)
                    ->into(self::$table)
                    ->execute();
            }
            return array(
                'result' => 'New tags inserted!'
            );
        } catch (\Exception $e) {
            return array(
                'result' => 'Failed to insert tags!',
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Remove already known tags from array
     *
     * @param array $tags
     * @return array
     */
    private function filter(array $tags)
    {
        $tagsList = implode("','", $tags);
        $result = $this->select()
                        ->from(self::$table)
                        ->where("title IN ('{$tagsList}')")
                        ->execute()
                        ->fetchAll();

        $existentTags = array();

        foreach ($result as $existentTag) {
            $existentTags[] = $existentTag->getTitle();
        }

        $newTags = array_diff($tags, $existentTags);

        return $newTags;
    }
}
