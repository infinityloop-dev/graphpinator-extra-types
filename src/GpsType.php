<?php

declare(strict_types = 1);

namespace Graphpinator\ExtraTypes;

final class GpsType extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'Gps';
    protected const DESCRIPTION = 'Gps type - latitude and longitude.';

    public function __construct(
        private \Graphpinator\ConstraintDirectives\ConstraintDirectiveAccessor $constraintDirectiveAccessor,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'lat')
            && \property_exists($rawValue, 'lng')
            && \is_float($rawValue->lat)
            && \is_float($rawValue->lng);
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'lat',
                \Graphpinator\Typesystem\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lat;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -90.0, 'max' => 90.0],
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'lng',
                \Graphpinator\Typesystem\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lng;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -180.0, 'max' => 180.0],
            ),
        ]);
    }
}
