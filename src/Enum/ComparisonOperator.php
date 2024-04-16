<?php

declare(strict_types=1);

namespace SimpleCollections\Enum;

enum ComparisonOperator
{
    case Equals;
    case Greater;
    case GreaterOrEqual;
    case In;
    case Less;
    case LessOrEqual;
    case NotEquals;
    case NotIn;
}
