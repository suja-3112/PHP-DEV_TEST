<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\Model;

class PostDetails extends Controller
{
    private ?Model\Post $post = null;

    public function getContext(): Context
    {
        $context = new Context();

        if ($this->post === null) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
        } else {
            $context->title = $this->post->title;
            $context->postTitle = $this->post->title;
            $context->postAuthor = $this->post->author;
            $context->postBodyHtml = $this->convertMarkdownToHtml($this->post->body);
        }

        return $context;
    }

    // convert Markdown to HTML
    protected function convertMarkdownToHtml(string $markdown): string
    {
        $converter = new \League\CommonMark\CommonMarkConverter();
        return $converter->convertToHtml($markdown);
    }


    public function getTemplate(): Template\Template
    {
        if ($this->post === null) {
            return new Template\NotFound();
        }

        return new Template\PostDetails();
    }

    public function getStatus(): string
    {
        if ($this->post === null) {
            return $this->getProtocol() . ' 404 Not Found';
        }

        return $this->getProtocol() . ' 200 OK';
    }

    protected function loadData(): void
    {
        $postId = $this->params[0] ?? null;
        if ($postId === null) {
            $this->post = null;
            return;
        }

        $stmt = $this->db->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $postId]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            $this->post = null;
            return;
        }

        $post = new \silverorange\DevTest\Model\Post();
        $post->id = $data['id'];
        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->created_at = $data['created_at'];
        $post->modified_at = $data['modified_at'];
        $post->author = $data['author'];

        $this->post = $post;
    }
}
