<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\Url;

use Decarte\Shop\Entity\BlogPost;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class BlogPostUrl
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generate(BlogPost $post, bool $absolute = false): string
    {
        return $this->router->generate('blog_post', [
            'slugName' => $post->getName(),
        ], $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}
