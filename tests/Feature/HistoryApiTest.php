<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class HistoryApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $baseUrl = config('nytimes.url');
        $this->testResponse = [
            'status' => 'OK',
            'copyright' => 'Copyright (c) 2025 The New York Times Company. All Rights Reserved.',
            'num_results' => 1,
            'results' => [
                [
                    'title' => 'A BRIEF HISTORY OF TIME',
                    'description' => 'The British cosmologist reviews efforts to create a unified theory of the universe.',
                    'contributor' => 'by Stephen Hawking',
                    'author' => 'Stephen Hawking',
                    'contributor_note' => '',
                    'price' => '0.00',
                    'age_group' => '',
                    'publisher' => 'Bantam',
                    'isbn' => [
                        [
                            'isbn10' => '0553380168',
                            'isbn13' => '9780553380163'
                        ]
                    ],
                    'ranks_history' => [
                        [
                            'primary_isbn10' => '0553380168',
                            'primary_isbn13' => '9780553380163',
                            'rank' => 6,
                            'list_name' => 'Science',
                            'display_name' => 'Science',
                            'published_date' => '2018-07-15',
                            'bestsellers_date' => '2018-06-30',
                            'weeks_on_list' => 16,
                            'rank_last_week' => 0,
                            'asterisk' => 0,
                            'dagger' => 0
                        ]
                    ],
                    'reviews' => [
                        [
                            'book_review_link' => 'https://www.nytimes.com/1988/05/04/books/books-of-the-times-making-the-big-bang-almost-understandable.html',
                            'first_chapter_link' => '',
                            'sunday_review_link' => '',
                            'article_chapter_link' => ''
                        ]
                    ]
                ]
            ],
        ];

        Http::fake([
            $baseUrl . '/svc/books/v3/lists/best-sellers/history.json*' => Http::response($this->testResponse)
        ]);
    }

    public function test_successful_response(): void
    {
        $payload = [
            'author' => 'Stephen Hawking',
            'isbn' => ['9780553380163'],
            'title' => 'A BRIEF HISTORY OF TIME',
            'offset' => 20
        ];
        $response = $this->postJson('/api/v1/history', $payload);
        $response
            ->assertStatus(200)
            ->assertJson($this->testResponse);
    }

    public function test_invalid_isbn(): void
    {
        $payload = [
            'author' => 'Stephen Hawking',
            'isbn' => ['978055338016'],
            'title' => 'A BRIEF HISTORY OF TIME',
            'offset' => 20
        ];
        $response = $this->postJson('/api/v1/history', $payload);
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => [
                    'isbn.0' => [
                        'The ISBN must be 10 or 13 digits.'
                    ],
                ]
            ]);
    }

    public function test_invalid_offset(): void
    {
        $payload = [
            'author' => 'Stephen Hawking',
            'title' => 'A BRIEF HISTORY OF TIME',
            'offset' => 2
        ];
        $response = $this->postJson('/api/v1/history', $payload);
        $response
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => [
                    'offset' => [
                        'The offset must be a multiple of 20.'
                    ],
                ]
            ]);
    }
}
