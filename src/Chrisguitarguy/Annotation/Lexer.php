<?php
/**
 * Annotations
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\Annotation;

class Lexer implements LexerInterface
{
    /**
     * Our tokens in type => regex pairs.
     *
     * @since   0.1
     * @access  protected
     * @var     array
     */
    protected $tokens = array(
        Tokens::T_AT            => '@',
        Tokens::T_TRUE          => 'true',
        Tokens::T_FALSE         => 'false',
        Tokens::T_NULL          => 'null',
        Tokens::T_IDENTIFIER    => '[A-Za-z][A-Za-z0-9_]*',
        Tokens::T_OPEN_PAREN    => '\(',
        Tokens::T_CLOSE_PAREN   => '\)',
        Tokens::T_COMMA         => ',',
        Tokens::T_COLON         => ':',
        Tokens::T_EQUALS        => '=',
        Tokens::T_OPEN_BRACKET  => '\[',
        Tokens::T_CLOSE_BRACKET => '\]',
        Tokens::T_OPEN_BRACE    => '\{',
        Tokens::T_CLOSE_BRACE   => '\}',
        Tokens::T_FLOAT         => '-?(?:[0-9]+)?\.[0-9]+',
        Tokens::T_INT           => '-?[0-9]+',
        Tokens::T_STRING        => '(?<!\\\\)"(?:[^"]|(?<=\\\\)")*"|(?<!\\\\)\'(?:[^\']|(?<=\\\\)\')*\'',
        Tokens::T_WHITESPACE    => '\s+',
    );

    /**
     * Regular expression cache.
     *
     * @since   0.1
     * @access  protected
     * @var     string
     */
    protected $regex = null;

    /**
     * Token name cache.
     *
     * @since   0.1
     * @access  public
     * @var     array
     */
    protected $name_cache = null;

    /**
     * Variables we'll use on our way through the lexing process.
     */
    protected $input = null;
    protected $length = null;
    protected $position = null;
    protected $stream = array();

    /**
     * Constructor. Set up the regex and name caches.
     *
     * @since   0.1
     * @access  public
     * @return  void
     */
    public function __construct()
    {
        $this->regex = '/(' . implode(')|(', array_values($this->tokens)) . ')/u';
        $this->name_cache = array_keys($this->tokens);
    }

    /**
     * {@inheritdoc}
     */
    public function lex($input)
    {
        $this->input = $input;
        $this->length = strlen($input);
        $this->position = 0;
        $this->stream = array();

        while ($this->match()) {
            // noop
        }

        return new TokenStream($this->stream);
    }

    /**
     * Search for a match with our regex (created in the constructor). Only
     * returns the first match.
     *
     * @since   0.1
     * @access  public
     * @return  boolean
     */
    protected function match()
    {
        if ($this->position >= $this->length) {
            $this->stream[] = new Token(
                Tokens::T_EOF,
                null,
                $this->position
            );

            return false;
        }

        // if we don't get any matches move the position to the end of the file
        // and return true so we can get an EOF token on the next round.
        if (!preg_match($this->regex, $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
            $this->position = $this->length;
            return true;
        }

        // if we're heare we found some tokens
        $c = count($matches);
        for ($i = 1; $i < $c; $i++) {
            if ('' === $matches[$i][0] || !isset($this->name_cache[$i-1])) {
                continue;
            }

            // move the position to our found offset
            $this->position = $matches[$i][1];

            $this->stream[] = new Token(
                $this->name_cache[$i-1],
                $matches[$i][0],
                $this->position
            );

            break;
        }

        $this->position += strlen($matches[0][0]);

        return true;
    }
}
