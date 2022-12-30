<?php

return [
    'katas_per_page' => 20,
    'ranks' => range(1,8),
    'languages' => [
        ['slug' => 'javascript', 'name' => 'JavaScript', 'code'=>'e640'],
        ['slug' => 'php', 'name' => 'PHP', 'code'=>'e60e'],
        ['slug' => 'python', 'name' => 'Python', 'code'=>'e06f'],
    ],
    'sort' => [
        'newest' => ['name' => 'Newest', 'column' => 'created_at_orig', 'direction' => 'desc'],
        'oldest' => ['name' => 'Oldest', 'column' => 'created_at_orig', 'direction' => 'asc'],
        'popularity' => ['name' => 'Popularity', 'column' => 'total_attempts', 'direction' => 'desc'],
        'most_completed' => ['name' => 'Most completed', 'column' => 'total_completed', 'direction' => 'desc'],
        'least_completed' => ['name' => 'Least completed', 'column' => 'total_completed', 'direction' => 'asc'],
        'recently_published' => ['name' => 'Recently Published', 'column' => 'published_at', 'direction' => 'desc'],
        'hardest' => ['name' => 'Hardest', 'column' => 'rank', 'direction' => 'asc'],
        'easiest' => ['name' => 'Easiest', 'column' => 'rank', 'direction' => 'desc'],
        'name' => ['name' => 'Name', 'column' => 'name', 'direction' => 'asc'],
    ],
    'categories' => ['fundamentals', 'reference','algorithms','bug_fixes','refactoring','puzzle','games'],
    'tags' => ['fundamentals', 'reference','algorithms','bug_fixes','refactoring','puzzle','games'],
];
