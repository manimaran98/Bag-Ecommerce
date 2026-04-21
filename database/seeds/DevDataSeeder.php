<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

/**
 * Optional dev/demo data (matches legacy database/bag_biz.sql sample rows).
 * Run: vendor/bin/phinx seed:run -s DevDataSeeder
 */
final class DevDataSeeder extends AbstractSeed
{
    /**
     * Salted SHA-256 (s256$…) with fixed all-zero salt for reproducible demo hashes (matches app hasher format).
     */
    private function saltedSha256Dev(string $plainPassword): string
    {
        $salt = str_repeat("\0", 32);

        return 's256$'.bin2hex($salt).'$'.hash('sha256', $salt.$plainPassword);
    }

    public function run(): void
    {
        $pw12345 = $this->saltedSha256Dev('12345');
        $pwAbc123 = $this->saltedSha256Dev('abc123');

        $this->table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'password' => $pw12345,
                'name' => 'admin',
                'address' => '64 LUN Perusahaan Keledang 1 Taman Perindustrian Chandan Raya 31450 Menglembu Perak Malaysia',
                'contact' => '0162153958',
            ],
            [
                'id' => 2,
                'username' => 'ahmad123',
                'password' => $pw12345,
                'name' => 'Ahmad',
                'address' => '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia',
                'contact' => '0123456789',
            ],
            [
                'id' => 20,
                'username' => 'Siti99',
                'password' => $pwAbc123,
                'name' => 'Siti Salfa',
                'address' => 'Blok 81 Jln Tembusu Perjiranan 9 81700 Pasir Gudang Johor Pasir Gudang Johor 81700 Malaysia',
                'contact' => '0198765432',
            ],
            [
                'id' => 21,
                'username' => 'sameul98',
                'password' => $pw12345,
                'name' => 'Samuel Jackson',
                'address' => '61B Jln Semeliang(Pekan Baru Kkn) 06300 Kuala Nerang Kedah Kuala Nerang Kedah 06300 Malaysia',
                'contact' => '0121555525',
            ],
            [
                'id' => 22,
                'username' => 'chong98',
                'password' => $pw12345,
                'name' => 'Lee Chong Wei',
                'address' => '30 Jln 10/34A Kepong Entrepreneurs Park 52100 Wilayah Persekutuan 52100 Malaysia 52100 Malaysia',
                'contact' => '0111155574 ',
            ],
        ])->saveData();

        $this->table('suppliers')->insert([
            ['suppliers_id' => 3, 'suppliers_name' => 'Urban Carrier', 'stock_brand' => 'Gucci'],
            ['suppliers_id' => 4, 'suppliers_name' => 'Adidas Malaysia', 'stock_brand' => 'Adidas '],
            ['suppliers_id' => 5, 'suppliers_name' => 'Haute Zone', 'stock_brand' => 'Bellroy'],
            ['suppliers_id' => 6, 'suppliers_name' => 'AdVenture Bags', 'stock_brand' => 'Céline'],
            ['suppliers_id' => 7, 'suppliers_name' => 'StrapIt', 'stock_brand' => 'Christian Dior'],
            ['suppliers_id' => 8, 'suppliers_name' => 'West Bag Co', 'stock_brand' => 'Coach'],
            ['suppliers_id' => 9, 'suppliers_name' => 'Pursify', 'stock_brand' => 'J. W. Anderson'],
            ['suppliers_id' => 10, 'suppliers_name' => 'Royal Bag', 'stock_brand' => 'Louis Vuitton'],
            ['suppliers_id' => 11, 'suppliers_name' => 'Nike Malaysia', 'stock_brand' => 'Nike'],
            ['suppliers_id' => 12, 'suppliers_name' => 'Sandast', 'stock_brand' => 'Prada'],
            ['suppliers_id' => 13, 'suppliers_name' => 'Puma Malaysia', 'stock_brand' => 'Puma'],
            ['suppliers_id' => 14, 'suppliers_name' => 'Baganic', 'stock_brand' => 'Valentino'],
        ])->saveData();

        $stockRows = [
            [10, 'Classic Backpack', 'Gucci', 'Mens', 89, "With a minimalist look and maximum access, this compact backpack keeps urban professionals ready for business.\r\n\r\n", '1.png', 379],
            [11, 'Classic Backpack Plus', 'Gucci', 'Mens', 0, 'This thoughtfully designed travel backpack is loaded with organization and easy access, to keep your business trip or long weekend adventure moving smoothly.', '3.png', 449],
            [12, 'City Pouch Plus', 'Bellroy', 'Mens', 84, 'This slim sidekick keeps your pocket essentials and small devices locked and loaded, so you and your pockets stay agile.', '2.png', 379],
            [13, 'Tokyo Totepack', 'Céline', 'Mens', 96, 'This slim urban backpack features pared-down aesthetics and easy access, to keep modern professionals ready for business.', '4.png', 859],
            [14, 'Weekender Plus', 'Coach', 'Mens', 85, 'This spacious bag combines casual weekend styling with clever organization, so you can move smoothly and focus on the journey.', '0__2_-removebg-preview (1).png', 1379],
            [15, 'Melbourne Backpack', 'J. W. Anderson', 'Mens', 98, "This experimental Melbourne Backpack is a super slim sidekick, with a uniquely expressive textured finish.\r\n\r\n", '5.png', 679],
            [16, 'Market Tote', 'Louis Vuitton', 'Women', 98, 'From grocery runs to beach days, this versatile tote folds easily away, and pops out when and where you need it.', '11.png', 189],
            [17, 'Sling', 'Christian Dior', 'Women', 89, 'Keep your hands free and pockets light with a sling that’s big enough for the essentials, yet small enough to let you move like a New York minute.', '12.png', 369],
            [18, 'Tokyo Tote Compact', 'Prada', 'Women', 99, 'This premium leather upgrade to our Tokyo Tote Compact has easy organization, to keep your everyday sorted, with a touch of luxe.', '13.png', 929],
            [19, 'City Pouch', 'Valentino', 'Women', 100, 'A super slim sidekick that keeps essentials at hand, this is a premium leather upgrade on our regular City Pouch.', '14.png', 479],
            [20, 'Duo Totepack', 'Céline', 'Women', 100, 'The leathers we use are premium hides tanned under gold-rated Leather Working Group environmental protocols, then dyed through so they age gracefully.', '15.png', 929],
            [21, 'The Saddle Bag', 'Coach', 'Women', 100, 'The leathers we use are premium hides tanned under gold-rated Leather Working Group environmental protocols, then dyed through so they age gracefully.', '16.png', 1229],
            [22, 'ELMNTL BKPK', 'Nike', 'Sports', 100, 'Best for: Lifestyle/Nike Sportswear Elemental. Backpack. CLASSIC DESIGN. DURABLE STORAGE. The Nike Sportswear Elemental Backpack is a new spin on an old classic. Its durable design features 2 large compartments and 2 external pockets for small-item storage, while the padded shoulder straps offer supportive comfort.', '21.png', 145],
            [23, 'Originals Classic', 'Adidas', 'Sports', 100, 'This small adidas backpack is built of fabric crafted from 100% recycled materials, which means you can feel good about your daily carry. Plus, a bottle sleeve on each side makes it easy to bring your beverages from home.', '22.png', 99],
            [24, 'ADICOLOR CLASSIC', 'Adidas', 'Sports', 100, 'Since 1972, the Trefoil has stood out on the streets. Carry on the tradition every time you load up this adidas backpack. Padded shoulder straps keep you comfortable no matter what you toss inside. This product is made with Primegreen, a series of high-performance recycled materials.', '23.png', 859],
            [25, 'Nike Brasilia', 'Nike', 'Sports', 100, 'Get some statement Swoosh style that keeps your essentials covered with this just Do It Mini Backpack from Nike. In a black colourway, this backpack is made from durable poly to keep all your things safe and secure. With a front and main zip compartment for easy access to ample storage, this downsized backpack comes with adjustable backstraps for the perfect fit, along with a carry handle for easy transport. Finished with contrasting \'Just Do It\' branding across the top, along with the iconic Swoosh to the front.', '24.png', 99],
            [26, 'Originals Mini', 'Adidas', 'Sports', 100, 'Embrace an icon with open arms. The beloved adidas 3-Stripes hug the sides of this roomy backpack for a clean, athletic look. Padded shoulder straps keep you feeling comfortable, even when you\'re carrying the weight of a legend.', '25.png', 120],
            [27, 'Originals Putro', 'Puma', 'Sports', 100, 'When retro design meets future-forward thinking, when sports heritage combines with urban contemporary influences, you end up with a backpack like this. With a two-way zip opening into the main compartment, a large zip pocket on the front, a slip-in pocket on both sides and a padded laptop compartment, it\'s got room to spare. Adjustable and padded shoulder straps mean it\'s comfy, too.', '26.png', 110],
        ];
        $stockInsert = [];
        foreach ($stockRows as $r) {
            $stockInsert[] = [
                'stock_id' => $r[0],
                'stock_name' => $r[1],
                'stock_brand' => $r[2],
                'stock_category' => $r[3],
                'stock_quantity' => $r[4],
                'stock_description' => $r[5],
                'stock_img' => $r[6],
                'stock_price' => $r[7],
            ];
        }
        $this->table('stock_inventory')->insert($stockInsert)->saveData();

        $this->table('purchase')->insert([
            [
                'purchase_id' => 'RST-1945584853',
                'id' => 2,
                'total_price' => '1758',
                'purchase_date' => '2021-07-31',
                'payment_resit' => 'RST-1439740896.png',
                'purchase_validation' => 'Approved',
            ],
            [
                'purchase_id' => 'RST-2052580739',
                'id' => 2,
                'total_price' => '17580',
                'purchase_date' => '2021-07-31',
                'payment_resit' => 'RST-1439740896.png',
                'purchase_validation' => 'Approved',
            ],
            [
                'purchase_id' => 'RST-642027559',
                'id' => 2,
                'total_price' => '44002',
                'purchase_date' => '2021-08-01',
                'payment_resit' => 'RST-1439740896.png',
                'purchase_validation' => 'Approved',
            ],
        ])->saveData();

        $this->table('purchase_item')->insert([
            [
                'purchase_item_id' => 75,
                'purchase_id' => 'RST-2052580739',
                'id' => 2,
                'stock_id' => 12,
                'stock_img' => '2.png',
                'stock_name' => 'City Pouch Plus',
                'stock_quantity' => 10,
                'stock_price' => '379',
                'purchase_date' => '2021-07-31',
            ],
            [
                'purchase_item_id' => 76,
                'purchase_id' => 'RST-2052580739',
                'id' => 2,
                'stock_id' => 14,
                'stock_img' => '0__2_-removebg-preview (1).png',
                'stock_name' => 'Weekender Plus',
                'stock_quantity' => 10,
                'stock_price' => '1379',
                'purchase_date' => '2021-07-31',
            ],
            [
                'purchase_item_id' => 77,
                'purchase_id' => 'RST-1945584853',
                'id' => 2,
                'stock_id' => 10,
                'stock_img' => '1.png',
                'stock_name' => 'Classic Backpack',
                'stock_quantity' => 1,
                'stock_price' => '379',
                'purchase_date' => '2021-07-31',
            ],
            [
                'purchase_item_id' => 78,
                'purchase_id' => 'RST-1945584853',
                'id' => 2,
                'stock_id' => 14,
                'stock_img' => '0__2_-removebg-preview (1).png',
                'stock_name' => 'Weekender Plus',
                'stock_quantity' => 1,
                'stock_price' => '1379',
                'purchase_date' => '2021-07-31',
            ],
            [
                'purchase_item_id' => 79,
                'purchase_id' => 'RST-642027559',
                'id' => 2,
                'stock_id' => 11,
                'stock_img' => '3.png',
                'stock_name' => 'Classic Backpack Plus',
                'stock_quantity' => 98,
                'stock_price' => '449',
                'purchase_date' => '2021-08-01',
            ],
        ])->saveData();

        $this->table('delivery')->insert([
            [
                'delivery_id' => 18,
                'id' => 2,
                'purchase_id' => 'RST-2052580739',
                'delivery_agent' => 'Processing',
                'delivery_status' => 'Processing',
                'address' => '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia',
                'payment_status' => 'Approved',
            ],
            [
                'delivery_id' => 19,
                'id' => 2,
                'purchase_id' => 'RST-1945584853',
                'delivery_agent' => 'Processing',
                'delivery_status' => 'Processing',
                'address' => '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia',
                'payment_status' => 'Approved',
            ],
            [
                'delivery_id' => 20,
                'id' => 2,
                'purchase_id' => 'RST-642027559',
                'delivery_agent' => 'Processing',
                'delivery_status' => 'Processing',
                'address' => '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia',
                'payment_status' => 'Approved',
            ],
        ])->saveData();
    }
}
