-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2021 at 01:57 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bag_biz`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `item_img` varchar(100) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_price` varchar(100) NOT NULL,
  `item_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `purchase_id` varchar(100) NOT NULL,
  `delivery_agent` varchar(100) NOT NULL,
  `delivery_status` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `payment_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `id`, `purchase_id`, `delivery_agent`, `delivery_status`, `address`, `payment_status`) VALUES
(18, 2, 'RST-2052580739', 'Processing', 'Processing', '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia', 'Approved'),
(19, 2, 'RST-1945584853', 'Processing', 'Processing', '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia', 'Approved'),
(20, 2, 'RST-642027559', 'Processing', 'Processing', '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `purchase_date` date NOT NULL,
  `payment_resit` varchar(200) NOT NULL,
  `purchase_validation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchase_id`, `id`, `total_price`, `purchase_date`, `payment_resit`, `purchase_validation`) VALUES
('RST-1945584853', 2, '1758', '2021-07-31', 'RST-1439740896.png', 'Approved'),
('RST-2052580739', 2, '17580', '2021-07-31', 'RST-1439740896.png', 'Approved'),
('RST-642027559', 2, '44002', '2021-08-01', 'RST-1439740896.png', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_item`
--

CREATE TABLE `purchase_item` (
  `purchase_item_id` int(11) NOT NULL,
  `purchase_id` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_img` varchar(200) NOT NULL,
  `stock_name` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `stock_price` varchar(100) NOT NULL,
  `purchase_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase_item`
--

INSERT INTO `purchase_item` (`purchase_item_id`, `purchase_id`, `id`, `stock_id`, `stock_img`, `stock_name`, `stock_quantity`, `stock_price`, `purchase_date`) VALUES
(75, 'RST-2052580739', 2, 12, '2.png', 'City Pouch Plus', 10, '379', '2021-07-31'),
(76, 'RST-2052580739', 2, 14, '0__2_-removebg-preview (1).png', 'Weekender Plus', 10, '1379', '2021-07-31'),
(77, 'RST-1945584853', 2, 10, '1.png', 'Classic Backpack', 1, '379', '2021-07-31'),
(78, 'RST-1945584853', 2, 14, '0__2_-removebg-preview (1).png', 'Weekender Plus', 1, '1379', '2021-07-31'),
(79, 'RST-642027559', 2, 11, '3.png', 'Classic Backpack Plus', 98, '449', '2021-08-01');

-- --------------------------------------------------------

--
-- Table structure for table `stock_inventory`
--

CREATE TABLE `stock_inventory` (
  `stock_id` int(11) NOT NULL,
  `stock_name` varchar(100) NOT NULL,
  `stock_brand` varchar(100) NOT NULL,
  `stock_category` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `stock_description` varchar(10000) NOT NULL,
  `stock_img` varchar(200) NOT NULL,
  `stock_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_inventory`
--

INSERT INTO `stock_inventory` (`stock_id`, `stock_name`, `stock_brand`, `stock_category`, `stock_quantity`, `stock_description`, `stock_img`, `stock_price`) VALUES
(10, 'Classic Backpack', 'Gucci', 'Mens', 89, 'With a minimalist look and maximum access, this compact backpack keeps urban professionals ready for business.\r\n\r\n', '1.png', 379),
(11, 'Classic Backpack Plus', 'Gucci', 'Mens', 0, 'This thoughtfully designed travel backpack is loaded with organization and easy access, to keep your business trip or long weekend adventure moving smoothly.', '3.png', 449),
(12, 'City Pouch Plus', 'Bellroy', 'Mens', 84, 'This slim sidekick keeps your pocket essentials and small devices locked and loaded, so you and your pockets stay agile.', '2.png', 379),
(13, 'Tokyo Totepack', 'Céline', 'Mens', 96, 'This slim urban backpack features pared-down aesthetics and easy access, to keep modern professionals ready for business.', '4.png', 859),
(14, 'Weekender Plus', 'Coach', 'Mens', 85, 'This spacious bag combines casual weekend styling with clever organization, so you can move smoothly and focus on the journey.', '0__2_-removebg-preview (1).png', 1379),
(15, 'Melbourne Backpack', 'J. W. Anderson', 'Mens', 98, 'This experimental Melbourne Backpack is a super slim sidekick, with a uniquely expressive textured finish.\r\n\r\n', '5.png', 679),
(16, 'Market Tote', 'Louis Vuitton', 'Women', 98, 'From grocery runs to beach days, this versatile tote folds easily away, and pops out when and where you need it.', '11.png', 189),
(17, 'Sling', 'Christian Dior', 'Women', 89, 'Keep your hands free and pockets light with a sling that’s big enough for the essentials, yet small enough to let you move like a New York minute.', '12.png', 369),
(18, 'Tokyo Tote Compact', 'Prada', 'Women', 99, 'This premium leather upgrade to our Tokyo Tote Compact has easy organization, to keep your everyday sorted, with a touch of luxe.', '13.png', 929),
(19, 'City Pouch', 'Valentino', 'Women', 100, 'A super slim sidekick that keeps essentials at hand, this is a premium leather upgrade on our regular City Pouch.', '14.png', 479),
(20, 'Duo Totepack', 'Céline', 'Women', 100, 'The leathers we use are premium hides tanned under gold-rated Leather Working Group environmental protocols, then dyed through so they age gracefully.', '15.png', 929),
(21, 'The Saddle Bag', 'Coach', 'Women', 100, 'The leathers we use are premium hides tanned under gold-rated Leather Working Group environmental protocols, then dyed through so they age gracefully.', '16.png', 1229),
(22, 'ELMNTL BKPK', 'Nike', 'Sports', 100, 'Best for: Lifestyle/Nike Sportswear Elemental. Backpack. CLASSIC DESIGN. DURABLE STORAGE. The Nike Sportswear Elemental Backpack is a new spin on an old classic. Its durable design features 2 large compartments and 2 external pockets for small-item storage, while the padded shoulder straps offer supportive comfort.', '21.png', 145),
(23, 'Originals Classic', 'Adidas', 'Sports', 100, 'This small adidas backpack is built of fabric crafted from 100% recycled materials, which means you can feel good about your daily carry. Plus, a bottle sleeve on each side makes it easy to bring your beverages from home.', '22.png', 99),
(24, 'ADICOLOR CLASSIC', 'Adidas', 'Sports', 100, 'Since 1972, the Trefoil has stood out on the streets. Carry on the tradition every time you load up this adidas backpack. Padded shoulder straps keep you comfortable no matter what you toss inside. This product is made with Primegreen, a series of high-performance recycled materials.', '23.png', 859),
(25, 'Nike Brasilia', 'Nike', 'Sports', 100, 'Get some statement Swoosh style that keeps your essentials covered with this just Do It Mini Backpack from Nike. In a black colourway, this backpack is made from durable poly to keep all your things safe and secure. With a front and main zip compartment for easy access to ample storage, this downsized backpack comes with adjustable backstraps for the perfect fit, along with a carry handle for easy transport. Finished with contrasting \'Just Do It\' branding across the top, along with the iconic Swoosh to the front.', '24.png', 99),
(26, 'Originals Mini', 'Adidas', 'Sports', 100, 'Embrace an icon with open arms. The beloved adidas 3-Stripes hug the sides of this roomy backpack for a clean, athletic look. Padded shoulder straps keep you feeling comfortable, even when you\'re carrying the weight of a legend.', '25.png', 120),
(27, 'Originals Putro', 'Puma', 'Sports', 100, 'When retro design meets future-forward thinking, when sports heritage combines with urban contemporary influences, you end up with a backpack like this. With a two-way zip opening into the main compartment, a large zip pocket on the front, a slip-in pocket on both sides and a padded laptop compartment, it\'s got room to spare. Adjustable and padded shoulder straps mean it\'s comfy, too.', '26.png', 110);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `suppliers_id` int(11) NOT NULL,
  `suppliers_name` varchar(100) NOT NULL,
  `stock_brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`suppliers_id`, `suppliers_name`, `stock_brand`) VALUES
(3, 'Urban Carrier', 'Gucci'),
(4, 'Adidas Malaysia', 'Adidas '),
(5, 'Haute Zone', 'Bellroy'),
(6, 'AdVenture Bags', 'Céline'),
(7, 'StrapIt', 'Christian Dior'),
(8, 'West Bag Co', 'Coach'),
(9, 'Pursify', 'J. W. Anderson'),
(10, 'Royal Bag', 'Louis Vuitton'),
(11, 'Nike Malaysia', 'Nike'),
(12, 'Sandast', 'Prada'),
(13, 'Puma Malaysia', 'Puma'),
(14, 'Baganic', 'Valentino');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `address`, `contact`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'admin', '64 LUN Perusahaan Keledang 1 Taman Perindustrian Chandan Raya 31450 Menglembu Perak Malaysia', '0162153958'),
(2, 'ahmad123', '827ccb0eea8a706c4c34a16891f84e7b', 'Ahmad', '2 Oversea Chinese Bank Corp Jln Ibrahim 80000 Johor 80000 Malaysia Johor 80000 Malaysia', '0123456789'),
(20, 'Siti99', '900150983cd24fb0d6963f7d28e17f72', 'Siti Salfa', 'Blok 81 Jln Tembusu Perjiranan 9 81700 Pasir Gudang Johor Pasir Gudang Johor 81700 Malaysia', '0198765432'),
(21, 'sameul98', '827ccb0eea8a706c4c34a16891f84e7b', 'Samuel Jackson', '61B Jln Semeliang(Pekan Baru Kkn) 06300 Kuala Nerang Kedah Kuala Nerang Kedah 06300 Malaysia', '0121555525'),
(22, 'chong98', 'e2fc714c4727ee9395f324cd2e7f331f', 'Lee Chong Wei', '30 Jln 10/34A Kepong Entrepreneurs Park 52100 Wilayah Persekutuan 52100 Malaysia 52100 Malaysia', '0111155574 ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `User_id` (`id`);

--
-- Indexes for table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD PRIMARY KEY (`purchase_item_id`);

--
-- Indexes for table `stock_inventory`
--
ALTER TABLE `stock_inventory`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`suppliers_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `purchase_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `stock_inventory`
--
ALTER TABLE `stock_inventory`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `suppliers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
