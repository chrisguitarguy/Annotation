<?php
/**
 * Annotation
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\Annotation;

/**
 * The default parser.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class Parser implements ParserInterface
{
    /**
     * The lexer.
     *
     * @since   0.1
     * @access  protected
     * @var     LexerInterface
     */
    protected $lexer;

    /**
     * Used in a few places when we parse "values"
     *
     * @since   0.1
     * @access  private
     * @var     string[]
     */
    protected $literals = array(
        Tokens::T_INT,
        Tokens::T_FLOAT,
        Tokens::T_STRING,
        Tokens::T_TRUE,
        Tokens::T_FALSE,
        Tokens::T_NULL,
        Tokens::T_OPEN_BRACE, // signals that we need to parse a dict
        Tokens::T_OPEN_BRACKET, // singles that we need to parse a list
    );

    /**
     * Construct. Set up the lexer.
     *
     * @since   0.1
     * @access  public
     * @param   LexerInterface $lexer
     * @return  void
     */
    public function __construct(LexerInterface $lexer=null)
    {
        $this->lexer = $lexer ?: new Lexer();
    }

    /**
     * {@inheritdoc}
     */
    public function parse($input)
    {
        $stream = $this->lexer->lex($input);

        $annotations = array();
        while ($stream->valid()) {
            if (!$stream->skipUntil(Tokens::T_AT)) {
                break;
            }

            if (!$stream->peek()->test(Tokens::T_IDENTIFIER)) {
                $stream->next(); // move along
                continue;
            }

            if (!$stream->peek(2)->test(Tokens::T_OPEN_PAREN)) {
                $stream->next(); // move along
                continue;
            }

            $annotations[] = $this->parseAnnotation($stream);
        }

        return $annotations;
    }

    protected function parseAnnotation(TokenStreamInterface $stream)
    {
        // disgard the @ sign
        $stream->next();

        // get the annotation name
        $stream->expect(Tokens::T_IDENTIFIER);
        $annotation_name = $stream->current()->value;

        // next we should expect an open parenthesis
        $stream->next();
        $stream->expect(Tokens::T_OPEN_PAREN);

        $args = $this->parseArguments($stream);

        $stream->expect(Tokens::T_CLOSE_PAREN);
        $stream->next();

        return array($annotation_name, $args);
    }

    protected function parseArguments(TokenStreamInterface $stream)
    {
        // discard the opening paren
        $stream->next();

        $arguments = array();
        while ($stream->valid()) {
            $this->consumeWhitespace($stream);

            // when we reach the closing parent, bail
            if ($stream->current()->test(Tokens::T_CLOSE_PAREN)) {
                break;
            }

            list($identifier, $value) = $this->parseArgument($stream);
            $arguments[$identifier] = $value;

            $this->consumeWhitespace($stream);

            $stream->expect(array(Tokens::T_COMMA, Tokens::T_CLOSE_PAREN));

            // if we did get a comma, move to the next
            if ($stream->current()->test(Tokens::T_COMMA)) {
                $stream->next();
            }
        }

        return $arguments;
    }

    protected function parseArgument(TokenStreamInterface $stream)
    {
        $stream->expect(Tokens::T_IDENTIFIER);
        $identifier = $stream->current()->value;
        $stream->next();

        $this->consumeWhitespace($stream);

        // we expect an equal since, look for it then discard it
        $stream->expect(Tokens::T_EQUALS);
        $stream->next();

        $this->consumeWhitespace($stream);

        $value = $this->parseValue($stream);

        return array($identifier, $value);
    }

    protected function consumeWhitespace(TokenStreamInterface $stream)
    {
        while($stream->valid()) {
            if (!$stream->current()->test(Tokens::T_WHITESPACE)) {
                break;
            }

            $stream->next();
        }
    }

    protected function parseValue(TokenStream $stream)
    {
        // if we're here we expect a literal
        $stream->expect($this->literals);

        $token = $stream->current();

        switch ($token->name) {
            case Tokens::T_INT:
                $value = intval($token->value);
                break;
            case Tokens::T_FLOAT:
                $value = floatval($token->value);
                break;
            case Tokens::T_STRING:
                $value = $this->cleanString($token->value);
                break;
            case Tokens::T_OPEN_BRACKET:
                $value = $this->parseList($stream);
                break;
            case Tokens::T_OPEN_BRACE:
                $value = $this->parseDict($stream);
                break;
            case Tokens::T_TRUE:
                $value = true;
                break;
            case Tokens::T_FALSE:
                $value = false;
                break;
            case Tokens::T_NULL:
            default:
                $value = null;
                break;
        }

        $stream->next();

        return $value;
    }

    protected function parseList(TokenStream $stream)
    {
        // expect and discard the open brace
        $stream->expect(Tokens::T_OPEN_BRACKET);
        $stream->next();

        $values = array();
        while ($stream->valid()) {
            $this->consumeWhitespace($stream);

            if ($stream->current()->test(Tokens::T_CLOSE_BRACKET)) {
                break;
            }

            $values[] = $this->parseValue($stream);

            $this->consumeWhitespace($stream);

            // we expect a comma or the close of the opening bracket
            $stream->expect(array(
                Tokens::T_COMMA,
                Tokens::T_CLOSE_BRACKET,
            ));

            // discard the comma if we have it
            if ($stream->current()->test(Tokens::T_COMMA)) {
                $stream->next();
            }
        }

        // we expect a closing bracket, look for it and discard it
        $stream->expect(Tokens::T_CLOSE_BRACKET);

        return $values;
    }

    protected function parseDict(TokenStream $stream)
    {
        // expect and discard the openig brace
        $stream->expect(Tokens::T_OPEN_BRACE);
        $stream->next();

        $values = array();
        while ($stream->valid()) {
            $this->consumeWhitespace($stream);

            if ($stream->current()->test(Tokens::T_CLOSE_BRACE)) {
                break;
            }

            $key = $this->parseDictKey($stream);

            $this->consumeWhitespace($stream);

            $stream->expect(Tokens::T_COLON);
            $stream->next();

            $this->consumeWhitespace($stream);

            $values[$key] = $this->parseValue($stream);

            $this->consumeWhitespace($stream);

            $stream->expect(array(
                Tokens::T_COMMA,
                Tokens::T_CLOSE_BRACE,
            ));

            if ($stream->current()->test(Tokens::T_COMMA)) {
                $stream->next();
            }
        }

        $stream->expect(Tokens::T_CLOSE_BRACE);

        return $values;
    }

    protected function parseDictKey(TokenStream $stream)
    {
        $stream->expect(array(
            Tokens::T_IDENTIFIER,
            Tokens::T_STRING,
        ));

        $token = $stream->current();
        switch ($token->name) {
            case Tokens::T_STRING:
                $key = $this->cleanString($token->value);
                break;
            case Tokens::T_IDENTIFIER:
            default:
                $key = null;
                break;
        }

        $stream->next();

        return $key;
    }

    protected function cleanString($str)
    {
        return substr($str, 1, strlen($str) - 2);
    }
}
