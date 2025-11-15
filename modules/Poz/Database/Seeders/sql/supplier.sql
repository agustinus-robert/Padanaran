/*
 Navicat Premium Data Transfer

 Source Server         : Afadas
 Source Server Type    : PostgreSQL
 Source Server Version : 150013 (150013)
 Source Host           : 192.168.45.146:5432
 Source Catalog        : db_boarding2
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 150013 (150013)
 File Encoding         : 65001

 Date: 16/08/2025 11:15:03
*/


-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS "public"."supplier";
CREATE TABLE "public"."supplier" (
  "id" int8 NOT NULL DEFAULT nextval('supplier_id_seq'::regclass),
  "code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "email" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "phone" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "address" text COLLATE "pg_catalog"."default",
  "location" varchar(255) COLLATE "pg_catalog"."default",
  "image_name" varchar(255) COLLATE "pg_catalog"."default",
  "user_id" int8,
  "created_by" int8,
  "deleted_by" int8,
  "updated_by" int8,
  "deleted_at" timestamptz(0),
  "created_at" timestamptz(0),
  "updated_at" timestamptz(0)
)
;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO "public"."supplier" VALUES (1, '1265200562', 'Robert', 'robert@gmail.com', '0895393047418', 'Jalan Ibu pertiwi no 27, solo surakarta', NULL, NULL, 10000524, 10000014, NULL, 10000014, NULL, '2025-08-14 07:21:47+00', '2025-08-14 07:21:47+00');
INSERT INTO "public"."supplier" VALUES (2, '7206228911', 'Cahyo', 'cahyo', '089993456778', 'Jalan Ngemplak wonigiri, no 37', NULL, NULL, 10000525, 10000014, NULL, 10000014, NULL, '2025-08-14 07:22:25+00', '2025-08-14 07:22:25+00');
INSERT INTO "public"."supplier" VALUES (3, '8987505780', 'Adam Jaya', 'admJy@gmail.com', '081127764855', 'Jalan Purwodadi no 87, ngapeman', NULL, NULL, 10000526, 10000014, NULL, 10000014, NULL, '2025-08-14 07:22:59+00', '2025-08-14 07:22:59+00');
INSERT INTO "public"."supplier" VALUES (4, '8342963428', 'Jay', 'jays@gmail.com', '089223887899', 'Jalan godean, arah parangtritis 07 Yogyakarta', NULL, NULL, 10000527, 10000014, NULL, 10000014, NULL, '2025-08-14 07:23:58+00', '2025-08-14 07:23:58+00');
INSERT INTO "public"."supplier" VALUES (5, '9126288117', 'Agusta', 'agusta@gmail.com', '0882345889', 'agusta@gmail.com', NULL, NULL, 10000528, 10000014, NULL, 10000014, NULL, '2025-08-14 07:24:24+00', '2025-08-14 07:24:24+00');

-- ----------------------------
-- Primary Key structure for table supplier
-- ----------------------------
ALTER TABLE "public"."supplier" ADD CONSTRAINT "supplier_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table supplier
-- ----------------------------
ALTER TABLE "public"."supplier" ADD CONSTRAINT "supplier_created_by_foreign" FOREIGN KEY ("created_by") REFERENCES "public"."users" ("id") ON DELETE SET NULL ON UPDATE NO ACTION;
ALTER TABLE "public"."supplier" ADD CONSTRAINT "supplier_deleted_by_foreign" FOREIGN KEY ("deleted_by") REFERENCES "public"."users" ("id") ON DELETE SET NULL ON UPDATE NO ACTION;
ALTER TABLE "public"."supplier" ADD CONSTRAINT "supplier_updated_by_foreign" FOREIGN KEY ("updated_by") REFERENCES "public"."users" ("id") ON DELETE SET NULL ON UPDATE NO ACTION;
ALTER TABLE "public"."supplier" ADD CONSTRAINT "supplier_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE SET NULL ON UPDATE NO ACTION;
