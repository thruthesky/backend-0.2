<?php
/**
 * @see README.md
 */
namespace model\post;

class Post_Vote extends Post
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('post_vote');
    }

}
