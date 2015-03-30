<?php

namespace Smilesrg\PipelineParserBundle\Parser;

use FOS\CommentBundle\Markup\ParserInterface;

class PipelineParser implements ParserInterface
{
    /** @var array pipeline */
    protected $pipeline = array();

    /**
     * Adds parser to pipeline.
     *
     * @param ParserInterface $parser Parser
     */
    public function addToPipeline(ParserInterface $parser)
    {
        $this->pipeline[] = $parser;
    }

    /**
     * Parses comment with all parsers in pipeline.
     *
     * @param string $text Comment to be parsed.
     *
     * @return string Comment that has been parsed with all parsers in pipeline.
     */
    public function parse($text)
    {
        foreach ($this->pipeline as $parser) {
            $text = $parser->parse($text);
        }

        return $text;
    }
}