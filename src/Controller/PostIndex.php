<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Template;

class PostIndex extends Controller
{
    private array $posts = [];

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts';
        $context->posts = $this->posts;
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostIndex();
    }

    protected function loadData(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM posts ORDER BY created_at DESC');
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $post = new Post();
            $post->id = $row['id'];
            $post->title = $row['title'];
            $post->body = $row['body'];
            $post->created_at = $row['created_at'];
            $post->modified_at = $row['modified_at'];
            $post->author = $row['author'];
            $this->posts[] = $post;
        }
    }
}
