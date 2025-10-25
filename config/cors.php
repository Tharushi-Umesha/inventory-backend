<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:3000',
        'http://127.0.0.1:5173',
        'http://127.0.0.1:3000',
        'https://inventory-frontend-lake-chi.vercel.app', // Your Vercel URL
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];
```

#### 1.2: Create a `Procfile` in your Laravel root folder

Create a new file called `Procfile` (no extension) with this content:
```
web: vendor/bin/heroku-php-apache2 public/
```

#### 1.3: Make sure you have `.gitignore`

Check your `.gitignore` includes:
```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup