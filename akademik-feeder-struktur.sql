/*
Navicat MySQL Data Transfer

Source Server         : localhost2
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : pddikti2

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-10-27 12:15:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for agama
-- ----------------------------
DROP TABLE IF EXISTS `agama`;
CREATE TABLE `agama` (
  `id_agama` smallint(6) NOT NULL,
  `nm_agama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_agama`),
  KEY `id_agama` (`id_agama`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ajar_dosen
-- ----------------------------
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
  CONSTRAINT `ajar_dosen_ibfk_3` FOREIGN KEY (`id_kls`) REFERENCES `kelas_kuliah` (`id_kls`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ajar_dosen_ibfk_5` FOREIGN KEY (`id_jns_eval`) REFERENCES `jenis_evaluasi` (`id_jns_eval`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for bentuk_pendidikan
-- ----------------------------
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

-- ----------------------------
-- Table structure for bobot_nilai
-- ----------------------------
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

-- ----------------------------
-- Table structure for cms_menu
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_options
-- ----------------------------
DROP TABLE IF EXISTS `cms_options`;
CREATE TABLE `cms_options` (
  `config_id` int(20) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(50) NOT NULL,
  `value` text,
  `description` text,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_role_menu_group
-- ----------------------------
DROP TABLE IF EXISTS `cms_role_menu_group`;
CREATE TABLE `cms_role_menu_group` (
  `role_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cms_user_group
-- ----------------------------
DROP TABLE IF EXISTS `cms_user_group`;
CREATE TABLE `cms_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) DEFAULT NULL,
  `group_description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cms_users
-- ----------------------------
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cms_validation
-- ----------------------------
DROP TABLE IF EXISTS `cms_validation`;
CREATE TABLE `cms_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `rules` varchar(100) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `filter` varchar(100) DEFAULT NULL,
  `a_active` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for daya_tampung
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_jabfung
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_kepangkatan
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_pendidikan
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_pt
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_sertifikasi
-- ----------------------------
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

-- ----------------------------
-- Table structure for dosen_struktural
-- ----------------------------
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

-- ----------------------------
-- Table structure for ikatan_kerja_dosen
-- ----------------------------
DROP TABLE IF EXISTS `ikatan_kerja_dosen`;
CREATE TABLE `ikatan_kerja_dosen` (
  `id_ikatan_kerja` varchar(1) NOT NULL,
  `nm_ikatan_kerja` varchar(50) DEFAULT NULL,
  `ket_ikatan_kerja` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_ikatan_kerja`),
  KEY `id_ikatan_kerja` (`id_ikatan_kerja`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jabfung
-- ----------------------------
DROP TABLE IF EXISTS `jabfung`;
CREATE TABLE `jabfung` (
  `id_jabfung` decimal(2,0) NOT NULL,
  `nm_jabfung` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_jabfung`),
  KEY `id_jabfung` (`id_jabfung`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jabstruk
-- ----------------------------
DROP TABLE IF EXISTS `jabstruk`;
CREATE TABLE `jabstruk` (
  `id_jabstruk` decimal(2,0) NOT NULL,
  `nm_jabstruk` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_jabstruk`),
  KEY `id_jabstruk` (`id_jabstruk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_evaluasi
-- ----------------------------
DROP TABLE IF EXISTS `jenis_evaluasi`;
CREATE TABLE `jenis_evaluasi` (
  `id_jns_eval` smallint(6) NOT NULL,
  `nm_jns_eval` varchar(25) DEFAULT NULL,
  `ket_jns_eval` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_jns_eval`),
  KEY `id_jns_eval` (`id_jns_eval`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_keluar
-- ----------------------------
DROP TABLE IF EXISTS `jenis_keluar`;
CREATE TABLE `jenis_keluar` (
  `id_jns_keluar` varchar(1) NOT NULL,
  `ket_keluar` varchar(40) DEFAULT NULL,
  `a_pd` decimal(1,0) DEFAULT NULL,
  `a_ptk` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jns_keluar`),
  KEY `id_jns_keluar` (`id_jns_keluar`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_matakuliah
-- ----------------------------
DROP TABLE IF EXISTS `jenis_matakuliah`;
CREATE TABLE `jenis_matakuliah` (
  `id_jns_mk` varchar(1) NOT NULL,
  `nm_jns_mk` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jns_mk`),
  KEY `id_jns_mk` (`id_jns_mk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_pendaftaran
-- ----------------------------
DROP TABLE IF EXISTS `jenis_pendaftaran`;
CREATE TABLE `jenis_pendaftaran` (
  `id_jns_daftar` decimal(1,0) NOT NULL,
  `nm_jns_daftar` varchar(20) DEFAULT NULL,
  `u_daftar_sekolah` decimal(1,0) DEFAULT NULL,
  `u_daftar_rombel` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jns_daftar`),
  KEY `id_jns_daftar` (`id_jns_daftar`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_sert
-- ----------------------------
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

-- ----------------------------
-- Table structure for jenis_sms
-- ----------------------------
DROP TABLE IF EXISTS `jenis_sms`;
CREATE TABLE `jenis_sms` (
  `id_jns_sms` decimal(2,0) NOT NULL,
  `nm_jns_sms` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_jns_sms`),
  KEY `id_jns_sms` (`id_jns_sms`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenis_subst
-- ----------------------------
DROP TABLE IF EXISTS `jenis_subst`;
CREATE TABLE `jenis_subst` (
  `id_jns_subst` varchar(5) NOT NULL,
  `nm_jns_subst` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jns_subst`),
  KEY `id_jns_subst` (`id_jns_subst`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jenjang_pendidikan
-- ----------------------------
DROP TABLE IF EXISTS `jenjang_pendidikan`;
CREATE TABLE `jenjang_pendidikan` (
  `id_jenj_didik` decimal(2,0) NOT NULL,
  `nm_jenj_didik` varchar(25) DEFAULT NULL,
  `u_jenj_lemb` decimal(1,0) DEFAULT NULL,
  `u_jenj_org` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_jenj_didik`),
  KEY `id_jenj_didik` (`id_jenj_didik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jurusan
-- ----------------------------
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

-- ----------------------------
-- Table structure for kebutuhan_khusus
-- ----------------------------
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

-- ----------------------------
-- Table structure for kel_matakuliah
-- ----------------------------
DROP TABLE IF EXISTS `kel_matakuliah`;
CREATE TABLE `kel_matakuliah` (
  `id_kel_mk` varchar(1) NOT NULL,
  `nm_kel_mk` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_kel_mk`),
  KEY `id_kel_mk` (`id_kel_mk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for kelas_kuliah
-- ----------------------------
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
  CONSTRAINT `kelas_kuliah_ibfk_3` FOREIGN KEY (`id_smt`) REFERENCES `semester` (`id_smt`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kelas_kuliah_ibfk_6` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id_mk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelas_kuliah_ibfk_7` FOREIGN KEY (`id_sms`) REFERENCES `sms` (`id_sms`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for krs
-- ----------------------------
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
) ENGINE=MyISAM AUTO_INCREMENT=11621 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for kuliah_mahasiswa
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=110683 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for kurikulum
-- ----------------------------
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

-- ----------------------------
-- Table structure for lembaga_pengangkat
-- ----------------------------
DROP TABLE IF EXISTS `lembaga_pengangkat`;
CREATE TABLE `lembaga_pengangkat` (
  `id_lemb_angkat` decimal(2,0) NOT NULL,
  `nm_lemb_angkat` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id_lemb_angkat`),
  KEY `id_lemb_angkat` (`id_lemb_angkat`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for level_wilayah
-- ----------------------------
DROP TABLE IF EXISTS `level_wilayah`;
CREATE TABLE `level_wilayah` (
  `id_level_wil` smallint(6) NOT NULL,
  `nm_level_wilayah` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_level_wil`),
  KEY `id_level_wil` (`id_level_wil`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mahasiswa
-- ----------------------------
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

-- ----------------------------
-- Table structure for mahasiswa_pt
-- ----------------------------
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

-- ----------------------------
-- Table structure for mata_kuliah
-- ----------------------------
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

-- ----------------------------
-- Table structure for mata_kuliah_kurikulum
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=847 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mhs
-- ----------------------------
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

-- ----------------------------
-- Table structure for negara
-- ----------------------------
DROP TABLE IF EXISTS `negara`;
CREATE TABLE `negara` (
  `id_negara` varchar(2) NOT NULL,
  `nm_negara` varchar(45) DEFAULT NULL,
  `a_ln` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`id_negara`),
  KEY `id_negara` (`id_negara`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for nilai
-- ----------------------------
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
  `kode_mk` varchar(255) DEFAULT NULL,
  `kelas` varchar(5) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`,`id_kls`,`id_reg_pd`)
) ENGINE=InnoDB AUTO_INCREMENT=2829 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for nilai_transfer
-- ----------------------------
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

-- ----------------------------
-- Table structure for pangkat_golongan
-- ----------------------------
DROP TABLE IF EXISTS `pangkat_golongan`;
CREATE TABLE `pangkat_golongan` (
  `id_pangkat_gol` decimal(2,0) NOT NULL,
  `kode_gol` varchar(5) DEFAULT NULL,
  `nm_pangkat` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_pangkat_gol`),
  KEY `id_pangkat_gol` (`id_pangkat_gol`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for pekerjaan
-- ----------------------------
DROP TABLE IF EXISTS `pekerjaan`;
CREATE TABLE `pekerjaan` (
  `id_pekerjaan` int(11) NOT NULL,
  `nm_pekerjaan` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_pekerjaan`),
  KEY `id_pekerjaan` (`id_pekerjaan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for penghasilan
-- ----------------------------
DROP TABLE IF EXISTS `penghasilan`;
CREATE TABLE `penghasilan` (
  `id_penghasilan` int(11) NOT NULL,
  `nm_penghasilan` varchar(40) DEFAULT NULL,
  `batas_bawah` int(11) DEFAULT NULL,
  `batas_atas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_penghasilan`),
  KEY `id_penghasilan` (`id_penghasilan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for satuan_pendidikan
-- ----------------------------
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

-- ----------------------------
-- Table structure for semester
-- ----------------------------
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

-- ----------------------------
-- Table structure for sms
-- ----------------------------
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

-- ----------------------------
-- Table structure for status_keaktifan_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `status_keaktifan_pegawai`;
CREATE TABLE `status_keaktifan_pegawai` (
  `id_stat_aktif` decimal(2,0) NOT NULL,
  `nm_stat_aktif` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_stat_aktif`),
  KEY `id_stat_aktif` (`id_stat_aktif`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for status_kepegawaian
-- ----------------------------
DROP TABLE IF EXISTS `status_kepegawaian`;
CREATE TABLE `status_kepegawaian` (
  `id_stat_pegawai` smallint(6) NOT NULL,
  `nm_stat_pegawai` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_stat_pegawai`),
  KEY `id_stat_pegawai` (`id_stat_pegawai`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for status_mahasiswa
-- ----------------------------
DROP TABLE IF EXISTS `status_mahasiswa`;
CREATE TABLE `status_mahasiswa` (
  `id_stat_mhs` varchar(1) NOT NULL,
  `nm_stat_mhs` varchar(50) DEFAULT NULL,
  `ket_stat_mhs` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_stat_mhs`),
  KEY `id_stat_mhs` (`id_stat_mhs`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for substansi_kuliah
-- ----------------------------
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

-- ----------------------------
-- Table structure for tahun_ajaran
-- ----------------------------
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

-- ----------------------------
-- Table structure for wilayah
-- ----------------------------
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
