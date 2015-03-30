<?php


namespace Smilesrg\PipelineParserBundle\Parser;

use FOS\CommentBundle\Markup\ParserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class UserMentionParser implements ParserInterface
{
    const LINK_PATTERN = '<a href="%s">%s</a>';

    /** @var string Regex pattern for user mentioning */
    private $pattern;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var string route */
    private $route;

    /** @var \FOS\UserBundle\Model\UserManagerInterface */
    private $userManager;

    public function __construct(UserManagerInterface $userManager, RouterInterface $router, $route, $pattern)
    {
        $this->userManager = $userManager;
        $this->router = $router;
        $this->route = $route;
        $this->pattern = $pattern;
    }

    public function parse($raw)
    {
        $callback = function($matches) {
            $username = $matches[1];
            if ($this->userManager->findUserByUsername($username)) {
                $path = $this->router->generate($this->route, array('username' => $username));
                $result = sprintf(self::LINK_PATTERN, $path, $matches[0]);
                return $result;
            }
            return $matches[0];
        };

        return preg_replace_callback($this->pattern, $callback, $raw);
    }
}
