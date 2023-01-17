CREATE TABLE actions (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    controller varchar(255) NOT NULL,
    action varchar(255) NOT NULL,
    description varchar(255) NOT NULL
);

CREATE TABLE ads (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title varchar(250) NOT NULL,
    description text NOT NULL,
    catid int(11) NOT NULL,
    price float NOT NULL,
    price_curr varchar(3) NOT NULL,
    videoembed varchar(1000) DEFAULT NULL,
    loclng double DEFAULT NULL,
    loclat double DEFAULT NULL,
    loczipcode varchar(25) DEFAULT NULL,
    loccity int(11) DEFAULT NULL,
    locdept int(11) DEFAULT NULL,
    lcocity2 varchar(200) DEFAULT NULL,
    locdept2 varchar(200) DEFAULT NULL,
    locregion varchar(50) DEFAULT NULL,
    loccountrycode varchar(2) NOT NULL,
    id_user int(11) NOT NULL,
    phone varchar(50) NOT NULL,
    phone2 varchar(50) DEFAULT NULL,
    wtsp varchar(50) DEFAULT NULL,
    email varchar(150) NOT NULL,
    likes int(11) NOT NULL DEFAULT '0',
    audio int(11) DEFAULT NULL,
    rooms int(11) DEFAULT NULL,
    bedrooms int(11) DEFAULT NULL,
    bathrooms int(11) DEFAULT NULL,
    surface int(11) DEFAULT NULL,
    surface2 int(11) DEFAULT NULL,
    built_year varchar(5) DEFAULT NULL,
    parking int(11) DEFAULT NULL,
    jardin int(11) DEFAULT NULL,
    piscine int(11) DEFAULT NULL,
    price_surface int(11) DEFAULT NULL,
    property_type int(11) DEFAULT NULL,
    standing int(11) DEFAULT NULL,
    ref varchar(25) DEFAULT NULL,
    is_project tinyint(1) DEFAULT NULL,
    bg_image int(11) DEFAULT NULL,
    parent_project int(11) DEFAULT NULL,
    meuble tinyint(1) DEFAULT NULL,
    vr_link varchar(300) DEFAULT NULL,
    expiredate timestamp NULL DEFAULT NULL,
    status varchar(2) NOT NULL DEFAULT '00'
);

CREATE TABLE ad_media (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ad_id int(11) NOT NULL,
    media_id int(11) NOT NULL
);

CREATE TABLE article (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title varchar(150) NOT NULL,
    text text NOT NULL,
    status varchar(2) NOT NULL
);

CREATE TABLE article_media (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    media_id int(11) NOT NULL
);

CREATE TABLE auth_type (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(25) NOT NULL
);

CREATE TABLE banner (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    posistion varchar(10) NOT NULL,
    html_code text NOT NULL,
    active tinyint(1) NOT NULL
);

CREATE TABLE cats (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title varchar(100) NOT NULL,
    parent_cat int(11) DEFAULT NULL,
    type varchar(10) NOT NULL,
    status varchar(2) NOT NULL
);

CREATE TABLE cities (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    lat double DEFAULT NULL,
    lng double DEFAULT NULL,
    coordinates multipoint DEFAULT NULL,
    point point DEFAULT NULL,
    province_id int(11) DEFAULT NULL
);

