<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Enum;

enum ComparisonOperator
{
    case Equals;
    case GreaterThan;
    case GreaterThanOrEqual;
    case In;
    case LessThan;
    case LessThanOrEqual;
    case NotEquals;
    case NotIn;
}
