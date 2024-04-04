/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ico` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffuseur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `derives` text COLLATE utf8mb4_unicode_ci,
  `ordre` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_post_id_foreign` (`post_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefixe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_clubs` int(11) NOT NULL,
  `nb_profs` int(11) NOT NULL,
  `nb_lieux` int(11) NOT NULL,
  `nb_events` int(11) NOT NULL,
  `view_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departements_slug_unique` (`slug`),
  KEY `departements_view_count_index` (`view_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `discipline_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discipline_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discipline_post_discipline_id_post_id_unique` (`discipline_id`,`post_id`),
  KEY `discipline_post_post_id_foreign` (`post_id`),
  CONSTRAINT `discipline_post_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discipline_post_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_d_c_t_booking_field_ss_field_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_d_c_t_booking_field_ss_field_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sousfield_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `inclus_all` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_d_c_t_booking_field_ss_field_valeurs_sousfield_id_foreign` (`sousfield_id`),
  CONSTRAINT `liens_d_c_t_booking_field_ss_field_valeurs_sousfield_id_foreign` FOREIGN KEY (`sousfield_id`) REFERENCES `liens_dis_cat_tar_book_field_ss_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_crit_val_sous_criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_crit_val_sous_criteres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dis_cat_crit_val_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_crit_val_sous_criteres_dis_cat_crit_val_id_foreign` (`dis_cat_crit_val_id`),
  CONSTRAINT `liens_dis_cat_crit_val_sous_criteres_dis_cat_crit_val_id_foreign` FOREIGN KEY (`dis_cat_crit_val_id`) REFERENCES `liens_disciplines_categories_criteres_valeurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_crit_val_ss_crit_valeur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_crit_val_ss_crit_valeur` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dcc_val_ss_crit_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `defaut` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_crit_val_ss_crit_valeur_dcc_val_ss_crit_id_foreign` (`dcc_val_ss_crit_id`),
  CONSTRAINT `liens_dis_cat_crit_val_ss_crit_valeur_dcc_val_ss_crit_id_foreign` FOREIGN KEY (`dcc_val_ss_crit_id`) REFERENCES `liens_dis_cat_crit_val_sous_criteres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_att_sous_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_att_sous_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tar_att_id` bigint(20) unsigned NOT NULL,
  `att_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_att_sous_attributs_cat_tar_att_id_foreign` (`cat_tar_att_id`),
  KEY `liens_dis_cat_tar_att_sous_attributs_att_valeur_id_foreign` (`att_valeur_id`),
  CONSTRAINT `liens_dis_cat_tar_att_sous_attributs_att_valeur_id_foreign` FOREIGN KEY (`att_valeur_id`) REFERENCES `liens_dis_cat_tar_att_valeurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `liens_dis_cat_tar_att_sous_attributs_cat_tar_att_id_foreign` FOREIGN KEY (`cat_tar_att_id`) REFERENCES `liens_dis_cat_tartyp_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_att_ssattr_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_att_ssattr_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sousattribut_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `inclus_all` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_att_ssattr_valeurs_sousattribut_id_foreign` (`sousattribut_id`),
  CONSTRAINT `liens_dis_cat_tar_att_ssattr_valeurs_sousattribut_id_foreign` FOREIGN KEY (`sousattribut_id`) REFERENCES `liens_dis_cat_tar_att_sous_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_att_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_att_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tar_att_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_att_valeurs_cat_tar_att_id_foreign` (`cat_tar_att_id`),
  CONSTRAINT `liens_dis_cat_tar_att_valeurs_cat_tar_att_id_foreign` FOREIGN KEY (`cat_tar_att_id`) REFERENCES `liens_dis_cat_tartyp_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_book_field_ss_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_book_field_ss_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_field_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_book_field_ss_fields_booking_field_id_foreign` (`booking_field_id`),
  CONSTRAINT `liens_dis_cat_tar_book_field_ss_fields_booking_field_id_foreign` FOREIGN KEY (`booking_field_id`) REFERENCES `liens_dis_cat_tar_booking_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_booking_field_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_booking_field_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tar_field_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_booking_field_valeurs_cat_tar_field_id_foreign` (`cat_tar_field_id`),
  CONSTRAINT `liens_dis_cat_tar_booking_field_valeurs_cat_tar_field_id_foreign` FOREIGN KEY (`cat_tar_field_id`) REFERENCES `liens_dis_cat_tar_booking_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tar_booking_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tar_booking_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tarif_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tar_booking_fields_cat_tarif_id_foreign` (`cat_tarif_id`),
  CONSTRAINT `liens_dis_cat_tar_booking_fields_cat_tarif_id_foreign` FOREIGN KEY (`cat_tarif_id`) REFERENCES `liens_dis_cat_tariftypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tariftypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tariftypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `tarif_type_id` bigint(20) unsigned NOT NULL,
  `show_planning` tinyint(1) NOT NULL DEFAULT '1',
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tariftypes_discipline_id_foreign` (`discipline_id`),
  KEY `liens_dis_cat_tariftypes_categorie_id_foreign` (`categorie_id`),
  KEY `liens_dis_cat_tariftypes_tarif_type_id_foreign` (`tarif_type_id`),
  CONSTRAINT `liens_dis_cat_tariftypes_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `liens_dis_cat_tariftypes_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `liens_dis_cat_tariftypes_tarif_type_id_foreign` FOREIGN KEY (`tarif_type_id`) REFERENCES `liste_tarifs_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_dis_cat_tartyp_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_dis_cat_tartyp_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tarif_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_dis_cat_tartyp_attributs_cat_tarif_id_foreign` (`cat_tarif_id`),
  CONSTRAINT `liens_dis_cat_tartyp_attributs_cat_tarif_id_foreign` FOREIGN KEY (`cat_tarif_id`) REFERENCES `liens_dis_cat_tariftypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_disciplines_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_disciplines_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `nom_categorie_pro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_categorie_client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `liens_disciplines_categories_slug_unique` (`slug`),
  UNIQUE KEY `liens_disciplines_categories_categorie_id_discipline_id_unique` (`categorie_id`,`discipline_id`),
  KEY `liens_disciplines_categories_discipline_id_foreign` (`discipline_id`),
  CONSTRAINT `liens_disciplines_categories_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_disciplines_categories_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_disciplines_categories_criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_disciplines_categories_criteres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `critere_id` bigint(20) unsigned DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `visible_back` tinyint(1) NOT NULL DEFAULT '1',
  `visible_front` tinyint(1) NOT NULL DEFAULT '1',
  `visible_block` tinyint(1) NOT NULL DEFAULT '1',
  `indexable` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_disciplines_categories_criteres_discipline_id_foreign` (`discipline_id`),
  KEY `liens_disciplines_categories_criteres_categorie_id_foreign` (`categorie_id`),
  KEY `liens_disciplines_categories_criteres_critere_id_foreign` (`critere_id`),
  CONSTRAINT `liens_disciplines_categories_criteres_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_disciplines_categories_criteres_critere_id_foreign` FOREIGN KEY (`critere_id`) REFERENCES `liste_criteres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_disciplines_categories_criteres_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_disciplines_categories_criteres_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_disciplines_categories_criteres_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_categorie_critere_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(10) unsigned DEFAULT NULL,
  `defaut` tinyint(1) NOT NULL,
  `inclus_all` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_disciplines_similaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_disciplines_similaires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `discipline_similaire_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `liens_disciplines_similaires_discipline_id_foreign` (`discipline_id`),
  KEY `liens_disciplines_similaires_discipline_similaire_id_foreign` (`discipline_similaire_id`),
  CONSTRAINT `liens_disciplines_similaires_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_disciplines_similaires_discipline_similaire_id_foreign` FOREIGN KEY (`discipline_similaire_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liens_familles_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liens_familles_disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `famille_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `liens_familles_disciplines_famille_id_foreign` (`famille_id`),
  KEY `liens_familles_disciplines_discipline_id_foreign` (`discipline_id`),
  CONSTRAINT `liens_familles_disciplines_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_familles_disciplines_famille_id_foreign` FOREIGN KEY (`famille_id`) REFERENCES `liste_familles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_user_id_foreign` (`user_id`),
  KEY `likes_post_id_foreign` (`post_id`),
  CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_criteres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `famille` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sous_famille` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefixe_de` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefixe_du` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefixe_le_la` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pratiquant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clubs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieux` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coachs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pratiquants` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_clubs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_profs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_clubs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_profs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h1_clubs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h1_profs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h2_clubs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h2_profs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description2` text COLLATE utf8mb4_unicode_ci,
  `description_clubs` text COLLATE utf8mb4_unicode_ci,
  `description_profs` text COLLATE utf8mb4_unicode_ci,
  `popularite` int(11) DEFAULT NULL,
  `fait` int(11) DEFAULT NULL,
  `nb_clubs` int(11) DEFAULT NULL,
  `nb_profs` int(11) DEFAULT NULL,
  `nb_lieux` int(11) DEFAULT NULL,
  `nb_events` int(11) DEFAULT NULL,
  `nb_articles` int(11) DEFAULT NULL,
  `view_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `liste_disciplines_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_familles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_familles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_long` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_clubs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_profs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description_clubs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description_profs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h1_clubs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h1_profs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h2_clubs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h2_profs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_clubs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_profs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(11) NOT NULL,
  `view_count` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `liste_familles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_pays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `alpha2` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha3` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_en_gb` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `defaut` int(11) NOT NULL,
  `tva` int(11) NOT NULL,
  `taux_tva` decimal(4,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_structures_types_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_structures_types_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structuretype_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_champ_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `liste_structures_types_attributs_structuretype_id_foreign` (`structuretype_id`),
  CONSTRAINT `liste_structures_types_attributs_structuretype_id_foreign` FOREIGN KEY (`structuretype_id`) REFERENCES `structuretypes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_structures_types_valeurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_structures_types_valeurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_champ` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `liste_structures_types_valeurs_id_champ_foreign` (`id_champ`),
  CONSTRAINT `liste_structures_types_valeurs_id_champ_foreign` FOREIGN KEY (`id_champ`) REFERENCES `liste_structures_types_attributs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_tarifs_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_tarifs_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `liste_tarifs_types_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste_tarifs_types_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` bigint(20) unsigned NOT NULL,
  `attribut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `liste_tarifs_types_attributs_type_id_foreign` (`type_id`),
  CONSTRAINT `liste_tarifs_types_attributs_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `liste_tarifs_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_tag_post_id_tag_id_unique` (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `views_count` bigint(20) NOT NULL DEFAULT '0',
  `likes` bigint(20) NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produit_cat_tarif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit_cat_tarif` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produit_id` bigint(20) unsigned NOT NULL,
  `cat_tarif_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produit_cat_tarif_produit_id_foreign` (`produit_id`),
  KEY `produit_cat_tarif_cat_tarif_id_foreign` (`cat_tarif_id`),
  CONSTRAINT `produit_cat_tarif_cat_tarif_id_foreign` FOREIGN KEY (`cat_tarif_id`) REFERENCES `structures_cat_tarifs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `produit_cat_tarif_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produit_tarif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit_tarif` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produit_id` bigint(20) unsigned NOT NULL,
  `tarif_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produit_tarif_produit_id_foreign` (`produit_id`),
  KEY `produit_tarif_tarif_id_foreign` (`tarif_id`),
  CONSTRAINT `produit_tarif_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produit_tarif_tarif_id_foreign` FOREIGN KEY (`tarif_id`) REFERENCES `structures_tarifs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `publictypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publictypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publictypes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pulse_aggregates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pulse_aggregates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bucket` int(10) unsigned NOT NULL,
  `period` mediumint(8) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `aggregate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(20,2) NOT NULL,
  `count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_aggregates_bucket_period_type_aggregate_key_hash_unique` (`bucket`,`period`,`type`,`aggregate`,`key_hash`),
  KEY `pulse_aggregates_period_bucket_index` (`period`,`bucket`),
  KEY `pulse_aggregates_type_index` (`type`),
  KEY `pulse_aggregates_period_type_aggregate_bucket_index` (`period`,`type`,`aggregate`,`bucket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pulse_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pulse_entries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pulse_entries_timestamp_index` (`timestamp`),
  KEY `pulse_entries_type_index` (`type`),
  KEY `pulse_entries_key_hash_index` (`key_hash`),
  KEY `pulse_entries_timestamp_type_key_hash_value_index` (`timestamp`,`type`,`key_hash`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pulse_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pulse_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_values_type_key_hash_unique` (`type`,`key_hash`),
  KEY `pulse_values_timestamp_index` (`timestamp`),
  KEY `pulse_values_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservation_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `booking_field_id` bigint(20) unsigned NOT NULL,
  `booking_field_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_attributs_reservation_id_foreign` (`reservation_id`),
  KEY `reservation_attributs_booking_field_id_foreign` (`booking_field_id`),
  KEY `reservation_attributs_booking_field_valeur_id_foreign` (`booking_field_valeur_id`),
  CONSTRAINT `reservation_attributs_booking_field_id_foreign` FOREIGN KEY (`booking_field_id`) REFERENCES `liens_dis_cat_tar_booking_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_attributs_booking_field_valeur_id_foreign` FOREIGN KEY (`booking_field_valeur_id`) REFERENCES `liens_dis_cat_tar_booking_field_valeurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_attributs_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservation_sous_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_sous_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `reservation_attribut_id` bigint(20) unsigned NOT NULL,
  `booking_field_ss_field_id` bigint(20) unsigned NOT NULL,
  `booking_ss_field_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_sous_attributs_reservation_id_foreign` (`reservation_id`),
  KEY `reservation_sous_attributs_reservation_attribut_id_foreign` (`reservation_attribut_id`),
  KEY `reservation_sous_attributs_booking_field_ss_field_id_foreign` (`booking_field_ss_field_id`),
  KEY `reservation_sous_attributs_booking_ss_field_valeur_id_foreign` (`booking_ss_field_valeur_id`),
  CONSTRAINT `reservation_sous_attributs_booking_field_ss_field_id_foreign` FOREIGN KEY (`booking_field_ss_field_id`) REFERENCES `liens_dis_cat_tar_book_field_ss_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_sous_attributs_booking_ss_field_valeur_id_foreign` FOREIGN KEY (`booking_ss_field_valeur_id`) REFERENCES `liens_d_c_t_booking_field_ss_field_valeurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_sous_attributs_reservation_attribut_id_foreign` FOREIGN KEY (`reservation_attribut_id`) REFERENCES `reservation_attributs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_sous_attributs_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservation_structure_planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_structure_planning` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `planning_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_structure_planning_reservation_id_foreign` (`reservation_id`),
  KEY `reservation_structure_planning_planning_id_foreign` (`planning_id`),
  CONSTRAINT `reservation_structure_planning_planning_id_foreign` FOREIGN KEY (`planning_id`) REFERENCES `structure_produits_planning` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_structure_planning_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `discipline_id` bigint(20) unsigned DEFAULT NULL,
  `categorie_id` bigint(20) unsigned DEFAULT NULL,
  `structure_id` bigint(20) unsigned DEFAULT NULL,
  `activite_id` bigint(20) unsigned DEFAULT NULL,
  `activite_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `produit_id` bigint(20) unsigned DEFAULT NULL,
  `produit_criteres` json DEFAULT NULL,
  `cat_tarif_id` bigint(20) unsigned DEFAULT NULL,
  `tarif_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarif_amount` decimal(7,2) DEFAULT NULL,
  `quantity` bigint(20) unsigned DEFAULT '1',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `user_payeur_id` bigint(20) unsigned DEFAULT NULL,
  `paiement_datetime` datetime DEFAULT NULL,
  `paiement_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_number` bigint(20) unsigned DEFAULT NULL,
  `client_confirmed` tinyint(1) DEFAULT NULL,
  `datetime_client_confirmed` datetime DEFAULT NULL,
  `client_cancelled` tinyint(1) DEFAULT NULL,
  `datetime_client_cancelled` datetime DEFAULT NULL,
  `pending` tinyint(1) NOT NULL DEFAULT '1',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `datetime_structure_confirmed` datetime DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  `datetime_structure_finished` datetime DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `datetime_structure_cancelled` datetime DEFAULT NULL,
  `code` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_confirmed` tinyint(1) DEFAULT NULL,
  `datetime_code_confirmed` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_user_id_foreign` (`user_id`),
  KEY `reservations_discipline_id_foreign` (`discipline_id`),
  KEY `reservations_categorie_id_foreign` (`categorie_id`),
  KEY `reservations_structure_id_foreign` (`structure_id`),
  KEY `reservations_activite_id_foreign` (`activite_id`),
  KEY `reservations_produit_id_foreign` (`produit_id`),
  KEY `reservations_cat_tarif_id_foreign` (`cat_tarif_id`),
  KEY `reservations_user_payeur_id_foreign` (`user_payeur_id`),
  CONSTRAINT `reservations_activite_id_foreign` FOREIGN KEY (`activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_cat_tarif_id_foreign` FOREIGN KEY (`cat_tarif_id`) REFERENCES `structures_cat_tarifs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_user_payeur_id_foreign` FOREIGN KEY (`user_payeur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_activite_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_activite_date` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_activite_id` bigint(20) unsigned NOT NULL,
  `structure_produit_id` bigint(20) unsigned DEFAULT NULL,
  `dayopen` date DEFAULT NULL,
  `dayclose` date DEFAULT NULL,
  `houropen` time DEFAULT NULL,
  `hourclose` time DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `time_debut` time DEFAULT NULL,
  `start_month` date DEFAULT NULL,
  `end_month` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_activite_date_structure_activite_id_foreign` (`structure_activite_id`),
  KEY `structure_activite_date_structure_produit_id_foreign` (`structure_produit_id`),
  CONSTRAINT `structure_activite_date_structure_activite_id_foreign` FOREIGN KEY (`structure_activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_activite_date_structure_produit_id_foreign` FOREIGN KEY (`structure_produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_activite_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_activite_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_activite_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_activite_user_structure_activite_id_foreign` (`structure_activite_id`),
  KEY `structure_activite_user_user_id_foreign` (`user_id`),
  CONSTRAINT `structure_activite_user_structure_activite_id_foreign` FOREIGN KEY (`structure_activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_activite_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_adresse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_adresse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_lat` double NOT NULL,
  `address_lng` double NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_adresse_structure_id_foreign` (`structure_id`),
  KEY `structure_adresse_city_id_foreign` (`city_id`),
  KEY `structure_adresse_country_id_foreign` (`country_id`),
  KEY `structure_adresse_address_lat_index` (`address_lat`),
  KEY `structure_adresse_address_lng_index` (`address_lng`),
  CONSTRAINT `structure_adresse_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `villes_france` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_adresse_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `liste_pays` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_adresse_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_produit_sous_criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_produit_sous_criteres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activite_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `critere_id` bigint(20) unsigned NOT NULL,
  `prod_crit_id` bigint(20) unsigned DEFAULT NULL,
  `critere_valeur_id` bigint(20) unsigned NOT NULL,
  `sous_critere_id` bigint(20) unsigned NOT NULL,
  `sous_critere_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_produit_sous_criteres_activite_id_foreign` (`activite_id`),
  KEY `structure_produit_sous_criteres_produit_id_foreign` (`produit_id`),
  KEY `structure_produit_sous_criteres_critere_id_foreign` (`critere_id`),
  KEY `structure_produit_sous_criteres_critere_valeur_id_foreign` (`critere_valeur_id`),
  KEY `structure_produit_sous_criteres_sous_critere_id_foreign` (`sous_critere_id`),
  KEY `structure_produit_sous_criteres_sous_critere_valeur_id_foreign` (`sous_critere_valeur_id`),
  KEY `structure_produit_sous_criteres_prod_crit_id_foreign` (`prod_crit_id`),
  CONSTRAINT `structure_produit_sous_criteres_activite_id_foreign` FOREIGN KEY (`activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_critere_id_foreign` FOREIGN KEY (`critere_id`) REFERENCES `liens_disciplines_categories_criteres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_critere_valeur_id_foreign` FOREIGN KEY (`critere_valeur_id`) REFERENCES `liens_disciplines_categories_criteres_valeurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_prod_crit_id_foreign` FOREIGN KEY (`prod_crit_id`) REFERENCES `structures_produits_criteres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_sous_critere_id_foreign` FOREIGN KEY (`sous_critere_id`) REFERENCES `liens_dis_cat_crit_val_sous_criteres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produit_sous_criteres_sous_critere_valeur_id_foreign` FOREIGN KEY (`sous_critere_valeur_id`) REFERENCES `liens_dis_cat_crit_val_ss_crit_valeur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_produits_planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_produits_planning` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `activite_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `structure_produits_planning_structure_id_foreign` (`structure_id`),
  KEY `structure_produits_planning_discipline_id_foreign` (`discipline_id`),
  KEY `structure_produits_planning_categorie_id_foreign` (`categorie_id`),
  KEY `structure_produits_planning_activite_id_foreign` (`activite_id`),
  KEY `structure_produits_planning_produit_id_foreign` (`produit_id`),
  CONSTRAINT `structure_produits_planning_activite_id_foreign` FOREIGN KEY (`activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produits_planning_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produits_planning_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produits_planning_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_produits_planning_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `niveau` int(11) DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_user_structure_id_foreign` (`structure_id`),
  KEY `structure_user_user_id_foreign` (`user_id`),
  CONSTRAINT `structure_user_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structure_villes_france`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_villes_france` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `villes_france_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structure_villes_france_structure_id_foreign` (`structure_id`),
  KEY `structure_villes_france_villes_france_id_foreign` (`villes_france_id`),
  CONSTRAINT `structure_villes_france_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structure_villes_france_villes_france_id_foreign` FOREIGN KEY (`villes_france_id`) REFERENCES `villes_france` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `structuretype_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `afficher_adresse` int(11) DEFAULT NULL,
  `address_lat` double NOT NULL,
  `address_lng` double NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_sauvegarde` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `presentation_courte` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `presentation_longue` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `premium` tinyint(1) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `valide_client` tinyint(1) NOT NULL DEFAULT '0',
  `valide_admin` tinyint(1) NOT NULL DEFAULT '0',
  `valide_update` tinyint(1) NOT NULL DEFAULT '0',
  `abo_news` tinyint(1) NOT NULL DEFAULT '1',
  `abo_promo` tinyint(1) NOT NULL DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_actif` timestamp NULL DEFAULT NULL,
  `ajout_admin` tinyint(1) DEFAULT NULL,
  `nb_activites` int(11) NOT NULL DEFAULT '0',
  `nb_produits` int(11) NOT NULL DEFAULT '0',
  `nb_reservations` int(11) NOT NULL DEFAULT '0',
  `view_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `moyenne_notes` decimal(2,1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `structures_slug_unique` (`slug`),
  KEY `structures_user_id_foreign` (`user_id`),
  KEY `structures_structuretype_id_foreign` (`structuretype_id`),
  KEY `structures_city_id_foreign` (`city_id`),
  KEY `structures_departement_id_foreign` (`departement_id`),
  KEY `structures_country_id_foreign` (`country_id`),
  KEY `structures_view_count_index` (`view_count`),
  KEY `structures_name_index` (`name`),
  KEY `structures_address_lat_index` (`address_lat`),
  KEY `structures_address_lng_index` (`address_lng`),
  CONSTRAINT `structures_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `villes_france` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `liste_pays` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_structuretype_id_foreign` FOREIGN KEY (`structuretype_id`) REFERENCES `structuretypes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_activites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_activites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `structure_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_activites_discipline_id_foreign` (`discipline_id`),
  KEY `structures_activites_structure_id_foreign` (`structure_id`),
  KEY `structures_activites_categorie_id_foreign` (`categorie_id`),
  CONSTRAINT `structures_activites_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_activites_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_activites_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_cat_tar_att_ssattr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_cat_tar_att_ssattr` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `str_cat_tar_att_id` bigint(20) unsigned NOT NULL,
  `sousattribut_id` bigint(20) unsigned NOT NULL,
  `ss_att_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_cat_tar_att_ssattr_str_cat_tar_att_id_foreign` (`str_cat_tar_att_id`),
  KEY `structures_cat_tar_att_ssattr_sousattribut_id_foreign` (`sousattribut_id`),
  KEY `structures_cat_tar_att_ssattr_ss_att_valeur_id_foreign` (`ss_att_valeur_id`),
  CONSTRAINT `structures_cat_tar_att_ssattr_sousattribut_id_foreign` FOREIGN KEY (`sousattribut_id`) REFERENCES `liens_dis_cat_tar_att_sous_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tar_att_ssattr_ss_att_valeur_id_foreign` FOREIGN KEY (`ss_att_valeur_id`) REFERENCES `liens_dis_cat_tar_att_ssattr_valeurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tar_att_ssattr_str_cat_tar_att_id_foreign` FOREIGN KEY (`str_cat_tar_att_id`) REFERENCES `structures_cat_tar_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_cat_tar_attributs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_cat_tar_attributs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `str_cat_tar_id` bigint(20) unsigned NOT NULL,
  `cat_tar_att_id` bigint(20) unsigned NOT NULL,
  `dis_cat_tar_att_valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_cat_tar_attributs_str_cat_tar_id_foreign` (`str_cat_tar_id`),
  KEY `structures_cat_tar_attributs_cat_tar_att_id_foreign` (`cat_tar_att_id`),
  KEY `structures_cat_tar_attributs_dis_cat_tar_att_valeur_id_foreign` (`dis_cat_tar_att_valeur_id`),
  CONSTRAINT `structures_cat_tar_attributs_cat_tar_att_id_foreign` FOREIGN KEY (`cat_tar_att_id`) REFERENCES `liens_dis_cat_tartyp_attributs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tar_attributs_dis_cat_tar_att_valeur_id_foreign` FOREIGN KEY (`dis_cat_tar_att_valeur_id`) REFERENCES `liens_dis_cat_tar_att_valeurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tar_attributs_str_cat_tar_id_foreign` FOREIGN KEY (`str_cat_tar_id`) REFERENCES `structures_cat_tarifs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_cat_tarifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_cat_tarifs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `dis_cat_tar_typ_id` bigint(20) unsigned NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(7,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_cat_tarifs_structure_id_foreign` (`structure_id`),
  KEY `structures_cat_tarifs_categorie_id_foreign` (`categorie_id`),
  KEY `structures_cat_tarifs_dis_cat_tar_typ_id_foreign` (`dis_cat_tar_typ_id`),
  CONSTRAINT `structures_cat_tarifs_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tarifs_dis_cat_tar_typ_id_foreign` FOREIGN KEY (`dis_cat_tar_typ_id`) REFERENCES `liens_dis_cat_tariftypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `structures_cat_tarifs_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_categories_structure_id_foreign` (`structure_id`),
  KEY `structures_categories_discipline_id_foreign` (`discipline_id`),
  KEY `structures_categories_categorie_id_foreign` (`categorie_id`),
  CONSTRAINT `structures_categories_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_categories_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_categories_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `structure_id` bigint(20) unsigned NOT NULL,
  `nb_produits` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_disciplines_discipline_id_foreign` (`discipline_id`),
  KEY `structures_disciplines_structure_id_foreign` (`structure_id`),
  CONSTRAINT `structures_disciplines_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_disciplines_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_horaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_horaires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `dayopen` date DEFAULT NULL,
  `dayclose` date DEFAULT NULL,
  `houropen` time DEFAULT NULL,
  `hourclose` time DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_horaires_structure_id_foreign` (`structure_id`),
  CONSTRAINT `structures_horaires_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `activite_id` bigint(20) unsigned NOT NULL,
  `lieu_id` bigint(20) unsigned NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `horaire_id` bigint(20) unsigned DEFAULT NULL,
  `reservable` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_produits_structure_id_foreign` (`structure_id`),
  KEY `structures_produits_discipline_id_foreign` (`discipline_id`),
  KEY `structures_produits_categorie_id_foreign` (`categorie_id`),
  KEY `structures_produits_activite_id_foreign` (`activite_id`),
  KEY `structures_produits_lieu_id_foreign` (`lieu_id`),
  KEY `structures_produits_horaire_id_foreign` (`horaire_id`),
  CONSTRAINT `structures_produits_activite_id_foreign` FOREIGN KEY (`activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_horaire_id_foreign` FOREIGN KEY (`horaire_id`) REFERENCES `structures_horaires` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_lieu_id_foreign` FOREIGN KEY (`lieu_id`) REFERENCES `structure_adresse` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_produits_criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_produits_criteres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned NOT NULL,
  `activite_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `critere_id` bigint(20) unsigned NOT NULL,
  `valeur_id` bigint(20) unsigned DEFAULT NULL,
  `valeur` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `defaut` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_produits_criteres_structure_id_foreign` (`structure_id`),
  KEY `structures_produits_criteres_discipline_id_foreign` (`discipline_id`),
  KEY `structures_produits_criteres_categorie_id_foreign` (`categorie_id`),
  KEY `structures_produits_criteres_activite_id_foreign` (`activite_id`),
  KEY `structures_produits_criteres_produit_id_foreign` (`produit_id`),
  KEY `structures_produits_criteres_critere_id_foreign` (`critere_id`),
  KEY `structures_produits_criteres_valeur_id_foreign` (`valeur_id`),
  CONSTRAINT `structures_produits_criteres_activite_id_foreign` FOREIGN KEY (`activite_id`) REFERENCES `structures_activites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `liens_disciplines_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_critere_id_foreign` FOREIGN KEY (`critere_id`) REFERENCES `liens_disciplines_categories_criteres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `liste_disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `structures_produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_produits_criteres_valeur_id_foreign` FOREIGN KEY (`valeur_id`) REFERENCES `liens_disciplines_categories_criteres_valeurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_tarifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_tarifs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(7,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_tarifs_structure_id_foreign` (`structure_id`),
  KEY `structures_tarifs_type_id_foreign` (`type_id`),
  CONSTRAINT `structures_tarifs_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_tarifs_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `liste_tarifs_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_tarifs_types_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_tarifs_types_infos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `tarif_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned NOT NULL,
  `attribut_id` bigint(20) unsigned NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `structures_tarifs_types_infos_structure_id_foreign` (`structure_id`),
  KEY `structures_tarifs_types_infos_tarif_id_foreign` (`tarif_id`),
  KEY `structures_tarifs_types_infos_type_id_foreign` (`type_id`),
  KEY `structures_tarifs_types_infos_attribut_id_foreign` (`attribut_id`),
  CONSTRAINT `structures_tarifs_types_infos_attribut_id_foreign` FOREIGN KEY (`attribut_id`) REFERENCES `liste_tarifs_types_attributs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_tarifs_types_infos_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_tarifs_types_infos_tarif_id_foreign` FOREIGN KEY (`tarif_id`) REFERENCES `structures_tarifs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `structures_tarifs_types_infos_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `liste_tarifs_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structures_types_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures_types_infos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `structure_id` bigint(20) unsigned NOT NULL,
  `attribut_id` bigint(20) unsigned NOT NULL,
  `valeur` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `structuretypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structuretypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `structuretypes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `villes_france`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `villes_france` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_insee` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `article` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville_sans_article` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville_formatee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_unique_ville` int(11) NOT NULL,
  `article_maj` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville_maj` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` int(11) NOT NULL,
  `nom_region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_departement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `codex` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metaphone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tolerance_rayon` int(11) NOT NULL,
  `nb_clubs` int(11) NOT NULL,
  `nb_profs` int(11) NOT NULL,
  `nb_lieux` int(11) NOT NULL,
  `nb_events` int(11) NOT NULL,
  `view_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `villes_france_slug_unique` (`slug`),
  KEY `latitude` (`latitude`,`longitude`),
  KEY `villes_france_view_count_index` (`view_count`),
  KEY `villes_france_ville_index` (`ville`),
  KEY `villes_france_ville_formatee_index` (`ville_formatee`),
  KEY `villes_france_code_postal_index` (`code_postal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2023_06_07_000001_create_pulse_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2023_08_23_144010_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2023_08_23_144011_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2023_08_23_144012_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_08_23_144013_create_liste_familles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_08_23_144014_create_liste_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_08_23_144014_create_liste_pays_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2023_08_23_144015_create_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2023_08_23_144017_create_villes_france_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2023_08_23_144019_create_liste_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2023_08_23_144019_create_structuretypes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2023_08_23_144020_create_departements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2023_08_23_144020_create_liens_disciplines_categories_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2023_08_23_144020_create_liens_disciplines_categories_criteres_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2023_08_23_144020_create_liens_disciplines_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2023_08_23_144020_create_liens_disciplines_similaires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2023_08_23_144020_create_liens_familles_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2023_08_23_144020_create_liste_structures_types_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2023_08_23_144020_create_liste_structures_types_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2023_08_23_144020_create_liste_tarifs_types_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2023_08_23_144020_create_liste_tarifs_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2023_08_23_144020_create_produit_tarif_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2023_08_23_144020_create_publictypes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2023_08_23_144020_create_structure_adresse_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2023_08_23_144020_create_structure_produits_planning_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2023_08_23_144020_create_structure_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2023_08_23_144020_create_structure_villes_france_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2023_08_23_144020_create_structures_activites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2023_08_23_144020_create_structures_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2023_08_23_144020_create_structures_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2023_08_23_144020_create_structures_horaires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2023_08_23_144020_create_structures_produits_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2023_08_23_144020_create_structures_produits_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2023_08_23_144020_create_structures_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2023_08_23_144020_create_structures_tarifs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2023_08_23_144020_create_structures_tarifs_types_infos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2023_08_23_144020_create_structures_types_infos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2023_08_23_144022_add_foreign_keys_to_structures_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2023_08_23_144023_add_foreign_keys_to_structure_adresse_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2023_08_23_144023_add_foreign_keys_to_structure_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2023_08_23_144023_add_foreign_keys_to_structure_villes_france_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2023_08_25_115305_add_foreign_keys_to_liens_familles_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2023_08_25_115629_add_foreign_keys_to_liens_disciplines_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2023_08_25_115928_add_foreign_keys_to_liens_disciplines_similaires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2023_08_25_120924_add_foreign_keys_to_liens_disciplines_categories_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2023_08_25_121436_add_foreign_keys_to_liens_disciplines_categories_criteres_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2023_08_25_121800_add_foreign_key_to_liste_tarifs_types_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2023_08_25_121802_add_foreign_keys_to_liste_structures_types_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2023_08_25_121804_add_foreign_key_to_liste_structures_types_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2023_08_25_122056_add_foreign_keys_to_structures_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2023_08_25_122124_add_foreign_keys_to_structures_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2023_08_25_131645_add_foreign_keys_to_structures_activites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2023_08_25_132019_add_foreign_key_to_structures_horaires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2023_08_25_132102_add_foreign_keys_to_structures_produits_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2023_08_25_133529_add_foreign_keys_to_structures_produits_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2023_08_25_134840_add_foreign_keys_to_structures_tarifs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2023_08_25_141637_add_foreign_keys_to_structures_tarifs_types_infos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2023_08_25_144727_add_foreign_keys_to_structure_produits_planning_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2023_08_28_160444_add_foreign_keys_to_produit_tarif_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2023_08_29_082552_create_reservations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2023_09_01_162100_add_code_to_reservations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2023_10_06_151329_add_slug_to_villes_france',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2023_10_09_223949_add_slug_to_departements',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2023_10_10_131619_add_slug_to_liens_disciplines_categories',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2023_10_19_134236_create_structure_activite_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2023_10_23_120525_modify_index_column_to_villes_france_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2023_10_23_120822_modify_index_column_to_departements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2023_10_23_121300_modify_index_column_to_liste_disciplines_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2023_10_23_121740_modify_index_column_to_structures_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2023_10_23_122458_modify_index_column_to_liste_familles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2023_10_23_122706_modify_index_column_to_liens_disciplines_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2023_10_23_143609_modify_index_column_to_structure_adresse_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2023_10_23_145557_modify_index_column_to_structures_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2023_10_23_150926_modify_index_column_to_villes_france_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2023_10_24_163533_create_structure_activite_date_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2023_10_25_132921_create_liens_dis_cat_crit_val_sous_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2023_10_25_135243_create_liens_dis_cat_crit_val_ss_crit_valeur_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2023_10_28_100938_modify_structures_produits_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2023_10_29_135311_create_structure_produit_sous_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2023_11_02_105042_make_sous_critere_valeur_id_nullable_in_structure_produit_sous_criteres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2023_11_07_113834_add_columns_to_liens_disciplines_categories_criteres',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2023_11_15_113044_add_theme_column_to_liste_disciplines',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2023_11_16_165542_add_ordre_column_to_liens_disciplines_categories_criteres',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2023_11_17_110942_add_ordre_column_to_liens_disciplines_categories_criteres_valeurs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2023_11_17_122330_add_ordre_column_to_liens_dis_cat_crit_val_ss_crit_valeur',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2023_11_24_160912_add_indexable_column_to_liens_disciplines_categories_criteres',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2023_11_24_170429_add_inclus_tout_column_to_liens_disciplines_categories_criteres_valeurs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2023_11_27_135759_add_structure_produit_critere_id_column_to_structure_produit_sous_criteres',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2023_11_30_100524_add_structure_produit_id_column_to_structure_activite_date',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2023_12_02_140115_create_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2023_12_02_140643_create_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2023_12_02_141036_create_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2023_12_08_105852_update_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2023_12_13_122721_create_liens_dis_cat_tariftypes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2023_12_14_105025_create_liens_dis_cat_tartyp_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2023_12_18_132821_create_liens_dis_cat_tar_att_sous_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2023_12_18_162138_create_liens_dis_cat_tar_att_ssattr_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2023_12_19_105049_create_liens_dis_cat_tar_att_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2023_12_19_110826_add_cat_tar_att_valeur_id_to_liens_dis_cat_tar_att_sous_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2023_12_29_113005_create_structures_cat_tarifs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2023_12_29_123838_create_structures_cat_tar_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2023_12_29_160428_create_structures_cat_tar_att_ssattr_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2024_01_02_115306_create_produit_cat_tarif_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2024_01_10_100904_add_fields_to_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2024_01_11_140712_add_viewcounts_to_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2024_01_11_145118_create_likes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2024_01_16_083314_create_discipline_post_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2024_01_17_163400_add_unique_constraint_to_liens_disciplines_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2024_01_19_152804_add_visible_block_to_liens_disciplines_categories_criteres_name',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2024_02_15_171730_create_liens_dis_cat_tar_booking_fields_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2024_02_16_111611_create_liens_dis_cat_tar_booking_field_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2024_02_16_132753_create_liens_dis_cat_tar_booking_field_sous_fields_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2024_02_19_141638_create_liens_dis_cat_tar_booking_field_ss_field_valeurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2024_02_20_115903_add_show_planning_to_liens_dis_cat_tariftypes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2024_02_28_122728_drop_foreign_keys_and_columns_from_reservations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2024_02_29_113137_modify_reservations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2024_03_05_142309_create_reservation_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2024_03_05_173116_create_reservation_sous_attributs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2024_03_05_184816_create_reservation_structure_planning_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2024_03_07_165942_add_quantity_to_reservation_structure_planning_table',1);
