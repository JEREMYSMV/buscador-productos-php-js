-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2026 a las 16:51:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_productos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `title`) VALUES
(1, 'Ropa'),
(2, 'Calzado'),
(3, 'Accesorios'),
(4, 'men\'s clothing'),
(5, 'men\'s clothing'),
(6, 'men\'s clothing'),
(7, 'men\'s clothing'),
(8, 'jewelery'),
(9, 'jewelery'),
(10, 'jewelery'),
(11, 'jewelery'),
(12, 'electronics'),
(13, 'electronics'),
(14, 'electronics'),
(15, 'electronics'),
(16, 'electronics'),
(17, 'electronics'),
(18, 'women\'s clothing'),
(19, 'women\'s clothing'),
(20, 'women\'s clothing'),
(21, 'women\'s clothing'),
(22, 'women\'s clothing'),
(23, 'women\'s clothing');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id`, `producto_id`, `url`) VALUES
(1, 1, 'images/polo-negro.jpg'),
(2, 2, 'images/zapatillas-blancas.jpg'),
(3, 3, 'images/mochila-azul.jpg'),
(4, 4, 'https://fakestoreapi.com/img/71YXzeOuslL._AC_UY879_t.png'),
(5, 5, 'https://fakestoreapi.com/img/71pWzhdJNwL._AC_UL640_QL65_ML3_t.png'),
(6, 6, 'https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_t.png'),
(7, 7, 'https://fakestoreapi.com/img/71YAIFU48IL._AC_UL640_QL65_ML3_t.png'),
(8, 8, 'https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_t.png'),
(9, 9, 'https://fakestoreapi.com/img/61IBBVJvSDL._AC_SY879_t.png'),
(10, 10, 'https://fakestoreapi.com/img/61U7T1koQqL._AC_SX679_t.png'),
(11, 11, 'https://fakestoreapi.com/img/71kWymZ+c+L._AC_SX679_t.png'),
(12, 12, 'https://fakestoreapi.com/img/61mtL65D4cL._AC_SX679_t.png'),
(13, 13, 'https://fakestoreapi.com/img/81QpkIctqPL._AC_SX679_t.png'),
(14, 14, 'https://fakestoreapi.com/img/81Zt42ioCgL._AC_SX679_t.png'),
(15, 15, 'https://fakestoreapi.com/img/51Y5NI-I5jL._AC_UX679_t.png'),
(16, 16, 'https://fakestoreapi.com/img/81XH0e8fefL._AC_UY879_t.png'),
(17, 17, 'https://fakestoreapi.com/img/71HblAHs5xL._AC_UY879_-2t.png'),
(18, 18, 'https://fakestoreapi.com/img/71z3kpMAYsL._AC_UY879_t.png'),
(19, 19, 'https://fakestoreapi.com/img/51eg55uWmdL._AC_UX679_t.png'),
(20, 20, 'https://fakestoreapi.com/img/61pHAEJ4NML._AC_UX679_t.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `title`, `price`, `description`, `category`, `categoria_id`, `stock`) VALUES
(1, 'Polo negro', 35.00, 'Polo de algodón', 'Ropa', 1, 10),
(2, 'Zapatillas blancas', 120.00, 'Zapatillas casuales', 'Calzado', 2, 10),
(3, 'Mochila azul', 65.00, 'Mochila para uso diario', 'Accesorios', 3, 10),
(4, 'Mens Casual Slim Fit', 15.99, 'The color could be slightly different between on the screen and in practice. / Please note that body builds vary by person, therefore, detailed size information should be reviewed below on the product description.', NULL, 4, 10),
(5, 'John Hardy Women\'s Legends Naga Gold & Silver Dragon Station Chain Bracelet', 695.00, 'From our Legends Collection, the Naga was inspired by the mythical water dragon that protects the ocean\'s pearl. Wear facing inward to be bestowed with love and abundance, or outward for protection.', NULL, 8, 10),
(6, 'Solid Gold Petite Micropave ', 168.00, 'Satisfaction Guaranteed. Return or exchange any order within 30 days.Designed and sold by Hafeez Center in the United States. Satisfaction Guaranteed. Return or exchange any order within 30 days.', NULL, 8, 10),
(7, 'White Gold Plated Princess', 9.99, 'Classic Created Wedding Engagement Solitaire Diamond Promise Ring for Her. Gifts to spoil your love more for Engagement, Wedding, Anniversary, Valentine\'s Day...', NULL, 8, 10),
(8, 'Pierced Owl Rose Gold Plated Stainless Steel Double', 10.99, 'Rose Gold Plated Double Flared Tunnel Plug Earrings. Made of 316L Stainless Steel', NULL, 8, 10),
(9, 'WD 2TB Elements Portable External Hard Drive - USB 3.0 ', 64.00, 'USB 3.0 and USB 2.0 Compatibility Fast data transfers Improve PC Performance High Capacity; Compatibility Formatted NTFS for Windows 10, Windows 8.1, Windows 7; Reformatting may be required for other operating systems; Compatibility may vary depending on user’s hardware configuration and operating system', NULL, 12, 10),
(10, 'SanDisk SSD PLUS 1TB Internal SSD - SATA III 6 Gb/s', 109.00, 'Easy upgrade for faster boot up, shutdown, application load and response (As compared to 5400 RPM SATA 2.5” hard drive; Based on published specifications and internal benchmarking tests using PCMark vantage scores) Boosts burst write performance, making it ideal for typical PC workloads The perfect balance of performance and reliability Read/write speeds of up to 535MB/s/450MB/s (Based on internal testing; Performance may vary depending upon drive capacity, host device, OS and application.)', NULL, 12, 10),
(11, 'Silicon Power 256GB SSD 3D NAND A55 SLC Cache Performance Boost SATA III 2.5', 109.00, '3D NAND flash are applied to deliver high transfer speeds Remarkable transfer speeds that enable faster bootup and improved overall system performance. The advanced SLC Cache Technology allows performance boost and longer lifespan 7mm slim design suitable for Ultrabooks and Ultra-slim notebooks. Supports TRIM command, Garbage Collection technology, RAID, and ECC (Error Checking & Correction) to provide the optimized performance and enhanced reliability.', NULL, 12, 10),
(12, 'WD 4TB Gaming Drive Works with Playstation 4 Portable External Hard Drive', 114.00, 'Expand your PS4 gaming experience, Play anywhere Fast and easy, setup Sleek design with high capacity, 3-year manufacturer\'s limited warranty', NULL, 12, 10),
(13, 'Acer SB220Q bi 21.5 inches Full HD (1920 x 1080) IPS Ultra-Thin', 599.00, '21. 5 inches Full HD (1920 x 1080) widescreen IPS display And Radeon free Sync technology. No compatibility for VESA Mount Refresh Rate: 75Hz - Using HDMI port Zero-frame design | ultra-thin | 4ms response time | IPS panel Aspect ratio - 16: 9. Color Supported - 16. 7 million colors. Brightness - 250 nit Tilt angle -5 degree to 15 degree. Horizontal viewing angle-178 degree. Vertical viewing angle-178 degree 75 hertz', NULL, 12, 10),
(14, 'Samsung 49-Inch CHG90 144Hz Curved Gaming Monitor (LC49HG90DMNXZA) – Super Ultrawide Screen QLED ', 999.99, '49 INCH SUPER ULTRAWIDE 32:9 CURVED GAMING MONITOR with dual 27 inch screen side by side QUANTUM DOT (QLED) TECHNOLOGY, HDR support and factory calibration provides stunningly realistic and accurate color and contrast 144HZ HIGH REFRESH RATE and 1ms ultra fast response time work to eliminate motion blur, ghosting, and reduce input lag', NULL, 12, 10),
(15, 'BIYLACLESEN Women\'s 3-in-1 Snowboard Jacket Winter Coats', 56.99, 'Note:The Jackets is US standard size, Please choose size as your usual wear Material: 100% Polyester; Detachable Liner Fabric: Warm Fleece. Detachable Functional Liner: Skin Friendly, Lightweigt and Warm.Stand Collar Liner jacket, keep you warm in cold weather. Zippered Pockets: 2 Zippered Hand Pockets, 2 Zippered Pockets on Chest (enough to keep cards or keys)and 1 Hidden Pocket Inside.Zippered Hand Pockets and Hidden Pocket keep your things secure. Humanized Design: Adjustable and Detachable Hood and Adjustable cuff to prevent the wind and water,for a comfortable fit. 3 in 1 Detachable Design provide more convenience, you can separate the coat and inner as needed, or wear it together. It is suitable for different season and help you adapt to different climates', NULL, 18, 10),
(16, 'Lock and Love Women\'s Removable Hooded Faux Leather Moto Biker Jacket', 29.95, '100% POLYURETHANE(shell) 100% POLYESTER(lining) 75% POLYESTER 25% COTTON (SWEATER), Faux leather material for style and comfort / 2 pockets of front, 2-For-One Hooded denim style faux leather jacket, Button detail on waist / Detail stitching at sides, HAND WASH ONLY / DO NOT BLEACH / LINE DRY / DO NOT IRON', NULL, 18, 10),
(17, 'Rain Jacket Women Windbreaker Striped Climbing Raincoats', 39.99, 'Lightweight perfet for trip or casual wear---Long sleeve with hooded, adjustable drawstring waist design. Button and zipper front closure raincoat, fully stripes Lined and The Raincoat has 2 side pockets are a good size to hold all kinds of things, it covers the hips, and the hood is generous but doesn\'t overdo it.Attached Cotton Lined Hood with Adjustable Drawstrings give it a real styled look.', NULL, 18, 10),
(18, 'MBJ Women\'s Solid Short Sleeve Boat Neck V ', 9.85, '95% RAYON 5% SPANDEX, Made in USA or Imported, Do Not Bleach, Lightweight fabric with great stretch for comfort, Ribbed on sleeves and neckline / Double stitching on bottom hem', NULL, 18, 10),
(19, 'Opna Women\'s Short Sleeve Moisture', 7.95, '100% Polyester, Machine wash, 100% cationic polyester interlock, Machine Wash & Pre Shrunk for a Great Fit, Lightweight, roomy and highly breathable with moisture wicking fabric which helps to keep moisture away, Soft Lightweight Fabric with comfortable V-neck collar and a slimmer fit, delivers a sleek, more feminine silhouette and Added Comfort', NULL, 18, 10),
(20, 'DANVOUY Womens T Shirt Casual Cotton Short', 12.99, '95%Cotton,5%Spandex, Features: Casual, Short Sleeve, Letter Print,V-Neck,Fashion Tees, The fabric is soft and has some stretch., Occasion: Casual/Office/Beach/School/Home/Street. Season: Spring,Summer,Autumn,Winter.', NULL, 18, 10);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_productos_categoria` (`categoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
