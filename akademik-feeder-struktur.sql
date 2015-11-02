-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `agama`;
CREATE TABLE `agama` (
  `id_agama` smallint(6) NOT NULL,
  `nm_agama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_agama`),
  KEY `id_agama` (`id_agama`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ajar_dosen`;
CREATE TABLE `ajar_dosen` (
  `id_ajar` varchar(255) NOT NULL,
  `id_reg_ptk` varchar(255) DEFAULT NULL,
  `id_subst` varchar(255) DEFAULT '',
  `id_kls` varchar(255) DEFAULT NULL,
  `sks_subst_tot` decimal(5,2) DEFAULT '0.00',
  `sks_tm_subst` decimal(5,2) DEFAULT '0.00',
  `sks_prak_subst` decimal(5,2) DEFAULT '0.00',
  `sks_prak_lap_subst` decimal(5,2) DEFAULT '0.00',
  `sks_sim_subst` decimal(5,2) DEFAULT '0.00',
  `jml_tm_renc` decimal(2,0) DEFAULT '0',
  `jml_tm_real` decimal(2,0) DEFAULT '0',
  `id_jns_eval` smallint(6) NOT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_ajar`),
  KEY `id_ajar` (`id_ajar`) USING BTREE,
  KEY `id_jns_eval` (`id_jns_eval`) USING BTREE,
  KEY `fk_ajar_dosen_kelas_kuliah_id_kls` (`id_kls`) USING BTREE,
  KEY `fk_ajar_dosen_dosen_pt_id_reg_ptk` (`id_reg_ptk`) USING BTREE,
  KEY `id_subst` (`id_subst`),
  CONSTRAINT `ajar_dosen_ibfk_1` FOREIGN KEY (`id_reg_ptk`) REFERENCES `dosen_pt` (`id_reg_ptk`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ajar_dosen_ibfk_3` FOREIGN KEY (`id_kls`) REFERENCES `kelas_kuliah_` (`id_kls`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ajar_dosen_ibfk_5` FOREIGN KEY (`id_jns_eval`) REFERENCES `jenis_evaluasi` (`id_jns_eval`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bentuk_pendidikan`;
CREATE TABLE `bentuk_pendidikan` (
  `id_bp` smallint(6) NOT NULL,
  `nm_bp` varchar(50) DEFAULT NULL,
  `a_jenj_paud` decimal(1,0) DEFAULT NULL,
  `a_jenj_tk` decimal(1,0) DEFAULT NULL,
  `a_jenj_sd` decimal(1,0) DEFAULT NULL,
  `a_jenj_smp` decimal(1,0) DEFAULT NULL,
  `a_jenj_sma` decimal(1,0) DEFAULT NULL,
  `a_jenj_tinggi` decimal(1,0) DEFAULT NULL,
  `dir_bina` varchar(40) DEFAULT NULL,
  `a_aktif` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_bp`),
  KEY `id_bp` (`id_bp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bobot_nilai`;
CREATE TABLE `bobot_nilai` (
  `kode_bobot_nilai` varchar(255) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `nilai_huruf` varchar(3) DEFAULT NULL,
  `bobot_nilai_min` decimal(5,2) DEFAULT NULL,
  `bobot_nilai_maks` decimal(5,2) DEFAULT NULL,
  `nilai_indeks` decimal(4,2) DEFAULT NULL,
  `tgl_mulai_efektif` date DEFAULT NULL,
  `tgl_akhir_efektif` date DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`kode_bobot_nilai`),
  KEY `kode_bobot_nilai` (`kode_bobot_nilai`) USING BTREE,
  KEY `fk_bobot_nilai_sms_id_sms` (`id_sms`) USING BTREE,
  CONSTRAINT `bobot_nilai_ibfk_1` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `build_menu_view`;
CREATE TABLE `build_menu_view` (`id` tinyint(3) unsigned, `parent_id` tinyint(3) unsigned, `value` varchar(100), `url` varchar(100), `menu_order` tinyint(3) unsigned, `details` varchar(255), `icon` varchar(255), `published` tinyint(2), `open` tinyint(2), `type` varchar(10), `role_menu_id` int(11), `menu_id` int(11), `group_id` int(11), `is_active` tinyint(1));


DROP TABLE IF EXISTS `cms_menu`;
CREATE TABLE `cms_menu` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `value` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `url` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `menu_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `details` varchar(255) DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `published` tinyint(2) DEFAULT NULL,
  `open` tinyint(2) DEFAULT NULL,
  `type` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cms_menu` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_options`;
CREATE TABLE `cms_options` (
  `config_id` int(20) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(50) NOT NULL,
  `value` text,
  `description` text,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_role_menu_group`;
CREATE TABLE `cms_role_menu_group` (
  `role_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_table_rules`;
CREATE TABLE `cms_table_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tables` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `rule` varchar(255) DEFAULT NULL,
  `messages` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_users`;
CREATE TABLE `cms_users` (
  `id_user` int(12) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `user_login` varchar(60) DEFAULT NULL,
  `user_author` varchar(80) DEFAULT NULL,
  `user_pass` varchar(64) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_sex` enum('p','l') DEFAULT NULL,
  `user_registered` datetime DEFAULT NULL,
  `user_last_update` datetime DEFAULT NULL,
  `user_activation_key` varchar(60) DEFAULT NULL,
  `user_level` varchar(25) NOT NULL DEFAULT 'user',
  `user_url` varchar(100) DEFAULT NULL,
  `display_name` smallint(250) DEFAULT NULL,
  `user_avatar` longtext,
  `user_status` int(1) NOT NULL DEFAULT '0',
  `count_login` int(11) DEFAULT NULL,
  `user_desc` varchar(255) DEFAULT NULL,
  `api_key` varchar(32) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_user_group`;
CREATE TABLE `cms_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) DEFAULT NULL,
  `group_description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_validation`;
CREATE TABLE `cms_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `rules` varchar(100) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `filter` varchar(100) DEFAULT NULL,
  `a_active` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_validation_copy`;
CREATE TABLE `cms_validation_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `rules` varchar(100) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `filter` varchar(100) DEFAULT NULL,
  `a_active` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `daya_tampung`;
CREATE TABLE `daya_tampung` (
  `id_smt` varchar(5) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `target_mhs_baru` decimal(6,0) DEFAULT NULL,
  `calon_ikut_seleksi` decimal(6,0) DEFAULT NULL,
  `calon_lulus_seleksi` decimal(6,0) DEFAULT NULL,
  `daftar_sbg_mhs` decimal(6,0) DEFAULT NULL,
  `pst_undur_diri` decimal(5,0) DEFAULT NULL,
  `tgl_awal_kul` date DEFAULT NULL,
  `tgl_akhir_kul` date DEFAULT NULL,
  `jml_mgu_kul` decimal(2,0) DEFAULT NULL,
  `metode_kul` varchar(1) DEFAULT NULL,
  `metode_kul_eks` varchar(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  KEY `id_smt` (`id_smt`) USING BTREE,
  KEY `fk_daya_tampung_sms_id_sms` (`id_sms`) USING BTREE,
  CONSTRAINT `daya_tampung_ibfk_1` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `detail_nilai_view`;
CREATE TABLE `detail_nilai_view` (`id_nilai` int(11), `id_kls` varchar(255), `id_reg_pd` varchar(255), `nm_lemb` varchar(80), `kode_mk` varchar(20), `id_mk` varchar(255), `nm_mk` varchar(200), `sks_mk` decimal(2,0), `id_smt` varchar(5), `nm_kls` varchar(5), `nipd` varchar(18), `nm_pd` varchar(60), `id_sms` varchar(255), `mulai_smt` varchar(4), `nilai_angka` decimal(4,1), `nilai_huruf` varchar(3), `nilai_indeks` decimal(4,2));


DROP TABLE IF EXISTS `dosen`;
CREATE TABLE `dosen` (
  `id_ptk` varchar(255) NOT NULL,
  `id_blob` varchar(255) DEFAULT NULL,
  `id_ikatan_kerja` varchar(1) DEFAULT NULL,
  `nm_ptk` varchar(60) DEFAULT NULL,
  `nidn` varchar(10) DEFAULT NULL,
  `nip` varchar(18) DEFAULT NULL,
  `jk` varchar(1) DEFAULT NULL,
  `tmpt_lahir` varchar(32) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `niy_nigk` varchar(30) DEFAULT NULL,
  `nuptk` varchar(16) DEFAULT NULL,
  `id_stat_pegawai` smallint(6) DEFAULT NULL,
  `id_jns_ptk` decimal(2,0) DEFAULT NULL,
  `id_bid_pengawas` int(11) DEFAULT NULL,
  `id_agama` smallint(6) DEFAULT NULL,
  `jln` varchar(80) DEFAULT NULL,
  `rt` decimal(2,0) DEFAULT NULL,
  `rw` decimal(2,0) DEFAULT NULL,
  `nm_dsn` varchar(50) DEFAULT NULL,
  `ds_kel` varchar(50) DEFAULT NULL,
  `id_wil` varchar(8) DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `no_tel_rmh` varchar(20) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_stat_aktif` decimal(2,0) DEFAULT NULL,
  `sk_cpns` varchar(40) DEFAULT NULL,
  `tgl_sk_cpns` date DEFAULT NULL,
  `sk_angkat` varchar(40) DEFAULT NULL,
  `tmt_sk_angkat` date DEFAULT NULL,
  `id_lemb_angkat` decimal(2,0) DEFAULT NULL,
  `id_pangkat_gol` decimal(2,0) DEFAULT NULL,
  `id_keahlian_lab` smallint(6) DEFAULT NULL,
  `id_sumber_gaji` decimal(2,0) DEFAULT NULL,
  `nm_ibu_kandung` varchar(60) DEFAULT NULL,
  `stat_kawin` decimal(1,0) DEFAULT NULL,
  `nm_suami_istri` varchar(60) DEFAULT NULL,
  `nip_suami_istri` varchar(18) DEFAULT NULL,
  `id_pekerjaan_suami_istri` int(11) DEFAULT NULL,
  `tmt_pns` date DEFAULT NULL,
  `a_lisensi_kepsek` decimal(1,0) DEFAULT NULL,
  `jml_sekolah_binaan` smallint(6) DEFAULT NULL,
  `a_diklat_awas` decimal(1,0) DEFAULT NULL,
  `akta_ijin_ajar` varchar(1) DEFAULT NULL,
  `nira` varchar(30) DEFAULT NULL,
  `stat_data` int(11) DEFAULT NULL,
  `mampu_handle_kk` int(11) DEFAULT NULL,
  `a_braille` decimal(1,0) DEFAULT NULL,
  `a_bhs_isyarat` decimal(1,0) DEFAULT NULL,
  `npwp` varchar(15) DEFAULT NULL,
  `kewarganegaraan` varchar(2) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_ptk`),
  KEY `id_ptk` (`id_ptk`) USING BTREE,
  KEY `id_agama` (`id_agama`) USING BTREE,
  KEY `id_ikatan_kerja` (`id_ikatan_kerja`) USING BTREE,
  KEY `fk_dosen_lembaga_pengangkat_id_lemb_angkat` (`id_lemb_angkat`) USING BTREE,
  KEY `fk_dosen_pangkat_golongan_id_pangkat_gol` (`id_pangkat_gol`) USING BTREE,
  KEY `fk_dosen_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_dosen_status_keaktifan_pegawai_id_stat_aktif` (`id_stat_aktif`) USING BTREE,
  KEY `fk_dosen_status_kepegawaian_id_stat_pegawai` (`id_stat_pegawai`) USING BTREE,
  KEY `fk_dosen_wilayah_id_wil` (`id_wil`) USING BTREE,
  KEY `nm_ptk` (`nm_ptk`),
  KEY `nidn` (`nidn`),
  CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_agama`) REFERENCES `agama` (`id_agama`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_2` FOREIGN KEY (`id_ikatan_kerja`) REFERENCES `ikatan_kerja_dosen` (`id_ikatan_kerja`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_3` FOREIGN KEY (`id_lemb_angkat`) REFERENCES `lembaga_pengangkat` (`id_lemb_angkat`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_4` FOREIGN KEY (`id_pangkat_gol`) REFERENCES `pangkat_golongan` (`id_pangkat_gol`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_5` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_6` FOREIGN KEY (`id_stat_aktif`) REFERENCES `status_keaktifan_pegawai` (`id_stat_aktif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_7` FOREIGN KEY (`id_stat_pegawai`) REFERENCES `status_kepegawaian` (`id_stat_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ibfk_8` FOREIGN KEY (`id_wil`) REFERENCES `wilayah` (`id_wil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_detail_view`;
CREATE TABLE `dosen_detail_view` (`nm_ptk` varchar(60), `id_ptk` varchar(255), `id_agama` smallint(6), `id_ikatan_kerja` varchar(1), `id_lemb_angkat` decimal(2,0), `id_pangkat_gol` decimal(2,0), `id_sp` varchar(255), `id_stat_aktif` decimal(2,0), `id_stat_pegawai` smallint(6), `id_wil` varchar(8), `nm_agama` varchar(25), `nm_ikatan_kerja` varchar(50), `nm_lemb_angkat` varchar(80), `nm_pangkat` varchar(20), `nm_lemb` varchar(80), `nm_stat_aktif` varchar(30), `nm_stat_pegawai` varchar(30), `nm_wil` varchar(255), `jk` varchar(1), `nidn` varchar(10), `tmpt_lahir` varchar(32), `tgl_lahir` date, `jln` varchar(80), `rt` decimal(2,0), `rw` decimal(2,0), `nm_dsn` varchar(50), `ds_kel` varchar(50), `kode_pos` varchar(5), `no_tel_rmh` varchar(20), `no_hp` varchar(20), `email` varchar(50), `nm_ibu_kandung` varchar(60), `stat_kawin` decimal(1,0), `nm_suami_istri` varchar(60), `nip_suami_istri` varchar(18), `id_pekerjaan_suami_istri` int(11), `npwp` varchar(15), `kewarganegaraan` varchar(2), `last_update` datetime, `nip` varchar(18), `id_blob` varchar(255), `nik` varchar(16), `niy_nigk` varchar(30), `nuptk` varchar(16), `id_jns_ptk` decimal(2,0), `id_bid_pengawas` int(11), `sk_cpns` varchar(40), `tgl_sk_cpns` date, `sk_angkat` varchar(40), `tmt_sk_angkat` date, `id_keahlian_lab` smallint(6), `id_sumber_gaji` decimal(2,0), `tmt_pns` date, `a_lisensi_kepsek` decimal(1,0), `jml_sekolah_binaan` smallint(6), `a_diklat_awas` decimal(1,0), `akta_ijin_ajar` varchar(1), `nira` varchar(30), `stat_data` int(11), `mampu_handle_kk` int(11), `a_braille` decimal(1,0), `a_bhs_isyarat` decimal(1,0), `soft_delete` enum('1','0'), `last_sync` datetime, `id_updater` varchar(255));


DROP TABLE IF EXISTS `dosen_jabfung`;
CREATE TABLE `dosen_jabfung` (
  `id_dos_jabfung` int(11) NOT NULL AUTO_INCREMENT,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_jabfung` decimal(2,0) DEFAULT NULL,
  `sk_jabatan` varchar(40) DEFAULT NULL,
  `tgl_sk_jabatan` date DEFAULT NULL,
  `tmt_jabatan` date DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_dos_jabfung`),
  KEY `fk_dosen_jabfung_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_jabfung_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `fk_dosen_jabfung_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `id_jabfung` (`id_jabfung`) USING BTREE,
  CONSTRAINT `dosen_jabfung_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_jabfung_ibfk_2` FOREIGN KEY (`id_jabfung`) REFERENCES `jabfung` (`id_jabfung`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_jabfung_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_jabfung_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_jabfungsional_view`;
CREATE TABLE `dosen_jabfungsional_view` (`id_dos_jabfung` int(11), `id_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `id_jabfung` decimal(2,0), `nm_prodi` varchar(80), `kode_prodi` varchar(10), `sk_jabatan` varchar(40), `nm_jabfung` varchar(30), `tgl_sk_jabatan` date, `tmt_jabatan` date);


DROP TABLE IF EXISTS `dosen_kepangkatan`;
CREATE TABLE `dosen_kepangkatan` (
  `id_dos_pangkat` varchar(255) NOT NULL,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_pangkat_gol` decimal(2,0) DEFAULT NULL,
  `sk_pangkat` varchar(40) DEFAULT NULL,
  `tgl_sk_pangkat` date DEFAULT NULL,
  `tmt_sk_pangkat` date DEFAULT NULL,
  `masa_kerja_thn` int(5) DEFAULT NULL,
  `masa_kerja_bln` int(5) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_dos_pangkat`),
  KEY `fk_dosen_kepangkatan_pangkat_golongan_id_pangkat_gol` (`id_pangkat_gol`) USING BTREE,
  KEY `fk_dosen_kepangkatan_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_kepangkatan_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `fk_dosen_kepangkatan_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `id_dos_pangkat` (`id_dos_pangkat`) USING BTREE,
  CONSTRAINT `dosen_kepangkatan_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_kepangkatan_ibfk_2` FOREIGN KEY (`id_pangkat_gol`) REFERENCES `pangkat_golongan` (`id_pangkat_gol`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_kepangkatan_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_kepangkatan_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_kepangkatan_view`;
CREATE TABLE `dosen_kepangkatan_view` (`id_dos_pangkat` varchar(255), `id_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `id_pangkat_gol` decimal(2,0), `nm_pangkat` varchar(20), `kode_gol` varchar(5), `nm_prodi` varchar(80), `kode_prodi` varchar(10), `sk_pangkat` varchar(40), `tgl_sk_pangkat` date, `tmt_sk_pangkat` date, `masa_kerja_thn` int(5), `masa_kerja_bln` int(5));


DROP VIEW IF EXISTS `dosen_list_view`;
CREATE TABLE `dosen_list_view` (`id_reg_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `nm_agama` varchar(25), `id_ptk` varchar(255), `nm_ptk` varchar(60), `nidn` varchar(10), `jk` varchar(1), `tmpt_lahir` varchar(32), `nm_stat_pegawai` varchar(30), `last_update` datetime);


DROP TABLE IF EXISTS `dosen_pendidikan`;
CREATE TABLE `dosen_pendidikan` (
  `id_dos_pendidikan` varchar(255) NOT NULL,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `bidang_studi` varchar(100) DEFAULT NULL,
  `id_jenj_didik` decimal(2,0) DEFAULT NULL,
  `gelar` varchar(40) DEFAULT NULL,
  `id_sp_asal` varchar(255) DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `thn_lulus` varchar(4) DEFAULT NULL,
  `sks_lulus` varchar(10) DEFAULT NULL,
  `ipk_lulus` varchar(10) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_dos_pendidikan`),
  KEY `id_dos_pendidikan` (`id_dos_pendidikan`) USING BTREE,
  KEY `fk_dosen_pendidikan_jenjang_pendidikan_id_jenj_didik` (`id_jenj_didik`) USING BTREE,
  KEY `fk_dosen_pendidikan_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_pendidikan_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_dosen_pendidikan_satuan_pendidikan_id_sp_asal` (`id_sp_asal`) USING BTREE,
  CONSTRAINT `dosen_pendidikan_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pendidikan_ibfk_2` FOREIGN KEY (`id_jenj_didik`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pendidikan_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pendidikan_ibfk_4` FOREIGN KEY (`id_sp_asal`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_pendidikan_view`;
CREATE TABLE `dosen_pendidikan_view` (`id_ptk` varchar(255), `id_sp` varchar(255), `bidang_studi` varchar(100), `id_dos_pendidikan` varchar(255), `id_jenj_didik` decimal(2,0), `gelar` varchar(40), `id_sp_asal` varchar(255), `fakultas` varchar(255), `thn_lulus` varchar(4), `sks_lulus` varchar(10), `ipk_lulus` varchar(10), `nm_pt` varchar(80), `nm_jenj_didik` varchar(25));


DROP VIEW IF EXISTS `dosen_penugasan_view`;
CREATE TABLE `dosen_penugasan_view` (`id_reg_ptk` varchar(255), `id_thn_ajaran` decimal(4,0), `id_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `nm_thn_ajaran` varchar(10), `nm_prodi` varchar(80), `kode_prodi` varchar(10), `no_srt_tgs` varchar(40), `tgl_srt_tgs` date, `tmt_srt_tgs` date);


DROP TABLE IF EXISTS `dosen_pt`;
CREATE TABLE `dosen_pt` (
  `id_reg_ptk` varchar(255) NOT NULL,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_thn_ajaran` decimal(4,0) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `no_srt_tgs` varchar(40) DEFAULT NULL,
  `tgl_srt_tgs` date DEFAULT NULL,
  `tmt_srt_tgs` date DEFAULT NULL,
  `a_sp_homebase` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_1` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_2` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_3` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_4` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_5` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_6` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_7` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_8` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_9` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_10` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_11` decimal(1,0) DEFAULT NULL,
  `a_aktif_bln_12` decimal(1,0) DEFAULT NULL,
  `id_jns_keluar` varchar(1) DEFAULT NULL,
  `tgl_ptk_keluar` date DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_reg_ptk`),
  KEY `id_reg_ptk` (`id_reg_ptk`) USING BTREE,
  KEY `id_jns_keluar` (`id_jns_keluar`) USING BTREE,
  KEY `fk_dosen_pt_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_dosen_pt_tahun_ajaran_id_thn_ajaran` (`id_thn_ajaran`) USING BTREE,
  KEY `fk_dosen_pt_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_pt_sms_id_sms` (`id_sms`) USING BTREE,
  CONSTRAINT `dosen_pt_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pt_ibfk_2` FOREIGN KEY (`id_jns_keluar`) REFERENCES `jenis_keluar` (`id_jns_keluar`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pt_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pt_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_pt_ibfk_5` FOREIGN KEY (`id_thn_ajaran`) REFERENCES `tahun_ajaran` (`id_thn_ajaran`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `dosen_sertifikasi`;
CREATE TABLE `dosen_sertifikasi` (
  `id_dos_sert` varchar(255) NOT NULL,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `no_peserta` varchar(100) DEFAULT NULL,
  `bid_studi` varchar(100) DEFAULT NULL,
  `id_jns_sert` decimal(2,0) DEFAULT NULL,
  `thn_sert` varchar(4) DEFAULT NULL,
  `no_sk_sert` varchar(255) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_dos_sert`),
  KEY `fk_dosen_sertifikasi_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_sertifikasi_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `fk_dosen_sertifikasi_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `id_dos_sert` (`id_dos_sert`) USING BTREE,
  KEY `fk_dosen_sertifikasi_jenis_sert_id_jns_sert` (`id_jns_sert`) USING BTREE,
  CONSTRAINT `dosen_sertifikasi_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_sertifikasi_ibfk_2` FOREIGN KEY (`id_jns_sert`) REFERENCES `jenis_sert` (`id_jns_sert`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_sertifikasi_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_sertifikasi_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_sertifikasi_view`;
CREATE TABLE `dosen_sertifikasi_view` (`id_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `nm_prodi` varchar(80), `kode_prodi` varchar(10), `id_dos_sert` varchar(255), `no_peserta` varchar(100), `bid_studi` varchar(100), `id_jns_sert` decimal(2,0), `nm_jns_sert` varchar(30), `thn_sert` varchar(4), `no_sk_sert` varchar(255));


DROP TABLE IF EXISTS `dosen_struktural`;
CREATE TABLE `dosen_struktural` (
  `id_dos_struktural` int(11) NOT NULL AUTO_INCREMENT,
  `id_ptk` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_jabstruk` decimal(2,0) DEFAULT NULL,
  `sk_jabatan` varchar(40) DEFAULT NULL,
  `tgl_sk_jabatan` date DEFAULT NULL,
  `tmt_jabatan` date DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_dos_struktural`),
  KEY `fk_dosen_struktural_dosen_id_ptk` (`id_ptk`) USING BTREE,
  KEY `fk_dosen_struktural_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `fk_dosen_struktural_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `id_jabstruk` (`id_jabstruk`) USING BTREE,
  CONSTRAINT `dosen_struktural_ibfk_1` FOREIGN KEY (`id_ptk`) REFERENCES `dosen` (`id_ptk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_struktural_ibfk_2` FOREIGN KEY (`id_jabstruk`) REFERENCES `jabstruk` (`id_jabstruk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_struktural_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_struktural_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `dosen_struktural_view`;
CREATE TABLE `dosen_struktural_view` (`id_ptk` varchar(255), `id_sms` varchar(255), `id_sp` varchar(255), `nm_prodi` varchar(80), `kode_prodi` varchar(10), `id_jabstruk` decimal(2,0), `nm_jabstruk` varchar(30), `sk_jabatan` varchar(40), `tgl_sk_jabatan` date, `tmt_jabatan` date, `id_dos_struktural` int(11));


DROP VIEW IF EXISTS `fastikom_krs_view`;
CREATE TABLE `fastikom_krs_view` (`nim` varchar(20), `kdmk` varchar(20), `thak` varchar(15), `smt` int(10) unsigned, `nilhrf` varchar(5), `nilang` float, `kls` varchar(15), `kdpro` varchar(30), `noreg` varchar(45), `smtkrs` int(10) unsigned, `noid` int(11), `nmmhs` varchar(100));


DROP VIEW IF EXISTS `hitung_aktifitas_mahasiswa_view`;
CREATE TABLE `hitung_aktifitas_mahasiswa_view` (`id_smt` varchar(5), `id_reg_pd` varchar(255), `nipd` varchar(18), `h_ips` decimal(29,2), `h_sks_smt` decimal(24,0));


DROP TABLE IF EXISTS `ikatan_kerja_dosen`;
CREATE TABLE `ikatan_kerja_dosen` (
  `id_ikatan_kerja` varchar(1) NOT NULL,
  `nm_ikatan_kerja` varchar(50) DEFAULT NULL,
  `ket_ikatan_kerja` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_ikatan_kerja`),
  KEY `id_ikatan_kerja` (`id_ikatan_kerja`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `input_nilai`;
CREATE TABLE `input_nilai` (`id_reg_pd` varchar(255), `id_kls` varchar(255), `nipd` varchar(18), `nm_pd` varchar(60), `kode_mk` varchar(20), `nm_mk` varchar(200), `nilai_angka` decimal(4,1), `nilai_huruf` varchar(3), `nilai_indeks` decimal(4,2));


DROP VIEW IF EXISTS `insert_aktifitas_kuliah_mahasiwa_view`;
CREATE TABLE `insert_aktifitas_kuliah_mahasiwa_view` (`id_reg_pd` varchar(255), `id_smt` varchar(5), `h_sks_total` decimal(24,0), `h_ipk` decimal(29,2));


DROP TABLE IF EXISTS `jabfung`;
CREATE TABLE `jabfung` (
  `id_jabfung` decimal(2,0) NOT NULL,
  `nm_jabfung` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_jabfung`),
  KEY `id_jabfung` (`id_jabfung`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jabstruk`;
CREATE TABLE `jabstruk` (
  `id_jabstruk` decimal(2,0) NOT NULL,
  `nm_jabstruk` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_jabstruk`),
  KEY `id_jabstruk` (`id_jabstruk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_evaluasi`;
CREATE TABLE `jenis_evaluasi` (
  `id_jns_eval` smallint(6) NOT NULL,
  `nm_jns_eval` varchar(25) DEFAULT NULL,
  `ket_jns_eval` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_jns_eval`),
  KEY `id_jns_eval` (`id_jns_eval`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_keluar`;
CREATE TABLE `jenis_keluar` (
  `id_jns_keluar` varchar(1) NOT NULL,
  `ket_keluar` varchar(40) DEFAULT NULL,
  `a_pd` decimal(1,0) DEFAULT NULL,
  `a_ptk` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jns_keluar`),
  KEY `id_jns_keluar` (`id_jns_keluar`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_matakuliah`;
CREATE TABLE `jenis_matakuliah` (
  `id_jns_mk` varchar(1) NOT NULL,
  `nm_jns_mk` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jns_mk`),
  KEY `id_jns_mk` (`id_jns_mk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_pendaftaran`;
CREATE TABLE `jenis_pendaftaran` (
  `id_jns_daftar` decimal(1,0) NOT NULL,
  `nm_jns_daftar` varchar(20) DEFAULT NULL,
  `u_daftar_sekolah` decimal(1,0) DEFAULT NULL,
  `u_daftar_rombel` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jns_daftar`),
  KEY `id_jns_daftar` (`id_jns_daftar`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_sert`;
CREATE TABLE `jenis_sert` (
  `id_jns_sert` decimal(2,0) NOT NULL,
  `id_kk` int(11) NOT NULL,
  `nm_jns_sert` varchar(30) DEFAULT NULL,
  `u_prof_guru` decimal(1,0) DEFAULT NULL,
  `u_kepsek` decimal(1,0) DEFAULT NULL,
  `u_laboran` decimal(1,0) DEFAULT NULL,
  `u_prof_dosen` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jns_sert`,`id_kk`),
  KEY `id_kk` (`id_kk`) USING BTREE,
  KEY `id_jns_sert` (`id_jns_sert`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_sms`;
CREATE TABLE `jenis_sms` (
  `id_jns_sms` decimal(2,0) NOT NULL,
  `nm_jns_sms` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_jns_sms`),
  KEY `id_jns_sms` (`id_jns_sms`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenis_subst`;
CREATE TABLE `jenis_subst` (
  `id_jns_subst` varchar(5) NOT NULL,
  `nm_jns_subst` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jns_subst`),
  KEY `id_jns_subst` (`id_jns_subst`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jenjang_pendidikan`;
CREATE TABLE `jenjang_pendidikan` (
  `id_jenj_didik` decimal(2,0) NOT NULL,
  `nm_jenj_didik` varchar(25) DEFAULT NULL,
  `u_jenj_lemb` decimal(1,0) DEFAULT NULL,
  `u_jenj_org` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jenj_didik`),
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE `jurusan` (
  `id_jur` varchar(10) NOT NULL,
  `nm_jur` varchar(60) DEFAULT NULL,
  `nm_intl_jur` varchar(60) DEFAULT NULL,
  `u_sma` decimal(1,0) DEFAULT NULL,
  `u_smk` decimal(1,0) DEFAULT NULL,
  `u_pt` decimal(1,0) DEFAULT NULL,
  `u_slb` decimal(1,0) DEFAULT NULL,
  `id_jenj_didik` decimal(2,0) NOT NULL,
  `id_induk_jurusan` varchar(10) DEFAULT NULL,
  `id_kel_bidang` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_jur`),
  KEY `id_jur` (`id_jur`) USING BTREE,
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE,
  CONSTRAINT `jurusan_ibfk_1` FOREIGN KEY (`id_jenj_didik`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `kebutuhan_khusus`;
CREATE TABLE `kebutuhan_khusus` (
  `id_kk` int(11) NOT NULL,
  `nm_kk` varchar(40) DEFAULT NULL,
  `a_kk_a` decimal(1,0) DEFAULT NULL,
  `a_kk_b` decimal(1,0) DEFAULT NULL,
  `a_kk_c` decimal(1,0) DEFAULT NULL,
  `a_kk_c1` decimal(1,0) DEFAULT NULL,
  `a_kk_d` decimal(1,0) DEFAULT NULL,
  `a_kk_d1` decimal(1,0) DEFAULT NULL,
  `a_kk_e` decimal(1,0) DEFAULT NULL,
  `a_kk_f` decimal(1,0) DEFAULT NULL,
  `a_kk_h` decimal(1,0) DEFAULT NULL,
  `a_kk_i` decimal(1,0) DEFAULT NULL,
  `a_kk_j` decimal(1,0) DEFAULT NULL,
  `a_kk_k` decimal(1,0) DEFAULT NULL,
  `a_kk_n` decimal(1,0) DEFAULT NULL,
  `a_kk_o` decimal(1,0) DEFAULT NULL,
  `a_kk_p` decimal(1,0) DEFAULT NULL,
  `a_kk_q` decimal(1,0) DEFAULT NULL,
  `u_lemb` decimal(1,0) DEFAULT NULL,
  `u_ptk` decimal(1,0) DEFAULT NULL,
  `u_pd` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_kk`),
  KEY `id_kk` (`id_kk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `kelas_kuliah`;
CREATE TABLE `kelas_kuliah` (
  `id_kls` varchar(255) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_smt` varchar(5) DEFAULT NULL,
  `id_mk` varchar(255) DEFAULT NULL,
  `nm_kls` varchar(5) DEFAULT NULL,
  `sks_mk` decimal(2,0) DEFAULT NULL,
  `sks_tm` decimal(2,0) DEFAULT NULL,
  `sks_prak` decimal(2,0) DEFAULT NULL,
  `sks_prak_lap` decimal(2,0) DEFAULT NULL,
  `sks_sim` decimal(2,0) DEFAULT NULL,
  `bahasan_case` varchar(200) DEFAULT NULL,
  `tgl_mulai_koas` date DEFAULT NULL,
  `tgl_selesai_koas` date DEFAULT NULL,
  `id_mou` varchar(255) DEFAULT NULL,
  `a_selenggara_pditt` decimal(1,0) DEFAULT NULL,
  `kuota_pditt` decimal(4,0) DEFAULT NULL,
  `a_pengguna_pditt` decimal(1,0) DEFAULT NULL,
  `id_kls_pditt` varchar(255) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_kls`),
  KEY `id_kls` (`id_kls`) USING BTREE,
  KEY `id_sms` (`id_sms`) USING BTREE,
  KEY `id_smt` (`id_smt`) USING BTREE,
  KEY `id_mk` (`id_mk`) USING BTREE,
  KEY `nm_kls` (`nm_kls`) USING BTREE,
  CONSTRAINT `kelas_kuliah_ibfk_1` FOREIGN KEY (`id_smt`) REFERENCES `semester` (`id_smt`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kelas_kuliah_ibfk_2` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id_mk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelas_kuliah_ibfk_3` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `kelas_kuliah_view`;
CREATE TABLE `kelas_kuliah_view` (`id_kls` varchar(255), `nm_lemb` varchar(80), `id_smt` varchar(5), `kode_mk` varchar(20), `nm_mk` varchar(200), `nm_kls` varchar(5), `sks_mk` decimal(2,0), `peserta_kelas` bigint(21), `dosen_mengajar` bigint(21));


DROP TABLE IF EXISTS `kel_matakuliah`;
CREATE TABLE `kel_matakuliah` (
  `id_kel_mk` varchar(1) NOT NULL,
  `nm_kel_mk` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_kel_mk`),
  KEY `id_kel_mk` (`id_kel_mk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `krs`;
CREATE TABLE `krs` (
  `nim` varchar(20) DEFAULT NULL,
  `kdmk` varchar(20) DEFAULT NULL,
  `thak` varchar(15) DEFAULT NULL,
  `smt` int(10) unsigned DEFAULT NULL,
  `nilhrf` varchar(5) DEFAULT '0',
  `nilang` float DEFAULT '0',
  `kls` varchar(15) DEFAULT NULL,
  `kdpro` varchar(30) DEFAULT NULL,
  `noreg` varchar(45) DEFAULT NULL,
  `smtkrs` int(10) unsigned DEFAULT NULL,
  `noid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`noid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `kuliah_mahasiswa`;
CREATE TABLE `kuliah_mahasiswa` (
  `id_kuliah_pd` int(11) NOT NULL AUTO_INCREMENT,
  `id_smt` varchar(5) NOT NULL,
  `id_reg_pd` varchar(255) DEFAULT NULL,
  `ips` double DEFAULT NULL,
  `sks_smt` decimal(3,0) DEFAULT NULL,
  `ipk` double DEFAULT NULL,
  `sks_total` decimal(3,0) DEFAULT NULL,
  `id_stat_mhs` varchar(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_kuliah_pd`),
  KEY `fk_kuliah_mahasiswa_mahasiswa_pt_id_reg_pd` (`id_reg_pd`) USING BTREE,
  KEY `fk_kuliah_mahasiswa_status_mahasiswa_id_stat_mhs` (`id_stat_mhs`) USING BTREE,
  KEY `id_kuliah_pd` (`id_kuliah_pd`) USING BTREE,
  KEY `fk_kuilah_mahasiswa_semester_id_smt` (`id_smt`) USING BTREE,
  CONSTRAINT `kuliah_mahasiswa_ibfk_1` FOREIGN KEY (`id_smt`) REFERENCES `semester` (`id_smt`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kuliah_mahasiswa_ibfk_2` FOREIGN KEY (`id_reg_pd`) REFERENCES `mahasiswa_pt` (`id_reg_pd`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kuliah_mahasiswa_ibfk_3` FOREIGN KEY (`id_stat_mhs`) REFERENCES `status_mahasiswa` (`id_stat_mhs`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `kuliah_subtansi`;
CREATE TABLE `kuliah_subtansi` (`id_subst` varchar(255), `id_jns_subst` varchar(5), `id_sms` varchar(255), `nm_subst` varchar(40), `nm_jns_subst` varchar(50), `nm_prodi` varchar(80), `sks_mk` decimal(3,1), `sks_tm` decimal(2,0), `sks_prak` decimal(2,0), `sks_prak_lap` decimal(2,0), `sks_sim` decimal(2,0), `last_update` datetime);


DROP TABLE IF EXISTS `kurikulum`;
CREATE TABLE `kurikulum` (
  `id_kurikulum_sp` varchar(255) NOT NULL,
  `nm_kurikulum_sp` varchar(60) DEFAULT NULL,
  `jml_sem_normal` decimal(2,0) DEFAULT NULL,
  `jml_sks_lulus` decimal(3,0) DEFAULT NULL,
  `jml_sks_wajib` decimal(3,0) DEFAULT NULL,
  `jml_sks_pilihan` decimal(3,0) DEFAULT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_jenj_didik` decimal(2,0) DEFAULT NULL,
  `id_smt_berlaku` varchar(5) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_kurikulum_sp`),
  KEY `id_kurikulum_sp` (`id_kurikulum_sp`) USING BTREE,
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE,
  KEY `fk_kurikulum_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `fk_kurikulim_semester_id_smt_berlaku` (`id_smt_berlaku`) USING BTREE,
  CONSTRAINT `kurikulum_ibfk_1` FOREIGN KEY (`id_smt_berlaku`) REFERENCES `semester` (`id_smt`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kurikulum_ibfk_2` FOREIGN KEY (`id_jenj_didik`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kurikulum_ibfk_3` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `kurikulum_list_view`;
CREATE TABLE `kurikulum_list_view` (`id_kurikulum_sp` varchar(255), `nm_kurikulum_sp` varchar(60), `jml_sem_normal` decimal(2,0), `jml_sks_lulus` decimal(3,0), `jml_sks_wajib` decimal(3,0), `jml_sks_pilihan` decimal(3,0), `id_sms` varchar(255), `id_jenj_didik` decimal(2,0), `id_smt_berlaku` varchar(5), `last_update` datetime, `soft_delete` enum('1','0'), `last_sync` datetime, `id_updater` varchar(255), `last_update_local` datetime, `nm_prodi` varchar(80));


DROP TABLE IF EXISTS `lembaga_pengangkat`;
CREATE TABLE `lembaga_pengangkat` (
  `id_lemb_angkat` decimal(2,0) NOT NULL,
  `nm_lemb_angkat` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id_lemb_angkat`),
  KEY `id_lemb_angkat` (`id_lemb_angkat`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `level_wilayah`;
CREATE TABLE `level_wilayah` (
  `id_level_wil` smallint(6) NOT NULL,
  `nm_level_wilayah` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_level_wil`),
  KEY `id_level_wil` (`id_level_wil`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `list_kelas_perkuliahan_view`;
CREATE TABLE `list_kelas_perkuliahan_view` (`id_kls` varchar(255), `id_sms` varchar(255), `id_mk` varchar(255), `nm_prodi` varchar(80), `id_smt` varchar(5), `kode_mk` varchar(20), `nm_mk` varchar(200), `nm_kls` varchar(5), `sks_mk` decimal(2,0), `nm_smt` varchar(20), `smt` decimal(2,0));


DROP VIEW IF EXISTS `list_mahasiswa_status_view`;
CREATE TABLE `list_mahasiswa_status_view` (`id_reg_pd` varchar(255), `nipd` varchar(18), `nm_pd` varchar(60), `nm_lemb` varchar(80), `mulai_smt` varchar(5), `ket_keluar` varchar(40), `tgl_keluar` date, `ket` varchar(128), `id_jns_keluar` varchar(1), `jalur_skripsi` decimal(1,0), `judul_skripsi` varchar(250), `bln_awal_bimbingan` date, `bln_akhir_bimbingan` date, `sk_yudisium` varchar(40), `tgl_sk_yudisium` date, `ipk` double, `no_seri_ijazah` varchar(40));


DROP VIEW IF EXISTS `list_nilai_view`;
CREATE TABLE `list_nilai_view` (`id_kls` varchar(255), `nm_lemb` varchar(80), `id_smt` varchar(5), `kode_mk` varchar(20), `nm_mk` varchar(200), `nm_kls` varchar(5), `sks_mk` decimal(2,0), `peserta_kelas` bigint(21), `nilai_mhs` bigint(21));


DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id_pd` varchar(255) NOT NULL,
  `nm_pd` varchar(60) DEFAULT NULL,
  `jk` varchar(1) NOT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `tmpt_lahir` varchar(32) DEFAULT NULL,
  `tgl_lahir` date NOT NULL,
  `id_agama` smallint(6) DEFAULT NULL,
  `id_kk` int(11) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `jln` varchar(80) DEFAULT NULL,
  `rt` decimal(2,0) DEFAULT NULL,
  `rw` decimal(2,0) DEFAULT NULL,
  `nm_dsn` varchar(50) DEFAULT NULL,
  `ds_kel` varchar(50) DEFAULT NULL,
  `id_wil` varchar(8) DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `id_jns_tinggal` decimal(2,0) DEFAULT NULL,
  `id_alat_transport` decimal(2,0) DEFAULT NULL,
  `telepon_rumah` varchar(20) DEFAULT NULL,
  `telepon_seluler` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `a_terima_kps` decimal(1,0) DEFAULT NULL,
  `no_kps` varchar(40) DEFAULT NULL,
  `stat_pd` varchar(1) DEFAULT NULL,
  `nm_ayah` varchar(60) DEFAULT NULL,
  `tgl_lahir_ayah` date DEFAULT NULL,
  `id_jenjang_pendidikan_ayah` decimal(2,0) DEFAULT NULL,
  `id_pekerjaan_ayah` int(11) DEFAULT NULL,
  `id_penghasilan_ayah` int(11) DEFAULT NULL,
  `id_kebutuhan_khusus_ayah` int(11) DEFAULT NULL,
  `nm_ibu_kandung` varchar(60) DEFAULT NULL,
  `tgl_lahir_ibu` date DEFAULT NULL,
  `id_jenjang_pendidikan_ibu` decimal(2,0) DEFAULT NULL,
  `id_penghasilan_ibu` int(11) DEFAULT NULL,
  `id_pekerjaan_ibu` int(11) DEFAULT NULL,
  `id_kebutuhan_khusus_ibu` int(11) DEFAULT NULL,
  `nm_wali` varchar(30) DEFAULT NULL,
  `tgl_lahir_wali` date DEFAULT NULL,
  `id_jenjang_pendidikan_wali` decimal(2,0) DEFAULT NULL,
  `id_pekerjaan_wali` int(11) DEFAULT NULL,
  `id_penghasilan_wali` int(11) DEFAULT NULL,
  `kewarganegaraan` varchar(2) DEFAULT NULL,
  `regpd_id_reg_pd` varchar(255) DEFAULT NULL,
  `regpd_id_sms` varchar(255) DEFAULT NULL,
  `regpd_id_pd` varchar(255) DEFAULT NULL,
  `regpd_id_sp` varchar(255) DEFAULT NULL,
  `regpd_id_jns_daftar` decimal(1,0) DEFAULT NULL,
  `regpd_nipd` varchar(18) DEFAULT NULL,
  `regpd_tgl_masuk_sp` date DEFAULT NULL,
  `regpd_id_jns_keluar` varchar(1) DEFAULT NULL,
  `regpd_tgl_keluar` date DEFAULT NULL,
  `regpd_ket` varchar(128) DEFAULT NULL,
  `regpd_skhun` varchar(20) DEFAULT NULL,
  `regpd_a_pernah_paud` decimal(1,0) DEFAULT NULL,
  `regpd_a_pernah_tk` decimal(1,0) DEFAULT NULL,
  `regpd_mulai_smt` varchar(5) DEFAULT NULL,
  `regpd_sks_diakui` decimal(3,0) DEFAULT NULL,
  `regpd_jalur_skripsi` decimal(1,0) DEFAULT NULL,
  `regpd_judul_skripsi` varchar(250) DEFAULT NULL,
  `regpd_bln_awal_bimbingan` date DEFAULT NULL,
  `regpd_bln_akhir_bimbingan` date DEFAULT NULL,
  `regpd_sk_yudisium` varchar(40) DEFAULT NULL,
  `regpd_tgl_sk_yudisium` date DEFAULT NULL,
  `regpd_ipk` double DEFAULT NULL,
  `regpd_no_seri_ijazah` varchar(40) DEFAULT NULL,
  `regpd_sert_prof` varchar(40) DEFAULT NULL,
  `regpd_a_pindah_mhs_asing` decimal(1,0) DEFAULT NULL,
  `regpd_nm_pt_asal` varchar(50) DEFAULT NULL,
  `regpd_nm_prodi_asal` varchar(50) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_pd`),
  KEY `id_pd` (`id_pd`) USING BTREE,
  KEY `id_agama` (`id_agama`) USING BTREE,
  KEY `fk_mahasiswa_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_mahasiswa_kebutuhan_khusus_id_kk` (`id_kk`) USING BTREE,
  KEY `fk_mahasiswa_wilayah_id_wil` (`id_wil`) USING BTREE,
  KEY `fk_mahasiswa_pekerjaan_id_pekerjaan_ayah` (`id_pekerjaan_ayah`) USING BTREE,
  KEY `fk_mahasiswa_penghasilan_id_penghasilan_ayah` (`id_penghasilan_ayah`) USING BTREE,
  KEY `fk_mahasiswa_jenjang_pendidikan_id_jenjang_pendidikan_ayah` (`id_jenjang_pendidikan_ayah`) USING BTREE,
  KEY `fk_mahasiswa_kebutuhan_khusus_id_kebutuhan_khusus_ayah` (`id_kebutuhan_khusus_ayah`) USING BTREE,
  KEY `fk_mahasiswa_kebutuhan_khusus_id_kebutuhan_khusus_ibu` (`id_kebutuhan_khusus_ibu`) USING BTREE,
  KEY `fk_mahasiswa_jenjang_pendidikan_id_jenjang_pendidikan_ibu` (`id_jenjang_pendidikan_ibu`) USING BTREE,
  KEY `fk_mahasiswa_jenjang_pendidikan_id_jenjang_pendidikan_wali` (`id_jenjang_pendidikan_wali`) USING BTREE,
  KEY `fk_mahasiswa_pekerjaan_id_pekerjaan_ibu` (`id_pekerjaan_ibu`) USING BTREE,
  KEY `fk_mahasiswa_pekerjaan_id_pekerjaan_wali` (`id_pekerjaan_wali`) USING BTREE,
  KEY `fk_mahasiswa_penghasilan_id_penghasilan_ibu` (`id_penghasilan_ibu`) USING BTREE,
  KEY `fk_mahasiswa_penghasilan_id_penghasilan_wali` (`id_penghasilan_wali`) USING BTREE,
  KEY `fk_mahasiswa_status_mahasiswa_id_stat_mhs` (`stat_pd`) USING BTREE,
  KEY `nm_pd` (`nm_pd`) USING BTREE,
  CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_agama`) REFERENCES `agama` (`id_agama`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_10` FOREIGN KEY (`id_pekerjaan_wali`) REFERENCES `pekerjaan` (`id_pekerjaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_11` FOREIGN KEY (`id_penghasilan_ayah`) REFERENCES `penghasilan` (`id_penghasilan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_12` FOREIGN KEY (`id_penghasilan_ibu`) REFERENCES `penghasilan` (`id_penghasilan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_13` FOREIGN KEY (`id_penghasilan_wali`) REFERENCES `penghasilan` (`id_penghasilan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_14` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_15` FOREIGN KEY (`stat_pd`) REFERENCES `status_mahasiswa` (`id_stat_mhs`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_16` FOREIGN KEY (`id_wil`) REFERENCES `wilayah` (`id_wil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_jenjang_pendidikan_ayah`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_3` FOREIGN KEY (`id_jenjang_pendidikan_ibu`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_4` FOREIGN KEY (`id_jenjang_pendidikan_wali`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_5` FOREIGN KEY (`id_kebutuhan_khusus_ayah`) REFERENCES `kebutuhan_khusus` (`id_kk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_6` FOREIGN KEY (`id_kebutuhan_khusus_ibu`) REFERENCES `kebutuhan_khusus` (`id_kk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_7` FOREIGN KEY (`id_kk`) REFERENCES `kebutuhan_khusus` (`id_kk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_8` FOREIGN KEY (`id_pekerjaan_ayah`) REFERENCES `pekerjaan` (`id_pekerjaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_9` FOREIGN KEY (`id_pekerjaan_ibu`) REFERENCES `pekerjaan` (`id_pekerjaan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='nomor induk kependudukan ';


DROP VIEW IF EXISTS `mahasiswa_aktifitas_kuliah_view`;
CREATE TABLE `mahasiswa_aktifitas_kuliah_view` (`id_kuliah_pd` int(11), `id_reg_pd` varchar(255), `id_smt` varchar(5), `id_stat_mhs` varchar(1), `ips` double, `ipk` double, `sks_smt` decimal(3,0), `sks_total` decimal(3,0), `nm_stat_mhs` varchar(50), `nm_smt` varchar(20));


DROP VIEW IF EXISTS `mahasiswa_detail_view`;
CREATE TABLE `mahasiswa_detail_view` (`id_agama` smallint(6), `FK__agama` varchar(25), `id_jenjang_pendidikan_ayah` decimal(2,0), `FK__jenjang_pendidikan_ayah` varchar(25), `id_jenjang_pendidikan_ibu` decimal(2,0), `FK__jenjang_pendidikan_ibu` varchar(25), `id_jenjang_pendidikan_wali` decimal(2,0), `FK__jenjang_pendidikan_wali` varchar(25), `id_kebutuhan_khusus_ayah` int(11), `FK__kebutuhan_khusus_ayah` decimal(1,0), `id_kebutuhan_khusus_ibu` int(11), `FK__kebutuhan_khusus_ibu` decimal(1,0), `id_kk` int(11), `FK__kk` decimal(1,0), `id_pekerjaan_ayah` int(11), `FK__pekerjaan_ayah` varchar(25), `id_pekerjaan_ibu` int(11), `FK__pekerjaan_ibu` varchar(25), `id_pekerjaan_wali` int(11), `FK__pekerjaan_wali` varchar(25), `id_penghasilan_ayah` int(11), `FK__penghasilan_ayah` varchar(40), `id_penghasilan_ibu` int(11), `FK__penghasilan_ibu` varchar(40), `id_penghasilan_wali` int(11), `FK__penghasilan_wali` varchar(40), `id_sp` varchar(255), `FK__sp` varchar(80), `id_wil` varchar(8), `FK__wil` varchar(255), `id_pd` varchar(255), `nm_pd` varchar(60), `jk` varchar(1), `nisn` varchar(10), `nik` varchar(16), `tmpt_lahir` varchar(32), `tgl_lahir` date, `jln` varchar(80), `rt` decimal(2,0), `rw` decimal(2,0), `nm_dsn` varchar(50), `ds_kel` varchar(50), `kode_pos` varchar(5), `id_jns_tinggal` decimal(2,0), `id_alat_transport` decimal(2,0), `telepon_rumah` varchar(20), `telepon_seluler` varchar(20), `email` varchar(50), `a_terima_kps` decimal(1,0), `no_kps` varchar(40), `stat_pd` varchar(1), `nm_ayah` varchar(60), `tgl_lahir_ayah` date, `nm_ibu_kandung` varchar(60), `tgl_lahir_ibu` date, `nm_wali` varchar(30), `tgl_lahir_wali` date, `kewarganegaraan` varchar(2), `regpd_id_reg_pd` varchar(255), `regpd_id_sms` varchar(255), `regpd_id_pd` varchar(255), `regpd_id_sp` varchar(255), `regpd_id_jns_daftar` decimal(1,0), `regpd_nipd` varchar(18), `regpd_tgl_masuk_sp` date, `regpd_id_jns_keluar` varchar(1), `regpd_tgl_keluar` date, `regpd_ket` varchar(128), `regpd_skhun` varchar(20), `regpd_a_pernah_paud` decimal(1,0), `regpd_a_pernah_tk` decimal(1,0), `regpd_mulai_smt` varchar(5), `regpd_sks_diakui` decimal(3,0), `regpd_jalur_skripsi` decimal(1,0), `regpd_judul_skripsi` varchar(250), `regpd_bln_awal_bimbingan` date, `regpd_bln_akhir_bimbingan` date, `regpd_sk_yudisium` varchar(40), `regpd_tgl_sk_yudisium` date, `regpd_ipk` double, `regpd_no_seri_ijazah` varchar(40), `regpd_sert_prof` varchar(40), `regpd_a_pindah_mhs_asing` decimal(1,0), `regpd_nm_pt_asal` varchar(50), `regpd_nm_prodi_asal` varchar(50), `last_update` datetime, `soft_delete` enum('1','0'), `last_sync` datetime, `id_updater` varchar(255));


DROP VIEW IF EXISTS `mahasiswa_list_view`;
CREATE TABLE `mahasiswa_list_view` (`id_pd` varchar(255), `id_reg_pd` varchar(255), `id_sms` varchar(255), `nm_pd` varchar(60), `lower_nm_pd` varchar(60), `nipd` varchar(18), `jk` varchar(1), `nm_agama` varchar(25), `tgl_lahir` date, `nm_lemb` varchar(80), `nm_stat_mhs` varchar(50), `mulai_smt` varchar(4), `last_update` datetime);


DROP TABLE IF EXISTS `mahasiswa_pt`;
CREATE TABLE `mahasiswa_pt` (
  `id_reg_pd` varchar(255) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_pd` varchar(255) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_jns_daftar` decimal(1,0) DEFAULT NULL,
  `nipd` varchar(18) DEFAULT NULL,
  `tgl_masuk_sp` date DEFAULT NULL,
  `id_jns_keluar` varchar(1) DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `ket` varchar(128) DEFAULT NULL,
  `skhun` varchar(20) DEFAULT NULL,
  `a_pernah_paud` decimal(1,0) DEFAULT NULL,
  `a_pernah_tk` decimal(1,0) DEFAULT NULL,
  `mulai_smt` varchar(5) DEFAULT NULL,
  `sks_diakui` decimal(3,0) DEFAULT NULL,
  `jalur_skripsi` decimal(1,0) DEFAULT NULL,
  `judul_skripsi` varchar(250) DEFAULT NULL,
  `bln_awal_bimbingan` date DEFAULT NULL,
  `bln_akhir_bimbingan` date DEFAULT NULL,
  `sk_yudisium` varchar(40) DEFAULT NULL,
  `tgl_sk_yudisium` date DEFAULT NULL,
  `ipk` double DEFAULT NULL,
  `no_seri_ijazah` varchar(40) DEFAULT NULL,
  `sert_prof` varchar(40) DEFAULT NULL,
  `a_pindah_mhs_asing` decimal(1,0) DEFAULT NULL,
  `nm_pt_asal` varchar(50) DEFAULT NULL,
  `nm_prodi_asal` varchar(50) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_reg_pd`),
  KEY `id_reg_pd` (`id_reg_pd`) USING BTREE,
  KEY `id_jns_daftar` (`id_jns_daftar`) USING BTREE,
  KEY `id_jns_keluar` (`id_jns_keluar`) USING BTREE,
  KEY `fk_mahasiswa_pt_mahasiswa_id_pd` (`id_pd`) USING BTREE,
  KEY `fk_mahasiswa_pt_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_mahasiswa_pt_sms_id_sms` (`id_sms`) USING BTREE,
  KEY `nipd` (`nipd`) USING BTREE,
  CONSTRAINT `mahasiswa_pt_ibfk_1` FOREIGN KEY (`id_jns_keluar`) REFERENCES `jenis_keluar` (`id_jns_keluar`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_pt_ibfk_2` FOREIGN KEY (`id_jns_daftar`) REFERENCES `jenis_pendaftaran` (`id_jns_daftar`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_pt_ibfk_3` FOREIGN KEY (`id_pd`) REFERENCES `mahasiswa` (`id_pd`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_pt_ibfk_4` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_pt_ibfk_5` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `mahasiswa_suggest`;
CREATE TABLE `mahasiswa_suggest` (`id_pd` varchar(255), `id_reg_pd` varchar(255), `nm_pd` varchar(60), `nipd` varchar(18));


DROP VIEW IF EXISTS `mahasiswa_view`;
CREATE TABLE `mahasiswa_view` (`id_agama` smallint(6), `FK__agama` varchar(25), `id_jenjang_pendidikan_ayah` decimal(2,0), `FK__jenjang_pendidikan_ayah` varchar(25), `id_jenjang_pendidikan_ibu` decimal(2,0), `FK__jenjang_pendidikan_ibu` varchar(25), `id_jenjang_pendidikan_wali` decimal(2,0), `FK__jenjang_pendidikan_wali` varchar(25), `id_kebutuhan_khusus_ayah` int(11), `FK__kebutuhan_khusus_ayah` decimal(1,0), `id_kebutuhan_khusus_ibu` int(11), `FK__kebutuhan_khusus_ibu` decimal(1,0), `id_kk` int(11), `FK__kk` decimal(1,0), `id_pekerjaan_ayah` int(11), `FK__pekerjaan_ayah` varchar(25), `id_pekerjaan_ibu` int(11), `FK__pekerjaan_ibu` varchar(25), `id_pekerjaan_wali` int(11), `FK__pekerjaan_wali` varchar(25), `id_penghasilan_ayah` int(11), `FK__penghasilan_ayah` varchar(40), `id_penghasilan_ibu` int(11), `FK__penghasilan_ibu` varchar(40), `id_penghasilan_wali` int(11), `FK__penghasilan_wali` varchar(40), `id_sp` varchar(255), `FK__sp` varchar(80), `id_wil` varchar(8), `FK__wil` varchar(255), `id_pd` varchar(255), `nm_pd` varchar(60), `jk` varchar(1), `nisn` varchar(10), `nik` varchar(16), `tmpt_lahir` varchar(32), `tgl_lahir` date, `jln` varchar(80), `rt` decimal(2,0), `rw` decimal(2,0), `nm_dsn` varchar(50), `ds_kel` varchar(50), `kode_pos` varchar(5), `id_jns_tinggal` decimal(2,0), `id_alat_transport` decimal(2,0), `telepon_rumah` varchar(20), `telepon_seluler` varchar(20), `email` varchar(50), `a_terima_kps` decimal(1,0), `no_kps` varchar(40), `stat_pd` varchar(1), `nm_ayah` varchar(60), `tgl_lahir_ayah` date, `nm_ibu_kandung` varchar(60), `tgl_lahir_ibu` date, `nm_wali` varchar(30), `tgl_lahir_wali` date, `kewarganegaraan` varchar(2), `regpd_id_reg_pd` varchar(255), `regpd_id_sms` varchar(255), `regpd_id_pd` varchar(255), `regpd_id_sp` varchar(255), `regpd_id_jns_daftar` decimal(1,0), `regpd_nipd` varchar(18), `regpd_tgl_masuk_sp` date, `regpd_id_jns_keluar` varchar(1), `regpd_tgl_keluar` date, `regpd_ket` varchar(128), `regpd_skhun` varchar(20), `regpd_a_pernah_paud` decimal(1,0), `regpd_a_pernah_tk` decimal(1,0), `regpd_mulai_smt` varchar(5), `regpd_sks_diakui` decimal(3,0), `regpd_jalur_skripsi` decimal(1,0), `regpd_judul_skripsi` varchar(250), `regpd_bln_awal_bimbingan` date, `regpd_bln_akhir_bimbingan` date, `regpd_sk_yudisium` varchar(40), `regpd_tgl_sk_yudisium` date, `regpd_ipk` double, `regpd_no_seri_ijazah` varchar(40), `regpd_sert_prof` varchar(40), `regpd_a_pindah_mhs_asing` decimal(1,0), `regpd_nm_pt_asal` varchar(50), `regpd_nm_prodi_asal` varchar(50), `last_update` datetime, `soft_delete` enum('1','0'), `last_sync` datetime, `id_updater` varchar(255));


DROP TABLE IF EXISTS `mata_kuliah`;
CREATE TABLE `mata_kuliah` (
  `id_mk` varchar(255) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `id_jenj_didik` decimal(2,0) DEFAULT NULL,
  `kode_mk` varchar(20) DEFAULT NULL,
  `nm_mk` varchar(200) DEFAULT NULL,
  `jns_mk` varchar(1) DEFAULT NULL,
  `kel_mk` varchar(1) DEFAULT NULL,
  `sks_mk` decimal(2,0) DEFAULT NULL,
  `sks_tm` decimal(2,0) DEFAULT NULL,
  `sks_prak` decimal(2,0) DEFAULT NULL,
  `sks_prak_lap` decimal(2,0) DEFAULT NULL,
  `sks_sim` decimal(2,0) DEFAULT NULL,
  `metode_pelaksanaan_kuliah` varchar(50) DEFAULT NULL,
  `a_sap` enum('1','0') DEFAULT '0',
  `a_silabus` enum('1','0') DEFAULT '0',
  `a_bahan_ajar` enum('1','0') DEFAULT '0',
  `acara_prak` enum('1','0') DEFAULT '0',
  `a_diktat` enum('1','0') DEFAULT '0',
  `tgl_mulai_efektif` date DEFAULT NULL,
  `tgl_akhir_efektif` date DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_mk`),
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE,
  KEY `kode_mk` (`kode_mk`) USING BTREE,
  KEY `nm_mk` (`nm_mk`) USING BTREE,
  KEY `id_sms` (`id_sms`) USING BTREE,
  KEY `fk_mata_kuliah_jenis_matakuilah_id_jns_mk` (`jns_mk`) USING BTREE,
  KEY `fk_mata_kuliah_kel_matakuilah_id_kel_mk` (`kel_mk`) USING BTREE,
  KEY `id_mk` (`id_mk`) USING BTREE,
  CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`jns_mk`) REFERENCES `jenis_matakuliah` (`id_jns_mk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mata_kuliah_ibfk_2` FOREIGN KEY (`id_jenj_didik`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mata_kuliah_ibfk_3` FOREIGN KEY (`kel_mk`) REFERENCES `kel_matakuliah` (`id_kel_mk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mata_kuliah_ibfk_4` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `mata_kuliah_kurikulum`;
CREATE TABLE `mata_kuliah_kurikulum` (
  `id_mk_kurikulum` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurikulum_sp` varchar(255) NOT NULL,
  `id_mk` varchar(255) DEFAULT NULL,
  `smt` decimal(2,0) DEFAULT NULL,
  `sks_mk` decimal(2,0) DEFAULT NULL,
  `sks_tm` decimal(2,0) DEFAULT NULL,
  `sks_prak` decimal(2,0) DEFAULT NULL,
  `sks_prak_lap` decimal(2,0) DEFAULT NULL,
  `sks_sim` decimal(2,0) DEFAULT NULL,
  `a_wajib` decimal(1,0) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_mk_kurikulum`),
  KEY `fk_mata_kuliah_kurikulum_kurikulum_id_kurikulum_sp` (`id_kurikulum_sp`) USING BTREE,
  KEY `fk_mata_kuliah_kurikulum_mata_kuliah_id_mk` (`id_mk`) USING BTREE,
  KEY `id_mk_kurikulum` (`id_mk_kurikulum`) USING BTREE,
  CONSTRAINT `mata_kuliah_kurikulum_ibfk_1` FOREIGN KEY (`id_kurikulum_sp`) REFERENCES `kurikulum` (`id_kurikulum_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mata_kuliah_kurikulum_ibfk_2` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id_mk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `mata_kuliah_kurikulum_smt`;
CREATE TABLE `mata_kuliah_kurikulum_smt` (`id_sms` varchar(255), `id_smt_berlaku` varchar(5), `id_mk_kurikulum` int(11), `id_kurikulum_sp` varchar(255), `id_mk` varchar(255), `nm_kurikulum_sp` varchar(60), `kode_mk` varchar(20), `nm_mk` varchar(200), `sks_mk` decimal(2,0), `sks_tm` decimal(2,0), `sks_prak` decimal(2,0), `sks_prak_lap` decimal(2,0), `sks_sim` decimal(2,0), `smt` decimal(2,0), `a_wajib` decimal(1,0));


DROP VIEW IF EXISTS `mata_kuliah_view`;
CREATE TABLE `mata_kuliah_view` (`nm_prodi` varchar(80), `kode_mk` varchar(20), `nm_mk` varchar(200), `nm_jns_mk` varchar(50), `nm_kel_mk` varchar(50), `sks_mk` decimal(2,0), `a_bahan_ajar` enum('1','0'), `a_sap` enum('1','0'), `a_silabus` enum('1','0'), `id_mk` varchar(255), `id_sms` varchar(255), `id_jenj_didik` decimal(2,0), `jns_mk` varchar(1), `kel_mk` varchar(1), `sks_tm` decimal(2,0), `sks_prak` decimal(2,0), `sks_prak_lap` decimal(2,0), `sks_sim` decimal(2,0), `metode_pelaksanaan_kuliah` varchar(50), `acara_prak` enum('1','0'), `a_diktat` enum('1','0'), `tgl_mulai_efektif` date, `tgl_akhir_efektif` date);


DROP TABLE IF EXISTS `mhs`;
CREATE TABLE `mhs` (
  `nim` varchar(20) NOT NULL,
  `nmmhs` varchar(100) DEFAULT NULL,
  `kdpro` varchar(45) DEFAULT NULL,
  `thaka` varchar(12) DEFAULT NULL,
  `tptlhr` varchar(45) DEFAULT NULL,
  `tgllhr` date DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `thlulus` int(10) DEFAULT NULL,
  `telp` varchar(45) DEFAULT NULL,
  `almasl` varchar(200) DEFAULT NULL,
  `sekasl` varchar(200) DEFAULT NULL,
  `nmortu` varchar(45) DEFAULT NULL,
  `kel` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `transfer` tinyint(11) DEFAULT NULL,
  `cetakkrs` tinyint(4) DEFAULT NULL,
  `kelas` varchar(45) NOT NULL,
  `registrasi` tinyint(3) NOT NULL,
  `cekkrs` tinyint(4) NOT NULL,
  `maxsks` int(11) NOT NULL,
  `matrikulasi` tinyint(1) NOT NULL,
  `maxsksc` int(11) NOT NULL,
  `sksde` int(11) NOT NULL,
  `praktek` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`nim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `negara`;
CREATE TABLE `negara` (
  `id_negara` varchar(2) NOT NULL,
  `nm_negara` varchar(45) DEFAULT NULL,
  `a_ln` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_negara`),
  KEY `id_negara` (`id_negara`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_kls` varchar(255) NOT NULL,
  `id_reg_pd` varchar(255) NOT NULL,
  `asal_data` varchar(1) DEFAULT '9',
  `nilai_angka` decimal(4,1) DEFAULT NULL,
  `nilai_huruf` varchar(3) DEFAULT NULL,
  `nilai_indeks` decimal(4,2) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_nilai`,`id_kls`,`id_reg_pd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `nilai_ti`;
CREATE TABLE `nilai_ti` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_kls` varchar(255) NOT NULL,
  `id_reg_pd` varchar(255) NOT NULL,
  `asal_data` varchar(1) DEFAULT '9',
  `nilai_angka` decimal(4,1) DEFAULT NULL,
  `nilai_huruf` varchar(3) DEFAULT NULL,
  `nilai_indeks` decimal(4,2) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  `kode_mk` varchar(255) DEFAULT NULL,
  `kelas` varchar(5) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`,`id_kls`,`id_reg_pd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `nilai_transfer`;
CREATE TABLE `nilai_transfer` (
  `id_ekuivalensi` varchar(255) NOT NULL,
  `id_reg_pd` varchar(255) DEFAULT NULL,
  `id_mk` varchar(255) DEFAULT NULL,
  `kode_mk_asal` varchar(20) DEFAULT NULL,
  `nm_mk_asal` varchar(200) DEFAULT NULL,
  `sks_asal` decimal(2,0) DEFAULT NULL,
  `sks_diakui` decimal(2,0) DEFAULT NULL,
  `nilai_huruf_asal` varchar(3) DEFAULT NULL,
  `nilai_huruf_diakui` varchar(3) DEFAULT NULL,
  `nilai_angka_diakui` decimal(5,2) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_ekuivalensi`),
  KEY `id_ekuivalensi` (`id_ekuivalensi`) USING BTREE,
  KEY `fk_nilai_transfer_mata_kuliah_id_mk` (`id_mk`) USING BTREE,
  KEY `fk_nilai_transfer_mahasiswa_pt_id_reg_pd` (`id_reg_pd`) USING BTREE,
  CONSTRAINT `nilai_transfer_ibfk_1` FOREIGN KEY (`id_reg_pd`) REFERENCES `mahasiswa_pt` (`id_reg_pd`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilai_transfer_ibfk_2` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id_mk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `nilai_view`;
CREATE TABLE `nilai_view` (`id_kls` varchar(255), `kode_mk` varchar(20), `nm_mk` varchar(200), `sks_mk` decimal(2,0), `id_smt` varchar(5), `nm_kls` varchar(5), `nipd` varchar(18), `nm_pd` varchar(60), `mulai_smt` varchar(5), `nilai_angka` decimal(4,1), `nilai_huruf` varchar(3), `id_sms` varchar(255));


DROP TABLE IF EXISTS `pangkat_golongan`;
CREATE TABLE `pangkat_golongan` (
  `id_pangkat_gol` decimal(2,0) NOT NULL,
  `kode_gol` varchar(5) DEFAULT NULL,
  `nm_pangkat` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_pangkat_gol`),
  KEY `id_pangkat_gol` (`id_pangkat_gol`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pekerjaan`;
CREATE TABLE `pekerjaan` (
  `id_pekerjaan` int(11) NOT NULL,
  `nm_pekerjaan` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_pekerjaan`),
  KEY `id_pekerjaan` (`id_pekerjaan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `penghasilan`;
CREATE TABLE `penghasilan` (
  `id_penghasilan` int(11) NOT NULL,
  `nm_penghasilan` varchar(40) DEFAULT NULL,
  `batas_bawah` int(11) DEFAULT NULL,
  `batas_atas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_penghasilan`),
  KEY `id_penghasilan` (`id_penghasilan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `satuan_pendidikan`;
CREATE TABLE `satuan_pendidikan` (
  `id_sp` varchar(255) NOT NULL,
  `nm_lemb` varchar(80) DEFAULT NULL,
  `nss` varchar(12) DEFAULT NULL,
  `npsn` varchar(8) DEFAULT NULL,
  `nm_singkat` varchar(20) DEFAULT NULL,
  `id_bp` smallint(6) DEFAULT NULL,
  `jln` varchar(80) DEFAULT NULL,
  `rt` decimal(2,0) DEFAULT NULL,
  `rw` decimal(2,0) DEFAULT NULL,
  `nm_dsn` varchar(50) DEFAULT NULL,
  `ds_kel` varchar(50) DEFAULT NULL,
  `id_wil` varchar(8) DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `lintang` decimal(10,6) DEFAULT NULL,
  `bujur` decimal(10,6) DEFAULT NULL,
  `no_tel` varchar(20) DEFAULT NULL,
  `no_fax` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `id_kk` int(11) DEFAULT NULL,
  `stat_sp` varchar(1) DEFAULT NULL,
  `sk_pendirian_sp` varchar(40) DEFAULT NULL,
  `tgl_sk_pendirian_sp` date DEFAULT NULL,
  `tgl_berdiri` date DEFAULT NULL,
  `id_stat_milik` decimal(1,0) DEFAULT NULL,
  `sk_izin_operasi` varchar(40) DEFAULT NULL,
  `tgl_sk_izin_operasi` date DEFAULT NULL,
  `no_rek` varchar(20) DEFAULT NULL,
  `nm_bank` varchar(20) DEFAULT NULL,
  `unit_cabang` varchar(50) DEFAULT NULL,
  `nm_rek` varchar(50) DEFAULT NULL,
  `a_mbs` decimal(1,0) DEFAULT NULL,
  `luas_tanah_milik` decimal(7,0) DEFAULT NULL,
  `luas_tanah_bukan_milik` decimal(7,0) DEFAULT NULL,
  `a_lptk` decimal(1,0) DEFAULT NULL,
  `id_pembina` varchar(255) DEFAULT NULL,
  `id_blob` varchar(255) DEFAULT NULL,
  `id_pic` varchar(255) DEFAULT NULL,
  `kode_reg` bigint(20) DEFAULT NULL,
  `flag` varchar(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_sp`),
  KEY `id_sp` (`id_sp`) USING BTREE,
  KEY `id_bp` (`id_bp`) USING BTREE,
  KEY `id_kk` (`id_kk`) USING BTREE,
  KEY `fk_satuan_pendidikan_wilayah_id_wil` (`id_wil`) USING BTREE,
  KEY `nm_lemb` (`nm_lemb`),
  CONSTRAINT `satuan_pendidikan_ibfk_1` FOREIGN KEY (`id_bp`) REFERENCES `bentuk_pendidikan` (`id_bp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `satuan_pendidikan_ibfk_2` FOREIGN KEY (`id_kk`) REFERENCES `jenis_sert` (`id_kk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `satuan_pendidikan_ibfk_3` FOREIGN KEY (`id_kk`) REFERENCES `kebutuhan_khusus` (`id_kk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `satuan_pendidikan_ibfk_4` FOREIGN KEY (`id_wil`) REFERENCES `wilayah` (`id_wil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `semester`;
CREATE TABLE `semester` (
  `id_smt` varchar(5) NOT NULL,
  `id_thn_ajaran` decimal(4,0) DEFAULT NULL,
  `nm_smt` varchar(20) DEFAULT NULL,
  `smt` decimal(1,0) DEFAULT NULL,
  `a_periode_aktif` decimal(1,0) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  PRIMARY KEY (`id_smt`),
  KEY `id_smt` (`id_smt`) USING BTREE,
  KEY `fk_semester_tahun_ajaran_id_thn_ajaran` (`id_thn_ajaran`) USING BTREE,
  CONSTRAINT `semester_ibfk_1` FOREIGN KEY (`id_thn_ajaran`) REFERENCES `tahun_ajaran` (`id_thn_ajaran`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `id_sms` varchar(255) NOT NULL,
  `nm_lemb` varchar(80) DEFAULT NULL,
  `smt_mulai` varchar(5) DEFAULT NULL,
  `kode_prodi` varchar(10) DEFAULT NULL,
  `id_sp` varchar(255) DEFAULT NULL,
  `id_jenj_didik` decimal(2,0) DEFAULT NULL,
  `id_jns_sms` decimal(2,0) DEFAULT NULL,
  `id_pengguna` varchar(255) DEFAULT NULL,
  `id_fungsi_lab` varchar(1) DEFAULT NULL,
  `id_kel_usaha` varchar(8) DEFAULT NULL,
  `id_blob` varchar(255) DEFAULT NULL,
  `id_wil` varchar(8) DEFAULT NULL,
  `id_jur` varchar(10) DEFAULT NULL,
  `id_induk_sms` varchar(255) DEFAULT NULL,
  `jln` varchar(80) DEFAULT NULL,
  `rt` decimal(2,0) DEFAULT NULL,
  `rw` decimal(2,0) DEFAULT NULL,
  `nm_dsn` varchar(50) DEFAULT NULL,
  `ds_kel` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(5) DEFAULT NULL,
  `lintang` decimal(10,6) DEFAULT NULL,
  `bujur` decimal(10,6) DEFAULT NULL,
  `no_tel` varchar(20) DEFAULT NULL,
  `no_fax` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `singkatan` varchar(50) DEFAULT NULL,
  `tgl_berdiri` date DEFAULT NULL,
  `sk_selenggara` varchar(40) DEFAULT NULL,
  `tgl_sk_selenggara` date DEFAULT NULL,
  `tmt_sk_selenggara` date DEFAULT NULL,
  `tst_sk_selenggara` date DEFAULT NULL,
  `kpst_pd` decimal(5,0) DEFAULT NULL,
  `sks_lulus` decimal(3,0) DEFAULT NULL,
  `gelar_lulusan` varchar(10) DEFAULT NULL,
  `stat_prodi` varchar(1) DEFAULT NULL,
  `polesei_nilai` varchar(1) DEFAULT NULL,
  `luas_lab` decimal(5,0) DEFAULT NULL,
  `kapasitas_prak_satu_shift` decimal(4,0) DEFAULT NULL,
  `jml_mhs_pengguna` decimal(6,0) DEFAULT NULL,
  `jml_jam_penggunaan` decimal(5,1) DEFAULT NULL,
  `jml_prodi_pengguna` decimal(3,0) DEFAULT NULL,
  `jml_modul_prak_sendiri` decimal(4,0) DEFAULT NULL,
  `jml_modul_prak_lain` decimal(4,0) DEFAULT NULL,
  `fungsi_selain_prak` varchar(1) DEFAULT NULL,
  `penggunaan_lab` varchar(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  PRIMARY KEY (`id_sms`),
  KEY `id_sms` (`id_sms`) USING BTREE,
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE,
  KEY `id_jns_sms` (`id_jns_sms`) USING BTREE,
  KEY `id_jur` (`id_jur`) USING BTREE,
  KEY `fk_sms_satuan_pendidikan_id_sp` (`id_sp`) USING BTREE,
  KEY `fk_sms_wilayah_id_wil` (`id_wil`) USING BTREE,
  KEY `nm_lemb` (`nm_lemb`) USING BTREE,
  CONSTRAINT `sms_ibfk_1` FOREIGN KEY (`id_jns_sms`) REFERENCES `jenis_sms` (`id_jns_sms`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sms_ibfk_2` FOREIGN KEY (`id_jenj_didik`) REFERENCES `jenjang_pendidikan` (`id_jenj_didik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sms_ibfk_3` FOREIGN KEY (`id_jur`) REFERENCES `jurusan` (`id_jur`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sms_ibfk_4` FOREIGN KEY (`id_sp`) REFERENCES `satuan_pendidikan` (`id_sp`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sms_ibfk_5` FOREIGN KEY (`id_wil`) REFERENCES `wilayah` (`id_wil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `status_keaktifan_pegawai`;
CREATE TABLE `status_keaktifan_pegawai` (
  `id_stat_aktif` decimal(2,0) NOT NULL,
  `nm_stat_aktif` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_stat_aktif`),
  KEY `id_stat_aktif` (`id_stat_aktif`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `status_kepegawaian`;
CREATE TABLE `status_kepegawaian` (
  `id_stat_pegawai` smallint(6) NOT NULL,
  `nm_stat_pegawai` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_stat_pegawai`),
  KEY `id_stat_pegawai` (`id_stat_pegawai`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `status_mahasiswa`;
CREATE TABLE `status_mahasiswa` (
  `id_stat_mhs` varchar(1) NOT NULL,
  `nm_stat_mhs` varchar(50) DEFAULT NULL,
  `ket_stat_mhs` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_stat_mhs`),
  KEY `id_stat_mhs` (`id_stat_mhs`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `substansi_kuliah`;
CREATE TABLE `substansi_kuliah` (
  `id_subst` varchar(255) NOT NULL,
  `id_sms` varchar(255) DEFAULT NULL,
  `nm_subst` varchar(40) DEFAULT NULL,
  `id_jns_subst` varchar(5) DEFAULT NULL,
  `sks_mk` decimal(3,1) DEFAULT NULL,
  `sks_tm` decimal(2,0) DEFAULT NULL,
  `sks_prak` decimal(2,0) DEFAULT NULL,
  `sks_prak_lap` decimal(2,0) DEFAULT NULL,
  `sks_sim` decimal(2,0) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `soft_delete` enum('1','0') NOT NULL DEFAULT '0',
  `last_sync` datetime NOT NULL,
  `id_updater` varchar(255) DEFAULT NULL,
  `last_update_local` datetime NOT NULL,
  KEY `id_subst` (`id_subst`) USING BTREE,
  KEY `id_jns_subst` (`id_jns_subst`) USING BTREE,
  KEY `fk_substansi_kuliah_sms_id_sms` (`id_sms`) USING BTREE,
  CONSTRAINT `substansi_kuliah_ibfk_1` FOREIGN KEY (`id_jns_subst`) REFERENCES `jenis_subst` (`id_jns_subst`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `substansi_kuliah_ibfk_2` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `suggest_bobot_nilai`;
CREATE TABLE `suggest_bobot_nilai` (`kode_bobot_nilai` varchar(255), `id_sms` varchar(255), `nilai_huruf` varchar(3), `nilai_indeks` decimal(4,2), `bobot_nilai_min` decimal(5,2), `bobot_nilai_maks` decimal(5,2), `tgl_mulai_efektif` date, `tgl_akhir_efektif` date, `id` varchar(10), `value` varchar(11));


DROP TABLE IF EXISTS `tahun_ajaran`;
CREATE TABLE `tahun_ajaran` (
  `id_thn_ajaran` decimal(4,0) NOT NULL,
  `nm_thn_ajaran` varchar(10) DEFAULT NULL,
  `a_periode_aktif` decimal(1,0) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  PRIMARY KEY (`id_thn_ajaran`),
  KEY `id_thn_ajaran` (`id_thn_ajaran`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `wilayah`;
CREATE TABLE `wilayah` (
  `id_wil` varchar(255) NOT NULL,
  `nm_wil` varchar(255) DEFAULT NULL,
  `asal_wil` varchar(8) DEFAULT NULL,
  `kode_bps` varchar(7) DEFAULT NULL,
  `kode_dagri` varchar(7) DEFAULT NULL,
  `kode_keu` varchar(255) DEFAULT NULL,
  `id_induk_wilayah` varchar(8) DEFAULT NULL,
  `id_level_wil` smallint(6) DEFAULT NULL,
  `id_negara` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id_wil`),
  KEY `id_wil` (`id_wil`) USING BTREE,
  KEY `fk_wilayah_level_wilayah_id_level_wil` (`id_level_wil`) USING BTREE,
  KEY `fk_wilayah_negara_id_negara` (`id_negara`) USING BTREE,
  CONSTRAINT `wilayah_ibfk_1` FOREIGN KEY (`id_level_wil`) REFERENCES `level_wilayah` (`id_level_wil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wilayah_ibfk_2` FOREIGN KEY (`id_negara`) REFERENCES `negara` (`id_negara`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `build_menu_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `build_menu_view` AS select `a`.`id` AS `id`,`a`.`parent_id` AS `parent_id`,`a`.`value` AS `value`,`a`.`url` AS `url`,`a`.`menu_order` AS `menu_order`,`a`.`details` AS `details`,`a`.`icon` AS `icon`,`a`.`published` AS `published`,`a`.`open` AS `open`,`a`.`type` AS `type`,`b`.`role_menu_id` AS `role_menu_id`,`b`.`menu_id` AS `menu_id`,`b`.`group_id` AS `group_id`,`b`.`is_active` AS `is_active` from (`cms_menu` `a` join `cms_role_menu_group` `b` on((`a`.`id` = `b`.`menu_id`))) where ((`a`.`published` = '1') and (`a`.`type` = 'back') and (`b`.`is_active` = '1')) order by `a`.`parent_id`,`a`.`menu_order`;

DROP TABLE IF EXISTS `detail_nilai_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `detail_nilai_view` AS select `a`.`id_nilai` AS `id_nilai`,`a`.`id_kls` AS `id_kls`,`a`.`id_reg_pd` AS `id_reg_pd`,`d`.`nm_lemb` AS `nm_lemb`,`f`.`kode_mk` AS `kode_mk`,`f`.`id_mk` AS `id_mk`,`f`.`nm_mk` AS `nm_mk`,`f`.`sks_mk` AS `sks_mk`,`e`.`id_smt` AS `id_smt`,`e`.`nm_kls` AS `nm_kls`,`b`.`nipd` AS `nipd`,`c`.`nm_pd` AS `nm_pd`,`b`.`id_sms` AS `id_sms`,substr(`b`.`mulai_smt`,1,4) AS `mulai_smt`,`a`.`nilai_angka` AS `nilai_angka`,`a`.`nilai_huruf` AS `nilai_huruf`,`a`.`nilai_indeks` AS `nilai_indeks` from (((((`nilai` `a` join `mahasiswa_pt` `b` on((`a`.`id_reg_pd` = `b`.`id_reg_pd`))) join `mahasiswa` `c` on((`b`.`id_pd` = `c`.`id_pd`))) join `sms` `d` on((`b`.`id_sms` = `d`.`id_sms`))) join `kelas_kuliah` `e` on((`a`.`id_kls` = `e`.`id_kls`))) join `mata_kuliah` `f` on((`e`.`id_mk` = `f`.`id_mk`)));

DROP TABLE IF EXISTS `dosen_detail_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_detail_view` AS select `dosen`.`nm_ptk` AS `nm_ptk`,`dosen`.`id_ptk` AS `id_ptk`,`dosen`.`id_agama` AS `id_agama`,`dosen`.`id_ikatan_kerja` AS `id_ikatan_kerja`,`dosen`.`id_lemb_angkat` AS `id_lemb_angkat`,`dosen`.`id_pangkat_gol` AS `id_pangkat_gol`,`dosen`.`id_sp` AS `id_sp`,`dosen`.`id_stat_aktif` AS `id_stat_aktif`,`dosen`.`id_stat_pegawai` AS `id_stat_pegawai`,`dosen`.`id_wil` AS `id_wil`,`agama`.`nm_agama` AS `nm_agama`,`ikatan_kerja_dosen`.`nm_ikatan_kerja` AS `nm_ikatan_kerja`,`lembaga_pengangkat`.`nm_lemb_angkat` AS `nm_lemb_angkat`,`pangkat_golongan`.`nm_pangkat` AS `nm_pangkat`,`satuan_pendidikan`.`nm_lemb` AS `nm_lemb`,`status_keaktifan_pegawai`.`nm_stat_aktif` AS `nm_stat_aktif`,`status_kepegawaian`.`nm_stat_pegawai` AS `nm_stat_pegawai`,`wilayah`.`nm_wil` AS `nm_wil`,`dosen`.`jk` AS `jk`,`dosen`.`nidn` AS `nidn`,`dosen`.`tmpt_lahir` AS `tmpt_lahir`,`dosen`.`tgl_lahir` AS `tgl_lahir`,`dosen`.`jln` AS `jln`,`dosen`.`rt` AS `rt`,`dosen`.`rw` AS `rw`,`dosen`.`nm_dsn` AS `nm_dsn`,`dosen`.`ds_kel` AS `ds_kel`,`dosen`.`kode_pos` AS `kode_pos`,`dosen`.`no_tel_rmh` AS `no_tel_rmh`,`dosen`.`no_hp` AS `no_hp`,`dosen`.`email` AS `email`,`dosen`.`nm_ibu_kandung` AS `nm_ibu_kandung`,`dosen`.`stat_kawin` AS `stat_kawin`,`dosen`.`nm_suami_istri` AS `nm_suami_istri`,`dosen`.`nip_suami_istri` AS `nip_suami_istri`,`dosen`.`id_pekerjaan_suami_istri` AS `id_pekerjaan_suami_istri`,`dosen`.`npwp` AS `npwp`,`dosen`.`kewarganegaraan` AS `kewarganegaraan`,`dosen`.`last_update` AS `last_update`,`dosen`.`nip` AS `nip`,`dosen`.`id_blob` AS `id_blob`,`dosen`.`nik` AS `nik`,`dosen`.`niy_nigk` AS `niy_nigk`,`dosen`.`nuptk` AS `nuptk`,`dosen`.`id_jns_ptk` AS `id_jns_ptk`,`dosen`.`id_bid_pengawas` AS `id_bid_pengawas`,`dosen`.`sk_cpns` AS `sk_cpns`,`dosen`.`tgl_sk_cpns` AS `tgl_sk_cpns`,`dosen`.`sk_angkat` AS `sk_angkat`,`dosen`.`tmt_sk_angkat` AS `tmt_sk_angkat`,`dosen`.`id_keahlian_lab` AS `id_keahlian_lab`,`dosen`.`id_sumber_gaji` AS `id_sumber_gaji`,`dosen`.`tmt_pns` AS `tmt_pns`,`dosen`.`a_lisensi_kepsek` AS `a_lisensi_kepsek`,`dosen`.`jml_sekolah_binaan` AS `jml_sekolah_binaan`,`dosen`.`a_diklat_awas` AS `a_diklat_awas`,`dosen`.`akta_ijin_ajar` AS `akta_ijin_ajar`,`dosen`.`nira` AS `nira`,`dosen`.`stat_data` AS `stat_data`,`dosen`.`mampu_handle_kk` AS `mampu_handle_kk`,`dosen`.`a_braille` AS `a_braille`,`dosen`.`a_bhs_isyarat` AS `a_bhs_isyarat`,`dosen`.`soft_delete` AS `soft_delete`,`dosen`.`last_sync` AS `last_sync`,`dosen`.`id_updater` AS `id_updater` from ((((((((`dosen` left join `agama` on((`dosen`.`id_agama` = `agama`.`id_agama`))) left join `ikatan_kerja_dosen` on((`dosen`.`id_ikatan_kerja` = `ikatan_kerja_dosen`.`id_ikatan_kerja`))) left join `lembaga_pengangkat` on((`dosen`.`id_lemb_angkat` = `lembaga_pengangkat`.`id_lemb_angkat`))) left join `pangkat_golongan` on((`dosen`.`id_pangkat_gol` = `pangkat_golongan`.`id_pangkat_gol`))) left join `satuan_pendidikan` on((`dosen`.`id_sp` = `satuan_pendidikan`.`id_sp`))) left join `status_keaktifan_pegawai` on((`dosen`.`id_stat_aktif` = `status_keaktifan_pegawai`.`id_stat_aktif`))) left join `status_kepegawaian` on((`dosen`.`id_stat_pegawai` = `status_kepegawaian`.`id_stat_pegawai`))) left join `wilayah` on((`dosen`.`id_wil` = `wilayah`.`id_wil`)));

DROP TABLE IF EXISTS `dosen_jabfungsional_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_jabfungsional_view` AS select `a`.`id_dos_jabfung` AS `id_dos_jabfung`,`a`.`id_ptk` AS `id_ptk`,`a`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`a`.`id_jabfung` AS `id_jabfung`,`b`.`nm_lemb` AS `nm_prodi`,`b`.`kode_prodi` AS `kode_prodi`,`a`.`sk_jabatan` AS `sk_jabatan`,`c`.`nm_jabfung` AS `nm_jabfung`,`a`.`tgl_sk_jabatan` AS `tgl_sk_jabatan`,`a`.`tmt_jabatan` AS `tmt_jabatan` from ((`dosen_jabfung` `a` left join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`))) left join `jabfung` `c` on((`a`.`id_jabfung` = `c`.`id_jabfung`)));

DROP TABLE IF EXISTS `dosen_kepangkatan_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_kepangkatan_view` AS select `a`.`id_dos_pangkat` AS `id_dos_pangkat`,`a`.`id_ptk` AS `id_ptk`,`a`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`a`.`id_pangkat_gol` AS `id_pangkat_gol`,`c`.`nm_pangkat` AS `nm_pangkat`,`c`.`kode_gol` AS `kode_gol`,`b`.`nm_lemb` AS `nm_prodi`,`b`.`kode_prodi` AS `kode_prodi`,`a`.`sk_pangkat` AS `sk_pangkat`,`a`.`tgl_sk_pangkat` AS `tgl_sk_pangkat`,`a`.`tmt_sk_pangkat` AS `tmt_sk_pangkat`,`a`.`masa_kerja_thn` AS `masa_kerja_thn`,`a`.`masa_kerja_bln` AS `masa_kerja_bln` from ((`dosen_kepangkatan` `a` left join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`))) left join `pangkat_golongan` `c` on((`a`.`id_pangkat_gol` = `c`.`id_pangkat_gol`)));

DROP TABLE IF EXISTS `dosen_list_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_list_view` AS select `e`.`id_reg_ptk` AS `id_reg_ptk`,`e`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`b`.`nm_agama` AS `nm_agama`,`a`.`id_ptk` AS `id_ptk`,`a`.`nm_ptk` AS `nm_ptk`,`a`.`nidn` AS `nidn`,`a`.`jk` AS `jk`,`a`.`tmpt_lahir` AS `tmpt_lahir`,`c`.`nm_stat_pegawai` AS `nm_stat_pegawai`,`a`.`last_update` AS `last_update` from (((`dosen` `a` left join `agama` `b` on((`a`.`id_agama` = `b`.`id_agama`))) left join `status_kepegawaian` `c` on((`a`.`id_stat_pegawai` = `c`.`id_stat_pegawai`))) left join `dosen_pt` `e` on((`a`.`id_ptk` = `e`.`id_ptk`)));

DROP TABLE IF EXISTS `dosen_pendidikan_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_pendidikan_view` AS select `a`.`id_ptk` AS `id_ptk`,`a`.`id_sp` AS `id_sp`,`a`.`bidang_studi` AS `bidang_studi`,`a`.`id_dos_pendidikan` AS `id_dos_pendidikan`,`a`.`id_jenj_didik` AS `id_jenj_didik`,`a`.`gelar` AS `gelar`,`a`.`id_sp_asal` AS `id_sp_asal`,`a`.`fakultas` AS `fakultas`,`a`.`thn_lulus` AS `thn_lulus`,`a`.`sks_lulus` AS `sks_lulus`,`a`.`ipk_lulus` AS `ipk_lulus`,`b`.`nm_lemb` AS `nm_pt`,`c`.`nm_jenj_didik` AS `nm_jenj_didik` from ((`dosen_pendidikan` `a` left join `satuan_pendidikan` `b` on((`a`.`id_sp_asal` = `b`.`id_sp`))) left join `jenjang_pendidikan` `c` on((`a`.`id_jenj_didik` = `c`.`id_jenj_didik`)));

DROP TABLE IF EXISTS `dosen_penugasan_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_penugasan_view` AS select `a`.`id_reg_ptk` AS `id_reg_ptk`,`a`.`id_thn_ajaran` AS `id_thn_ajaran`,`a`.`id_ptk` AS `id_ptk`,`a`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`b`.`nm_thn_ajaran` AS `nm_thn_ajaran`,`c`.`nm_lemb` AS `nm_prodi`,`c`.`kode_prodi` AS `kode_prodi`,`a`.`no_srt_tgs` AS `no_srt_tgs`,`a`.`tgl_srt_tgs` AS `tgl_srt_tgs`,`a`.`tmt_srt_tgs` AS `tmt_srt_tgs` from ((`dosen_pt` `a` left join `tahun_ajaran` `b` on((`a`.`id_thn_ajaran` = `b`.`id_thn_ajaran`))) left join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`)));

DROP TABLE IF EXISTS `dosen_sertifikasi_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_sertifikasi_view` AS select `a`.`id_ptk` AS `id_ptk`,`a`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`b`.`nm_lemb` AS `nm_prodi`,`b`.`kode_prodi` AS `kode_prodi`,`a`.`id_dos_sert` AS `id_dos_sert`,`a`.`no_peserta` AS `no_peserta`,`a`.`bid_studi` AS `bid_studi`,`a`.`id_jns_sert` AS `id_jns_sert`,`c`.`nm_jns_sert` AS `nm_jns_sert`,`a`.`thn_sert` AS `thn_sert`,`a`.`no_sk_sert` AS `no_sk_sert` from ((`dosen_sertifikasi` `a` left join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`))) left join `jenis_sert` `c` on((`a`.`id_jns_sert` = `c`.`id_jns_sert`)));

DROP TABLE IF EXISTS `dosen_struktural_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dosen_struktural_view` AS select `a`.`id_ptk` AS `id_ptk`,`a`.`id_sms` AS `id_sms`,`a`.`id_sp` AS `id_sp`,`b`.`nm_lemb` AS `nm_prodi`,`b`.`kode_prodi` AS `kode_prodi`,`a`.`id_jabstruk` AS `id_jabstruk`,`c`.`nm_jabstruk` AS `nm_jabstruk`,`a`.`sk_jabatan` AS `sk_jabatan`,`a`.`tgl_sk_jabatan` AS `tgl_sk_jabatan`,`a`.`tmt_jabatan` AS `tmt_jabatan`,`a`.`id_dos_struktural` AS `id_dos_struktural` from ((`dosen_struktural` `a` left join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`))) left join `jabstruk` `c` on((`a`.`id_jabstruk` = `c`.`id_jabstruk`)));

DROP TABLE IF EXISTS `fastikom_krs_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `fastikom_krs_view` AS select `a`.`nim` AS `nim`,`a`.`kdmk` AS `kdmk`,`a`.`thak` AS `thak`,`a`.`smt` AS `smt`,`a`.`nilhrf` AS `nilhrf`,`a`.`nilang` AS `nilang`,`a`.`kls` AS `kls`,`a`.`kdpro` AS `kdpro`,`a`.`noreg` AS `noreg`,`a`.`smtkrs` AS `smtkrs`,`a`.`noid` AS `noid`,`b`.`nmmhs` AS `nmmhs` from (`krs` `a` join `mhs` `b` on((`a`.`nim` = `b`.`nim`)));

DROP TABLE IF EXISTS `hitung_aktifitas_mahasiswa_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `hitung_aktifitas_mahasiswa_view` AS select `a`.`id_smt` AS `id_smt`,`a`.`id_reg_pd` AS `id_reg_pd`,`b`.`nipd` AS `nipd`,(select round((sum((`bb`.`sks_mk` * `aa`.`nilai_indeks`)) / sum(`bb`.`sks_mk`)),2) from ((`nilai` `aa` join `kelas_kuliah` `bb` on((`aa`.`id_kls` = `bb`.`id_kls`))) join `mata_kuliah` `cc` on((`bb`.`id_mk` = `cc`.`id_mk`))) where ((`bb`.`id_smt` = `a`.`id_smt`) and (`aa`.`id_reg_pd` = `a`.`id_reg_pd`)) group by `aa`.`id_reg_pd`,`bb`.`id_smt`) AS `h_ips`,(select sum(`bb`.`sks_mk`) AS `sks_smt` from ((`nilai` `aa` join `kelas_kuliah` `bb` on((`aa`.`id_kls` = `bb`.`id_kls`))) join `mata_kuliah` `cc` on((`bb`.`id_mk` = `cc`.`id_mk`))) where ((`bb`.`id_smt` = `a`.`id_smt`) and (`aa`.`id_reg_pd` = `a`.`id_reg_pd`)) group by `aa`.`id_reg_pd`,`bb`.`id_smt`) AS `h_sks_smt` from (((`kuliah_mahasiswa` `a` join `mahasiswa_pt` `b` on((`a`.`id_reg_pd` = `b`.`id_reg_pd`))) join `sms` `c` on((`b`.`id_sms` = `c`.`id_sms`))) join `mahasiswa` `d` on((`b`.`id_pd` = `d`.`id_pd`))) order by `a`.`id_smt` desc,`d`.`nm_pd`;

DROP TABLE IF EXISTS `input_nilai`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `input_nilai` AS select `a`.`id_reg_pd` AS `id_reg_pd`,`a`.`id_kls` AS `id_kls`,`b`.`nipd` AS `nipd`,`c`.`nm_pd` AS `nm_pd`,`e`.`kode_mk` AS `kode_mk`,`e`.`nm_mk` AS `nm_mk`,`a`.`nilai_angka` AS `nilai_angka`,`a`.`nilai_huruf` AS `nilai_huruf`,`a`.`nilai_indeks` AS `nilai_indeks` from ((((`nilai` `a` join `mahasiswa_pt` `b` on((`a`.`id_reg_pd` = `b`.`id_reg_pd`))) join `mahasiswa` `c` on((`b`.`id_pd` = `c`.`id_pd`))) join `kelas_kuliah` `d` on((`a`.`id_kls` = `d`.`id_kls`))) join `mata_kuliah` `e` on((`d`.`id_mk` = `e`.`id_mk`))) order by `b`.`nipd`;

DROP TABLE IF EXISTS `insert_aktifitas_kuliah_mahasiwa_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `insert_aktifitas_kuliah_mahasiwa_view` AS select `a`.`id_reg_pd` AS `id_reg_pd`,`b`.`id_smt` AS `id_smt`,sum(`b`.`sks_mk`) AS `h_sks_total`,round((sum((`b`.`sks_mk` * `a`.`nilai_indeks`)) / sum(`b`.`sks_mk`)),2) AS `h_ipk` from (`nilai` `a` join `kelas_kuliah` `b` on((`a`.`id_kls` = `b`.`id_kls`))) where ((`b`.`id_smt` = '20142') and (`b`.`id_sms` = '83a0b8ad-8da3-4b38-b33e-7e849e801021')) group by `a`.`id_reg_pd` order by `a`.`id_reg_pd`;

DROP TABLE IF EXISTS `kelas_kuliah_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `kelas_kuliah_view` AS select `a`.`id_kls` AS `id_kls`,`c`.`nm_lemb` AS `nm_lemb`,`a`.`id_smt` AS `id_smt`,`b`.`kode_mk` AS `kode_mk`,`b`.`nm_mk` AS `nm_mk`,`a`.`nm_kls` AS `nm_kls`,`a`.`sks_mk` AS `sks_mk`,count(`d`.`id_reg_pd`) AS `peserta_kelas`,count(`e`.`id_reg_ptk`) AS `dosen_mengajar` from ((((`kelas_kuliah` `a` join `mata_kuliah` `b` on((`a`.`id_mk` = `b`.`id_mk`))) join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`))) left join `nilai` `d` on((`a`.`id_kls` = `d`.`id_kls`))) left join `ajar_dosen` `e` on((`a`.`id_kls` = `e`.`id_kls`))) group by `d`.`id_kls`,`e`.`id_kls` order by `c`.`nm_lemb`,`a`.`id_smt` desc,`b`.`kode_mk`;

DROP TABLE IF EXISTS `kuliah_subtansi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `kuliah_subtansi` AS select `a`.`id_subst` AS `id_subst`,`a`.`id_jns_subst` AS `id_jns_subst`,`a`.`id_sms` AS `id_sms`,`a`.`nm_subst` AS `nm_subst`,`b`.`nm_jns_subst` AS `nm_jns_subst`,`c`.`nm_lemb` AS `nm_prodi`,`a`.`sks_mk` AS `sks_mk`,`a`.`sks_tm` AS `sks_tm`,`a`.`sks_prak` AS `sks_prak`,`a`.`sks_prak_lap` AS `sks_prak_lap`,`a`.`sks_sim` AS `sks_sim`,`a`.`last_update` AS `last_update` from ((`substansi_kuliah` `a` left join `jenis_subst` `b` on((`a`.`id_jns_subst` = `b`.`id_jns_subst`))) left join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`)));

DROP TABLE IF EXISTS `kurikulum_list_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `kurikulum_list_view` AS select `a`.`id_kurikulum_sp` AS `id_kurikulum_sp`,`a`.`nm_kurikulum_sp` AS `nm_kurikulum_sp`,`a`.`jml_sem_normal` AS `jml_sem_normal`,`a`.`jml_sks_lulus` AS `jml_sks_lulus`,`a`.`jml_sks_wajib` AS `jml_sks_wajib`,`a`.`jml_sks_pilihan` AS `jml_sks_pilihan`,`a`.`id_sms` AS `id_sms`,`a`.`id_jenj_didik` AS `id_jenj_didik`,`a`.`id_smt_berlaku` AS `id_smt_berlaku`,`a`.`last_update` AS `last_update`,`a`.`soft_delete` AS `soft_delete`,`a`.`last_sync` AS `last_sync`,`a`.`id_updater` AS `id_updater`,`a`.`last_update_local` AS `last_update_local`,`b`.`nm_lemb` AS `nm_prodi` from (`kurikulum` `a` join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`)));

DROP TABLE IF EXISTS `list_kelas_perkuliahan_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `list_kelas_perkuliahan_view` AS select `a`.`id_kls` AS `id_kls`,`a`.`id_sms` AS `id_sms`,`a`.`id_mk` AS `id_mk`,`c`.`nm_lemb` AS `nm_prodi`,`a`.`id_smt` AS `id_smt`,`b`.`kode_mk` AS `kode_mk`,`b`.`nm_mk` AS `nm_mk`,`a`.`nm_kls` AS `nm_kls`,`a`.`sks_mk` AS `sks_mk`,`d`.`nm_smt` AS `nm_smt`,`e`.`smt` AS `smt` from ((((`kelas_kuliah` `a` join `mata_kuliah_kurikulum` `e` on((`a`.`id_mk` = `e`.`id_mk`))) join `mata_kuliah` `b` on((`a`.`id_mk` = `b`.`id_mk`))) join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`))) left join `semester` `d` on((`a`.`id_smt` = `d`.`id_smt`))) order by `e`.`smt`,`a`.`id_mk`,`a`.`nm_kls`;

DROP TABLE IF EXISTS `list_mahasiswa_status_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `list_mahasiswa_status_view` AS select `a`.`id_reg_pd` AS `id_reg_pd`,`a`.`nipd` AS `nipd`,`b`.`nm_pd` AS `nm_pd`,`c`.`nm_lemb` AS `nm_lemb`,`a`.`mulai_smt` AS `mulai_smt`,`d`.`ket_keluar` AS `ket_keluar`,`a`.`tgl_keluar` AS `tgl_keluar`,`a`.`ket` AS `ket`,`a`.`id_jns_keluar` AS `id_jns_keluar`,`a`.`jalur_skripsi` AS `jalur_skripsi`,`a`.`judul_skripsi` AS `judul_skripsi`,`a`.`bln_awal_bimbingan` AS `bln_awal_bimbingan`,`a`.`bln_akhir_bimbingan` AS `bln_akhir_bimbingan`,`a`.`sk_yudisium` AS `sk_yudisium`,`a`.`tgl_sk_yudisium` AS `tgl_sk_yudisium`,`a`.`ipk` AS `ipk`,`a`.`no_seri_ijazah` AS `no_seri_ijazah` from (((`mahasiswa_pt` `a` join `mahasiswa` `b` on((`a`.`id_pd` = `b`.`id_pd`))) join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`))) join `jenis_keluar` `d` on((`a`.`id_jns_keluar` = `d`.`id_jns_keluar`)));

DROP TABLE IF EXISTS `list_nilai_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `list_nilai_view` AS select `a`.`id_kls` AS `id_kls`,`c`.`nm_lemb` AS `nm_lemb`,`a`.`id_smt` AS `id_smt`,`b`.`kode_mk` AS `kode_mk`,`b`.`nm_mk` AS `nm_mk`,`a`.`nm_kls` AS `nm_kls`,`a`.`sks_mk` AS `sks_mk`,count(`d`.`id_reg_pd`) AS `peserta_kelas`,count(`d`.`id_reg_pd`) AS `nilai_mhs` from (((`kelas_kuliah` `a` join `mata_kuliah` `b` on((`a`.`id_mk` = `b`.`id_mk`))) join `sms` `c` on((`a`.`id_sms` = `c`.`id_sms`))) left join `nilai` `d` on((`a`.`id_kls` = `d`.`id_kls`))) group by `d`.`id_kls`;

DROP TABLE IF EXISTS `mahasiswa_aktifitas_kuliah_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mahasiswa_aktifitas_kuliah_view` AS select `a`.`id_kuliah_pd` AS `id_kuliah_pd`,`a`.`id_reg_pd` AS `id_reg_pd`,`a`.`id_smt` AS `id_smt`,`a`.`id_stat_mhs` AS `id_stat_mhs`,`a`.`ips` AS `ips`,`a`.`ipk` AS `ipk`,`a`.`sks_smt` AS `sks_smt`,`a`.`sks_total` AS `sks_total`,`b`.`nm_stat_mhs` AS `nm_stat_mhs`,`c`.`nm_smt` AS `nm_smt` from ((`kuliah_mahasiswa` `a` join `status_mahasiswa` `b` on((`a`.`id_stat_mhs` = `b`.`id_stat_mhs`))) join `semester` `c` on((`a`.`id_smt` = `c`.`id_smt`)));

DROP TABLE IF EXISTS `mahasiswa_detail_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mahasiswa_detail_view` AS select `a`.`id_agama` AS `id_agama`,`b`.`nm_agama` AS `FK__agama`,`a`.`id_jenjang_pendidikan_ayah` AS `id_jenjang_pendidikan_ayah`,`c`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_ayah`,`a`.`id_jenjang_pendidikan_ibu` AS `id_jenjang_pendidikan_ibu`,`c`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_ibu`,`a`.`id_jenjang_pendidikan_wali` AS `id_jenjang_pendidikan_wali`,`c`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_wali`,`a`.`id_kebutuhan_khusus_ayah` AS `id_kebutuhan_khusus_ayah`,`d`.`a_kk_a` AS `FK__kebutuhan_khusus_ayah`,`a`.`id_kebutuhan_khusus_ibu` AS `id_kebutuhan_khusus_ibu`,`d`.`a_kk_a` AS `FK__kebutuhan_khusus_ibu`,`a`.`id_kk` AS `id_kk`,`d`.`a_kk_a` AS `FK__kk`,`a`.`id_pekerjaan_ayah` AS `id_pekerjaan_ayah`,`e`.`nm_pekerjaan` AS `FK__pekerjaan_ayah`,`a`.`id_pekerjaan_ibu` AS `id_pekerjaan_ibu`,`e`.`nm_pekerjaan` AS `FK__pekerjaan_ibu`,`a`.`id_pekerjaan_wali` AS `id_pekerjaan_wali`,`e`.`nm_pekerjaan` AS `FK__pekerjaan_wali`,`a`.`id_penghasilan_ayah` AS `id_penghasilan_ayah`,`f`.`nm_penghasilan` AS `FK__penghasilan_ayah`,`a`.`id_penghasilan_ibu` AS `id_penghasilan_ibu`,`f`.`nm_penghasilan` AS `FK__penghasilan_ibu`,`a`.`id_penghasilan_wali` AS `id_penghasilan_wali`,`f`.`nm_penghasilan` AS `FK__penghasilan_wali`,`a`.`id_sp` AS `id_sp`,`g`.`nm_lemb` AS `FK__sp`,`a`.`id_wil` AS `id_wil`,`h`.`nm_wil` AS `FK__wil`,`a`.`id_pd` AS `id_pd`,`a`.`nm_pd` AS `nm_pd`,`a`.`jk` AS `jk`,`a`.`nisn` AS `nisn`,`a`.`nik` AS `nik`,`a`.`tmpt_lahir` AS `tmpt_lahir`,`a`.`tgl_lahir` AS `tgl_lahir`,`a`.`jln` AS `jln`,`a`.`rt` AS `rt`,`a`.`rw` AS `rw`,`a`.`nm_dsn` AS `nm_dsn`,`a`.`ds_kel` AS `ds_kel`,`a`.`kode_pos` AS `kode_pos`,`a`.`id_jns_tinggal` AS `id_jns_tinggal`,`a`.`id_alat_transport` AS `id_alat_transport`,`a`.`telepon_rumah` AS `telepon_rumah`,`a`.`telepon_seluler` AS `telepon_seluler`,`a`.`email` AS `email`,`a`.`a_terima_kps` AS `a_terima_kps`,`a`.`no_kps` AS `no_kps`,`a`.`stat_pd` AS `stat_pd`,`a`.`nm_ayah` AS `nm_ayah`,`a`.`tgl_lahir_ayah` AS `tgl_lahir_ayah`,`a`.`nm_ibu_kandung` AS `nm_ibu_kandung`,`a`.`tgl_lahir_ibu` AS `tgl_lahir_ibu`,`a`.`nm_wali` AS `nm_wali`,`a`.`tgl_lahir_wali` AS `tgl_lahir_wali`,`a`.`kewarganegaraan` AS `kewarganegaraan`,`a`.`regpd_id_reg_pd` AS `regpd_id_reg_pd`,`a`.`regpd_id_sms` AS `regpd_id_sms`,`a`.`regpd_id_pd` AS `regpd_id_pd`,`a`.`regpd_id_sp` AS `regpd_id_sp`,`a`.`regpd_id_jns_daftar` AS `regpd_id_jns_daftar`,`a`.`regpd_nipd` AS `regpd_nipd`,`a`.`regpd_tgl_masuk_sp` AS `regpd_tgl_masuk_sp`,`a`.`regpd_id_jns_keluar` AS `regpd_id_jns_keluar`,`a`.`regpd_tgl_keluar` AS `regpd_tgl_keluar`,`a`.`regpd_ket` AS `regpd_ket`,`a`.`regpd_skhun` AS `regpd_skhun`,`a`.`regpd_a_pernah_paud` AS `regpd_a_pernah_paud`,`a`.`regpd_a_pernah_tk` AS `regpd_a_pernah_tk`,`a`.`regpd_mulai_smt` AS `regpd_mulai_smt`,`a`.`regpd_sks_diakui` AS `regpd_sks_diakui`,`a`.`regpd_jalur_skripsi` AS `regpd_jalur_skripsi`,`a`.`regpd_judul_skripsi` AS `regpd_judul_skripsi`,`a`.`regpd_bln_awal_bimbingan` AS `regpd_bln_awal_bimbingan`,`a`.`regpd_bln_akhir_bimbingan` AS `regpd_bln_akhir_bimbingan`,`a`.`regpd_sk_yudisium` AS `regpd_sk_yudisium`,`a`.`regpd_tgl_sk_yudisium` AS `regpd_tgl_sk_yudisium`,`a`.`regpd_ipk` AS `regpd_ipk`,`a`.`regpd_no_seri_ijazah` AS `regpd_no_seri_ijazah`,`a`.`regpd_sert_prof` AS `regpd_sert_prof`,`a`.`regpd_a_pindah_mhs_asing` AS `regpd_a_pindah_mhs_asing`,`a`.`regpd_nm_pt_asal` AS `regpd_nm_pt_asal`,`a`.`regpd_nm_prodi_asal` AS `regpd_nm_prodi_asal`,`a`.`last_update` AS `last_update`,`a`.`soft_delete` AS `soft_delete`,`a`.`last_sync` AS `last_sync`,`a`.`id_updater` AS `id_updater` from (((((((`mahasiswa` `a` left join `agama` `b` on((`a`.`id_agama` = `b`.`id_agama`))) left join `jenjang_pendidikan` `c` on(((`a`.`id_jenjang_pendidikan_ayah` = `c`.`id_jenj_didik`) and (`a`.`id_jenjang_pendidikan_ibu` = `c`.`id_jenj_didik`) and (`a`.`id_jenjang_pendidikan_wali` = `c`.`id_jenj_didik`)))) left join `kebutuhan_khusus` `d` on(((`a`.`id_kebutuhan_khusus_ayah` = `d`.`id_kk`) and (`a`.`id_kebutuhan_khusus_ibu` = `d`.`id_kk`) and (`a`.`id_kk` = `d`.`id_kk`)))) left join `pekerjaan` `e` on(((`a`.`id_pekerjaan_ayah` = `e`.`id_pekerjaan`) and (`a`.`id_pekerjaan_ibu` = `e`.`id_pekerjaan`) and (`a`.`id_pekerjaan_wali` = `e`.`id_pekerjaan`)))) left join `penghasilan` `f` on(((`a`.`id_penghasilan_ayah` = `f`.`id_penghasilan`) and (`a`.`id_penghasilan_ibu` = `f`.`id_penghasilan`)))) left join `satuan_pendidikan` `g` on((`a`.`id_sp` = `g`.`id_sp`))) left join `wilayah` `h` on((`a`.`id_wil` = `h`.`id_wil`)));

DROP TABLE IF EXISTS `mahasiswa_list_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mahasiswa_list_view` AS select `a`.`id_pd` AS `id_pd`,`b`.`id_reg_pd` AS `id_reg_pd`,`b`.`id_sms` AS `id_sms`,`a`.`nm_pd` AS `nm_pd`,lcase(`a`.`nm_pd`) AS `lower_nm_pd`,`b`.`nipd` AS `nipd`,`a`.`jk` AS `jk`,`c`.`nm_agama` AS `nm_agama`,`a`.`tgl_lahir` AS `tgl_lahir`,`d`.`nm_lemb` AS `nm_lemb`,`e`.`nm_stat_mhs` AS `nm_stat_mhs`,substr(`b`.`mulai_smt`,1,4) AS `mulai_smt`,`a`.`last_update` AS `last_update` from ((((`mahasiswa` `a` join `mahasiswa_pt` `b` on((`a`.`id_pd` = `b`.`id_pd`))) join `agama` `c` on((`a`.`id_agama` = `c`.`id_agama`))) join `sms` `d` on((`b`.`id_sms` = `d`.`id_sms`))) join `status_mahasiswa` `e` on((`a`.`stat_pd` = `e`.`id_stat_mhs`)));

DROP TABLE IF EXISTS `mahasiswa_suggest`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mahasiswa_suggest` AS select `a`.`id_pd` AS `id_pd`,`b`.`id_reg_pd` AS `id_reg_pd`,`a`.`nm_pd` AS `nm_pd`,`b`.`nipd` AS `nipd` from (`mahasiswa` `a` join `mahasiswa_pt` `b` on((`a`.`id_pd` = `b`.`id_pd`)));

DROP TABLE IF EXISTS `mahasiswa_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mahasiswa_view` AS select `mahasiswa`.`id_agama` AS `id_agama`,`agama`.`nm_agama` AS `FK__agama`,`mahasiswa`.`id_jenjang_pendidikan_ayah` AS `id_jenjang_pendidikan_ayah`,`jenjang_pendidikan`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_ayah`,`mahasiswa`.`id_jenjang_pendidikan_ibu` AS `id_jenjang_pendidikan_ibu`,`jenjang_pendidikan`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_ibu`,`mahasiswa`.`id_jenjang_pendidikan_wali` AS `id_jenjang_pendidikan_wali`,`jenjang_pendidikan`.`nm_jenj_didik` AS `FK__jenjang_pendidikan_wali`,`mahasiswa`.`id_kebutuhan_khusus_ayah` AS `id_kebutuhan_khusus_ayah`,`kebutuhan_khusus`.`a_kk_a` AS `FK__kebutuhan_khusus_ayah`,`mahasiswa`.`id_kebutuhan_khusus_ibu` AS `id_kebutuhan_khusus_ibu`,`kebutuhan_khusus`.`a_kk_a` AS `FK__kebutuhan_khusus_ibu`,`mahasiswa`.`id_kk` AS `id_kk`,`kebutuhan_khusus`.`a_kk_a` AS `FK__kk`,`mahasiswa`.`id_pekerjaan_ayah` AS `id_pekerjaan_ayah`,`pekerjaan`.`nm_pekerjaan` AS `FK__pekerjaan_ayah`,`mahasiswa`.`id_pekerjaan_ibu` AS `id_pekerjaan_ibu`,`pekerjaan`.`nm_pekerjaan` AS `FK__pekerjaan_ibu`,`mahasiswa`.`id_pekerjaan_wali` AS `id_pekerjaan_wali`,`pekerjaan`.`nm_pekerjaan` AS `FK__pekerjaan_wali`,`mahasiswa`.`id_penghasilan_ayah` AS `id_penghasilan_ayah`,`penghasilan`.`nm_penghasilan` AS `FK__penghasilan_ayah`,`mahasiswa`.`id_penghasilan_ibu` AS `id_penghasilan_ibu`,`penghasilan`.`nm_penghasilan` AS `FK__penghasilan_ibu`,`mahasiswa`.`id_penghasilan_wali` AS `id_penghasilan_wali`,`penghasilan`.`nm_penghasilan` AS `FK__penghasilan_wali`,`mahasiswa`.`id_sp` AS `id_sp`,`satuan_pendidikan`.`nm_lemb` AS `FK__sp`,`mahasiswa`.`id_wil` AS `id_wil`,`wilayah`.`nm_wil` AS `FK__wil`,`mahasiswa`.`id_pd` AS `id_pd`,`mahasiswa`.`nm_pd` AS `nm_pd`,`mahasiswa`.`jk` AS `jk`,`mahasiswa`.`nisn` AS `nisn`,`mahasiswa`.`nik` AS `nik`,`mahasiswa`.`tmpt_lahir` AS `tmpt_lahir`,`mahasiswa`.`tgl_lahir` AS `tgl_lahir`,`mahasiswa`.`jln` AS `jln`,`mahasiswa`.`rt` AS `rt`,`mahasiswa`.`rw` AS `rw`,`mahasiswa`.`nm_dsn` AS `nm_dsn`,`mahasiswa`.`ds_kel` AS `ds_kel`,`mahasiswa`.`kode_pos` AS `kode_pos`,`mahasiswa`.`id_jns_tinggal` AS `id_jns_tinggal`,`mahasiswa`.`id_alat_transport` AS `id_alat_transport`,`mahasiswa`.`telepon_rumah` AS `telepon_rumah`,`mahasiswa`.`telepon_seluler` AS `telepon_seluler`,`mahasiswa`.`email` AS `email`,`mahasiswa`.`a_terima_kps` AS `a_terima_kps`,`mahasiswa`.`no_kps` AS `no_kps`,`mahasiswa`.`stat_pd` AS `stat_pd`,`mahasiswa`.`nm_ayah` AS `nm_ayah`,`mahasiswa`.`tgl_lahir_ayah` AS `tgl_lahir_ayah`,`mahasiswa`.`nm_ibu_kandung` AS `nm_ibu_kandung`,`mahasiswa`.`tgl_lahir_ibu` AS `tgl_lahir_ibu`,`mahasiswa`.`nm_wali` AS `nm_wali`,`mahasiswa`.`tgl_lahir_wali` AS `tgl_lahir_wali`,`mahasiswa`.`kewarganegaraan` AS `kewarganegaraan`,`mahasiswa`.`regpd_id_reg_pd` AS `regpd_id_reg_pd`,`mahasiswa`.`regpd_id_sms` AS `regpd_id_sms`,`mahasiswa`.`regpd_id_pd` AS `regpd_id_pd`,`mahasiswa`.`regpd_id_sp` AS `regpd_id_sp`,`mahasiswa`.`regpd_id_jns_daftar` AS `regpd_id_jns_daftar`,`mahasiswa`.`regpd_nipd` AS `regpd_nipd`,`mahasiswa`.`regpd_tgl_masuk_sp` AS `regpd_tgl_masuk_sp`,`mahasiswa`.`regpd_id_jns_keluar` AS `regpd_id_jns_keluar`,`mahasiswa`.`regpd_tgl_keluar` AS `regpd_tgl_keluar`,`mahasiswa`.`regpd_ket` AS `regpd_ket`,`mahasiswa`.`regpd_skhun` AS `regpd_skhun`,`mahasiswa`.`regpd_a_pernah_paud` AS `regpd_a_pernah_paud`,`mahasiswa`.`regpd_a_pernah_tk` AS `regpd_a_pernah_tk`,`mahasiswa`.`regpd_mulai_smt` AS `regpd_mulai_smt`,`mahasiswa`.`regpd_sks_diakui` AS `regpd_sks_diakui`,`mahasiswa`.`regpd_jalur_skripsi` AS `regpd_jalur_skripsi`,`mahasiswa`.`regpd_judul_skripsi` AS `regpd_judul_skripsi`,`mahasiswa`.`regpd_bln_awal_bimbingan` AS `regpd_bln_awal_bimbingan`,`mahasiswa`.`regpd_bln_akhir_bimbingan` AS `regpd_bln_akhir_bimbingan`,`mahasiswa`.`regpd_sk_yudisium` AS `regpd_sk_yudisium`,`mahasiswa`.`regpd_tgl_sk_yudisium` AS `regpd_tgl_sk_yudisium`,`mahasiswa`.`regpd_ipk` AS `regpd_ipk`,`mahasiswa`.`regpd_no_seri_ijazah` AS `regpd_no_seri_ijazah`,`mahasiswa`.`regpd_sert_prof` AS `regpd_sert_prof`,`mahasiswa`.`regpd_a_pindah_mhs_asing` AS `regpd_a_pindah_mhs_asing`,`mahasiswa`.`regpd_nm_pt_asal` AS `regpd_nm_pt_asal`,`mahasiswa`.`regpd_nm_prodi_asal` AS `regpd_nm_prodi_asal`,`mahasiswa`.`last_update` AS `last_update`,`mahasiswa`.`soft_delete` AS `soft_delete`,`mahasiswa`.`last_sync` AS `last_sync`,`mahasiswa`.`id_updater` AS `id_updater` from (((((((`mahasiswa` left join `agama` on((`mahasiswa`.`id_agama` = `agama`.`id_agama`))) left join `jenjang_pendidikan` on(((`mahasiswa`.`id_jenjang_pendidikan_ayah` = `jenjang_pendidikan`.`id_jenj_didik`) and (`mahasiswa`.`id_jenjang_pendidikan_ibu` = `jenjang_pendidikan`.`id_jenj_didik`) and (`mahasiswa`.`id_jenjang_pendidikan_wali` = `jenjang_pendidikan`.`id_jenj_didik`)))) left join `kebutuhan_khusus` on(((`mahasiswa`.`id_kebutuhan_khusus_ayah` = `kebutuhan_khusus`.`id_kk`) and (`mahasiswa`.`id_kebutuhan_khusus_ibu` = `kebutuhan_khusus`.`id_kk`) and (`mahasiswa`.`id_kk` = `kebutuhan_khusus`.`id_kk`)))) left join `pekerjaan` on(((`mahasiswa`.`id_pekerjaan_ayah` = `pekerjaan`.`id_pekerjaan`) and (`mahasiswa`.`id_pekerjaan_ibu` = `pekerjaan`.`id_pekerjaan`) and (`mahasiswa`.`id_pekerjaan_wali` = `pekerjaan`.`id_pekerjaan`)))) left join `penghasilan` on(((`mahasiswa`.`id_penghasilan_ayah` = `penghasilan`.`id_penghasilan`) and (`mahasiswa`.`id_penghasilan_ibu` = `penghasilan`.`id_penghasilan`)))) left join `satuan_pendidikan` on((`mahasiswa`.`id_sp` = `satuan_pendidikan`.`id_sp`))) left join `wilayah` on((`mahasiswa`.`id_wil` = `wilayah`.`id_wil`)));

DROP TABLE IF EXISTS `mata_kuliah_kurikulum_smt`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mata_kuliah_kurikulum_smt` AS select `c`.`id_sms` AS `id_sms`,`c`.`id_smt_berlaku` AS `id_smt_berlaku`,`a`.`id_mk_kurikulum` AS `id_mk_kurikulum`,`a`.`id_kurikulum_sp` AS `id_kurikulum_sp`,`a`.`id_mk` AS `id_mk`,`c`.`nm_kurikulum_sp` AS `nm_kurikulum_sp`,`b`.`kode_mk` AS `kode_mk`,`b`.`nm_mk` AS `nm_mk`,`a`.`sks_mk` AS `sks_mk`,`a`.`sks_tm` AS `sks_tm`,`a`.`sks_prak` AS `sks_prak`,`a`.`sks_prak_lap` AS `sks_prak_lap`,`a`.`sks_sim` AS `sks_sim`,`a`.`smt` AS `smt`,`a`.`a_wajib` AS `a_wajib` from ((`mata_kuliah_kurikulum` `a` join `mata_kuliah` `b` on((`a`.`id_mk` = `b`.`id_mk`))) join `kurikulum` `c` on((`a`.`id_kurikulum_sp` = `c`.`id_kurikulum_sp`))) order by `a`.`smt`,`c`.`nm_kurikulum_sp`,`c`.`id_sms`;

DROP TABLE IF EXISTS `mata_kuliah_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mata_kuliah_view` AS select `b`.`nm_lemb` AS `nm_prodi`,`a`.`kode_mk` AS `kode_mk`,`a`.`nm_mk` AS `nm_mk`,ifnull(`c`.`nm_jns_mk`,'Belum diisi') AS `nm_jns_mk`,ifnull(`d`.`nm_kel_mk`,'Belum diisi') AS `nm_kel_mk`,`a`.`sks_mk` AS `sks_mk`,`a`.`a_bahan_ajar` AS `a_bahan_ajar`,`a`.`a_sap` AS `a_sap`,`a`.`a_silabus` AS `a_silabus`,`a`.`id_mk` AS `id_mk`,`a`.`id_sms` AS `id_sms`,`a`.`id_jenj_didik` AS `id_jenj_didik`,`a`.`jns_mk` AS `jns_mk`,`a`.`kel_mk` AS `kel_mk`,`a`.`sks_tm` AS `sks_tm`,`a`.`sks_prak` AS `sks_prak`,`a`.`sks_prak_lap` AS `sks_prak_lap`,`a`.`sks_sim` AS `sks_sim`,`a`.`metode_pelaksanaan_kuliah` AS `metode_pelaksanaan_kuliah`,`a`.`acara_prak` AS `acara_prak`,`a`.`a_diktat` AS `a_diktat`,`a`.`tgl_mulai_efektif` AS `tgl_mulai_efektif`,`a`.`tgl_akhir_efektif` AS `tgl_akhir_efektif` from ((((`mata_kuliah` `a` left join `sms` `b` on((`a`.`id_sms` = `b`.`id_sms`))) left join `jenis_matakuliah` `c` on((`a`.`jns_mk` = `c`.`id_jns_mk`))) left join `kel_matakuliah` `d` on((`a`.`kel_mk` = `d`.`id_kel_mk`))) left join `jenjang_pendidikan` `e` on((`a`.`id_jenj_didik` = `e`.`id_jenj_didik`)));

DROP TABLE IF EXISTS `nilai_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `nilai_view` AS select `a`.`id_kls` AS `id_kls`,`f`.`kode_mk` AS `kode_mk`,`f`.`nm_mk` AS `nm_mk`,`f`.`sks_mk` AS `sks_mk`,`e`.`id_smt` AS `id_smt`,`e`.`nm_kls` AS `nm_kls`,`b`.`nipd` AS `nipd`,`c`.`nm_pd` AS `nm_pd`,`b`.`mulai_smt` AS `mulai_smt`,`a`.`nilai_angka` AS `nilai_angka`,`a`.`nilai_huruf` AS `nilai_huruf`,`b`.`id_sms` AS `id_sms` from ((((`nilai` `a` join `mahasiswa_pt` `b` on((`a`.`id_reg_pd` = `b`.`id_reg_pd`))) join `mahasiswa` `c` on((`b`.`id_pd` = `c`.`id_pd`))) join `kelas_kuliah` `e` on((`a`.`id_kls` = `e`.`id_kls`))) join `mata_kuliah` `f` on((`e`.`id_mk` = `f`.`id_mk`))) where ((`a`.`id_kls` = '37e9d09c-a973-4131-8dbb-3f9fc0ac5f0d') and (`b`.`id_sms` = '83a0b8ad-8da3-4b38-b33e-7e849e801021')) group by `a`.`id_reg_pd` order by `b`.`nipd`;

DROP TABLE IF EXISTS `suggest_bobot_nilai`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `suggest_bobot_nilai` AS select `bobot_nilai`.`kode_bobot_nilai` AS `kode_bobot_nilai`,`bobot_nilai`.`id_sms` AS `id_sms`,`bobot_nilai`.`nilai_huruf` AS `nilai_huruf`,`bobot_nilai`.`nilai_indeks` AS `nilai_indeks`,`bobot_nilai`.`bobot_nilai_min` AS `bobot_nilai_min`,`bobot_nilai`.`bobot_nilai_maks` AS `bobot_nilai_maks`,`bobot_nilai`.`tgl_mulai_efektif` AS `tgl_mulai_efektif`,`bobot_nilai`.`tgl_akhir_efektif` AS `tgl_akhir_efektif`,concat(`bobot_nilai`.`nilai_huruf`,'#',`bobot_nilai`.`nilai_indeks`) AS `id`,concat(`bobot_nilai`.`nilai_huruf`,'(',`bobot_nilai`.`nilai_indeks`,')') AS `value` from `bobot_nilai`;

-- 2015-10-31 10:03:33
