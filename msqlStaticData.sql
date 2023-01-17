INSERT INTO `cats` (`id`, `title`, `parent_cat`, `type`, `status`) VALUES
(1, 'Homelist', NULL, '', '10'),
(2, 'Officelist', NULL, '', '10'),
(3, 'Primelist', NULL, '', '10'),
(4, 'Landlist', NULL, '', '10'),
(5, 'Booklist', NULL, '', '10'),
(6, 'Appartements à vendre', 1, 'vente', '10'),
(7, 'Appartements à louer', 1, 'location', '10'),
(8, 'Villas à vendre	', 1, 'vente', '10'),
(9, 'Villas à louer	', 1, 'location', '10'),
(10, 'Bureaux à vendre	', 2, 'vente', '10'),
(11, 'Bureaux à louer', 2, 'loction', '10'),
(12, 'Locaux commerciaux à vendre	', 2, 'vente', '10'),
(13, 'Locaux commerciaux à louer	', 2, 'location', '10'),
(14, 'PROJETS D\'APPARTEMENTS', 3, 'vente', '10'),
(15, 'PROJETS DE VILLAS	', 3, 'vente', '10'),
(16, 'Terrains à louer', 4, 'location', '10'),
(17, 'Terrains agricoles à vendre', 4, 'vente', '10'),
(20, 'Villas, riads courte durée	', 5, 'vacance', '10'),
(21, 'Appartements courte durée', 5, 'vacance', '10'),
(22, 'test23', 1, 'vente', '20');


INSERT INTO `auth_type` (`id`, `designation`) VALUES
(1, 'normal'),
(2, 'google'),
(3, 'facebook');

INSERT INTO `option_type` (`id`, `designation`) VALUES
(1, 'À la une'),
(2, 'Premium'),
(3, 'Tête de liste');

INSERT INTO `settings` (`id`, `max_ad_img`, `max_ad_video`, `max_ad_audio`, `ads_expire_duration`, `users_expire_duration`, `max_user_ads`, `image_max_size`, `video_max_size`, `audio_max_size`, `facebook`, `instagram`, `twitter`, `linkedin`, `youtube`, `tiktok`, `seo`) VALUES
(1, 12, 1, 1, 60, 365, 10, 1000, 10000, 3000, 'https://www.facebook.com/Multilist.group', 'https://www.instagram.com/multilist.immo/', NULL, 'https://www.linkedin.com/company/multilist-immo', 'https://www.youtube.com/channel/UCNjQB9JoAbAmQJDXrDIjn4w', NULL, '[{\"icon\": {\"id\": 1390, \"name\": \"/images/img_62b5e15245915.ico\"}, \"logo\": {\"id\": 1389, \"name\": \"/images/img_62b5e14aa6583.jpg\"}, \"name\": \"multilist\", \"main_bg\": {\"id\": 1391, \"name\": \"/images/img_62b5e155d09c9.png\"}, \"meta_tags\": \"\", \"main_color\": \"#ff0000\", \"third_color\": \"#0055ff\", \"secondary_color\": \"#00ff6e\"}, {\"name\": \"booklist\", \"meta_tags\": \"\", \"main_color\": \"#b52483\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"homelist\", \"meta_tags\": \"\", \"main_color\": \"#f64d4b\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"primelist\", \"meta_tags\": \"\", \"main_color\": \"#f3be2e\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"landlist\", \"meta_tags\": \"\", \"main_color\": \"#54c21b\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"officelist\", \"meta_tags\": \"\", \"main_color\": \"#00537d\", \"third_color\": \"\", \"secondary_color\": \"\"}]');

INSERT INTO `places_type` (`id`, `designation`, `icon`) VALUES
(1, 'école', NULL),
(2, 'mosquée', NULL);

INSERT INTO `options_catalogue` (`id`, `designation`, `price`, `type_id`, `duration`) VALUES
(1, 'À la une 7 jours', 0, 1, 7),
(2, 'À la une 15 jours', 0, 1, 15),
(3, 'À la une 30 jours', 0, 1, 30),
(4, 'Premium 7 jours', 0, 2, 7),
(5, 'Premium 15 jours', 0, 2, 15),
(6, 'Premium 30 jours', 0, 2, 30),
(7, 'Tête de liste 7 jours', 0, 3, 7),
(8, 'Tête de liste 15 jours', 0, 3, 15),
(9, 'Tête de liste 30 jours', 0, 3, 30);

INSERT INTO `user_type` (`id`, `designation`, `only_visible_to`) VALUES
(1, 'super admin', 'admin'),
(2, 'admin', 'admin'),
(3, 'moderator', 'admin'),
(4, 'promoter', 'admin'),
(5, 'agency', 'admin'),
(6, 'particular', 'admin'),
(7, 'comercial', 'admin'),
(8, 'ced', 'admin');
