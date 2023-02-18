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
        $indexes = array_map(fn(Header $header) => strtolower($header->getName()), $headers);
        $indexes_extended = array_merge(array_keys($this->headers), $indexes);

        foreach (array_count_values($indexes_extended) as $index => $count) {
            if ($count > 1) {
                throw new InvalidArgumentException(sprintf(
                    'The header with name "%s" has been provided %d times, header name must be unique.',
                    $index,
                    $count
                ));
            }
        }

        $this->headers = array_merge(
            $this->headers,
            array_combine($indexes, $headers)
        );
    }

    public function has(string $name): bool
    {
        return in_array(strtolower($name), array_keys($this->headers));
    }

    public function get(string $name): ?Header
    {
        return $this->headers[strtolower($name)] ?? null;
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
