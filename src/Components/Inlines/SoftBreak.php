<?php

namespace Erusev\Parsedown\Components\Inlines;

use Erusev\Parsedown\AST\StateRenderable;
use Erusev\Parsedown\Components\Inline;
use Erusev\Parsedown\Html\Renderables\Text;
use Erusev\Parsedown\Parsedown;
use Erusev\Parsedown\Parsing\Excerpt;
use Erusev\Parsedown\State;

final class SoftBreak implements Inline
{
    use WidthTrait;

    /** @var int */
    private $position;

    /**
     * @param int $width
     * @param int $position
     */
    public function __construct($width, $position)
    {
        $this->width = $width;
        $this->position = $position;
    }

    /**
     * @param Excerpt $Excerpt
     * @param State $State
     * @return static|null
     */
    public static function build(Excerpt $Excerpt, State $State)
    {
        $context = $Excerpt->context();
        $offset = $Excerpt->offset();

        $trimTrailingWhitespaceBefore = \rtrim(\substr($context, 0, $offset));
        $trimLeadingWhitespaceAfter = \ltrim(\substr($context, $offset + 1));
        $contentLenBefore = \strlen($trimTrailingWhitespaceBefore);
        $contentLenAfter = \strlen($trimLeadingWhitespaceAfter);

        $originalLen = \strlen($context);
        $afterWidth = $originalLen - $offset - $contentLenAfter;

        return new self($offset + $afterWidth - $contentLenBefore, $contentLenBefore);
    }

    /**
     * Return an integer to declare that the inline should be treated as if it
     * started from that position in the excerpt given to static::build.
     * Return null to use the excerpt offset value.
     * @return int|null
     * */
    public function modifyStartPositionTo()
    {
        return $this->position;
    }

    /**
     * @return Text
     */
    public function stateRenderable(Parsedown $_)
    {
        return new Text("\n");
    }

    /**
     * @return Text
     */
    public function bestPlaintext()
    {
        return new Text("\n");
    }
}
