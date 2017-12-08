alter table std_organizations change nameEn name_en varchar(256);
alter table std_organizations change nameZh name_zh varchar(256);

alter table organizationStdBrandLog rename organization_std_brand_log;
alter table organization_std_brand_log change userId user_id int ;
alter table organization_std_brand_log change stdBrandId std_brand_id int ;
alter table organization_std_brand_log change organizationId organization_id int ;
alter table organization_std_brand_log change createdAt created_at datetime ;
alter table organization_std_brand_log change updatedAt updated_at datetime ;

alter table organizationBrands rename  organization_brands ;
alter table organization_brands change stdBrandId std_brand_id int ;
alter table organization_brands change organizationId organization_id int ;
alter table organization_brands change createdBy created_by int ;
alter table organization_brands change updatedBy updated_by int ;
alter table organization_brands change createdAt created_at datetime ;
alter table organization_brands change updatedAt updated_at datetime ;

alter table brandStdBrands rename  brand_std_brands ;
alter table brand_std_brands change stdBrandId std_brand_id int ;
alter table brand_std_brands change brandId brand_id int ;
-- alter table brand_std_brands change createdBy created_by int ;
-- alter table brand_std_brands change updatedBy updated_by int ;
alter table brand_std_brands change createdAt created_at datetime ;
alter table brand_std_brands change updatedAt updated_at datetime ;

alter table brandStdBrandLog rename brand_std_brand_log;
alter table brand_std_brand_log change userId user_id int;
alter table brand_std_brand_log change stdBrandId std_brand_id int ;
alter table brand_std_brand_log change brandId brand_id int ;
alter table brand_std_brand_log change createdAt created_at datetime ;
alter table brand_std_brand_log change updatedAt updated_at datetime ;

alter table item_clean add spu_id int;

alter table brands change name_En name_en varchar(256);
alter table brands change name_Zh name_zh varchar(256);
alter table brands change createdBy created_by int;
alter table brands change updatedBy updated_by int;
alter table brands change createdAt created_at datetime ;
alter table brands change updatedAt updated_at datetime ;
alter table brands change deletedAt deleted_at datetime ;

alter table model_std_models drop column startAt;
alter table  model_std_models drop column  endAt;

alter table model_std_models change createdBy created_by int ;
alter table model_std_models change updatedBy updated_by int ;
alter table model_std_models change createdAt created_at datetime ;
alter table model_std_models change updatedAt updated_at datetime ;

CREATE TABLE model_std_models_log LIKE names_common_names_log;

alter table model_std_models_log change name_id model_id int ;
alter table model_std_models_log change common_name_id std_model_id int ;

CREATE TABLE organization_std_organizations LIKE names_common_names;
alter table organization_std_organizations change name_id organization_id int ;
alter table organization_std_organizations change common_name_id std_organization_id int ;

CREATE TABLE organization_std_organizations_log LIKE names_common_names_log;
alter table organization_std_organizations_log change name_id organization_id int ;
alter table organization_std_organizations_log change common_name_id std_organization_id int ;

alter table names_common_names add column deleted int not null default 0;
alter table names_common_names modify column method varchar(32);

alter table model_std_models modify column method varchar(32);

alter table brand_std_brands modify column method varchar(32);

alter table organizations change nameZh name_zh varchar(256) ;
alter table organizations change nameEn name_en varchar(256) ;
alter table organizations change isManufacturer is_manufacturer tinyint;
alter table organizations change isMaintenanceProvider is_maintenance_provider tinyint ;
alter table organizations change isDistributor is_distributor tinyint;
alter table organizations change isHospital is_hospital tinyint;
alter table organizations change isMedicalGroup is_medical_group tinyint ;
alter table organizations change isAuthority is_authority tinyint ;
alter table organizations change isAcademic is_academic tinyint ;
alter table organizations change createdBy created_by int ;
alter table organizations change updatedBy updated_by int ;
alter table organizations change approvedAt approved_at datetime ;
alter table organizations change createdAt created_at datetime ;
alter table organizations change updatedAt updated_at datetime ;
alter table organizations change deletedAt deleted_at datetime ;
alter table organizations modify column approved_at datetime;
alter table organizations modify column created_at datetime;
alter table organizations modify column updated_at datetime;
alter table organizations modify column deleted_at datetime;

alter table organization_std_organizations modify column method varchar(32);

alter table item_clean add column std_brand_id int;
alter table item_clean add column std_model_id int;
alter table item_clean add column std_manufacturer_id int;
alter table item_clean add column common_name_id int;

CREATE TABLE `foundation`.`global_op_logs` (
	`id` int NOT NULL AUTO_INCREMENT,
	`url` varchar(256),
	`params` varchar(1024),
	`user` int,
	`response_code` int,
	`response_content` blob,
	PRIMARY KEY (`id`)
) COMMENT='';


alter table spus change `version` configuration varchar(256);

alter table std_models add column identify varchar(128);


