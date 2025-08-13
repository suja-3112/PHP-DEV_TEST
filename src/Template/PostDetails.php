<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostDetails extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
        <h1>{$context->postTitle}</h1>
        <p><em>By {$context->postAuthor}</em></p>
        <article>
            {$context->postBodyHtml}
        </article>
        HTML;
    }
}
