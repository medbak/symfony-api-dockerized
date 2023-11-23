<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateNotebookDto
{
    public function __construct(
        #[NotBlank(message: 'field should not be empty')]
        #[Type('string')]
        public readonly string $identifier,

        #[NotBlank(message: 'field should not be empty')]
        #[Type('string')]
        public readonly string $headline,

        #[Type('string')]
        public ?string $content = null,
    ) {
    }

    public function toArray()
    {
        return [
            'identifier' => $this->identifier,
            'headline' => $this->headline,
            'content' => $this->content,
        ];
    }
}