CREATE TABLE clicks (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type varchar(10) NOT NULL,
    ad_id int(11) NOT NULL,
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contracts (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    assigned_user int(11) NOT NULL,
    comment varchar(500) DEFAULT NULL,
    price double NOT NULL,
    plan_id int(11) NOT NULL,
    ltc_nbr int(11) NOT NULL,
    ads_nbr int(11) NOT NULL,
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    duration int(11) NOT NULL,
    contract_file int(11) DEFAULT NULL,
    active tinyint(1) NOT NULL DEFAULT '1'
);

CREATE TABLE countries (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    code varchar(4) NOT NULL,
    coordinates multipolygon DEFAULT NULL
);

CREATE TABLE districts (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    lat double DEFAULT NULL,
    lng double DEFAULT NULL,
    coordinates multipolygon DEFAULT NULL,
    district_id int(11) DEFAULT NULL
);

CREATE TABLE districts_parent (
    id int(11) NOT NULL,
    name varchar(100) NOT NULL,
    coordinates multipolygon DEFAULT NULL,
    city_id int(11) NOT NULL
);

CREATE TABLE emails (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ad_id int(11) NOT NULL,
    name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    phone varchar(50) DEFAULT NULL,
    message text NOT NULL,
    status int(11) NOT NULL DEFAULT '0',
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userid int(11) NOT NULL,
    event_type int(11) NOT NULL,
    comment varchar(500) NOT NULL,
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE event_types (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(75) NOT NULL
);

CREATE TABLE failed_jobs (
    id bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uuid varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    connection text COLLATE utf8mb4_unicode_ci NOT NULL,
    queue text COLLATE utf8mb4_unicode_ci NOT NULL,
    payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    exception longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jobs (
    id bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    queue varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    attempts tinyint(3) UNSIGNED NOT NULL,
    reserved_at int(10) UNSIGNED DEFAULT NULL,
    available_at int(10) UNSIGNED NOT NULL
);

CREATE TABLE logs (
    id bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    action varchar(20) NOT NULL,
    what varchar(10) NOT NULL,
    what_id int(11) NOT NULL,
    description varchar(300) NOT NULL,
    timestamps timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE media (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    path varchar(100) NOT NULL,
    filename varchar(100) NOT NULL,
    filesize varchar(50) DEFAULT NULL,
    extension varchar(10) DEFAULT NULL,
    media_type int(11) DEFAULT NULL
);

CREATE TABLE media_type (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(25) NOT NULL
);

CREATE TABLE messages (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sender int(11) NOT NULL,
    receiver int(11) NOT NULL,
    message text NOT NULL,
    status int(11) NOT NULL DEFAULT '0',
);

CREATE TABLE migrations (
    id int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    migration varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    batch int(11) NOT NULL
);

CREATE TABLE nearby_places (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_ad int(11) NOT NULL,
    distance int(11) DEFAULT NULL,
    lng double DEFAULT NULL,
    lat double DEFAULT NULL,
    title varchar(100) NOT NULL,
    id_place_type int(11) NOT NULL
);

CREATE TABLE options (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    option_id int(11) NOT NULL,
    ad_id int(11) NOT NULL,
    status varchar(2) NOT NULL DEFAULT '00',
    timestamp timestamp NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE options_catalogue (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(100) NOT NULL,
    price float NOT NULL,
    type_id int(11) DEFAULT NULL,
    duration int(11) NOT NULL
);

CREATE TABLE option_type (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(100) NOT NULL
);

CREATE TABLE password_resets (
    email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    token varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
);

CREATE TABLE payements (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    contract_id int(11) NOT NULL,
    amount double NOT NULL,
    date timestamp NULL DEFAULT NULL,
    due_date timestamp NULL DEFAULT NULL,
    attach_file int(11) NOT NULL,
);

CREATE TABLE personal_access_tokens (
    id bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tokenable_type varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    tokenable_id bigint(20) UNSIGNED NOT NULL,
    name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    token varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
    abilities text COLLATE utf8mb4_unicode_ci,
    last_used_at timestamp NULL DEFAULT NULL,
);

CREATE TABLE places_type (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(50) NOT NULL,
    icon varchar(30) DEFAULT NULL
);

CREATE TABLE plan_catalogue (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ads_nbr int(11) NOT NULL,
    ltc_nbr int(11) NOT NULL,
    price double NOT NULL,
    duration int(11) NOT NULL,
    description varchar(500) NOT NULL
);

CREATE TABLE propositions (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    prospect_id int(11) NOT NULL,
    comment varchar(500) NOT NULL,
    amount double NOT NULL,
    plan_id int(11) NOT NULL,
    ltc_amount float NOT NULL,
    assigned_user int(11) NOT NULL,
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);

CREATE TABLE proprety_types (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(100) NOT NULL
);

CREATE TABLE prospects (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(75) NOT NULL,
    company_name varchar(75) NOT NULL,
    email varchar(75) NOT NULL,
    phone varchar(30) NOT NULL,
    assigned_user int(11) NOT NULL,
);

CREATE TABLE provinces (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    coordinates multipolygon DEFAULT NULL,
    region_id int(11) NOT NULL
);

CREATE TABLE pro_user_info (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    longdesc text,
    address varchar(300) DEFAULT NULL,
    loclng varchar(20) DEFAULT NULL,
    loclat varchar(20) DEFAULT NULL,
    city varchar(50) DEFAULT NULL,
    locdept varchar(50) DEFAULT NULL,
    locregion varchar(50) DEFAULT NULL,
    loccountrycode varchar(20) DEFAULT NULL,
    videoembed text,
    video int(11) DEFAULT NULL,
    audio int(11) DEFAULT NULL,
    image int(11) DEFAULT NULL,
    company varchar(50) NOT NULL,
    website varchar(70) DEFAULT NULL,
    probannerimg int(11) DEFAULT NULL,
    metatitle text,
    metadesc text
);

CREATE TABLE regions (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    coordinates multipolygon NOT NULL,
    country_id int(11) NOT NULL
);

CREATE TABLE settings (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    max_ad_img int(11) NOT NULL,
    max_ad_video int(11) DEFAULT NULL,
    max_ad_audio int(11) DEFAULT NULL,
    ads_expire_duration int(11) DEFAULT NULL,
    users_expire_duration int(11) DEFAULT NULL,
    max_user_ads int(11) DEFAULT NULL,
    image_max_size int(11) DEFAULT NULL,
    video_max_size int(11) DEFAULT NULL,
    audio_max_size int(11) DEFAULT NULL,
    facebook varchar(100) DEFAULT NULL,
    instagram varchar(100) DEFAULT NULL,
    twitter varchar(100) DEFAULT NULL,
    linkedin varchar(100) DEFAULT NULL,
    youtube varchar(100) DEFAULT NULL,
    tiktok varchar(100) DEFAULT NULL,
    seo json DEFAULT NULL
);

CREATE TABLE standing (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(50) NOT NULL
);

CREATE TABLE transactions (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    amount float NOT NULL,
    type varchar(60) NOT NULL,
    notes varchar(500) NOT NULL,
    datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE type_actions (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type_id int(11) NOT NULL,
    action_id int(11) NOT NULL
);

CREATE TABLE users (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    firstname varchar(50) NOT NULL,
    lastname varchar(50) NOT NULL,
    username varchar(25) NOT NULL,
    email varchar(100) NOT NULL,
    phone varchar(20) DEFAULT NULL,
    usertype int(11) NOT NULL,
    birthdate date DEFAULT NULL,
    expiredate timestamp NULL DEFAULT NULL,
    password varchar(100) NOT NULL,
    authtype int(11) DEFAULT NULL,
    status varchar(2) NOT NULL DEFAULT '00',
    api_token varchar(100) DEFAULT NULL,
    remember_token varchar(100) DEFAULT NULL,
    assigned_user int(11) DEFAULT NULL,
    assigned_ced int(11) DEFAULT NULL,
    coins int(11) NOT NULL DEFAULT '0'
);

CREATE TABLE user_contacts (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    value varchar(100) NOT NULL,
    type varchar(10) NOT NULL
);

CREATE TABLE user_info (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    gender varchar(50) NOT NULL,
    bio varchar(300) DEFAULT NULL,
    avatar int(11) DEFAULT NULL,
    likes int(11) NOT NULL DEFAULT '0'
);

CREATE TABLE user_plan (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    plan_id int(11) NOT NULL,
    status varchar(2) NOT NULL,
    timestamps timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
);

CREATE TABLE user_type (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    designation varchar(25) NOT NULL,
    only_visible_to varchar(255) DEFAULT NULL
);

# user ( id name email password usertype authtype status )
# roles ( id designation )
# model_has_roles ( model_id role_id )

