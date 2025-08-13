<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        $postsHtml = '';

        foreach ($context->posts as $post) {
            $title = htmlspecialchars($post->title);
            $author = htmlspecialchars($post->author);
            $id = htmlspecialchars($post->id);

            $postsHtml .= <<<HTML
                <li>
                    <a href="/posts/{$id}">{$title}</a> by {$author}
                </li>
            HTML;
        }

        return <<<HTML
            <h1>All Posts</h1>
            <ul>
                {$postsHtml}
            </ul>
        HTML;
    }
}
