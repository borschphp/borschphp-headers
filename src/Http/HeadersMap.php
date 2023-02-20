<?php declare(strict_types=1);
/**
 * @author debuss-a
 */

namespace Borsch\Http;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * Class HeadersMap
 */
class HeadersMap implements Countable, IteratorAggregate
{

    /** @var Header[] */
    protected array $headers = [];

    public function __construct(Header ...$headers)
    {
        $this->add(...$headers);
    }

    public function add(Header ...$headers): void
    {
        $this->headers = array_merge(
            $this->headers,
            array_combine(
                array_map(fn(Header $header) => strtolower($header->getName()), $headers),
                $headers
            )
        );
    }

    public function has(string $name): bool
    {
        return in_array(strtolower($name), array_keys($this->headers));
    }

    public function get(string $name): Header
    {
        return $this->headers[strtolower($name)] ??= new Header($name);
    }

    public function remove(string $name): void
    {
        unset($this->headers[strtolower($name)]);
    }

    public function getAll(): array
    {
        return array_values($this->headers);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->headers);
    }

    public function count(): int
    {
        return count($this->headers);
    }
}