CREATE TABLE `std_departments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `departments_std_departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `std_department_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `votes` int(11) DEFAULT NULL,
  `voters` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `method` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `score` float DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizationBrands_brandId_organizationId_unique` (`std_department_id`,`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `departments_std_departments_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `std_department_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

create table cfda_items_amount(
	cfda_id int,
	items_amount int,
	created_at datetime,
	updated_at datetime
)

alter table brand_std_brands add column created_by int;
alter table brand_std_brands add column updated_by int;

alter table departments drop column createdBy;
alter table departments drop column updatedBy;
alter table departments change column updatedAt updated_at datetime;
alter table departments change column createdAt created_at datetime;
alter table departments change column deletedAt deteled_at datetime;

alter table departments change column organizationId organization_id int;
alter table departments change column parentId parent_id int;

alter table cfda_items_amount  modify column cfda_id bigint ;

alter table cfdas add column items_amount int ;


alter table cfda_clean change column registryAgent_id registry_agent_id int ;
alter table cfda_clean change column registryAgent_id registry_agent_id int ;

alter table cfda_clean add column std_brand_id int ;
alter table cfda_clean add column std_organization_id int ;
alter table cfda_clean add column common_name_id int ;

create table cfda_models
(
id int NOT NULL AUTO_INCREMENT,
cfda_id int NOT NULL ,
std_model_id int NOT NULL ,
created_at datetime default now(),
updated_at datetime default now(),
deleted_at datetime default now(),
 PRIMARY KEY ( id ),
index(cfda_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


alter table organization_std_organizations change deleted_at deleted int;

alter table brand_std_brands change deleted_at deleted int;

alter table model_std_models change deleted_at deleted int;

alter table  departments_std_departments change deleted_at deleted int;

alter table organization_brands change deleted_at deleted int;

create table cfda_tags like spu_tags;

alter table cfda_tags change spu_id cfda_id int;

alter table std_organizations add column prefix varchar(512);
alter table std_organizations add column suffix varchar(512);
alter table std_organizations add column core varchar(512);

alter table std_models add column prefix varchar(512);
alter table std_models add column suffix varchar(512);
alter table std_models add column core varchar(512);


alter table std_brands add column prefix varchar(512);
alter table std_brands add column suffix varchar(512);
alter table std_brands add column core varchar(512);


alter table std_departments add column prefix varchar(512);
alter table std_departments add column suffix varchar(512);
alter table std_departments add column core varchar(512);


alter table common_names add column prefix varchar(512);
alter table common_names add column suffix varchar(512);
alter table common_names add column core varchar(512);

create table std_organizations_std_brands like organization_brands;

alter table std_organizations_std_brands change organization_id std_organization_id int;

create table std_organizations_std_brands_log like organization_std_brand_log;

alter table std_organizations_std_brands_log change organization_id std_organization_id int;


create table parameter_keys(
id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
parent_id bigint(20) unsigned NOT NULL,
title varchar(512),
common_name_id int ,
unit VARCHAR(32),
value_type int,
type int,
PRIMARY key(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

create table parameter_key_tree_desc(
ancestor int not null ,
descendant int not null,
path_length int default 0,
created_at datetime default now(),
updated_at datetime default now(),
PRIMARY key (ancestor,descendant)
);

create table parameter_key_entities(
id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
common_name_id int ,
parameter_key_id int,
value varchar(1024),
PRIMARY key(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

alter table tag_attributes drop column common_name_id;
alter table tag_attribute_entities drop column common_name_id;

create table common_name_tags(
id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
common_name_id int ,
tag_id int,
created_at datetime default now(),
updated_at datetime default now(),
PRIMARY key(id)
)

alter table parameters add column type int ;

alter table common_names add column icon varchar(1024);
alter table common_names add column description varchar(1024);


drop table parameter_key_entities;
drop table parameter_keys;
alter table parameter_key_tree_desc rename parameter_tree_desc;

alter table parameters add column is_tag  int;
alter table parameters add column level int;
alter table parameters drop column common_name_id ;
alter table parameters add column common_name_id int;
alter table parameters add column parent_id bigint(20);

alter table parameters change type type varchar(32);


alter table common_name_tags rename common_name_parameters;
alter table common_name_parameters change tag_id parameter_id bigint(20) ;
alter table common_name_parameters add  column parameter_entity_id bigint(20) ;
alter table common_name_parameters add  column spu_id bigint(20) ;

alter table parameter_entities drop column parameter_id;


alter table parameters drop column level;
alter table parameters drop column parent_id;

alter  table common_name_parameters rename spu_parameters;


create table parameter_attributes(
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(512),
  parameter_id int ,
  unit VARCHAR(32),
  value_type int,
  type int,
  PRIMARY key(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

create table parameter_attribute_entities(
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  parameter_attribute_id int ,
  parameter_id int,
  value varchar(1024),
  PRIMARY key(id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

alter table parameter_attribute_entities change parameter_id spu_id int ;