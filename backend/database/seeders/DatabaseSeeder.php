<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, User};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@amazon-clone.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);

        // Create 50 diverse products
        $products = [
            // Electronics
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system',
                'price' => 1199.99,
                'category' => 'Electronics',
                'stock' => 25,
                'images' => [
                    'https://via.placeholder.com/400x400/0066CC/FFFFFF?text=iPhone+15+Pro',
                    'https://via.placeholder.com/400x400/333333/FFFFFF?text=iPhone+Back',
                    'https://via.placeholder.com/400x400/666666/FFFFFF?text=iPhone+Side'
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android phone with S Pen, 200MP camera, and AI features',
                'price' => 1099.99,
                'category' => 'Electronics',
                'stock' => 30,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Galaxy+S24',
                    'https://via.placeholder.com/400x400/444444/FFFFFF?text=Galaxy+Back',
                    'https://via.placeholder.com/400x400/888888/FFFFFF?text=Galaxy+Side'
                ]
            ],
            [
                'name' => 'MacBook Pro 16-inch M3',
                'description' => 'Powerful laptop with M3 chip, 16GB RAM, 512GB SSD, perfect for professionals',
                'price' => 2499.99,
                'category' => 'Electronics',
                'stock' => 15,
                'images' => [
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=MacBook+Pro',
                    'https://via.placeholder.com/400x400/A0A0A0/000000?text=MacBook+Open',
                    'https://via.placeholder.com/400x400/808080/FFFFFF?text=MacBook+Side'
                ]
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'description' => 'Industry-leading noise canceling wireless headphones with 30-hour battery',
                'price' => 349.99,
                'category' => 'Electronics',
                'stock' => 40,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Sony+Headphones',
                    'https://via.placeholder.com/400x400/333333/FFFFFF?text=Headphones+Side',
                    'https://via.placeholder.com/400x400/666666/FFFFFF?text=Headphones+Case'
                ]
            ],
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Advanced smartwatch with health monitoring, GPS, and cellular connectivity',
                'price' => 429.99,
                'category' => 'Electronics',
                'stock' => 35,
                'images' => [
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Apple+Watch',
                    'https://via.placeholder.com/400x400/00FF00/000000?text=Watch+Sport',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Watch+Classic'
                ]
            ],

            // Fashion
            [
                'name' => 'Nike Air Max 90',
                'description' => 'Classic running shoes with Max Air cushioning and retro style',
                'price' => 129.99,
                'category' => 'Fashion',
                'stock' => 50,
                'images' => [
                    'https://via.placeholder.com/400x400/FFFFFF/000000?text=Nike+Air+Max',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Nike+Red',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Nike+Black'
                ]
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Premium running shoes with Boost midsole and Primeknit upper',
                'price' => 189.99,
                'category' => 'Fashion',
                'stock' => 45,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Adidas+Ultra',
                    'https://via.placeholder.com/400x400/FFFFFF/000000?text=Ultra+White',
                    'https://via.placeholder.com/400x400/0066CC/FFFFFF?text=Ultra+Blue'
                ]
            ],
            [
                'name' => 'Levi\'s 501 Original Jeans',
                'description' => 'Iconic straight-leg jeans in classic blue denim, available in multiple sizes',
                'price' => 89.99,
                'category' => 'Fashion',
                'stock' => 60,
                'images' => [
                    'https://via.placeholder.com/400x400/4169E1/FFFFFF?text=Levis+501',
                    'https://via.placeholder.com/400x400/000080/FFFFFF?text=Jeans+Dark',
                    'https://via.placeholder.com/400x400/87CEEB/000000?text=Jeans+Light'
                ]
            ],
            [
                'name' => 'Ray-Ban Aviator Sunglasses',
                'description' => 'Classic pilot sunglasses with gold frame and green lenses',
                'price' => 159.99,
                'category' => 'Fashion',
                'stock' => 25,
                'images' => [
                    'https://via.placeholder.com/400x400/FFD700/000000?text=Ray+Ban+Gold',
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=Ray+Ban+Silver',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Ray+Ban+Black'
                ]
            ],
            [
                'name' => 'North Face Hoodie',
                'description' => 'Comfortable pullover hoodie with kangaroo pocket and adjustable hood',
                'price' => 79.99,
                'category' => 'Fashion',
                'stock' => 40,
                'images' => [
                    'https://via.placeholder.com/400x400/800080/FFFFFF?text=North+Face',
                    'https://via.placeholder.com/400x400/008000/FFFFFF?text=Hoodie+Green',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Hoodie+Red'
                ]
            ],

            // Home & Garden
            [
                'name' => 'Dyson V15 Detect Vacuum',
                'description' => 'Cordless vacuum with laser dust detection and advanced filtration',
                'price' => 749.99,
                'category' => 'Home & Garden',
                'stock' => 20,
                'images' => [
                    'https://via.placeholder.com/400x400/800080/FFFFFF?text=Dyson+V15',
                    'https://via.placeholder.com/400x400/FFD700/000000?text=Vacuum+Gold',
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=Vacuum+Parts'
                ]
            ],
            [
                'name' => 'Ninja Foodi Air Fryer',
                'description' => '8-quart air fryer with multiple cooking functions and digital display',
                'price' => 199.99,
                'category' => 'Home & Garden',
                'stock' => 30,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Ninja+Foodi',
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=Air+Fryer',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Foodi+Red'
                ]
            ],
            [
                'name' => 'Instant Pot Duo 7-in-1',
                'description' => 'Multi-use pressure cooker with 7 appliances in 1, 6-quart capacity',
                'price' => 129.99,
                'category' => 'Home & Garden',
                'stock' => 35,
                'images' => [
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=Instant+Pot',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Pot+Red',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Pot+Black'
                ]
            ],
            [
                'name' => 'Philips Hue Smart Bulbs',
                'description' => 'Color-changing LED smart bulbs compatible with Alexa and Google Assistant',
                'price' => 49.99,
                'category' => 'Home & Garden',
                'stock' => 100,
                'images' => [
                    'https://via.placeholder.com/400x400/FFFF00/000000?text=Hue+Bulbs',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Bulb+Red',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Bulb+Blue'
                ]
            ],
            [
                'name' => 'Amazon Echo Dot 5th Gen',
                'description' => 'Smart speaker with Alexa, improved sound quality, and sleek design',
                'price' => 59.99,
                'category' => 'Home & Garden',
                'stock' => 75,
                'images' => [
                    'https://via.placeholder.com/400x400/4169E1/FFFFFF?text=Echo+Dot',
                    'https://via.placeholder.com/400x400/FFFFFF/000000?text=Dot+White',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Dot+Black'
                ]
            ],

            // Sports & Outdoors
            [
                'name' => 'Yeti Rambler 30oz Tumbler',
                'description' => 'Insulated stainless steel tumbler that keeps drinks hot or cold',
                'price' => 39.99,
                'category' => 'Sports & Outdoors',
                'stock' => 80,
                'images' => [
                    'https://via.placeholder.com/400x400/C0C0C0/000000?text=Yeti+Tumbler',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Tumbler+Black',
                    'https://via.placeholder.com/400x400/FF69B4/FFFFFF?text=Tumbler+Pink'
                ]
            ],
            [
                'name' => 'Coleman 4-Person Tent',
                'description' => 'Easy-setup dome tent with weather-resistant design, perfect for camping',
                'price' => 89.99,
                'category' => 'Sports & Outdoors',
                'stock' => 25,
                'images' => [
                    'https://via.placeholder.com/400x400/008000/FFFFFF?text=Coleman+Tent',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Tent+Blue',
                    'https://via.placeholder.com/400x400/FF8C00/FFFFFF?text=Tent+Orange'
                ]
            ],
            [
                'name' => 'Wilson Tennis Racket',
                'description' => 'Professional-grade tennis racket with graphite frame and comfort grip',
                'price' => 159.99,
                'category' => 'Sports & Outdoors',
                'stock' => 20,
                'images' => [
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Wilson+Racket',
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Racket+Black',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Racket+Blue'
                ]
            ],
            [
                'name' => 'Under Armour Gym Bag',
                'description' => 'Durable sports duffle bag with multiple compartments and shoe storage',
                'price' => 69.99,
                'category' => 'Sports & Outdoors',
                'stock' => 45,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=UA+Gym+Bag',
                    'https://via.placeholder.com/400x400/FF0000/FFFFFF?text=Bag+Red',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Bag+Blue'
                ]
            ],
            [
                'name' => 'Fitbit Charge 5',
                'description' => 'Advanced fitness tracker with GPS, heart rate monitoring, and 7-day battery',
                'price' => 199.99,
                'category' => 'Sports & Outdoors',
                'stock' => 40,
                'images' => [
                    'https://via.placeholder.com/400x400/000000/FFFFFF?text=Fitbit+5',
                    'https://via.placeholder.com/400x400/FF69B4/FFFFFF?text=Fitbit+Pink',
                    'https://via.placeholder.com/400x400/0000FF/FFFFFF?text=Fitbit+Blue'
                ]
            ],

            // Books
            [
                'name' => 'The Psychology of Money',
                'description' => 'Bestselling book about the psychology behind financial decisions by Morgan Housel',
                'price' => 16.99,
                'category' => 'Books',
                'stock' => 150,
                'images' => [
                    'https://via.placeholder.com/300x400/008000/FFFFFF?text=Psychology+Money',
                    'https://via.placeholder.com/300x400/FFD700/000000?text=Book+Cover',
                    'https://via.placeholder.com/300x400/4169E1/FFFFFF?text=Bestseller'
                ]
            ],
            [
                'name' => 'Atomic Habits',
                'description' => 'Life-changing book about building good habits and breaking bad ones by James Clear',
                'price' => 14.99,
                'category' => 'Books',
                'stock' => 200,
                'images' => [
                    'https://via.placeholder.com/300x400/FF4500/FFFFFF?text=Atomic+Habits',
                    'https://via.placeholder.com/300x400/32CD32/000000?text=Habits+Book',
                    'https://via.placeholder.com/300x400/8A2BE2/FFFFFF?text=Self+Help'
                ]
            ],
            [
                'name' => 'Where the Crawdads Sing',
                'description' => 'Mystery novel by Delia Owens, #1 New York Times bestseller',
                'price' => 15.99,
                'category' => 'Books',
                'stock' => 120,
                'images' => [
                    'https://via.placeholder.com/300x400/228B22/FFFFFF?text=Crawdads+Sing',
                    'https://via.placeholder.com/300x400/8FBC8F/000000?text=Mystery+Novel',
                    'https://via.placeholder.com/300x400/006400/FFFFFF?text=Bestseller'
                ]
            ],
            [
                'name' => 'The Seven Husbands of Evelyn Hugo',
                'description' => 'Captivating novel by Taylor Jenkins Reid about a reclusive Hollywood icon',
                'price' => 13.99,
                'category' => 'Books',
                'stock' => 180,
                'images' => [
                    'https://via.placeholder.com/300x400/FF1493/FFFFFF?text=Seven+Husbands',
                    'https://via.placeholder.com/300x400/FFB6C1/000000?text=Hollywood+Story',
                    'https://via.placeholder.com/300x400/DC143C/FFFFFF?text=Romance'
                ]
            ],
            [
                'name' => 'Educated',
                'description' => 'Powerful memoir by Tara Westover about education, family, and the struggle to forge an individual identity',
                'price' => 17.99,
                'category' => 'Books',
                'stock' => 90,
                'images' => [
                    'https://via.placeholder.com/300x400/4682B4/FFFFFF?text=Educated',
                    'https://via.placeholder.com/300x400/87CEEB/000000?text=Memoir',
                    'https://via.placeholder.com/300x400/191970/FFFFFF?text=Education'
                ]
            ],
        ];

        // Add remaining products to reach 50
        $additionalProducts = [
            // Electronics continued
            ['name' => 'iPad Pro 12.9"', 'description' => 'Professional tablet with M2 chip and Liquid Retina display', 'price' => 1099.99, 'category' => 'Electronics', 'stock' => 20],
            ['name' => 'AirPods Pro 2nd Gen', 'description' => 'Premium wireless earbuds with active noise cancellation', 'price' => 249.99, 'category' => 'Electronics', 'stock' => 60],
            ['name' => 'Nintendo Switch OLED', 'description' => 'Handheld gaming console with vibrant OLED screen', 'price' => 349.99, 'category' => 'Electronics', 'stock' => 35],
            ['name' => 'Canon EOS R5', 'description' => 'Professional mirrorless camera with 45MP sensor', 'price' => 3899.99, 'category' => 'Electronics', 'stock' => 10],
            ['name' => 'Dell XPS 13', 'description' => 'Ultra-thin laptop with Intel Core i7 and 16GB RAM', 'price' => 1299.99, 'category' => 'Electronics', 'stock' => 25],

            // Fashion continued
            ['name' => 'Patagonia Down Jacket', 'description' => 'Lightweight down jacket perfect for outdoor adventures', 'price' => 229.99, 'category' => 'Fashion', 'stock' => 30],
            ['name' => 'Converse Chuck Taylor', 'description' => 'Classic high-top sneakers in various colors', 'price' => 65.99, 'category' => 'Fashion', 'stock' => 80],
            ['name' => 'Michael Kors Handbag', 'description' => 'Designer leather handbag with multiple compartments', 'price' => 189.99, 'category' => 'Fashion', 'stock' => 25],
            ['name' => 'Casio G-Shock Watch', 'description' => 'Rugged digital watch with shock resistance', 'price' => 99.99, 'category' => 'Fashion', 'stock' => 45],
            ['name' => 'Calvin Klein T-Shirt', 'description' => 'Premium cotton t-shirt with classic logo', 'price' => 29.99, 'category' => 'Fashion', 'stock' => 100],

            // Home & Garden continued
            ['name' => 'KitchenAid Stand Mixer', 'description' => 'Professional 5-quart stand mixer for baking enthusiasts', 'price' => 379.99, 'category' => 'Home & Garden', 'stock' => 15],
            ['name' => 'Roomba i7+ Robot Vacuum', 'description' => 'Smart robot vacuum with automatic dirt disposal', 'price' => 599.99, 'category' => 'Home & Garden', 'stock' => 20],
            ['name' => 'Nest Learning Thermostat', 'description' => 'Smart thermostat that learns your preferences', 'price' => 249.99, 'category' => 'Home & Garden', 'stock' => 40],
            ['name' => 'Weber Genesis II Grill', 'description' => 'Premium gas grill with three burners and side tables', 'price' => 799.99, 'category' => 'Home & Garden', 'stock' => 12],
            ['name' => 'Shark Navigator Vacuum', 'description' => 'Upright vacuum with lift-away technology', 'price' => 179.99, 'category' => 'Home & Garden', 'stock' => 30],

            // Sports & Outdoors continued
            ['name' => 'Hydro Flask Water Bottle', 'description' => '32oz insulated water bottle that keeps drinks cold for 24 hours', 'price' => 44.99, 'category' => 'Sports & Outdoors', 'stock' => 90],
            ['name' => 'Spalding Basketball', 'description' => 'Official size basketball with excellent grip', 'price' => 24.99, 'category' => 'Sports & Outdoors', 'stock' => 60],
            ['name' => 'Yoga Mat Premium', 'description' => 'Extra thick yoga mat with non-slip surface', 'price' => 39.99, 'category' => 'Sports & Outdoors', 'stock' => 75],
            ['name' => 'Dumbbell Set 50lbs', 'description' => 'Adjustable dumbbell set perfect for home workouts', 'price' => 299.99, 'category' => 'Sports & Outdoors', 'stock' => 15],
            ['name' => 'Garmin Forerunner 945', 'description' => 'GPS running watch with advanced training metrics', 'price' => 599.99, 'category' => 'Sports & Outdoors', 'stock' => 18],

            // Books continued
            ['name' => 'Think and Grow Rich', 'description' => 'Classic self-help book by Napoleon Hill about success principles', 'price' => 12.99, 'category' => 'Books', 'stock' => 150],
            ['name' => 'The Midnight Library', 'description' => 'Philosophical novel by Matt Haig about life choices', 'price' => 14.99, 'category' => 'Books', 'stock' => 110],
            ['name' => 'Becoming', 'description' => 'Inspiring memoir by former First Lady Michelle Obama', 'price' => 18.99, 'category' => 'Books', 'stock' => 95],
            ['name' => 'The Silent Patient', 'description' => 'Psychological thriller by Alex Michaelides', 'price' => 15.99, 'category' => 'Books', 'stock' => 130],
            ['name' => 'Sapiens', 'description' => 'Fascinating look at human history by Yuval Noah Harari', 'price' => 16.99, 'category' => 'Books', 'stock' => 85],

            // Beauty & Personal Care
            ['name' => 'Fenty Beauty Foundation', 'description' => 'Full-coverage foundation with 40 shades for all skin tones', 'price' => 39.99, 'category' => 'Beauty', 'stock' => 50],
            ['name' => 'The Ordinary Skincare Set', 'description' => 'Complete skincare routine with serums and moisturizers', 'price' => 89.99, 'category' => 'Beauty', 'stock' => 40],
            ['name' => 'Oral-B Electric Toothbrush', 'description' => 'Advanced electric toothbrush with pressure sensor', 'price' => 129.99, 'category' => 'Beauty', 'stock' => 35],
            ['name' => 'Dove Body Wash Set', 'description' => 'Moisturizing body wash in multiple scents', 'price' => 24.99, 'category' => 'Beauty', 'stock' => 80],
            ['name' => 'Neutrogena Sunscreen SPF 50', 'description' => 'Broad-spectrum sunscreen for daily protection', 'price' => 12.99, 'category' => 'Beauty', 'stock' => 100]
        ];

        // Merge products and add default images for additional products
        foreach ($additionalProducts as $i => $product) {
            $productNumber = count($products) + $i + 1;
            $product['images'] = [
                "https://via.placeholder.com/400x400/666666/FFFFFF?text=Product+$productNumber",
                "https://via.placeholder.com/400x400/888888/FFFFFF?text=Product+{$productNumber}+Alt1",
                "https://via.placeholder.com/400x400/AAAAAA/000000?text=Product+{$productNumber}+Alt2"
            ];
            $products[] = $product;
        }

        // Insert all products
        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Database seeded successfully with 50 products!');
        $this->command->info('Admin login: admin@amazon-clone.com / admin123');
        $this->command->info('Test user login: test@example.com / password');
    }
}