<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class SumNumbers
{
    #[Assert\Type(['float', 'int'])]
    #[OA\Property(description: 'The first number to sum', type: 'number', nullable: false)]
    public float|int $number1;

    #[Assert\Type(['float', 'int'])]
    #[OA\Property(description: 'The second number to sum', type: 'number', nullable: false)]
    public float|int $number2;
}
