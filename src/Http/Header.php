<?php declare(strict_types=1);
/**
 * @author debuss-a
 */

namespace Borsch\Http;

/**
 * Class Header
 */
class Header
{

    protected string $name;

    /** @var string[] */
    protected array $values;

    public function __construct(string $name, string ...$values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    public function __toString(): string
    {
        return strtr('{name}: {values}', [
            '{name}' => $this->name,
            '{values}' => implode(', ', $this->values)
        ]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function hasValue(string $value): bool
    {
        $value = strtolower($value);
        $values = array_map('strtolower', $this->values);

        return in_array($value, $values);
    }

    public function addValue(string $value): void
    {
        $this->values[] = $value;
    }

    public function addValues(string ...$values)
    {
        $this->values = array_merge($this->values, $values);
    }

    public function equals(Header $other): bool
    {
        if ($this === $other) {
            return true;
        }

        $other_values = array_map('strtolower', $other->getValues());
        if (count($this->values) != count($other_values)) {
            return false;
        }

        $values = array_map('strtolower', $this->values);

        return count(array_diff($values, $other_values)) == 0;
    }
}
