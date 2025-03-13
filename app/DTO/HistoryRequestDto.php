<?php

namespace App\DTO;

class HistoryRequestDto
{
    public function __construct(
        public ?string $author,
        public ?array $isbn,
        public ?string $title,
        public ?int $offset,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            author: $data['author'] ?? null,
            isbn: $data['isbn'] ?? null,
            title: $data['title'] ?? null,
            offset: $data['offset'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'author' => $this->author,
            'isbn' => $this->isbn ? implode(';', $this->isbn) : null,
            'title' => $this->title,
            'offset' => $this->offset,
        ], fn ($value) => !is_null($value));
    }
}
