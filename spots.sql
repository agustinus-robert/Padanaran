/*
 Navicat Premium Data Transfer

 Source Server         : locals
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : spots

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 29/08/2024 16:28:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for app_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `app_failed_jobs`;
CREATE TABLE `app_failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `app_failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for app_jobs
-- ----------------------------
DROP TABLE IF EXISTS `app_jobs`;
CREATE TABLE `app_jobs`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `app_jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for app_permissions
-- ----------------------------
DROP TABLE IF EXISTS `app_permissions`;
CREATE TABLE `app_permissions`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `app_permissions_key_unique`(`key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 108 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_permissions
-- ----------------------------
INSERT INTO `app_permissions` VALUES (1, 'write-configs', 'Write configs', 'App', 'Config', 'Allow user to write configs', 'web');
INSERT INTO `app_permissions` VALUES (2, 'read-posting', 'Read posting', 'App', 'Post', 'Allow user to read posting', 'web');
INSERT INTO `app_permissions` VALUES (3, 'write-posting', 'Write posting', 'App', 'Post', 'Allow user to write posting', 'web');
INSERT INTO `app_permissions` VALUES (4, 'delete-posting', 'Delete posting', 'App', 'Post', 'Allow user to delete posting', 'web');
INSERT INTO `app_permissions` VALUES (5, 'read-documents', 'Read documents', 'App', 'Document', 'Allow user to read documents', 'web');
INSERT INTO `app_permissions` VALUES (6, 'write-documents', 'Write documents', 'App', 'Document', 'Allow user to write documents', 'web');
INSERT INTO `app_permissions` VALUES (7, 'delete-documents', 'Delete documents', 'App', 'Document', 'Allow user to delete documents', 'web');
INSERT INTO `app_permissions` VALUES (8, 'read-document-signatures', 'Read document signatures', 'App', 'DocumentSignature', 'Allow user to read document signatures', 'web');
INSERT INTO `app_permissions` VALUES (9, 'write-document-signatures', 'Write document signatures', 'App', 'DocumentSignature', 'Allow user to write document signatures', 'web');
INSERT INTO `app_permissions` VALUES (10, 'delete-document-signatures', 'Delete document signatures', 'App', 'DocumentSignature', 'Allow user to delete document signatures', 'web');
INSERT INTO `app_permissions` VALUES (11, 'read-positions', 'Read positions', 'App', 'Position', 'Allow user to read positions', 'web');
INSERT INTO `app_permissions` VALUES (12, 'write-positions', 'Write positions', 'App', 'Position', 'Allow user to write positions', 'web');
INSERT INTO `app_permissions` VALUES (13, 'delete-positions', 'Delete positions', 'App', 'Position', 'Allow user to delete positions', 'web');
INSERT INTO `app_permissions` VALUES (14, 'read-contracts', 'Read contracts', 'App', 'Contract', 'Allow user to read contracts', 'web');
INSERT INTO `app_permissions` VALUES (15, 'write-contracts', 'Write contracts', 'App', 'Contract', 'Allow user to write contracts', 'web');
INSERT INTO `app_permissions` VALUES (16, 'delete-contracts', 'Delete contracts', 'App', 'Contract', 'Allow user to delete contracts', 'web');
INSERT INTO `app_permissions` VALUES (17, 'read-roles', 'Read roles', 'App', 'Role', 'Allow user to read roles', 'web');
INSERT INTO `app_permissions` VALUES (18, 'write-roles', 'Write roles', 'App', 'Role', 'Allow user to write roles', 'web');
INSERT INTO `app_permissions` VALUES (19, 'delete-roles', 'Delete roles', 'App', 'Role', 'Allow user to delete roles', 'web');
INSERT INTO `app_permissions` VALUES (20, 'read-users', 'Read users', 'Account', 'User', 'Allow user to read users', 'web');
INSERT INTO `app_permissions` VALUES (21, 'write-users', 'Write users', 'Account', 'User', 'Allow user to write users', 'web');
INSERT INTO `app_permissions` VALUES (22, 'delete-users', 'Delete users', 'Account', 'User', 'Allow user to delete users', 'web');
INSERT INTO `app_permissions` VALUES (23, 'cross-login-users', 'Cross-login users', 'Account', 'User', 'Allow user to cross-login users', 'web');
INSERT INTO `app_permissions` VALUES (24, 'read-user-logs', 'Read user logs', 'Account', 'UserLog', 'Allow user to read user logs', 'web');
INSERT INTO `app_permissions` VALUES (25, 'delete-user-logs', 'Delete user logs', 'Account', 'UserLog', 'Allow user to delete user logs', 'web');
INSERT INTO `app_permissions` VALUES (26, 'read-employees', 'Read employees', 'Account', 'Employee', 'Allow user to read employees', 'web');
INSERT INTO `app_permissions` VALUES (27, 'write-employees', 'Write employees', 'Account', 'Employee', 'Allow user to write employees', 'web');
INSERT INTO `app_permissions` VALUES (28, 'delete-employees', 'Delete employees', 'Account', 'Employee', 'Allow user to delete employees', 'web');
INSERT INTO `app_permissions` VALUES (29, 'read-employee-contracts', 'Read employee contracts', 'Account', 'EmployeeContract', 'Allow user to read employee contracts', 'web');
INSERT INTO `app_permissions` VALUES (30, 'write-employee-contracts', 'Write employee contracts', 'Account', 'EmployeeContract', 'Allow user to write employee contracts', 'web');
INSERT INTO `app_permissions` VALUES (31, 'delete-employee-contracts', 'Delete employee contracts', 'Account', 'EmployeeContract', 'Allow user to delete employee contracts', 'web');
INSERT INTO `app_permissions` VALUES (32, 'read-employee-positions', 'Read employee positions', 'Account', 'EmployeePosition', 'Allow user to read employee positions', 'web');
INSERT INTO `app_permissions` VALUES (33, 'write-employee-positions', 'Write employee positions', 'Account', 'EmployeePosition', 'Allow user to write employee positions', 'web');
INSERT INTO `app_permissions` VALUES (34, 'delete-employee-positions', 'Delete employee positions', 'Account', 'EmployeePosition', 'Allow user to delete employee positions', 'web');
INSERT INTO `app_permissions` VALUES (35, 'read-buildings', 'Read buildings', 'Admin', 'Building', 'Allow user to read buildings', 'web');
INSERT INTO `app_permissions` VALUES (36, 'write-buildings', 'Write buildings', 'Admin', 'Building', 'Allow user to write buildings', 'web');
INSERT INTO `app_permissions` VALUES (37, 'delete-buildings', 'Delete buildings', 'Admin', 'Building', 'Allow user to delete buildings', 'web');
INSERT INTO `app_permissions` VALUES (38, 'read-building-rooms', 'Read building rooms', 'Admin', 'BuildingRoom', 'Allow user to read building rooms', 'web');
INSERT INTO `app_permissions` VALUES (39, 'write-building-rooms', 'Write building rooms', 'Admin', 'BuildingRoom', 'Allow user to write building rooms', 'web');
INSERT INTO `app_permissions` VALUES (40, 'delete-building-rooms', 'Delete building rooms', 'Admin', 'BuildingRoom', 'Allow user to delete building rooms', 'web');
INSERT INTO `app_permissions` VALUES (41, 'read-categories', 'Read categories', 'Admin', 'Category', 'Allow user to read categories', 'web');
INSERT INTO `app_permissions` VALUES (42, 'write-categories', 'Write categories', 'Admin', 'Category', 'Allow user to write categories', 'web');
INSERT INTO `app_permissions` VALUES (43, 'delete-categories', 'Delete categories', 'Admin', 'Category', 'Allow user to delete categories', 'web');
INSERT INTO `app_permissions` VALUES (44, 'read-category-groups', 'Read category groups', 'Admin', 'CategoryGroup', 'Allow user to read category groups', 'web');
INSERT INTO `app_permissions` VALUES (45, 'write-category-groups', 'Write category groups', 'Admin', 'CategoryGroup', 'Allow user to write category groups', 'web');
INSERT INTO `app_permissions` VALUES (46, 'delete-category-groups', 'Delete category groups', 'Admin', 'CategoryGroup', 'Allow user to delete category groups', 'web');
INSERT INTO `app_permissions` VALUES (47, 'resume-category-groups', 'Resume category groups', 'Admin', 'CategoryGroup', 'Allow user to resume category groups', 'web');
INSERT INTO `app_permissions` VALUES (48, 'read-category-group-types', 'Read category group types', 'Admin', 'CategoryGroupType', 'Allow user to read category group types', 'web');
INSERT INTO `app_permissions` VALUES (49, 'write-category-group-types', 'Write category group types', 'Admin', 'CategoryGroupType', 'Allow user to write category group types', 'web');
INSERT INTO `app_permissions` VALUES (50, 'delete-category-group-types', 'Delete category group types', 'Admin', 'CategoryGroupType', 'Allow user to delete category group types', 'web');
INSERT INTO `app_permissions` VALUES (51, 'read-inventories', 'Read inventories', 'Admin', 'Inventory', 'Allow user to read inventories', 'web');
INSERT INTO `app_permissions` VALUES (52, 'write-inventories', 'Write inventories', 'Admin', 'Inventory', 'Allow user to write inventories', 'web');
INSERT INTO `app_permissions` VALUES (53, 'delete-inventories', 'Delete inventories', 'Admin', 'Inventory', 'Allow user to delete inventories', 'web');
INSERT INTO `app_permissions` VALUES (54, 'read-inventory-controls', 'Read inventory controls', 'Admin', 'InventoryControl', 'Allow user to read inventory controls', 'web');
INSERT INTO `app_permissions` VALUES (55, 'write-inventory-controls', 'Write inventory controls', 'Admin', 'InventoryControl', 'Allow user to write inventory controls', 'web');
INSERT INTO `app_permissions` VALUES (56, 'delete-inventory-controls', 'Delete inventory controls', 'Admin', 'InventoryControl', 'Allow user to delete inventory controls', 'web');
INSERT INTO `app_permissions` VALUES (57, 'read-inventory-control-items', 'Read inventory control items', 'Admin', 'InventoryControlItem', 'Allow user to read inventory control items', 'web');
INSERT INTO `app_permissions` VALUES (58, 'write-inventory-control-items', 'Write inventory control items', 'Admin', 'InventoryControlItem', 'Allow user to write inventory control items', 'web');
INSERT INTO `app_permissions` VALUES (59, 'delete-inventory-control-items', 'Delete inventory control items', 'Admin', 'InventoryControlItem', 'Allow user to delete inventory control items', 'web');
INSERT INTO `app_permissions` VALUES (60, 'read-inventory-logs', 'Read inventory logs', 'Admin', 'InventoryLog', 'Allow user to read inventory logs', 'web');
INSERT INTO `app_permissions` VALUES (61, 'write-inventory-logs', 'Write inventory logs', 'Admin', 'InventoryLog', 'Allow user to write inventory logs', 'web');
INSERT INTO `app_permissions` VALUES (62, 'delete-inventory-logs', 'Delete inventory logs', 'Admin', 'InventoryLog', 'Allow user to delete inventory logs', 'web');
INSERT INTO `app_permissions` VALUES (63, 'read-procurements', 'Read procurements', 'Admin', 'Procurement', 'Allow user to read procurements', 'web');
INSERT INTO `app_permissions` VALUES (64, 'write-procurements', 'Write procurements', 'Admin', 'Procurement', 'Allow user to write procurements', 'web');
INSERT INTO `app_permissions` VALUES (65, 'delete-procurements', 'Delete procurements', 'Admin', 'Procurement', 'Allow user to delete procurements', 'web');
INSERT INTO `app_permissions` VALUES (66, 'read-procurement-items', 'Read procurement items', 'Admin', 'ProcurementItem', 'Allow user to read procurement items', 'web');
INSERT INTO `app_permissions` VALUES (67, 'write-procurement-items', 'Write procurement items', 'Admin', 'ProcurementItem', 'Allow user to write procurement items', 'web');
INSERT INTO `app_permissions` VALUES (68, 'delete-procurement-items', 'Delete procurement items', 'Admin', 'ProcurementItem', 'Allow user to delete procurement items', 'web');
INSERT INTO `app_permissions` VALUES (69, 'read-suppliers', 'Read suppliers', 'Admin', 'Supplier', 'Allow user to read suppliers', 'web');
INSERT INTO `app_permissions` VALUES (70, 'write-suppliers', 'Write suppliers', 'Admin', 'Supplier', 'Allow user to write suppliers', 'web');
INSERT INTO `app_permissions` VALUES (71, 'delete-suppliers', 'Delete suppliers', 'Admin', 'Supplier', 'Allow user to delete suppliers', 'web');
INSERT INTO `app_permissions` VALUES (72, 'read-land', 'Read land', 'Admin', 'Land', 'Allow user to read land', 'web');
INSERT INTO `app_permissions` VALUES (73, 'write-land', 'Write land', 'Admin', 'Land', 'Allow user to write land', 'web');
INSERT INTO `app_permissions` VALUES (74, 'delete-land', 'Delete land', 'Admin', 'Land', 'Allow user to delete land', 'web');
INSERT INTO `app_permissions` VALUES (75, 'read-tool', 'Read Tool', 'Admin', 'Tool', 'Allow user to read tool', 'web');
INSERT INTO `app_permissions` VALUES (76, 'write-tool', 'Write Tool', 'Admin', 'Tool', 'Allow user to write tool', 'web');
INSERT INTO `app_permissions` VALUES (77, 'delete-tool', 'Delete Tool', 'Admin', 'Tool', 'Allow user to delete tool', 'web');
INSERT INTO `app_permissions` VALUES (78, 'read-vehcile', 'Read Vehcile', 'Admin', 'Vehcile', 'Allow user to read vehcile', 'web');
INSERT INTO `app_permissions` VALUES (79, 'write-vehcile', 'Write Vehcile', 'Admin', 'Vehcile', 'Allow user to write vehcile', 'web');
INSERT INTO `app_permissions` VALUES (80, 'delete-vehcile', 'Delete Vehcile', 'Admin', 'Vehcile', 'Allow user to delete vehcile', 'web');
INSERT INTO `app_permissions` VALUES (81, 'read-vehcile-sell', 'Read Vehcile Sell', 'Admin', 'VehcileSell', 'Allow user to read vehcile sell', 'web');
INSERT INTO `app_permissions` VALUES (82, 'write-vehcile-sell', 'Write Vehcile Sell', 'Admin', 'VehcileSell', 'Allow user to write vehcile sell', 'web');
INSERT INTO `app_permissions` VALUES (83, 'delete-vehcile-sell', 'Delete Vehcile Sell', 'Admin', 'VehcileSell', 'Allow user to delete vehcile sell', 'web');
INSERT INTO `app_permissions` VALUES (84, 'read-tool-sell', 'Read Tool Sell', 'Admin', 'ToolSell', 'Allow user to read tool sell', 'web');
INSERT INTO `app_permissions` VALUES (85, 'write-tool-sell', 'Write Tool Sell', 'Admin', 'ToolSell', 'Allow user to write tool sell', 'web');
INSERT INTO `app_permissions` VALUES (86, 'delete-tool-sell', 'Delete Tool Sell', 'Admin', 'ToolSell', 'Allow user to delete tool sell', 'web');
INSERT INTO `app_permissions` VALUES (87, 'read-buildings-lands-sell', 'Read Buildings Lands Sell', 'Admin', 'BuildingsLandsSell', 'Allow user to read buildings lands sell', 'web');
INSERT INTO `app_permissions` VALUES (88, 'write-buildings-lands-sell', 'Write Buildings Lands Sell', 'Admin', 'BuildingsLandsSell', 'Allow user to write buildings lands sell', 'web');
INSERT INTO `app_permissions` VALUES (89, 'delete-buildings-lands-sell', 'Delete Buildings Lands Sell', 'Admin', 'BuildingsLandsSell', 'Allow user to delete buildings lands sell', 'web');
INSERT INTO `app_permissions` VALUES (90, 'read-mutation-vehcile', 'Read Mutation Vehcile', 'Admin', 'MutationVehcile', 'Allow user to read mutation vehcile', 'web');
INSERT INTO `app_permissions` VALUES (91, 'write-mutation-vehcile', 'Write Mutation Vehcile', 'Admin', 'MutationVehcile', 'Allow user to write mutation vehcile', 'web');
INSERT INTO `app_permissions` VALUES (92, 'delete-mutation-vehcile', 'Delete Mutation Vehcile', 'Admin', 'MutationVehcile', 'Allow user to delete mutation vehcile', 'web');
INSERT INTO `app_permissions` VALUES (93, 'read-mutation-tool', 'Read Mutation Tool', 'Admin', 'MutationTool', 'Allow user to read mutation tool', 'web');
INSERT INTO `app_permissions` VALUES (94, 'write-mutation-tool', 'Write Mutation Tool', 'Admin', 'MutationTool', 'Allow user to write mutation tool', 'web');
INSERT INTO `app_permissions` VALUES (95, 'delete-mutation-tool', 'Delete Mutation Tool', 'Admin', 'MutationTool', 'Allow user to delete mutation tool', 'web');
INSERT INTO `app_permissions` VALUES (96, 'read-floor', 'Read Floor', 'Admin', 'Floor', 'Allow user to read floor', 'web');
INSERT INTO `app_permissions` VALUES (97, 'write-floor', 'Write Floor', 'Admin', 'Floor', 'Allow user to write floor', 'web');
INSERT INTO `app_permissions` VALUES (98, 'delete-floor', 'Delete Floor', 'Admin', 'Floor', 'Allow user to delete floor', 'web');
INSERT INTO `app_permissions` VALUES (99, 'read-room', 'Read Room', 'Admin', 'Room', 'Allow user to read room', 'web');
INSERT INTO `app_permissions` VALUES (100, 'write-room', 'Write Room', 'Admin', 'Room', 'Allow user to write room', 'web');
INSERT INTO `app_permissions` VALUES (101, 'delete-room', 'Delete Room', 'Admin', 'Room', 'Allow user to delete room', 'web');
INSERT INTO `app_permissions` VALUES (102, 'read-buildings-lands-floor', 'Read Buildings Floor', 'Admin', 'BuildingsLandsFloor', 'Allow user to read buildings floor', 'web');
INSERT INTO `app_permissions` VALUES (103, 'write-buildings-lands-floor', 'Write Buildings Floor', 'Admin', 'BuildingsLandsFloor', 'Allow user to write buildings floor', 'web');
INSERT INTO `app_permissions` VALUES (104, 'delete-buildings-lands-floor', 'Delete Buildings Floor', 'Admin', 'BuildingsLandsFloor', 'Allow user to delete buildings floor', 'web');
INSERT INTO `app_permissions` VALUES (105, 'read-transaction-floor-room', 'Read Buildings Room', 'Admin', 'TransactionFloorRoom', 'Allow user to read buildings room', 'web');
INSERT INTO `app_permissions` VALUES (106, 'write-transaction-floor-room', 'Write Lands Room', 'Admin', 'TransactionFloorRoom', 'Allow user to write buildings room', 'web');
INSERT INTO `app_permissions` VALUES (107, 'delete-transaction-floor-room', 'Delete Buildings Room', 'Admin', 'TransactionFloorRoom', 'Allow user to delete buildings room', 'web');

-- ----------------------------
-- Table structure for app_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `app_role_permissions`;
CREATE TABLE `app_role_permissions`  (
  `role_id` smallint UNSIGNED NOT NULL,
  `permission_id` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE,
  INDEX `app_role_permissions_permission_id_foreign`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `app_role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `app_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `app_role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `app_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_role_permissions
-- ----------------------------
INSERT INTO `app_role_permissions` VALUES (1, 1);
INSERT INTO `app_role_permissions` VALUES (2, 1);
INSERT INTO `app_role_permissions` VALUES (1, 2);
INSERT INTO `app_role_permissions` VALUES (2, 2);
INSERT INTO `app_role_permissions` VALUES (1, 3);
INSERT INTO `app_role_permissions` VALUES (2, 3);
INSERT INTO `app_role_permissions` VALUES (1, 4);
INSERT INTO `app_role_permissions` VALUES (2, 4);
INSERT INTO `app_role_permissions` VALUES (1, 5);
INSERT INTO `app_role_permissions` VALUES (2, 5);
INSERT INTO `app_role_permissions` VALUES (1, 6);
INSERT INTO `app_role_permissions` VALUES (2, 6);
INSERT INTO `app_role_permissions` VALUES (1, 7);
INSERT INTO `app_role_permissions` VALUES (2, 7);
INSERT INTO `app_role_permissions` VALUES (1, 8);
INSERT INTO `app_role_permissions` VALUES (2, 8);
INSERT INTO `app_role_permissions` VALUES (1, 9);
INSERT INTO `app_role_permissions` VALUES (2, 9);
INSERT INTO `app_role_permissions` VALUES (1, 10);
INSERT INTO `app_role_permissions` VALUES (2, 10);
INSERT INTO `app_role_permissions` VALUES (1, 11);
INSERT INTO `app_role_permissions` VALUES (2, 11);
INSERT INTO `app_role_permissions` VALUES (1, 12);
INSERT INTO `app_role_permissions` VALUES (2, 12);
INSERT INTO `app_role_permissions` VALUES (1, 13);
INSERT INTO `app_role_permissions` VALUES (2, 13);
INSERT INTO `app_role_permissions` VALUES (1, 14);
INSERT INTO `app_role_permissions` VALUES (2, 14);
INSERT INTO `app_role_permissions` VALUES (1, 15);
INSERT INTO `app_role_permissions` VALUES (2, 15);
INSERT INTO `app_role_permissions` VALUES (1, 16);
INSERT INTO `app_role_permissions` VALUES (2, 16);
INSERT INTO `app_role_permissions` VALUES (1, 17);
INSERT INTO `app_role_permissions` VALUES (2, 17);
INSERT INTO `app_role_permissions` VALUES (1, 18);
INSERT INTO `app_role_permissions` VALUES (2, 18);
INSERT INTO `app_role_permissions` VALUES (1, 19);
INSERT INTO `app_role_permissions` VALUES (2, 19);
INSERT INTO `app_role_permissions` VALUES (1, 20);
INSERT INTO `app_role_permissions` VALUES (2, 20);
INSERT INTO `app_role_permissions` VALUES (3, 20);
INSERT INTO `app_role_permissions` VALUES (1, 21);
INSERT INTO `app_role_permissions` VALUES (2, 21);
INSERT INTO `app_role_permissions` VALUES (3, 21);
INSERT INTO `app_role_permissions` VALUES (1, 22);
INSERT INTO `app_role_permissions` VALUES (2, 22);
INSERT INTO `app_role_permissions` VALUES (3, 22);
INSERT INTO `app_role_permissions` VALUES (1, 23);
INSERT INTO `app_role_permissions` VALUES (3, 23);
INSERT INTO `app_role_permissions` VALUES (1, 24);
INSERT INTO `app_role_permissions` VALUES (2, 24);
INSERT INTO `app_role_permissions` VALUES (3, 24);
INSERT INTO `app_role_permissions` VALUES (1, 25);
INSERT INTO `app_role_permissions` VALUES (2, 25);
INSERT INTO `app_role_permissions` VALUES (3, 25);
INSERT INTO `app_role_permissions` VALUES (1, 26);
INSERT INTO `app_role_permissions` VALUES (2, 26);
INSERT INTO `app_role_permissions` VALUES (3, 26);
INSERT INTO `app_role_permissions` VALUES (1, 27);
INSERT INTO `app_role_permissions` VALUES (2, 27);
INSERT INTO `app_role_permissions` VALUES (3, 27);
INSERT INTO `app_role_permissions` VALUES (1, 28);
INSERT INTO `app_role_permissions` VALUES (2, 28);
INSERT INTO `app_role_permissions` VALUES (3, 28);
INSERT INTO `app_role_permissions` VALUES (1, 29);
INSERT INTO `app_role_permissions` VALUES (2, 29);
INSERT INTO `app_role_permissions` VALUES (3, 29);
INSERT INTO `app_role_permissions` VALUES (1, 30);
INSERT INTO `app_role_permissions` VALUES (2, 30);
INSERT INTO `app_role_permissions` VALUES (3, 30);
INSERT INTO `app_role_permissions` VALUES (1, 31);
INSERT INTO `app_role_permissions` VALUES (2, 31);
INSERT INTO `app_role_permissions` VALUES (3, 31);
INSERT INTO `app_role_permissions` VALUES (1, 32);
INSERT INTO `app_role_permissions` VALUES (2, 32);
INSERT INTO `app_role_permissions` VALUES (3, 32);
INSERT INTO `app_role_permissions` VALUES (1, 33);
INSERT INTO `app_role_permissions` VALUES (2, 33);
INSERT INTO `app_role_permissions` VALUES (3, 33);
INSERT INTO `app_role_permissions` VALUES (1, 34);
INSERT INTO `app_role_permissions` VALUES (2, 34);
INSERT INTO `app_role_permissions` VALUES (3, 34);
INSERT INTO `app_role_permissions` VALUES (1, 35);
INSERT INTO `app_role_permissions` VALUES (2, 35);
INSERT INTO `app_role_permissions` VALUES (3, 35);
INSERT INTO `app_role_permissions` VALUES (1, 36);
INSERT INTO `app_role_permissions` VALUES (2, 36);
INSERT INTO `app_role_permissions` VALUES (3, 36);
INSERT INTO `app_role_permissions` VALUES (1, 37);
INSERT INTO `app_role_permissions` VALUES (2, 37);
INSERT INTO `app_role_permissions` VALUES (3, 37);
INSERT INTO `app_role_permissions` VALUES (1, 38);
INSERT INTO `app_role_permissions` VALUES (2, 38);
INSERT INTO `app_role_permissions` VALUES (3, 38);
INSERT INTO `app_role_permissions` VALUES (1, 39);
INSERT INTO `app_role_permissions` VALUES (2, 39);
INSERT INTO `app_role_permissions` VALUES (3, 39);
INSERT INTO `app_role_permissions` VALUES (1, 40);
INSERT INTO `app_role_permissions` VALUES (2, 40);
INSERT INTO `app_role_permissions` VALUES (3, 40);
INSERT INTO `app_role_permissions` VALUES (1, 41);
INSERT INTO `app_role_permissions` VALUES (2, 41);
INSERT INTO `app_role_permissions` VALUES (3, 41);
INSERT INTO `app_role_permissions` VALUES (1, 42);
INSERT INTO `app_role_permissions` VALUES (2, 42);
INSERT INTO `app_role_permissions` VALUES (3, 42);
INSERT INTO `app_role_permissions` VALUES (1, 43);
INSERT INTO `app_role_permissions` VALUES (2, 43);
INSERT INTO `app_role_permissions` VALUES (3, 43);
INSERT INTO `app_role_permissions` VALUES (1, 44);
INSERT INTO `app_role_permissions` VALUES (2, 44);
INSERT INTO `app_role_permissions` VALUES (3, 44);
INSERT INTO `app_role_permissions` VALUES (1, 45);
INSERT INTO `app_role_permissions` VALUES (2, 45);
INSERT INTO `app_role_permissions` VALUES (3, 45);
INSERT INTO `app_role_permissions` VALUES (1, 46);
INSERT INTO `app_role_permissions` VALUES (2, 46);
INSERT INTO `app_role_permissions` VALUES (3, 46);
INSERT INTO `app_role_permissions` VALUES (1, 47);
INSERT INTO `app_role_permissions` VALUES (2, 47);
INSERT INTO `app_role_permissions` VALUES (3, 47);
INSERT INTO `app_role_permissions` VALUES (1, 48);
INSERT INTO `app_role_permissions` VALUES (2, 48);
INSERT INTO `app_role_permissions` VALUES (3, 48);
INSERT INTO `app_role_permissions` VALUES (1, 49);
INSERT INTO `app_role_permissions` VALUES (2, 49);
INSERT INTO `app_role_permissions` VALUES (3, 49);
INSERT INTO `app_role_permissions` VALUES (1, 50);
INSERT INTO `app_role_permissions` VALUES (2, 50);
INSERT INTO `app_role_permissions` VALUES (3, 50);
INSERT INTO `app_role_permissions` VALUES (1, 51);
INSERT INTO `app_role_permissions` VALUES (2, 51);
INSERT INTO `app_role_permissions` VALUES (3, 51);
INSERT INTO `app_role_permissions` VALUES (1, 52);
INSERT INTO `app_role_permissions` VALUES (2, 52);
INSERT INTO `app_role_permissions` VALUES (3, 52);
INSERT INTO `app_role_permissions` VALUES (1, 53);
INSERT INTO `app_role_permissions` VALUES (2, 53);
INSERT INTO `app_role_permissions` VALUES (3, 53);
INSERT INTO `app_role_permissions` VALUES (1, 54);
INSERT INTO `app_role_permissions` VALUES (2, 54);
INSERT INTO `app_role_permissions` VALUES (3, 54);
INSERT INTO `app_role_permissions` VALUES (1, 55);
INSERT INTO `app_role_permissions` VALUES (2, 55);
INSERT INTO `app_role_permissions` VALUES (3, 55);
INSERT INTO `app_role_permissions` VALUES (1, 56);
INSERT INTO `app_role_permissions` VALUES (2, 56);
INSERT INTO `app_role_permissions` VALUES (3, 56);
INSERT INTO `app_role_permissions` VALUES (1, 57);
INSERT INTO `app_role_permissions` VALUES (2, 57);
INSERT INTO `app_role_permissions` VALUES (3, 57);
INSERT INTO `app_role_permissions` VALUES (1, 58);
INSERT INTO `app_role_permissions` VALUES (2, 58);
INSERT INTO `app_role_permissions` VALUES (3, 58);
INSERT INTO `app_role_permissions` VALUES (1, 59);
INSERT INTO `app_role_permissions` VALUES (2, 59);
INSERT INTO `app_role_permissions` VALUES (3, 59);
INSERT INTO `app_role_permissions` VALUES (1, 60);
INSERT INTO `app_role_permissions` VALUES (2, 60);
INSERT INTO `app_role_permissions` VALUES (3, 60);
INSERT INTO `app_role_permissions` VALUES (1, 61);
INSERT INTO `app_role_permissions` VALUES (2, 61);
INSERT INTO `app_role_permissions` VALUES (3, 61);
INSERT INTO `app_role_permissions` VALUES (1, 62);
INSERT INTO `app_role_permissions` VALUES (2, 62);
INSERT INTO `app_role_permissions` VALUES (3, 62);
INSERT INTO `app_role_permissions` VALUES (1, 63);
INSERT INTO `app_role_permissions` VALUES (2, 63);
INSERT INTO `app_role_permissions` VALUES (3, 63);
INSERT INTO `app_role_permissions` VALUES (1, 64);
INSERT INTO `app_role_permissions` VALUES (2, 64);
INSERT INTO `app_role_permissions` VALUES (3, 64);
INSERT INTO `app_role_permissions` VALUES (1, 65);
INSERT INTO `app_role_permissions` VALUES (2, 65);
INSERT INTO `app_role_permissions` VALUES (3, 65);
INSERT INTO `app_role_permissions` VALUES (1, 66);
INSERT INTO `app_role_permissions` VALUES (2, 66);
INSERT INTO `app_role_permissions` VALUES (3, 66);
INSERT INTO `app_role_permissions` VALUES (1, 67);
INSERT INTO `app_role_permissions` VALUES (2, 67);
INSERT INTO `app_role_permissions` VALUES (3, 67);
INSERT INTO `app_role_permissions` VALUES (1, 68);
INSERT INTO `app_role_permissions` VALUES (2, 68);
INSERT INTO `app_role_permissions` VALUES (3, 68);
INSERT INTO `app_role_permissions` VALUES (1, 69);
INSERT INTO `app_role_permissions` VALUES (2, 69);
INSERT INTO `app_role_permissions` VALUES (3, 69);
INSERT INTO `app_role_permissions` VALUES (1, 70);
INSERT INTO `app_role_permissions` VALUES (2, 70);
INSERT INTO `app_role_permissions` VALUES (3, 70);
INSERT INTO `app_role_permissions` VALUES (1, 71);
INSERT INTO `app_role_permissions` VALUES (2, 71);
INSERT INTO `app_role_permissions` VALUES (3, 71);
INSERT INTO `app_role_permissions` VALUES (1, 72);
INSERT INTO `app_role_permissions` VALUES (2, 72);
INSERT INTO `app_role_permissions` VALUES (3, 72);
INSERT INTO `app_role_permissions` VALUES (1, 73);
INSERT INTO `app_role_permissions` VALUES (2, 73);
INSERT INTO `app_role_permissions` VALUES (3, 73);
INSERT INTO `app_role_permissions` VALUES (1, 74);
INSERT INTO `app_role_permissions` VALUES (2, 74);
INSERT INTO `app_role_permissions` VALUES (3, 74);
INSERT INTO `app_role_permissions` VALUES (1, 75);
INSERT INTO `app_role_permissions` VALUES (2, 75);
INSERT INTO `app_role_permissions` VALUES (3, 75);
INSERT INTO `app_role_permissions` VALUES (1, 76);
INSERT INTO `app_role_permissions` VALUES (2, 76);
INSERT INTO `app_role_permissions` VALUES (3, 76);
INSERT INTO `app_role_permissions` VALUES (1, 77);
INSERT INTO `app_role_permissions` VALUES (2, 77);
INSERT INTO `app_role_permissions` VALUES (3, 77);
INSERT INTO `app_role_permissions` VALUES (1, 78);
INSERT INTO `app_role_permissions` VALUES (2, 78);
INSERT INTO `app_role_permissions` VALUES (3, 78);
INSERT INTO `app_role_permissions` VALUES (1, 79);
INSERT INTO `app_role_permissions` VALUES (2, 79);
INSERT INTO `app_role_permissions` VALUES (3, 79);
INSERT INTO `app_role_permissions` VALUES (1, 80);
INSERT INTO `app_role_permissions` VALUES (2, 80);
INSERT INTO `app_role_permissions` VALUES (3, 80);
INSERT INTO `app_role_permissions` VALUES (1, 81);
INSERT INTO `app_role_permissions` VALUES (2, 81);
INSERT INTO `app_role_permissions` VALUES (3, 81);
INSERT INTO `app_role_permissions` VALUES (1, 82);
INSERT INTO `app_role_permissions` VALUES (2, 82);
INSERT INTO `app_role_permissions` VALUES (3, 82);
INSERT INTO `app_role_permissions` VALUES (1, 83);
INSERT INTO `app_role_permissions` VALUES (2, 83);
INSERT INTO `app_role_permissions` VALUES (3, 83);
INSERT INTO `app_role_permissions` VALUES (1, 84);
INSERT INTO `app_role_permissions` VALUES (2, 84);
INSERT INTO `app_role_permissions` VALUES (3, 84);
INSERT INTO `app_role_permissions` VALUES (1, 85);
INSERT INTO `app_role_permissions` VALUES (2, 85);
INSERT INTO `app_role_permissions` VALUES (3, 85);
INSERT INTO `app_role_permissions` VALUES (1, 86);
INSERT INTO `app_role_permissions` VALUES (2, 86);
INSERT INTO `app_role_permissions` VALUES (3, 86);
INSERT INTO `app_role_permissions` VALUES (1, 87);
INSERT INTO `app_role_permissions` VALUES (2, 87);
INSERT INTO `app_role_permissions` VALUES (3, 87);
INSERT INTO `app_role_permissions` VALUES (5, 87);
INSERT INTO `app_role_permissions` VALUES (1, 88);
INSERT INTO `app_role_permissions` VALUES (2, 88);
INSERT INTO `app_role_permissions` VALUES (3, 88);
INSERT INTO `app_role_permissions` VALUES (5, 88);
INSERT INTO `app_role_permissions` VALUES (1, 89);
INSERT INTO `app_role_permissions` VALUES (2, 89);
INSERT INTO `app_role_permissions` VALUES (3, 89);
INSERT INTO `app_role_permissions` VALUES (5, 89);
INSERT INTO `app_role_permissions` VALUES (1, 90);
INSERT INTO `app_role_permissions` VALUES (2, 90);
INSERT INTO `app_role_permissions` VALUES (3, 90);
INSERT INTO `app_role_permissions` VALUES (5, 90);
INSERT INTO `app_role_permissions` VALUES (1, 91);
INSERT INTO `app_role_permissions` VALUES (2, 91);
INSERT INTO `app_role_permissions` VALUES (3, 91);
INSERT INTO `app_role_permissions` VALUES (5, 91);
INSERT INTO `app_role_permissions` VALUES (1, 92);
INSERT INTO `app_role_permissions` VALUES (2, 92);
INSERT INTO `app_role_permissions` VALUES (3, 92);
INSERT INTO `app_role_permissions` VALUES (5, 92);
INSERT INTO `app_role_permissions` VALUES (1, 93);
INSERT INTO `app_role_permissions` VALUES (2, 93);
INSERT INTO `app_role_permissions` VALUES (3, 93);
INSERT INTO `app_role_permissions` VALUES (5, 93);
INSERT INTO `app_role_permissions` VALUES (1, 94);
INSERT INTO `app_role_permissions` VALUES (2, 94);
INSERT INTO `app_role_permissions` VALUES (3, 94);
INSERT INTO `app_role_permissions` VALUES (5, 94);
INSERT INTO `app_role_permissions` VALUES (1, 95);
INSERT INTO `app_role_permissions` VALUES (2, 95);
INSERT INTO `app_role_permissions` VALUES (3, 95);
INSERT INTO `app_role_permissions` VALUES (5, 95);
INSERT INTO `app_role_permissions` VALUES (1, 96);
INSERT INTO `app_role_permissions` VALUES (2, 96);
INSERT INTO `app_role_permissions` VALUES (3, 96);
INSERT INTO `app_role_permissions` VALUES (1, 97);
INSERT INTO `app_role_permissions` VALUES (2, 97);
INSERT INTO `app_role_permissions` VALUES (3, 97);
INSERT INTO `app_role_permissions` VALUES (1, 98);
INSERT INTO `app_role_permissions` VALUES (2, 98);
INSERT INTO `app_role_permissions` VALUES (3, 98);
INSERT INTO `app_role_permissions` VALUES (1, 99);
INSERT INTO `app_role_permissions` VALUES (2, 99);
INSERT INTO `app_role_permissions` VALUES (3, 99);
INSERT INTO `app_role_permissions` VALUES (1, 100);
INSERT INTO `app_role_permissions` VALUES (2, 100);
INSERT INTO `app_role_permissions` VALUES (3, 100);
INSERT INTO `app_role_permissions` VALUES (1, 101);
INSERT INTO `app_role_permissions` VALUES (2, 101);
INSERT INTO `app_role_permissions` VALUES (3, 101);
INSERT INTO `app_role_permissions` VALUES (1, 102);
INSERT INTO `app_role_permissions` VALUES (2, 102);
INSERT INTO `app_role_permissions` VALUES (3, 102);
INSERT INTO `app_role_permissions` VALUES (1, 103);
INSERT INTO `app_role_permissions` VALUES (2, 103);
INSERT INTO `app_role_permissions` VALUES (3, 103);
INSERT INTO `app_role_permissions` VALUES (1, 104);
INSERT INTO `app_role_permissions` VALUES (2, 104);
INSERT INTO `app_role_permissions` VALUES (3, 104);
INSERT INTO `app_role_permissions` VALUES (1, 105);
INSERT INTO `app_role_permissions` VALUES (2, 105);
INSERT INTO `app_role_permissions` VALUES (3, 105);
INSERT INTO `app_role_permissions` VALUES (1, 106);
INSERT INTO `app_role_permissions` VALUES (2, 106);
INSERT INTO `app_role_permissions` VALUES (3, 106);
INSERT INTO `app_role_permissions` VALUES (1, 107);
INSERT INTO `app_role_permissions` VALUES (2, 107);
INSERT INTO `app_role_permissions` VALUES (3, 107);

-- ----------------------------
-- Table structure for app_roles
-- ----------------------------
DROP TABLE IF EXISTS `app_roles`;
CREATE TABLE `app_roles`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `app_roles_kd_unique`(`kd` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_roles
-- ----------------------------
INSERT INTO `app_roles` VALUES (1, 'root', 'Super Administrator', NULL, 'web', NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_roles` VALUES (2, 'director', 'Direktur', NULL, 'web', NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_roles` VALUES (3, 'manager', 'Manajer', NULL, 'web', NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_roles` VALUES (5, 'client', 'Client', NULL, 'web', NULL, '2024-07-10 08:06:56', '2024-07-10 08:06:56');

-- ----------------------------
-- Table structure for app_settings
-- ----------------------------
DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE `app_settings`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `app_settings_key_unique`(`key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_settings
-- ----------------------------
INSERT INTO `app_settings` VALUES (1, 'app_short_name', 'EMBARK', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (2, 'app_name', 'Empower Aset Management', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (3, 'app_long_name', 'Evaluasi Penilaian Kinerja (EMBARK)', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (4, 'meta_author', 'pemad', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (5, 'meta_keywords', 'website, laravel, application, app, apps', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (6, 'meta_image', '/img/logo/logo-icon-sq-512.png', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `app_settings` VALUES (7, 'meta_description', 'http://localhost', 'string', '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for bed
-- ----------------------------
DROP TABLE IF EXISTS `bed`;
CREATE TABLE `bed`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `capacity` int NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bed
-- ----------------------------
INSERT INTO `bed` VALUES (1, 350000.00, 'Deluxe Twin Bed', 'Kamar Fasilitas lengkap dan luas', 5, 'https://dummyimage.com/300', NULL, NULL, NULL);
INSERT INTO `bed` VALUES (2, 150000.00, 'Suite Twin Bed', 'Kamar Fasilitas lengkap dan nyaman', 2, 'https://dummyimage.com/300', NULL, NULL, NULL);
INSERT INTO `bed` VALUES (3, 800000.00, 'Luxury Twin Bed', 'Kamar Fasilitas lengkap dan luas plus elegan', 5, 'https://dummyimage.com/300', NULL, NULL, NULL);
INSERT INTO `bed` VALUES (4, 550000.00, 'Family Twin Bed', 'Kamar Fasilitas lengkap dan nyaman plus elegan', 2, 'https://dummyimage.com/300', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for bed_facilities
-- ----------------------------
DROP TABLE IF EXISTS `bed_facilities`;
CREATE TABLE `bed_facilities`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `bed_id` bigint NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bed_facilities
-- ----------------------------
INSERT INTO `bed_facilities` VALUES (1, 1, 'Kopi Gajah', 'fa fa-coffee', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (2, 1, 'Wifi Gratis', 'fa fa-wifi', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (3, 2, 'Gym', 'fas fa-bicycle', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (4, 2, 'Wifi Gratis', 'fa fa-wifi', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (5, 3, 'Kopi Gajah', 'fa fa-coffee', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (6, 3, 'Pizza Gratis', 'fas fa-pizza-slice', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (7, 4, 'Kopi Gajah', 'fa fa-coffee', NULL, NULL, NULL);
INSERT INTO `bed_facilities` VALUES (8, 4, 'Hamburger Gratis', 'fas fa-hamburger', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for building_rooms
-- ----------------------------
DROP TABLE IF EXISTS `building_rooms`;
CREATE TABLE `building_rooms`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `building_id` tinyint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `building_rooms_code_unique`(`code` ASC) USING BTREE,
  INDEX `building_rooms_building_id_foreign`(`building_id` ASC) USING BTREE,
  CONSTRAINT `building_rooms_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of building_rooms
-- ----------------------------

-- ----------------------------
-- Table structure for buildings
-- ----------------------------
DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` tinyint UNSIGNED NULL DEFAULT NULL,
  `name_asset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `land_status` int NULL DEFAULT NULL,
  `land_condition` int NULL DEFAULT NULL,
  `land_gradual` int NULL DEFAULT NULL,
  `concrete` int NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_sub` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_goods` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `wide` int UNSIGNED NULL DEFAULT NULL,
  `floor` int NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `register_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_date` date NULL DEFAULT NULL,
  `certificate_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_document` int NULL DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `origin` int NULL DEFAULT NULL,
  `json_maps` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `address_primary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_secondary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `state_id` int UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `take_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `building_unit_id`(`unit_id` ASC) USING BTREE,
  CONSTRAINT `building_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `cmp_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings
-- ----------------------------
INSERT INTO `buildings` VALUES (5, NULL, 'Aset bangunan pemad Edit', 1, 3, 2, 2, '1', '2', '1', '3', 1, 1, 1000000.00, '1232345', '2024-06-07', '11123', 'file_building/66615deb08f5c/lpWd3IR1ABtkuVlxgIwkmbSM7I4PHe-metac2FtcGxlLnBkZg==-.pdf', 'test', 2023, 'test edit', 2, '{\"lat\":-7.73644077126882,\"lng\":110.39920118643295}', 'jalan solo raya', NULL, NULL, NULL, NULL, '2024-06-06 13:57:47', '2024-06-26 13:55:15', NULL);
INSERT INTO `buildings` VALUES (16, NULL, 'Bangunan Baru', 1, 2, 1, 1, '3', '1', '25', '81', 50, NULL, 10000.00, '12345', '2024-06-25', '2323434', 'file_building/667a4fa362b05/h245Mg9cF06d9eC7QK8o1kT03RuE0h-metac2FtcGxlLnBkZg==-.pdf', 'Sertifikat test', 2024, 'test', 2, '{\"lat\":-7.735082328985198,\"lng\":110.39478990139234}', 'Jalan oke', NULL, NULL, NULL, NULL, '2024-06-25 12:03:31', '2024-06-25 12:03:31', NULL);
INSERT INTO `buildings` VALUES (18, NULL, 'Bangun Tidur', 1, 1, 1, 1, '3', '1', '26', '89', 50, NULL, 400000.00, '567116', '2024-07-05', '12345', 'file_building/6687b1c0c824a/UhWP6UTYpa6dBmnVbV5KrikM951PQe-metac2FtcGxlLnBkZg==-.pdf', 'test', 2024, 'oke gass', 2, '{\"lat\":-7.733678498670076,\"lng\":110.39884843385373}', 'test', NULL, NULL, NULL, NULL, '2024-07-05 15:41:36', '2024-07-05 15:41:36', NULL);
INSERT INTO `buildings` VALUES (19, NULL, 'Testing bangunan asset', 1, 2, 1, 1, '3', '1', '26', '82', NULL, NULL, 3000000.00, '1111', '2024-07-12', '12344555', 'file_building/6690b12639b93/KuVrWLImKQU4uIxVKuWc8I9jOIr5oJ-metac2FtcGxlLnBkZg==-.pdf', 'test sertif', 2024, 'test', 1, '{\"lat\":-7.735569612628215,\"lng\":110.39458353627462}', 'Alamat Saya', NULL, NULL, NULL, NULL, '2024-07-12 11:29:26', '2024-07-12 11:30:39', NULL);

-- ----------------------------
-- Table structure for buildings_backup
-- ----------------------------
DROP TABLE IF EXISTS `buildings_backup`;
CREATE TABLE `buildings_backup`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_primary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_secondary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `state_id` int UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `buildings_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_backup
-- ----------------------------

-- ----------------------------
-- Table structure for buildings_floor
-- ----------------------------
DROP TABLE IF EXISTS `buildings_floor`;
CREATE TABLE `buildings_floor`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `building_id` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `information` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_sell` int NULL DEFAULT NULL,
  `is_lend` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_floor
-- ----------------------------
INSERT INTO `buildings_floor` VALUES (1, 5, 'Lantai 1', 'test oke', '2024-06-25 10:38:29', '2024-06-25 11:52:13', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (2, 16, 'Lantai 1', 'bangunan baru lantai 1', '2024-06-26 14:36:01', '2024-06-26 14:36:01', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (3, 5, 'Lantai 2', 'Ini lantai 2 bangunan pemad edit', '2024-06-26 14:41:18', '2024-06-26 14:41:18', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (4, 18, 'Lantai 1 Mul', 'Pokoknya lantai', '2024-07-05 15:42:19', '2024-07-05 15:42:19', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (5, 18, 'Lantai 2', 'Ini merupakan lantai 2 test', '2024-07-05 15:42:37', '2024-07-05 15:42:37', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (6, 18, 'Lantai 4', 'Ini adalah lantai 4', '2024-07-08 08:19:03', '2024-07-08 08:19:03', NULL, NULL, NULL);
INSERT INTO `buildings_floor` VALUES (7, 5, 'test Pemad 123', 'oke', '2024-07-12 11:47:16', '2024-07-12 11:47:32', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for buildings_lands
-- ----------------------------
DROP TABLE IF EXISTS `buildings_lands`;
CREATE TABLE `buildings_lands`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sell` date NULL DEFAULT NULL,
  `wide_land` int NULL DEFAULT NULL,
  `id_land` int NULL DEFAULT NULL,
  `remainder_land` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sell_land` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_building` int NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `status` int NULL DEFAULT NULL,
  `status_building` int NULL DEFAULT NULL,
  `forfeit_land` decimal(20, 2) NULL DEFAULT NULL,
  `early_period_land` datetime NULL DEFAULT NULL,
  `month_period_land` int NULL DEFAULT NULL,
  `forfeit_building` decimal(20, 2) NULL DEFAULT NULL,
  `early_period_building` datetime NULL DEFAULT NULL,
  `month_period_building` int NULL DEFAULT NULL,
  `price_lend_building` decimal(20, 2) NULL DEFAULT NULL,
  `is_back_land` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_lands
-- ----------------------------
INSERT INTO `buildings_lands` VALUES (2, NULL, 'INV/BUILDLANDS/1/2024', NULL, NULL, 16, '', '1', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 13:40:30', '2024-06-13 13:40:30', NULL);
INSERT INTO `buildings_lands` VALUES (3, NULL, 'INV/BUILDLANDS/2/2024', NULL, NULL, 17, '', '2', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 13:55:15', '2024-06-13 13:55:15', NULL);
INSERT INTO `buildings_lands` VALUES (4, NULL, 'INV/BUILDLANDS/3/2024', '2024-06-13', NULL, 13, '22', '1', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:02:17', '2024-06-13 14:02:17', NULL);
INSERT INTO `buildings_lands` VALUES (5, NULL, 'INV/BUILDLANDS/4/2024', '2024-06-13', NULL, 13, '20', '2', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:04:58', '2024-06-13 14:04:58', NULL);
INSERT INTO `buildings_lands` VALUES (7, 'file_building_land/666a9c9453224/p82USBpHGEGBfIXZI0WcKJaTjVfdGp-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/5/2024', '2024-06-13', NULL, 13, '18', '2', 5, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:15:32', '2024-06-13 14:15:32', NULL);
INSERT INTO `buildings_lands` VALUES (8, 'file_building_land/666a9d3b9faf4/El10kVb0C1JyNMKwD4fxPWfwW3Hd2R-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/6/2024', '2024-06-14', NULL, 15, '9', '1', 5, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:18:19', '2024-06-14 09:14:59', '2024-06-14 09:14:59');
INSERT INTO `buildings_lands` VALUES (9, 'file_building_land/666a9eb15072d/xhmKtXUg6IpLsM7PAHc0BPFW96khNs-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/7/2024', '2024-06-13', NULL, 13, '17', '1', 5, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:24:33', '2024-06-13 14:24:33', NULL);
INSERT INTO `buildings_lands` VALUES (10, 'file_building_land/666aa159e5405/I7cwjogVcqivwlsUVmhP9uUWmH1awv-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/8/2024', '2024-06-13', NULL, 0, '', '', 5, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:35:53', '2024-06-14 09:14:26', '2024-06-14 09:14:26');
INSERT INTO `buildings_lands` VALUES (11, 'file_building_land/666aa53426f22/kmBni44stnQcOJXxqXiLWvjhcVpLaw-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/9/2024', '2024-06-13', NULL, 0, '', '', 5, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 14:52:20', '2024-06-14 09:04:23', '2024-06-14 09:04:23');
INSERT INTO `buildings_lands` VALUES (12, 'file_building_land/666abce863255/qQo0Tvz2U7cBK0f4lnGWuIfw22ShGm-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/10/2024', '2024-06-13', NULL, 15, '8', '1', 5, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-13 16:33:28', '2024-06-14 09:01:36', '2024-06-14 09:01:36');
INSERT INTO `buildings_lands` VALUES (14, 'file_building_land/666b9b789e378/MovCpFzafZZ0JtX7vjkgR4fPellblV-metac2FtcGxlLnBkZg==-.pdf', 'INV/BUILDLANDS/12/2024', '2024-06-14', 7, 15, '6', '1', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 08:23:04', '2024-06-14 09:01:29', '2024-06-14 09:01:29');
INSERT INTO `buildings_lands` VALUES (15, 'file_building_land/666baf2be78e8/6ilXpz1qsSMvQv7CwWwqpAZOU2xrhO-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/550427/2024', '2024-06-14', 18, 13, '1', '17', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:47:07', '2024-06-14 09:47:07', NULL);
INSERT INTO `buildings_lands` VALUES (16, 'file_building_land/666baf5d715f0/oWVA40xQPe0WUCs0J2ejDOT9owngi9-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/220728/2024', '2024-06-14', 18, 13, '16', '2', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:47:57', '2024-06-14 09:47:57', NULL);
INSERT INTO `buildings_lands` VALUES (17, 'file_building_land/666bafe6a8afe/8TwaGAB105e9M8wy0BQbs8rhPKVTf4-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/151907/2024', '2024-06-15', 20, 13, '2', '18', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:50:14', '2024-06-14 09:50:14', NULL);
INSERT INTO `buildings_lands` VALUES (18, 'file_building_land/666bafebabf5a/dn2ZxtR7YcAYaMQmadIG0mlnLuNlLg-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/651932/2024', '2024-06-14', 20, 13, '17', '3', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:50:19', '2024-06-14 09:50:19', NULL);
INSERT INTO `buildings_lands` VALUES (19, 'file_building_land/666bb13e53d57/7T0DVlf1UhNO6BmT2qMEVopCERHbZb-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/512665/2024', '2024-06-14', 20, 13, '1', '19', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:55:58', '2024-06-14 09:55:58', NULL);
INSERT INTO `buildings_lands` VALUES (20, 'file_building_land/666bb144df89f/xlkLrBsHrBMH3YV4THuBwIpaHx1Ssd-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/130262/2024', '2024-06-14', 20, 13, '18', '2', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:56:04', '2024-06-14 09:56:04', NULL);
INSERT INTO `buildings_lands` VALUES (21, 'file_building_land/666bb1c22df16/n9oZll9CgwV9jPFXoHsmarwK6kNSDw-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/601368/2024', '2024-06-14', 20, 13, '1', '19', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:58:10', '2024-06-14 09:58:10', NULL);
INSERT INTO `buildings_lands` VALUES (22, 'file_building_land/666bb1c84c0db/Bmz8FrQLrJo2vXcrOhkE8iyXqx6hvJ-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/281511/2024', '2024-06-14', 20, 13, '18', '2', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 09:58:16', '2024-06-14 09:58:16', NULL);
INSERT INTO `buildings_lands` VALUES (23, 'file_building_land/666bb58aa1d85/j1Zvlemzls9nO8QcnnnVrt13DP8Jiw-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/674891/2024', '2024-06-14', 20, 13, '1', '19', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 10:14:18', '2024-06-14 10:14:18', NULL);
INSERT INTO `buildings_lands` VALUES (24, 'file_building_land/666bc3f06863c/6acVaXNSHojuxGnIWugwUNmMw06KCh-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/932226/2024', '2024-06-14', 20, 13, '2', '18', 0, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 11:15:44', '2024-06-14 11:15:44', NULL);
INSERT INTO `buildings_lands` VALUES (25, 'file_building_land/666bccc4f3140/4Jq7VYYZF71E0rbwGqUjmZ838kc6jW-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/958594/2024', '2024-06-14', 1, 13, '0', '1', 5, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 11:53:24', '2024-06-14 11:53:24', NULL);
INSERT INTO `buildings_lands` VALUES (26, 'file_building_land/666bcd4a4af63/hb3ix40V8IDMAYbRHH4NAk3j93Vebq-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/246990/2024', '2024-06-14', 1, 13, '0', '1', 5, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 11:55:38', '2024-06-14 11:55:38', NULL);
INSERT INTO `buildings_lands` VALUES (27, 'file_building_land/667bbb5399f2a/cNbCM4me8DyIqo0R2T7WY6cb7z8mD3-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/492680/2024', '2024-06-26', 0, 0, '', '', 5, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-26 13:55:15', '2024-06-26 13:55:15', NULL);
INSERT INTO `buildings_lands` VALUES (28, 'file_building_land/6684d594c3566/DhSh8PgsHoyfaKLauEPmeFMb3Bxf0Y-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/594318/2024', '2024-07-03', 300, 39, '298', '2', 0, 2, 600000.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-03 11:37:40', '2024-07-03 11:37:40', NULL);
INSERT INTO `buildings_lands` VALUES (29, 'file_building_land/66862b5508684/eslfKga7FsFpSyvQfaO2MjD6P8NSfT-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/801027/2024', '2024-07-04', 0, 0, '', '', 16, 3, NULL, 2, NULL, NULL, NULL, NULL, 1000.00, '2024-07-04 00:00:00', 5, 500000.00, NULL, '2024-07-04 11:55:49', '2024-07-04 11:55:49', NULL);
INSERT INTO `buildings_lands` VALUES (30, 'file_building_land/66862bef4c03b/MoF8W6sux3wWU7k0ie2BRihxJQS4U8-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/899188/2024', '2024-07-04', 0, 0, '', '', 16, 3, NULL, 2, NULL, NULL, NULL, NULL, 2000.00, '2024-07-04 00:00:00', 7, 5000000.00, NULL, '2024-07-04 11:58:23', '2024-07-04 11:58:23', NULL);
INSERT INTO `buildings_lands` VALUES (31, 'file_building_land/6686519266955/x6cWdVoHzPzksmFp8ojrPqPDnEKb53-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/355566/2024', '2024-07-04', 298, 39, '296', '2', 0, 2, 600.00, 2, 0, 30000.00, '2024-07-04 00:00:00', 5, NULL, NULL, NULL, NULL, NULL, '2024-07-04 14:38:58', '2024-07-04 14:38:58', NULL);
INSERT INTO `buildings_lands` VALUES (32, 'file_building_land/66865225258b9/J4SI7f01SBGU9mmBwOUNh8Gxrp2vrp-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/315798/2024', '2024-07-04', 296, 39, '293', '3', 16, 1, 900.00, 2, NULL, 300000.00, '2024-07-04 00:00:00', 5, NULL, NULL, NULL, NULL, NULL, '2024-07-04 14:41:25', '2024-07-04 14:41:25', NULL);
INSERT INTO `buildings_lands` VALUES (33, 'file_building_land/66865b21093e1/vURYudJYfv8uWwyJjmaN7Js6xQVf3v-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/833008/2024', '2024-07-04', 293, 39, '288', '5', 16, 1, 1.50, 2, NULL, 50000.00, '2024-07-04 00:00:00', 3, 1000.00, '2024-07-04 00:00:00', 1, 2000.00, 1, '2024-07-04 15:19:45', '2024-07-24 15:22:13', NULL);
INSERT INTO `buildings_lands` VALUES (34, 'file_building_land/6687b3a7cb9d1/TBlgM52Uerk2SnKTHkzzky733VSJMH-metac2FtcGxlLnBkZg==-.pdf', 'INV/PEMINJAMAN/BUILDLAND/298989/2024', '2024-07-05', 0, 0, '', '', 18, 3, NULL, 2, NULL, NULL, NULL, NULL, 3000.00, '2024-07-05 00:00:00', 4, 100000.00, NULL, '2024-07-05 15:49:43', '2024-07-05 15:49:43', NULL);
INSERT INTO `buildings_lands` VALUES (35, 'file_building_land/668cbb166b3e5/dpPdqfFwoo3coITCAJwNMYQOjD4k2p-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/491965/2024', '2024-07-09', 288, 39, '238', '50', 0, 2, 15.00, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-09 11:22:46', '2024-07-09 11:22:46', NULL);
INSERT INTO `buildings_lands` VALUES (36, 'file_building_land/668cc14a8f9cc/0Ws64CTko6iQcNDVWSKxkwjZmlCSfP-metac2FtcGxlLnBkZg==-.pdf', 'INV/PENJUALAN/BUILDLAND/302520/2024', '2024-07-09', 238, 39, '188', '50', 5, 1, 15.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-09 11:49:14', '2024-07-09 11:49:14', NULL);

-- ----------------------------
-- Table structure for buildings_lands_floor
-- ----------------------------
DROP TABLE IF EXISTS `buildings_lands_floor`;
CREATE TABLE `buildings_lands_floor`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `floor_id` int NULL DEFAULT NULL,
  `building_sell_id` int NULL DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_start` date NULL DEFAULT NULL,
  `month_period` int NULL DEFAULT NULL,
  `forheit` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_slice` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_status` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_lands_floor
-- ----------------------------
INSERT INTO `buildings_lands_floor` VALUES (1, 6, 34, 'INV/LEND/ONFLOOR/858943/2024', '2023-10-09', 9, 4000.00, 2500.00, 1, '2024-07-08 09:15:36', '2024-07-08 15:47:02', NULL);
INSERT INTO `buildings_lands_floor` VALUES (3, 6, 34, 'INV/LEND/ONFLOOR/659582/2024', '2024-05-01', 1, 3000.00, 4000.00, 1, '2024-07-08 15:41:56', '2024-07-08 16:31:28', NULL);
INSERT INTO `buildings_lands_floor` VALUES (4, 6, 34, 'INV/LEND/ONFLOOR/268676/2024', '2024-01-16', 6, 6000.00, 8000.00, 1, '2024-07-08 19:13:57', '2024-07-08 19:23:47', NULL);

-- ----------------------------
-- Table structure for buildings_lands_lend
-- ----------------------------
DROP TABLE IF EXISTS `buildings_lands_lend`;
CREATE TABLE `buildings_lands_lend`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sell` date NULL DEFAULT NULL,
  `wide_land` int NULL DEFAULT NULL,
  `id_land` int NULL DEFAULT NULL,
  `remainder_land` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sell_land` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `wide_building` int NULL DEFAULT NULL,
  `id_building` int NULL DEFAULT NULL,
  `remainder_building` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sell_building` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_lands_lend
-- ----------------------------

-- ----------------------------
-- Table structure for buildings_lands_room
-- ----------------------------
DROP TABLE IF EXISTS `buildings_lands_room`;
CREATE TABLE `buildings_lands_room`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `floor_id` int NULL DEFAULT NULL,
  `room_id` int NULL DEFAULT NULL,
  `building_sell_id` int NULL DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_start` date NULL DEFAULT NULL,
  `month_period` int NULL DEFAULT NULL,
  `forheit` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_slice` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_status` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_lands_room
-- ----------------------------
INSERT INTO `buildings_lands_room` VALUES (5, 6, 15, 34, 'INV/LEND/ONROOM/614778/2024', '2024-07-09', 5, 4000.00, 3000.00, 1, '2024-07-09 08:22:40', '2024-07-09 09:12:32', NULL);
INSERT INTO `buildings_lands_room` VALUES (6, 6, 15, 34, 'INV/LEND/ONROOM/715613/2024', '2024-01-01', 6, 7000.00, 7000.00, 1, '2024-07-09 09:13:03', '2024-07-09 09:14:18', NULL);
INSERT INTO `buildings_lands_room` VALUES (7, 6, 14, 34, 'INV/LEND/ONROOM/946131/2024', '2024-01-01', 6, 5000.00, 6000.00, 1, '2024-07-09 09:13:55', '2024-07-09 09:14:11', NULL);

-- ----------------------------
-- Table structure for buildings_photo
-- ----------------------------
DROP TABLE IF EXISTS `buildings_photo`;
CREATE TABLE `buildings_photo`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `building_id` int NULL DEFAULT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_photo
-- ----------------------------
INSERT INTO `buildings_photo` VALUES (14, 5, 'image_building/666162051a598', 'ueZWXH4M7SJ222E1bQApyLYqlkKppA-metaYWNRT01Fd2VCNVBZRnllMThoVTF3RGt0WXVUMkwxLW1ldGFZbTl2ZEhOMGNtRndMV1ZqYjIxdFpYSmpaUzEwWlcxd2JHRjBaUzUzWldKdy0ud2VicA==-.webp', NULL, '2024-06-06 14:15:17', '2024-06-06 14:22:35');
INSERT INTO `buildings_photo` VALUES (15, 5, 'image_building/666162051b683', '3nTNNEaZGCXnNfphBShGEs9Fnr40WQ-metad2FsbHBhcGVyZmxhcmUuY29tX3dhbGxwYXBlci5qcGc=-.jpg', NULL, '2024-06-06 14:15:17', '2024-06-06 14:22:35');
INSERT INTO `buildings_photo` VALUES (16, 18, 'image_building/6687b1c0c9bbe', 'lCfTGh7yYsYeMSIe34WeOzDNpEFMxj-metaaGVyby1pbWcucG5n-.png', NULL, '2024-07-05 15:41:36', '2024-07-05 15:41:36');
INSERT INTO `buildings_photo` VALUES (18, 19, 'image_building/6690b13ac0b99', 'wxI80xbtvLBLQCzn9McgBY0ap1fP62-metaaFNIejFPM0tvUHNydEp4em9vQ0xneG85Nnd3MVhULW1ldGFZbTl2ZEhOMGNtRndMV1ZqYjIxdFpYSmpaUzEwWlcxd2JHRjBaUzUzWldKdy0ud2VicA==-.webp', NULL, '2024-07-12 11:29:46', '2024-07-12 11:30:39');

-- ----------------------------
-- Table structure for buildings_room
-- ----------------------------
DROP TABLE IF EXISTS `buildings_room`;
CREATE TABLE `buildings_room`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `building_id` int NULL DEFAULT NULL,
  `floor_id` int NULL DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_sell` int NULL DEFAULT NULL,
  `is_lend` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_room
-- ----------------------------
INSERT INTO `buildings_room` VALUES (1, 'Ruang data', 5, 1, 'gasss', NULL, '2024-06-26 08:57:35', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (2, 'Ruangan B', 5, 1, 'ini ruangan B', NULL, '2024-06-26 14:14:15', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (3, 'Ruangan C', 5, 1, 'Ini ruangan C', NULL, '2024-06-26 14:14:30', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (4, 'Ruangan D', 5, 1, 'ini ruangan D', NULL, '2024-06-26 14:14:52', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (5, 'Ruangan 1 Baru', 16, 2, 'ruangan 1 baru', NULL, '2024-06-28 08:46:21', '2024-07-05 11:29:43', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (6, 'Ruangan 2 bangunan baru', 16, 2, 'informasi ruangan 2 baru', NULL, '2024-06-28 08:47:04', '2024-07-05 11:29:43', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (7, 'Ruangan Lantai 2 A', 5, 3, 'Ini ruangan lantai 2 A', NULL, '2024-06-28 10:10:32', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (8, 'Ruangan Lantai 2 B', 5, 3, 'Ruangan Lantai 2 B deskripsi', NULL, '2024-06-28 10:11:13', '2024-07-09 11:49:14', NULL, 1, NULL);
INSERT INTO `buildings_room` VALUES (9, 'Ruangan lagi lho', 16, 2, 'Ini ruangan', NULL, '2024-07-04 16:11:03', '2024-07-05 11:29:43', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (10, 'Ruangan lagi lho', 16, 2, 'Ini ruangan', NULL, '2024-07-04 16:13:25', '2024-07-05 11:29:43', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (11, 'Teeeesssttt', 16, 2, 'oke', 400000.00, '2024-07-04 16:17:32', '2024-07-05 11:29:43', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (12, 'Ruangan 1A', 18, 4, 'ini ruangan 1A', 3000.00, '2024-07-05 15:43:01', '2024-07-05 15:43:01', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (13, 'Tester 2A', 18, 4, 'informasi tester 2A', 70000.00, '2024-07-05 15:44:18', '2024-07-05 15:44:18', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (14, 'Testing 1', 18, 6, 'test', 3000.00, '2024-07-08 08:19:28', '2024-07-09 09:14:11', NULL, NULL, 1);
INSERT INTO `buildings_room` VALUES (15, 'Test 2', 18, 6, 'oke', 6000.00, '2024-07-08 08:19:45', '2024-07-09 09:14:18', NULL, NULL, NULL);
INSERT INTO `buildings_room` VALUES (16, 'Ruang BaruData', 5, 7, 'informasi data', 3434400.00, '2024-07-12 11:49:28', '2024-07-12 11:50:29', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for buildings_sell_floor_room
-- ----------------------------
DROP TABLE IF EXISTS `buildings_sell_floor_room`;
CREATE TABLE `buildings_sell_floor_room`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_sell_id` int NULL DEFAULT NULL,
  `building_id` int NULL DEFAULT NULL,
  `floor_id` int NULL DEFAULT NULL,
  `room_id` int NULL DEFAULT NULL,
  `status_transaction` int NULL DEFAULT NULL,
  `period_date` datetime NULL DEFAULT NULL,
  `period_month` int NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of buildings_sell_floor_room
-- ----------------------------
INSERT INTO `buildings_sell_floor_room` VALUES (1, 27, 5, 1, 1, NULL, NULL, NULL, NULL, '2024-06-27 12:00:31', '2024-06-27 12:00:31', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (2, 27, 5, 1, 2, NULL, NULL, NULL, NULL, '2024-06-27 12:00:31', '2024-06-27 12:00:31', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (3, 27, 5, 1, 3, NULL, NULL, NULL, NULL, '2024-06-27 12:00:31', '2024-06-27 12:00:31', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (4, 27, 5, 1, 4, NULL, NULL, NULL, NULL, '2024-06-27 12:00:31', '2024-06-27 12:00:31', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (5, 27, 5, 3, 8, NULL, NULL, NULL, NULL, '2024-06-28 11:10:14', '2024-06-28 11:10:14', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (6, 27, 5, 3, 8, NULL, NULL, NULL, NULL, '2024-06-28 11:13:39', '2024-06-28 11:13:39', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (7, 29, 16, 2, 5, NULL, NULL, NULL, NULL, '2024-07-05 11:29:42', '2024-07-05 11:29:42', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (8, 29, 16, 2, 6, NULL, NULL, NULL, NULL, '2024-07-05 11:29:43', '2024-07-05 11:29:43', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (9, 29, 16, 2, 9, NULL, NULL, NULL, NULL, '2024-07-05 11:29:43', '2024-07-05 11:29:43', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (10, 29, 16, 2, 10, NULL, NULL, NULL, NULL, '2024-07-05 11:29:43', '2024-07-05 11:29:43', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (11, 29, 16, 2, 11, NULL, NULL, NULL, NULL, '2024-07-05 11:29:43', '2024-07-05 11:29:43', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (20, 34, 18, 6, 14, NULL, NULL, NULL, NULL, '2024-07-08 09:15:36', '2024-07-08 09:15:36', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (21, 34, 18, 6, 15, NULL, NULL, NULL, NULL, '2024-07-08 09:15:36', '2024-07-08 09:15:36', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (22, 34, 18, 6, 14, NULL, NULL, NULL, NULL, '2024-07-08 15:41:56', '2024-07-08 15:41:56', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (23, 34, 18, 6, 15, NULL, NULL, NULL, NULL, '2024-07-08 15:41:56', '2024-07-08 15:41:56', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (24, 34, 18, 6, 14, NULL, NULL, NULL, NULL, '2024-07-08 19:13:57', '2024-07-08 19:13:57', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (25, 34, 18, 6, 15, NULL, NULL, NULL, NULL, '2024-07-08 19:13:57', '2024-07-08 19:13:57', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (26, 34, 18, 6, 15, NULL, NULL, NULL, NULL, '2024-07-08 19:44:06', '2024-07-08 19:44:06', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (27, 34, 18, 6, 14, NULL, NULL, NULL, NULL, '2024-07-09 00:56:52', '2024-07-09 00:56:52', NULL);
INSERT INTO `buildings_sell_floor_room` VALUES (28, 34, 18, 6, 15, NULL, NULL, NULL, NULL, '2024-07-09 02:16:45', '2024-07-09 02:16:45', NULL);

-- ----------------------------
-- Table structure for categoryzation
-- ----------------------------
DROP TABLE IF EXISTS `categoryzation`;
CREATE TABLE `categoryzation`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id_menu_category` bigint UNSIGNED NOT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1805625053969386 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of categoryzation
-- ----------------------------
INSERT INTO `categoryzation` VALUES (1805625020984993, 'Properti', 'properti', 1, 'Properti', 0, NULL, NULL, NULL, 1805624955748034, 1, NULL, 1, NULL, NULL);
INSERT INTO `categoryzation` VALUES (1805625039761164, 'Home', 'home', 1, 'home', 0, NULL, NULL, NULL, 1805624955748034, 1, NULL, 1, NULL, NULL);
INSERT INTO `categoryzation` VALUES (1805625053969385, 'House', 'house', 1, 'house', 0, NULL, NULL, NULL, 1805624955748034, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for categoryzation_menu
-- ----------------------------
DROP TABLE IF EXISTS `categoryzation_menu`;
CREATE TABLE `categoryzation_menu`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1805624955748035 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of categoryzation_menu
-- ----------------------------
INSERT INTO `categoryzation_menu` VALUES (1805624955748034, 'Category', 'category', 'fas fa-copyright', 3, NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for cmp_approvables
-- ----------------------------
DROP TABLE IF EXISTS `cmp_approvables`;
CREATE TABLE `cmp_approvables`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `modelable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelable_id` bigint UNSIGNED NOT NULL,
  `userable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userable_id` bigint UNSIGNED NOT NULL,
  `level` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `cancelable` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `result` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `history` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cmp_approvables_modelable_type_modelable_id_index`(`modelable_type` ASC, `modelable_id` ASC) USING BTREE,
  INDEX `cmp_approvables_userable_type_userable_id_index`(`userable_type` ASC, `userable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_approvables
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_contract_meta
-- ----------------------------
DROP TABLE IF EXISTS `cmp_contract_meta`;
CREATE TABLE `cmp_contract_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` tinyint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_contract_meta_contract_id_key_unique`(`contract_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `cmp_contract_meta_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `cmp_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_contract_meta
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_contracts
-- ----------------------------
DROP TABLE IF EXISTS `cmp_contracts`;
CREATE TABLE `cmp_contracts`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_kd_unique`(`kd` ASC) USING BTREE,
  UNIQUE INDEX `cmp_contracts_kd_unique`(`kd` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_contracts
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_depts
-- ----------------------------
DROP TABLE IF EXISTS `cmp_depts`;
CREATE TABLE `cmp_depts`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` tinyint UNSIGNED NULL DEFAULT NULL,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_id` tinyint UNSIGNED NULL DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_depts_kd_unique`(`kd` ASC) USING BTREE,
  INDEX `cmp_depts_unit_id_foreign`(`unit_id` ASC) USING BTREE,
  INDEX `cmp_depts_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `cmp_depts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `cmp_depts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cmp_depts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `cmp_units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_depts
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_position_meta
-- ----------------------------
DROP TABLE IF EXISTS `cmp_position_meta`;
CREATE TABLE `cmp_position_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `position_id` smallint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_position_meta_position_id_key_unique`(`position_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `cmp_position_meta_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `cmp_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_position_meta
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_position_trees
-- ----------------------------
DROP TABLE IF EXISTS `cmp_position_trees`;
CREATE TABLE `cmp_position_trees`  (
  `position_id` smallint UNSIGNED NOT NULL,
  `parent_id` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`position_id`, `parent_id`) USING BTREE,
  INDEX `cmp_position_trees_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `cmp_position_trees_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `cmp_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cmp_position_trees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `cmp_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_position_trees
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_positions
-- ----------------------------
DROP TABLE IF EXISTS `cmp_positions`;
CREATE TABLE `cmp_positions`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `level` tinyint UNSIGNED NULL DEFAULT NULL,
  `ctg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dept_id` tinyint UNSIGNED NULL DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_positions_kd_unique`(`kd` ASC) USING BTREE,
  INDEX `cmp_positions_dept_id_foreign`(`dept_id` ASC) USING BTREE,
  CONSTRAINT `cmp_positions_dept_id_foreign` FOREIGN KEY (`dept_id`) REFERENCES `cmp_depts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_positions
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_unit_meta
-- ----------------------------
DROP TABLE IF EXISTS `cmp_unit_meta`;
CREATE TABLE `cmp_unit_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` tinyint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_unit_meta_unit_id_key_unique`(`unit_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `cmp_unit_meta_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `cmp_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_unit_meta
-- ----------------------------

-- ----------------------------
-- Table structure for cmp_units
-- ----------------------------
DROP TABLE IF EXISTS `cmp_units`;
CREATE TABLE `cmp_units`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cmp_units_kd_unique`(`kd` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmp_units
-- ----------------------------
INSERT INTO `cmp_units` VALUES (1, '123', 'Unit A', NULL, '2024-06-04 08:23:00', NULL);
INSERT INTO `cmp_units` VALUES (2, '456', 'Unit B', NULL, '2024-06-04 08:23:20', NULL);
INSERT INTO `cmp_units` VALUES (3, '789', 'Unit C', NULL, '2024-06-20 15:02:56', NULL);

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES (1, 1792470458501574, NULL, 'tester', NULL, 'Title testing', 'Ini adalah deskripsi komentar', 'reply', NULL, 0, NULL, 0, NULL, NULL);
INSERT INTO `comments` VALUES (2, 1792470458501574, NULL, 'tester', NULL, 'Tes Komentar', 'Ini comment', 'reply', NULL, 0, NULL, 0, NULL, NULL);
INSERT INTO `comments` VALUES (10, 1792470458501574, 1, 'admin', 'admin@website.com', 'Title testing', '<p>Reply data<br></p>', 'reply', NULL, 1, NULL, 1, '2024-03-06 22:24:48', NULL);
INSERT INTO `comments` VALUES (12, 1792470458501574, 2, 'admin', 'admin@website.com', 'Tes Komentar', '<p>oke gass<br></p>', 'reply', NULL, 1, NULL, 1, '2024-03-06 22:29:17', NULL);
INSERT INTO `comments` VALUES (13, 1792470458501574, 1, 'admin', 'admin@website.com', 'Title testing', '<p>Wake me up when september ends<br></p>', 'reply', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `comments` VALUES (14, 1792470458501574, 2, 'admin', 'admin@website.com', 'Tes Komentar', 'wind of change<br>', 'reply', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `comments` VALUES (15, 1792470458501574, 1, 'admin', 'admin@website.com', 'Title testing', '<p>test komen<br></p>', 'reply', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for contact
-- ----------------------------
DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of contact
-- ----------------------------
INSERT INTO `contact` VALUES (1, 'robert', 'agustinus2h@gmail.com', NULL, 'Test', 'oke', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (2, 'robert', 'agustinus2h@gmail.com', NULL, 'test123', 'vbvnbnvn', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (3, 'robert', 'agustinus2h@gmail.com', NULL, 'test123', 'vxcv', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (4, 'robert', 'robert@gmail.com', NULL, 'testing', 'bvbvbc', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (8, 'coba', 'coba@gmail.com', NULL, 'testing', 'pesan', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (9, 'Robert', 'agustinus2h@gmail.com', NULL, 'testing', 'oke', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (10, 'Robert', 'testing@gmail.com', NULL, 'sbj1', 'oke', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `contact` VALUES (11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for doc_signatures
-- ----------------------------
DROP TABLE IF EXISTS `doc_signatures`;
CREATE TABLE `doc_signatures`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `doc_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `doc_signatures_qr_unique`(`qr` ASC) USING BTREE,
  INDEX `doc_signatures_doc_id_foreign`(`doc_id` ASC) USING BTREE,
  INDEX `doc_signatures_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `doc_signatures_doc_id_foreign` FOREIGN KEY (`doc_id`) REFERENCES `docs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `doc_signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of doc_signatures
-- ----------------------------

-- ----------------------------
-- Table structure for docs
-- ----------------------------
DROP TABLE IF EXISTS `docs`;
CREATE TABLE `docs`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `modelable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `modelable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `docs_kd_unique`(`kd` ASC) USING BTREE,
  UNIQUE INDEX `docs_qr_unique`(`qr` ASC) USING BTREE,
  INDEX `docs_modelable_type_modelable_id_index`(`modelable_type` ASC, `modelable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of docs
-- ----------------------------

-- ----------------------------
-- Table structure for employee_contract_meta
-- ----------------------------
DROP TABLE IF EXISTS `employee_contract_meta`;
CREATE TABLE `employee_contract_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` smallint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_contract_meta_contract_id_key_unique`(`contract_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `employee_contract_meta_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `employee_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employee_contract_meta
-- ----------------------------

-- ----------------------------
-- Table structure for employee_contracts
-- ----------------------------
DROP TABLE IF EXISTS `employee_contracts`;
CREATE TABLE `employee_contracts`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int UNSIGNED NOT NULL,
  `kd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contract_id` tinyint UNSIGNED NOT NULL,
  `work_location` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_contracts_kd_unique`(`kd` ASC) USING BTREE,
  INDEX `employee_contracts_employee_id_foreign`(`employee_id` ASC) USING BTREE,
  INDEX `employee_contracts_contract_id_foreign`(`contract_id` ASC) USING BTREE,
  CONSTRAINT `employee_contracts_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `cmp_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_contracts_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employee_contracts
-- ----------------------------

-- ----------------------------
-- Table structure for employee_meta
-- ----------------------------
DROP TABLE IF EXISTS `employee_meta`;
CREATE TABLE `employee_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_meta_employee_id_key_unique`(`employee_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `employee_meta_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employee_meta
-- ----------------------------

-- ----------------------------
-- Table structure for employee_positions
-- ----------------------------
DROP TABLE IF EXISTS `employee_positions`;
CREATE TABLE `employee_positions`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int UNSIGNED NOT NULL,
  `position_id` smallint UNSIGNED NOT NULL,
  `contract_id` smallint UNSIGNED NULL DEFAULT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employee_positions_employee_id_foreign`(`employee_id` ASC) USING BTREE,
  INDEX `employee_positions_position_id_foreign`(`position_id` ASC) USING BTREE,
  INDEX `employee_positions_contract_id_foreign`(`contract_id` ASC) USING BTREE,
  CONSTRAINT `employee_positions_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `employee_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_positions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_positions_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `cmp_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employee_positions
-- ----------------------------

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `permanent_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employees_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employees
-- ----------------------------
INSERT INTO `employees` VALUES (1, 1010101, '2024-05-29 15:34:37', '2024-05-29 15:34:37', NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for extras
-- ----------------------------
DROP TABLE IF EXISTS `extras`;
CREATE TABLE `extras`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of extras
-- ----------------------------
INSERT INTO `extras` VALUES (1, 'Kolam Renang', 'nikmati bersantai dan nikmati kunjungan anda', 25000.00, 'https://dummyimage.com/300', NULL, NULL, NULL);
INSERT INTO `extras` VALUES (2, 'Pijat SPA', 'nikmati pijatan yang luar biasa', 10000.00, 'https://dummyimage.com/300', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for features
-- ----------------------------
DROP TABLE IF EXISTS `features`;
CREATE TABLE `features`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `features_name_scope_unique`(`name` ASC, `scope` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of features
-- ----------------------------

-- ----------------------------
-- Table structure for inv_categories
-- ----------------------------
DROP TABLE IF EXISTS `inv_categories`;
CREATE TABLE `inv_categories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inv_categories_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inv_categories
-- ----------------------------
INSERT INTO `inv_categories` VALUES (1, '01', 'Peralatan Kantor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_categories` VALUES (2, '02', 'Aset Tak berwujud', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_categories` VALUES (3, '03', 'Bangunan', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_categories` VALUES (4, '04', 'Pengembangan program', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_categories` VALUES (5, '05', 'Tanah', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_categories` VALUES (6, '06', 'Kendaraan', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for inv_category_group_types
-- ----------------------------
DROP TABLE IF EXISTS `inv_category_group_types`;
CREATE TABLE `inv_category_group_types`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inv_category_group_types_group_id_foreign`(`group_id` ASC) USING BTREE,
  CONSTRAINT `inv_category_group_types_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `inv_category_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 100 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inv_category_group_types
-- ----------------------------
INSERT INTO `inv_category_group_types` VALUES (1, 1, '001', 'Monitor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (2, 1, '002', 'CPU Set', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (3, 1, '003', 'UPS', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (4, 1, '004', 'Headphone', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (5, 1, '005', 'Webcam', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (6, 1, '006', 'Modem USB', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (7, 1, '007', 'Speaker USB', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (8, 2, '001', 'PABX', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (9, 2, '002', 'Pesawat telepon', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (10, 3, '001', 'NAS', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (11, 3, '002', 'Hardisk External', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (12, 4, '001', 'Laptop', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (13, 5, '001', 'All in one PC', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (14, 6, '001', 'Projector', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (15, 7, '001', 'Handphone', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (16, 7, '002', 'Tablet', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (17, 7, '003', 'HT (Handy Talky)', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (18, 8, '001', 'Printer', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (19, 8, '002', 'All in one printer', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (20, 9, '001', 'Scanner', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (21, 10, '001', 'Mikrotik', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (22, 10, '002', 'Access Point', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (23, 11, '001', 'CPU Server', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (24, 11, '002', 'Monitor Server', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (25, 11, '003', 'UPS Server', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (26, 12, '001', 'DVR', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (27, 12, '002', 'Kamera CCTV', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (28, 13, '001', 'Speaker Aktif', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (29, 14, '001', 'Audio control center', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (30, 14, '002', 'Transmitter', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (31, 14, '003', 'Headset', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (32, 14, '004', 'Receiver', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (33, 15, '001', 'Apar', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (34, 16, '001', 'Genset', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (35, 17, '001', 'AC', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (36, 17, '002', 'Dispenser', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (37, 17, '003', 'Kulkas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (38, 17, '004', 'Air purifier', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (39, 17, '005', 'Vaccum cleaner', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (40, 17, '006', 'Thermogun', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (41, 17, '007', 'TV', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (42, 17, '008', 'Papper shredder', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (43, 17, '009', 'Exhaust/blower', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (44, 17, '010', 'Lain-lain', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (45, 18, '001', 'Hardisk', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (46, 18, '002', 'LCD Monitor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (47, 18, '003', 'Casing', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (48, 18, '004', 'PSU', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (49, 18, '005', 'Processor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (50, 18, '006', 'Motherboard', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (51, 19, '001', 'Meja kerja', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (52, 19, '002', 'Meja tamu', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (53, 19, '003', 'Meja kelas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (54, 20, '001', 'Almari kayu', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (55, 20, '002', 'Almari plat besi', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (56, 20, '003', 'Cabinet', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (57, 20, '004', 'Etalase kaca', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (58, 20, '005', 'Brankas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (59, 21, '001', 'Kursi kerja kantor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (60, 21, '002', 'Bench', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (61, 21, '003', 'Kursi kelas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (62, 21, '004', 'Kursi meeting', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (63, 22, '001', 'Whiteboard', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (64, 22, '002', 'Flipchart', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (65, 22, '003', 'Papan pengumuman', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (66, 23, '001', 'Teralis', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (67, 23, '002', 'Pintu kaca', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (68, 23, '003', 'Pintu kayu', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (69, 23, '004', 'Closet duduk', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (70, 23, '005', 'Neon box', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (71, 23, '006', 'Rak pantry', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (72, 23, '007', 'Kabinet bawah tangga', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (73, 23, '008', 'Sekat gypsum', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (74, 23, '009', 'Sekat kayu', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (75, 23, '010', 'Sekat kaca', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (76, 23, '011', 'Meja taman', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (77, 24, '001', 'ISO 9001', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (78, 24, '002', 'ISO 14001', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (79, 24, '003', 'ISO 17100', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (80, 24, '004', 'ISO 27001', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (81, 25, '001', 'Gedung', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (82, 26, '001', 'Sekat gypsum', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (83, 26, '002', 'Sekat Kaca', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (84, 26, '003', 'Pos satpam', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (85, 26, '004', 'Tangga LPK', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (86, 26, '005', 'Kanopi LPK', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (87, 26, '006', 'Backdrop', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (88, 26, '007', 'Ruang kedap suara', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (89, 26, '008', 'Teralis', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (90, 26, '009', 'Pengaman/rumah Genset', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (91, 26, '010', 'Perlengkapan bangunan', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (92, 27, '001', 'Lisensi lifetime', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (93, 27, '002', 'SIA', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (94, 27, '003', 'Lisensi terbatas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (95, 27, '004', 'Web Apps', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (96, 28, '001', 'Tanah', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (97, 29, '001', 'Motor', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (98, 29, '002', 'Sepeda', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_group_types` VALUES (99, 30, '001', 'Mobil Dinas', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for inv_category_groups
-- ----------------------------
DROP TABLE IF EXISTS `inv_category_groups`;
CREATE TABLE `inv_category_groups`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inv_category_groups_category_id_foreign`(`category_id` ASC) USING BTREE,
  CONSTRAINT `inv_category_groups_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `inv_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inv_category_groups
-- ----------------------------
INSERT INTO `inv_category_groups` VALUES (1, 1, '001', 'Komputer', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (2, 1, '002', 'Telekomunikasi', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (3, 1, '003', 'Storage', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (4, 1, '004', 'Laptop', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (5, 1, '005', 'AIO', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (6, 1, '006', 'Projector', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (7, 1, '007', 'Mobile device', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (8, 1, '008', 'Printer', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (9, 1, '009', 'Scanner', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (10, 1, '010', 'Router', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (11, 1, '011', 'Server', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (12, 1, '012', 'CCTV', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (13, 1, '013', 'Speaker', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (14, 1, '014', 'Peralatan Interpreting', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (15, 1, '015', 'Apar', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (16, 1, '016', 'Genset', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (17, 1, '017', 'Elektronik', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (18, 1, '018', 'Sparepart', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (19, 1, '019', 'Meja', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (20, 1, '020', 'Almari', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (21, 1, '021', 'Kursi', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (22, 1, '022', 'Whiteboard', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (23, 1, '023', 'Perlengkapan bangunan', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (24, 2, '001', 'ISO', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (25, 3, '001', 'Permanen', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (26, 3, '002', 'Tidak permanen', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (27, 4, '001', 'Software', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (28, 5, '001', 'Tanah', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (29, 6, '001', 'Roda 2', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `inv_category_groups` VALUES (30, 6, '002', 'Roda 4', NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for inventories
-- ----------------------------
DROP TABLE IF EXISTS `inventories`;
CREATE TABLE `inventories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `placeable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `pic_id` int UNSIGNED NULL DEFAULT NULL,
  `condition` tinyint UNSIGNED NULL DEFAULT NULL,
  `procurement_id` smallint UNSIGNED NULL DEFAULT NULL,
  `quantity` double NOT NULL DEFAULT 1,
  `bought_price` double NULL DEFAULT NULL,
  `bought_at` timestamp NULL DEFAULT NULL,
  `sold_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inventories_code_unique`(`code` ASC) USING BTREE,
  INDEX `inventories_placeable_type_placeable_id_index`(`placeable_type` ASC, `placeable_id` ASC) USING BTREE,
  INDEX `inventories_pic_id_foreign`(`pic_id` ASC) USING BTREE,
  INDEX `inventories_procurement_id_foreign`(`procurement_id` ASC) USING BTREE,
  CONSTRAINT `inventories_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `inventories_procurement_id_foreign` FOREIGN KEY (`procurement_id`) REFERENCES `procurements` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inventories
-- ----------------------------

-- ----------------------------
-- Table structure for inventories_meta
-- ----------------------------
DROP TABLE IF EXISTS `inventories_meta`;
CREATE TABLE `inventories_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` int UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inventories_meta_inventory_id_key_unique`(`inventory_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `inventories_meta_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inventories_meta
-- ----------------------------

-- ----------------------------
-- Table structure for inventory_control_items
-- ----------------------------
DROP TABLE IF EXISTS `inventory_control_items`;
CREATE TABLE `inventory_control_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `control_id` int UNSIGNED NOT NULL,
  `itemable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `itemable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `sold_at` timestamp NULL DEFAULT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inventory_control_items_itemable_type_itemable_id_index`(`itemable_type` ASC, `itemable_id` ASC) USING BTREE,
  INDEX `inventory_control_items_control_id_foreign`(`control_id` ASC) USING BTREE,
  CONSTRAINT `inventory_control_items_control_id_foreign` FOREIGN KEY (`control_id`) REFERENCES `inventory_controls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inventory_control_items
-- ----------------------------

-- ----------------------------
-- Table structure for inventory_controls
-- ----------------------------
DROP TABLE IF EXISTS `inventory_controls`;
CREATE TABLE `inventory_controls`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inventory_controls_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `inventory_controls_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `inventory_controls_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  CONSTRAINT `inventory_controls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_controls_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_controls_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inventory_controls
-- ----------------------------

-- ----------------------------
-- Table structure for inventory_logs
-- ----------------------------
DROP TABLE IF EXISTS `inventory_logs`;
CREATE TABLE `inventory_logs`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` int UNSIGNED NOT NULL,
  `action` tinyint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inventory_logs_inventory_id_foreign`(`inventory_id` ASC) USING BTREE,
  CONSTRAINT `inventory_logs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of inventory_logs
-- ----------------------------

-- ----------------------------
-- Table structure for land
-- ----------------------------
DROP TABLE IF EXISTS `land`;
CREATE TABLE `land`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` tinyint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_sub` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_goods` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `wide` int UNSIGNED NULL DEFAULT NULL,
  `register_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `call_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `classification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_date` date NULL DEFAULT NULL,
  `certificate_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_used` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `right_of_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `used_for` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `origin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `json_maps` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `address_primary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_secondary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `state_id` int UNSIGNED NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `take_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `building_unit_id`(`unit_id` ASC) USING BTREE,
  CONSTRAINT `land_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `cmp_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of land
-- ----------------------------
INSERT INTO `land` VALUES (13, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '2024-06-04', '3453453', 'file_land/665ec902542bf/hwAw6qEdn2MNobrqVThq4JDWrceiqC-metac2FtcGxlLnBkZg==-.pdf', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-04 14:57:54', '2024-06-14 11:55:38', NULL);
INSERT INTO `land` VALUES (15, NULL, '1', '1', '1', '1', 10, '1000', '23234234', NULL, '2024-06-05', '34555', 'file_land/666017e1dbff6/wYGpmWCdADuVBsf5MnnJXOMLIJFi9k-metaZHVtbXkucGRm-.pdf', 'Test sertifikat', '23232', '1', NULL, 'test', '{\"lat\":-7.735292525693361,\"lng\":110.39480063444594}', 'Test', 'oke', NULL, NULL, NULL, NULL, '2024-06-05 14:46:41', '2024-06-14 09:14:59', NULL);
INSERT INTO `land` VALUES (16, NULL, '1', '1', '1', '1', 1, '3453453', '0893444', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.736145303577927,\"lng\":110.39448937589077}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-06 09:13:42', '2024-06-13 13:40:30', 2);
INSERT INTO `land` VALUES (17, NULL, '1', '1', '1', '1', 2320, '3453453', '0893444', NULL, '2024-06-06', '1233', 'file_land/66611b5627a35/dAozkJS7WmZGg0h2Vg3MIacfe6QLDa-metaZHVtbXkucGRm-.pdf', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.736145303577927,\"lng\":110.39448937589077}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 13:40:30', '2024-06-13 13:55:15', NULL);
INSERT INTO `land` VALUES (18, NULL, '1', '1', '1', '1', 2, '3453453', '0893444', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.736145303577927,\"lng\":110.39448937589077}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 13:55:15', '2024-06-13 13:55:15', 3);
INSERT INTO `land` VALUES (19, NULL, '1', '1', '1', '1', 1, '54775767', '084335435', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 14:02:17', '2024-06-13 14:02:17', 4);
INSERT INTO `land` VALUES (20, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 14:04:58', '2024-06-13 14:04:58', 5);
INSERT INTO `land` VALUES (22, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 14:15:32', '2024-06-13 14:15:32', 7);
INSERT INTO `land` VALUES (23, NULL, '1', '1', '1', '1', 1, '1000', '23234234', NULL, '2024-06-14', '', '', 'Test sertifikat', '23232', '1', NULL, 'test', '{\"lat\":-7.735292525693361,\"lng\":110.39480063444594}', 'Test', 'oke', NULL, NULL, NULL, '2024-06-14 09:14:59', '2024-06-13 14:18:19', '2024-06-14 09:14:59', 8);
INSERT INTO `land` VALUES (24, NULL, '1', '1', '1', '1', 1, '1000', '23234234', NULL, '2024-06-14', '', '', 'Test sertifikat', '23232', '1', NULL, 'test', '{\"lat\":-7.735292525693361,\"lng\":110.39480063444594}', 'Test', 'oke', NULL, NULL, NULL, NULL, '2024-06-13 16:33:28', '2024-06-13 16:33:28', 12);
INSERT INTO `land` VALUES (26, NULL, '1', '1', '1', '1', 1, '1000', '23234234', NULL, '2024-06-14', '', '', 'Test sertifikat', '23232', '1', NULL, 'test', '{\"lat\":-7.735292525693361,\"lng\":110.39480063444594}', 'Test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 08:23:04', '2024-06-14 08:23:04', 14);
INSERT INTO `land` VALUES (27, NULL, '1', '1', '1', '1', 17, '54775767', '084335435', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:47:07', '2024-06-14 09:47:07', 15);
INSERT INTO `land` VALUES (28, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '2024-06-14', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:47:57', '2024-06-14 09:47:57', 16);
INSERT INTO `land` VALUES (29, NULL, '1', '1', '1', '1', 18, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:50:14', '2024-06-14 09:50:14', 17);
INSERT INTO `land` VALUES (30, NULL, '1', '1', '1', '1', 3, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:50:19', '2024-06-14 09:50:19', 18);
INSERT INTO `land` VALUES (31, NULL, '1', '1', '1', '1', 19, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:55:58', '2024-06-14 09:55:58', 19);
INSERT INTO `land` VALUES (32, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:56:04', '2024-06-14 09:56:04', 20);
INSERT INTO `land` VALUES (33, NULL, '1', '1', '1', '1', 19, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:58:10', '2024-06-14 09:58:10', 21);
INSERT INTO `land` VALUES (34, NULL, '1', '1', '1', '1', 2, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 09:58:16', '2024-06-14 09:58:16', 22);
INSERT INTO `land` VALUES (35, NULL, '1', '1', '1', '1', 19, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 10:14:18', '2024-06-14 10:14:18', 23);
INSERT INTO `land` VALUES (36, NULL, '1', '1', '1', '1', 18, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 11:15:44', '2024-06-14 11:15:44', 24);
INSERT INTO `land` VALUES (37, NULL, '1', '1', '1', '1', 1, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 11:53:25', '2024-06-14 11:53:25', 25);
INSERT INTO `land` VALUES (38, NULL, '1', '1', '1', '1', 1, '54775767', '084335435', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'test', '{\"lat\":-7.735281909800993,\"lng\":110.39489723192861}', 'test', 'oke', NULL, NULL, NULL, NULL, '2024-06-14 11:55:38', '2024-06-14 11:55:38', 26);
INSERT INTO `land` VALUES (39, NULL, '5', '1', '28', '96', 198, '212122', '08967567575', NULL, '2024-07-03', '234234234', 'file_land/6684c72990df2/6zMhR1uy9BfY1SQnHnSCG5uTfddSpE-metac2FtcGxlLnBkZg==-.pdf', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-03 10:00:41', '2024-07-24 15:22:13', NULL);
INSERT INTO `land` VALUES (40, NULL, '5', '1', '28', '96', 2, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-03 11:37:40', '2024-07-03 11:37:40', 28);
INSERT INTO `land` VALUES (41, NULL, '5', '1', '28', '96', 2, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-04 14:38:58', '2024-07-04 14:38:58', 31);
INSERT INTO `land` VALUES (42, NULL, '5', '1', '28', '96', 3, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-04 14:41:25', '2024-07-04 14:41:25', 32);
INSERT INTO `land` VALUES (43, NULL, '5', '1', '28', '96', 5, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-04 15:19:45', '2024-07-04 15:19:45', 33);
INSERT INTO `land` VALUES (44, NULL, '5', '1', '28', '96', 50, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-09 11:22:46', '2024-07-09 11:22:46', 35);
INSERT INTO `land` VALUES (45, NULL, '5', '1', '28', '96', 50, '212122', '08967567575', NULL, '0000-00-00', '', '', 'test', '2024', '1', NULL, 'untuk rumah', '{\"lat\":-7.735367541756032,\"lng\":110.3947791683387}', 'jalan kaliurang', 'test kaliurang', NULL, NULL, 300000.00, NULL, '2024-07-09 11:49:14', '2024-07-09 11:49:14', 36);
INSERT INTO `land` VALUES (46, NULL, '5', '1', '28', '96', 3000, '123459988779', '089234334665', NULL, '2024-07-12', '1234599', 'file_land/6690a90c69516/OArUrvoq0uTaeZplc2Uc8Tg9JYVOkA-metac2FtcGxlLnBkZg==-.pdf', 'Sertif 1-445', '2024', '1', NULL, 'test', '{\"lat\":-7.735358702637014,\"lng\":110.3948650327677}', 'test', 'oke', NULL, NULL, 10000.00, NULL, '2024-07-12 10:54:52', '2024-07-12 10:58:49', NULL);

-- ----------------------------
-- Table structure for land_photo
-- ----------------------------
DROP TABLE IF EXISTS `land_photo`;
CREATE TABLE `land_photo`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `land_id` int NULL DEFAULT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of land_photo
-- ----------------------------
INSERT INTO `land_photo` VALUES (3, NULL, NULL, NULL, NULL, '2024-06-04 14:34:26', '2024-06-04 14:34:26');
INSERT INTO `land_photo` VALUES (4, NULL, NULL, NULL, NULL, '2024-06-04 14:34:26', '2024-06-04 14:34:26');
INSERT INTO `land_photo` VALUES (5, NULL, NULL, NULL, NULL, '2024-06-04 14:50:18', '2024-06-04 14:50:18');
INSERT INTO `land_photo` VALUES (6, NULL, NULL, NULL, NULL, '2024-06-04 14:50:18', '2024-06-04 14:50:18');
INSERT INTO `land_photo` VALUES (9, 13, 'image_land/66601225f0ca6', 'YG8xctfqpl9WrDiz9F2dIn7Dknkvfg-metabFFJN1NkV2I4VFVLYTJjN21sb2JMUDJRMnBYa0pyLW1ldGFPRVZXTkU1dU9IQk9kRWwzTTJzNFNrSmxUblpNZEROdlVWcEpiMFpHTFcxbGRHRmhSMVo1WW5reGNHSlhZM1ZqUnpWdUxTNXdibWM9LS5wbmc=-.png', NULL, '2024-06-05 14:22:13', '2024-06-05 14:22:13');
INSERT INTO `land_photo` VALUES (10, 15, 'image_land/666017e1dd7b6', 'avIQ4rR01bQ9Ev49067sES10NXrcAw-metaOEVWNE5uOHBOdEl3M2s4SkJlTnZMdDNvUVpJb0ZGLW1ldGFhR1Z5YnkxcGJXY3VjRzVuLS5wbmc=-.png', NULL, '2024-06-05 14:46:41', '2024-06-06 08:33:56');
INSERT INTO `land_photo` VALUES (12, 16, 'image_land/66611b65e36f9', 'NIVGo3KyOjXtZ2FNeDKWdVBMq7TO7i-metaN2xHR3hPeWlBZW42dnJSTjhDSWdUWDE3cHZQUGJMLW1ldGFhR1Z5YnkxcGJXY3VjRzVuLS5wbmc=-.png', NULL, '2024-06-06 09:13:57', '2024-06-06 09:14:08');
INSERT INTO `land_photo` VALUES (14, 39, 'image_land/6684c72998c1a', '09mrGyWu6k64Kythy7ebGNgiS64rPx-metaSGNCWmZkcGZDZ1FUZGcyR0VHcnVSRXF4TEZwWEZwLW1ldGFlVzUwYTNSekxYbGhMVzVrWVdzdGRHRjFMV3R2YXkxMFlXNTVZUzF6WVhsaExtZHBaZz09LS5naWY=-.gif', NULL, '2024-07-03 10:36:09', '2024-07-03 10:36:09');
INSERT INTO `land_photo` VALUES (16, 46, 'image_land/6690a98b07396', 'WzgWF1SVzonPlJ2OYrcXZpGNkiHELI-metac1A3eGxHTlNIQ2RNVUR1VFUyUUtTdG13WlhmbU93LW1ldGFZbTl2ZEhOMGNtRndMV1ZqYjIxdFpYSmpaUzEwWlcxd2JHRjBaUzUzWldKdy0ud2VicA==-.webp', NULL, '2024-07-12 10:56:59', '2024-07-12 10:58:49');

-- ----------------------------
-- Table structure for log_sync
-- ----------------------------
DROP TABLE IF EXISTS `log_sync`;
CREATE TABLE `log_sync`  (
  `id` int NOT NULL,
  `time_sync` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of log_sync
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_links` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `post_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `taxonomy_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `woocomerce_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1806152746712776 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1804704017767449, '{\"id\":\"Beranda\",\"eng\":\"Home\"}', 'fas fa-home', '{\"id\":\"beranda\",\"eng\":\"home\"}', 1, '{\"id\":\"Beranda\",\"eng\":\"home\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804704466767430, '{\"id\":\"Beranda Slider\",\"eng\":\"Home Slider\"}', 'fas fa-images', '{\"id\":\"beranda-slider\",\"eng\":\"home-slider\"}', 2, '\"\"', '', '{\"field_1804704251570159\":{\"ft1804704251570159\":{\"id\":\"Deskripsi singkat\",\"eng\":\"Short Description\"},\"fy1804704251570159\":\"textarea\",\"v1804704251570159\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804704866994913, '{\"id\":\"Tentang\",\"eng\":\"about\"}', 'far fa-building', '{\"id\":\"tentang\",\"eng\":\"about\"}', 1, '{\"id\":\"tentang\",\"eng\":\"about\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804705090926172, '{\"id\":\"Informasi\",\"eng\":\"information\"}', 'fas fa-info', '{\"id\":\"informasi\",\"eng\":\"information\"}', 2, '\"\"', '', '{\"field_1804704963713770\":{\"ft1804704963713770\":{\"id\":\"Deskripsi Singkat\",\"eng\":\"Short Description\"},\"fy1804704963713770\":\"textarea\",\"v1804704963713770\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804714532632213, '{\"id\":\"Kontak\",\"eng\":\"Contact\"}', 'far fa-address-book', '{\"id\":\"kontak\",\"eng\":\"contact\"}', 1, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804714639993688, '{\"id\":\"segmen informasi\",\"eng\":\"Information segment\"}', 'far fa-image', '{\"id\":\"segmen-informasi\",\"eng\":\"information-segment\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804716691902264, '{\"id\":\"Konten Kontak\",\"eng\":\"Content Contact\"}', 'far fa-copyright', '{\"id\":\"konten-kontak\",\"eng\":\"content-contact\"}', 2, '\"\"', '', '{\"field_1804716550331649\":{\"ft1804716550331649\":{\"eng\":\"Icon\",\"id\":\"Ikon\"},\"fy1804716550331649\":\"raw_text\",\"v1804716550331649\":\"required\"},\"field_1804716565932632\":{\"ft1804716565932632\":{\"eng\":\"Short Description\",\"id\":\"Deskripsi singkat\"},\"fy1804716565932632\":\"raw_text\",\"v1804716565932632\":\"required\"},\"field_1804716596284630\":{\"ft1804716596284630\":{\"eng\":\"Information\",\"id\":\"Informasi\"},\"fy1804716596284630\":\"raw_text\",\"v1804716596284630\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804717068831422, '{\"id\":\"Tanya kami\",\"eng\":\"Ask us\"}', 'fas fa-question', '{\"id\":\"tanya-kami\",\"eng\":\"ask-us\"}', 2, '\"\"', '', '{\"field_1804716840032431\":{\"ft1804716840032431\":{\"eng\":\"Description ask us\",\"id\":\"Deskripsi tanya kami\"},\"fy1804716840032431\":\"editor\",\"v1804716840032431\":\"required\"},\"field_1804717293798660\":{\"ft1804717293798660\":{\"id\":\"Keterangan Jam\",\"eng\":\"Hour Information\"},\"fy1804717293798660\":\"raw_text\",\"v1804717293798660\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804717268227712, '{\"id\":\"Jam Kerja Konten\",\"eng\":\"Content Hour\"}', 'far fa-clock', '{\"id\":\"jam-kerja-konten\",\"eng\":\"content-hour\"}', 2, '\"\"', '', '{\"field_1804717195264102\":{\"ft1804717195264102\":{\"eng\":\"Hour Information\",\"id\":\"Informasi Jam\"},\"fy1804717195264102\":\"raw_text\",\"v1804717195264102\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1804717378953687, '{\"id\":\"Kontak Data\",\"eng\":\"Contact Data\"}', 'fas fa-database', '{\"id\":\"kontak data\",\"eng\":\"contact-data\"}', 5, '\"\"', 'contact', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805245723205037, '{\"id\":\"Event\"}', 'far fa-calendar-alt', '{\"id\":\"event\"}', 1, '{\"id\":\"event\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805245810098685, '{\"id\":\"Informasi Banner\",\"eng\":\"Banner Information\"}', 'fas fa-info', '{\"id\":\"informasi-banner\",\"eng\":\"banner-information\"}', 2, '\"\"', '', '{\"field_1805245791368383\":{\"ft1805245791368383\":{\"eng\":\"Description\",\"id\":\"Deskripsi\"},\"fy1805245791368383\":\"editor\",\"v1805245791368383\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805245954119890, '{\"id\":\"Konten Event\",\"eng\":\"Content Event\"}', 'far fa-copyright', '{\"id\":\"konten-event\",\"eng\":\"content-event\"}', 2, '\"\"', '', '{\"field_1805267314805945\":{\"ft1805267314805945\":{\"id\":\"Deskripsi\",\"eng\":\"Description\"},\"fy1805267314805945\":\"editor\",\"v1805267314805945\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805520440506706, '{\"id\":\"Room\",\"eng\":\"Ruangan\"}', 'fas fa-door-closed', '{\"id\":\"room\",\"eng\":\"ruangan\"}', 1, '{\"id\":\"room\",\"eng\":\"ruangan\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805520543632017, '{\"id\":\"Banner Ruangan\",\"eng\":\"Room Banner\"}', 'fas fa-info', '{\"id\":\"banner-ruangan\",\"eng\":\"room-banner\"}', 2, '\"\"', '', '{\"field_1805520499803729\":{\"ft1805520499803729\":{\"eng\":\"Short Description\",\"id\":\"Deskripsi Singkat\"},\"fy1805520499803729\":\"textarea\",\"v1805520499803729\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805520734980055, '{\"id\":\"Konten Ruangan\",\"eng\":\"Room Content\"}', 'fas fa-copyright', '{\"id\":\"konten-ruangan\",\"eng\":\"room-content\"}', 2, '\"\"', '', '{\"field_1805520629068866\":{\"ft1805520629068866\":{\"id\":\"Deskripsi\",\"eng\":\"Description\"},\"fy1805520629068866\":\"editor\",\"v1805520629068866\":\"required\"},\"field_1805520640800036\":{\"ft1805520640800036\":{\"id\":\"Harga\",\"eng\":\"Price\"},\"fy1805520640800036\":\"currency\",\"v1805520640800036\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805521276044259, '{\"id\":\"Banner Restoran\",\"eng\":\"Restaurant Banner\"}', 'fas fa-utensil-spoon', '{\"id\":\"banner-restoran\",\"eng\":\"restaurant-banner\"}', 2, '\"\"', '', '{\"field_1805521255674871\":{\"ft1805521255674871\":{\"id\":\"Deskripsi Singkat\",\"eng\":\"Short Description\"},\"fy1805521255674871\":\"textarea\",\"v1805521255674871\":\"required\"},\"field_1805611714902638\":{\"ft1805611714902638\":{\"id\":\"Deskripsi\",\"eng\":\"Description\"},\"fy1805611714902638\":\"editor\",\"v1805611714902638\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805521412957656, '{\"id\":\"Konten Makanan\",\"eng\":\"Food Content\"}', 'fas fa-apple-alt', '{\"id\":\"konten-makanan\",\"eng\":\"food-content\"}', 2, '\"\"', '', '{\"field_1805521354962740\":{\"ft1805521354962740\":{\"id\":\"Deskripsi singkat\",\"eng\":\"Short Description\"},\"fy1805521354962740\":\"textarea\",\"v1805521354962740\":\"required\"},\"field_1805521379026631\":{\"ft1805521379026631\":{\"eng\":\"Price\",\"id\":\"Harga\"},\"fy1805521379026631\":\"currency\",\"v1805521379026631\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805521742073751, '{\"id\":\"Restauran\",\"eng\":\"Restaurant\"}', 'fas fa-coffee', '{\"id\":\"restauran\",\"eng\":\"restaurant\"}', 1, '{\"id\":\"restauran\",\"eng\":\"restaurant\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805523269857973, '{\"id\":\"Konten Tentang\",\"eng\":\"About Content\"}', 'fas fa-list', '{\"id\":\"konten-tentang\",\"eng\":\"about-content\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805523513313374, '{\"id\":\"Identitas Hotel\",\"eng\":\"Hotel Identity\"}', 'far fa-building', '{\"id\":\"identitas-hotel\",\"eng\":\"hotel-identity\"}', 2, '\"\"', '', '{\"field_1805523454017298\":{\"ft1805523454017298\":{\"id\":\"Deskripsi singkat\",\"eng\":\"Short Description\"},\"fy1805523454017298\":\"textarea\",\"v1805523454017298\":\"required\"},\"field_1805523477846342\":{\"ft1805523477846342\":{\"eng\":\"Description\",\"id\":\"Deskripsi\"},\"fy1805523477846342\":\"editor\",\"v1805523477846342\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805523722012300, '{\"id\":\"Testimoni Banner\",\"eng\":\"Testimoni Banner\"}', 'fas fa-align-center', '{\"id\":\"testimoni-banner\",\"eng\":\"testimoni-banner\"}', 2, '\"\"', '', '{\"field_1805523662233735\":{\"ft1805523662233735\":{\"eng\":\"Short Description\",\"id\":\"Deskripsi Singkat\"},\"fy1805523662233735\":\"textarea\",\"v1805523662233735\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805523974676966, '{\"id\":\"Testimoni Konten\",\"eng\":\"Content Testimoni\"}', 'fas fa-copyright', '{\"id\":\"testimoni-konten\",\"eng\":\"content-testimoni\"}', 2, '\"\"', '', '{\"field_1805523838942184\":{\"ft1805523838942184\":{\"eng\":\"Position\",\"id\":\"Jabatan\"},\"fy1805523838942184\":\"raw_text\",\"v1805523838942184\":\"required\"},\"field_1805523863655653\":{\"ft1805523863655653\":{\"eng\":\"Description\",\"id\":\"Deskripsi\"},\"fy1805523863655653\":\"textarea\",\"v1805523863655653\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524446476218, '{\"id\":\"Blog\",\"eng\":\"Blog\"}', 'fab fa-blogger', '{\"id\":\"blog\",\"eng\":\"blog\"}', 1, '{\"id\":\"blog\",\"eng\":\"blog\"}', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524499252413, '{\"id\":\"Banner Blog\",\"eng\":\"Blog Banner\"}', 'far fa-image', '{\"id\":\"banner-blog\",\"eng\":\"Blog Banner\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524670278517, '{\"id\":\"Blog Konten\",\"eng\":\"Content Blog\"}', 'fas fa-copyright', '{\"id\":\"blog-konten\",\"eng\":\"content-blog\"}', 2, '\"\"', '', '{\"field_1805524583299546\":{\"ft1805524583299546\":{\"eng\":\"Description\",\"id\":\"Deskripsi\"},\"fy1805524583299546\":\"editor\",\"v1805524583299546\":\"required\"},\"field_1805524603725486\":{\"ft1805524603725486\":{\"id\":\"Tanggal\",\"eng\":\"Date\"},\"fy1805524603725486\":\"date\",\"v1805524603725486\":\"required\"}}', '{\"fieldtaxo_1805625069855578\":{\"ft_taxo1805625069855578\":\"Kategori\",\"fy_taxo1805625069855578\":\"1805624955748034\",\"v_taxo1805625069855578\":\"required\"}}', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524763324126, '{\"id\":\"Landing Room\",\"eng\":\"Room Landing\"}', 'far fa-image', '{\"id\":\"landing-room\",\"eng\":\"room-landing\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524841159124, '{\"id\":\"Landing Tentang\",\"eng\":\"About Landing\"}', 'far fa-image', '{\"id\":\"landing-tentang\",\"eng\":\"About Landing\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805524900917400, '{\"id\":\"Landing Restoran\",\"eng\":\"Restaurant Landing\"}', 'far fa-image', '{\"id\":\"landing-restoran\",\"eng\":\"restaurant-landing\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805609723461713, '{\"id\":\"Instagram\",\"eng\":\"Instagram\"}', 'fab fa-instagram', '{\"id\":\"instagram\",\"eng\":\"instagram\"}', 2, '\"\"', '', '{\"field_1805609697015117\":{\"ft1805609697015117\":{\"eng\":\"Link Instagram\",\"id\":\"Tautan Instagram\"},\"fy1805609697015117\":\"raw_text\",\"v1805609697015117\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805612394885444, '{\"id\":\"Informasi Restoran\",\"eng\":\"Resturant Information\"}', 'fas fa-info-circle', '{\"id\":\"informasi-restoran\",\"eng\":\"restaurant-information\"}', 2, '\"\"', '', '{\"field_1805612433766518\":{\"ft1805612433766518\":{\"id\":\"Deskripsi singkat\",\"eng\":\"Short Description\"},\"fy1805612433766518\":\"textarea\",\"v1805612433766518\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805625558522549, '{\"id\":\"Konfigurasi Situs\",\"eng\":\"Site Configuration\"}', 'fas fa-cog', '{\"id\":\"konfigurasi-situs\",\"eng\":\"site-configuration\"}', 5, '\"\"', 'site_configuration', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1805881306889511, '{\"id\":\"Instagram Banner\",\"eng\":\"Instagram Banner\"}', 'far fa-image', '{\"id\":\"instagram-banner\",\"eng\":\"instagram-banner\"}', 2, '\"\"', '', '{\"field_1805881275025229\":{\"ft1805881275025229\":{\"id\":\"Deskripsi Singkat\",\"eng\":\"Short Description\"},\"fy1805881275025229\":\"textarea\",\"v1805881275025229\":\"required\"}}', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1806152517705198, '{\"id\":\"Kontak Kami\",\"eng\":\"Contact US\"}', 'fas fa-phone-square', '{\"id\":\"kontak-kami\",\"eng\":\"contact-us\"}', 1, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1806152662201007, '{\"id\":\"Landing Kontak\",\"eng\":\"Landing Contact\"}', 'far fa-image', '{\"id\":\"landing-kontak\",\"eng\":\"landing-contact\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu` VALUES (1806152746712775, '{\"id\":\"Heading Kontak\",\"eng\":\"Contact Heading\"}', 'fas fa-heading', '{\"id\":\"heading-kontak\",\"eng\":\"contact-heading\"}', 2, '\"\"', '', '[]', '[]', '{\"desktop\":\"\",\"mobile\":\"\"}', '-', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for menu_has_role
-- ----------------------------
DROP TABLE IF EXISTS `menu_has_role`;
CREATE TABLE `menu_has_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `json_menu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menu_has_role
-- ----------------------------
INSERT INTO `menu_has_role` VALUES (1, 'Administrator', '{\"create\":{\"1792426811806891\":true},\"read\":{\"1792426757146150\":true,\"1792426811806891\":true,\"1792426852406044\":true},\"update\":{\"1792426811806891\":true},\"delete\":{\"1792426811806891\":true}}', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `menu_has_role` VALUES (2, 'User', '{\"create\":{\"1792426811806891\":true},\"read\":{\"1792426757146150\":true,\"1792426811806891\":true,\"1792426852406044\":true},\"update\":{\"1792426811806891\":true},\"delete\":{\"1792426811806891\":true}}', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for menu_order
-- ----------------------------
DROP TABLE IF EXISTS `menu_order`;
CREATE TABLE `menu_order`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menu_order
-- ----------------------------
INSERT INTO `menu_order` VALUES (1, '[{\"id\":1804704017767449,\"children\":[{\"id\":1804704466767430}]},{\"id\":1805520440506706,\"children\":[{\"id\":1805524763324126},{\"id\":1805520543632017},{\"id\":1805520734980055}]},{\"id\":1804704866994913,\"children\":[{\"id\":1805524841159124},{\"id\":1804705090926172},{\"id\":1805523269857973},{\"id\":1805523513313374},{\"id\":1805523722012300},{\"id\":1805523974676966},{\"id\":1805881306889511},{\"id\":1805609723461713}]},{\"id\":1805521742073751,\"children\":[{\"id\":1805524900917400},{\"id\":1805521276044259},{\"id\":1805612394885444},{\"id\":1805521412957656}]},{\"id\":1805524446476218,\"children\":[{\"id\":1805524499252413},{\"id\":1805524670278517},{\"id\":1805624955748034}]},{\"id\":1805245723205037,\"children\":[{\"id\":1805245810098685},{\"id\":1805245954119890}]},{\"id\":1804714532632213,\"children\":[{\"id\":1804714639993688},{\"id\":1804716691902264},{\"id\":1804717068831422},{\"id\":1804717268227712},{\"id\":1804717378953687}]},{\"id\":1805625558522549}]', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0000_00_00_000000_create_app_table', 1);
INSERT INTO `migrations` VALUES (2, '2022_00_00_000000_create_account_table', 1);
INSERT INTO `migrations` VALUES (3, '2022_00_00_000001_create_employee_table', 1);
INSERT INTO `migrations` VALUES (4, '2022_01_00_000000_create_inventories_table', 1);
INSERT INTO `migrations` VALUES (5, '2022_01_00_000000_create_oauth_table', 1);
INSERT INTO `migrations` VALUES (6, '2022_11_01_000001_create_features_table', 1);
INSERT INTO `migrations` VALUES (7, '2023_09_01_000000_create_document_table', 1);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_notifiable_type_notifiable_id_index`(`notifiable_type` ASC, `notifiable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of notifications
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `revoked` tinyint(1) NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_access_tokens_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_access_tokens
-- ----------------------------
INSERT INTO `oauth_access_tokens` VALUES ('06642d7c727fb4ec48a5d20f3c9007786331aa2ac8ef771d9a507c10ab8db02113af1abfa771eb47', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-08-28 14:46:05', '2024-08-28 14:46:05', '2025-08-28 14:46:05');
INSERT INTO `oauth_access_tokens` VALUES ('1cb3ea466c5a5451600e8d12d080d511ec12475fffa922f71606d6c489451900125c3eb276239557', 1010106, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-07-10 08:30:14', '2024-07-10 08:30:40', '2025-07-10 08:30:14');
INSERT INTO `oauth_access_tokens` VALUES ('465b4cc962c72fc75f77e7a2e868669a6de13b8a79bce22238aab8a64ab6839e1f7ab5bfc32bbdc2', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-08-16 11:59:55', '2024-08-16 12:00:12', '2025-08-16 11:59:55');
INSERT INTO `oauth_access_tokens` VALUES ('591da810a9affd8481f4ca1ba24b64c7cb68f101ddefaa37eeb5afa55c0071470f07359be01f3472', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-07-10 09:07:06', '2024-07-10 09:07:06', '2025-07-10 09:07:06');
INSERT INTO `oauth_access_tokens` VALUES ('5bceeca4dbab4e933a6826738bde40a589264ccb6fc2da205b796ab16e6e5c2d980c0adeb9ec92d9', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-07-05 14:19:40', '2024-07-23 08:27:14', '2025-07-05 14:19:40');
INSERT INTO `oauth_access_tokens` VALUES ('5c79f61b72aab111726909657da57aa23470e36a56a1c084e52a66a1c52cef3562b19c8b3d4a062a', 1010106, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-07-10 08:51:58', '2024-07-10 09:06:33', '2025-07-10 08:51:58');
INSERT INTO `oauth_access_tokens` VALUES ('73548b3e90d854c6c6c86cec3603a3d59b22ad4e2b286062adc9bfeada1df4cd2eb6d9971a2a1831', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-08-28 13:22:29', '2024-08-28 13:22:29', '2025-08-28 13:22:29');
INSERT INTO `oauth_access_tokens` VALUES ('773bd6df3843d97e7a4a53bca589f93672f30c915c7f59929f0428241b6fefe05bb88a76710ed388', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-05-29 15:42:33', '2024-05-29 15:42:33', '2025-05-29 15:42:33');
INSERT INTO `oauth_access_tokens` VALUES ('93b9dd63738c1cee5469e7bd8fe5ebe1184ac5417363f8a7a99f465ea7ca1d8c4f04c3fa74ca80f8', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-07-23 08:27:27', '2024-07-23 08:27:27', '2025-07-23 08:27:27');
INSERT INTO `oauth_access_tokens` VALUES ('948f19ed88121d839345201e07448d22e0e091a34cebaf215a9d6de225dc9922acf4c9a7c5722de2', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-07-08 19:03:56', '2024-07-08 19:03:56', '2025-07-08 19:03:56');
INSERT INTO `oauth_access_tokens` VALUES ('ab56728040925d36fdee0aabf7809785b0a87b1436424b8eab7e9840abe256d01372e5e010d68065', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-06-14 09:45:00', '2024-06-14 09:45:00', '2025-06-14 09:45:00');
INSERT INTO `oauth_access_tokens` VALUES ('c2a3a2227fd0017cfe3528e53a202a7dee8d3fa6a1b9774a820e3013fa73a88c864686a5269c34ec', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 0, NULL, NULL, '2024-05-29 15:42:42', '2024-05-29 15:42:42', '2025-05-29 15:42:42');
INSERT INTO `oauth_access_tokens` VALUES ('d0ac3678dbc3332f856e6531e3cd7f9587866dd099bb4d46ce043835313fe29ea9cb7dc1b0b172d3', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-06-06 09:03:11', '2024-07-10 08:28:34', '2025-06-06 09:03:11');
INSERT INTO `oauth_access_tokens` VALUES ('d516716612a5a04282502ad05ec361d9b673833e07639709b6186d40c38762ec55c9e19fe4fafc5a', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-07-10 08:30:54', '2024-07-10 08:50:57', '2025-07-10 08:30:54');
INSERT INTO `oauth_access_tokens` VALUES ('fee7fab10ec419e53fe4774b993c39b1e9f06759a57831bb1a40699fd3b85be4173b023cfcf2b040', 1010101, '9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, '[]', 1, NULL, NULL, '2024-05-29 15:43:01', '2024-06-06 09:02:56', '2025-05-29 15:43:01');

-- ----------------------------
-- Table structure for oauth_auth_codes
-- ----------------------------
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_auth_codes_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_auth_codes
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_clients_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_clients
-- ----------------------------
INSERT INTO `oauth_clients` VALUES ('9c285cb0-d641-4ff0-b385-e72e5df0d4a9', NULL, 'Laravel Personal Access Client', 'IMEc10IWjqe8BljVl4ZL2QsiIIk8do9O4NhEqzIR', NULL, 'http://localhost', 1, 0, 0, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `oauth_clients` VALUES ('9c285cb0-db12-4651-96c1-82cd187ea31b', NULL, 'Laravel Password Grant Client', 'KYxVw2CDpfbPrhYlPyNeV5awZlMcJCiJ5r7vKsgh', 'users', 'http://localhost', 0, 1, 0, '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for oauth_personal_access_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_personal_access_clients
-- ----------------------------
INSERT INTO `oauth_personal_access_clients` VALUES (1, '9c285cb0-d641-4ff0-b385-e72e5df0d4a9', '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for oauth_refresh_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_refresh_tokens_access_token_id_index`(`access_token_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_refresh_tokens
-- ----------------------------
INSERT INTO `oauth_refresh_tokens` VALUES ('1f53d4902b0380ed9d44100b4092d6c035858014a87d272ed1835195fe127a75b32fbf7727a326a6', '73548b3e90d854c6c6c86cec3603a3d59b22ad4e2b286062adc9bfeada1df4cd2eb6d9971a2a1831', 0, '2025-08-28 13:22:29');
INSERT INTO `oauth_refresh_tokens` VALUES ('4747c57047dfffffe7ce1442fdd62ecc7c6811c70f3c1999ffaba9d13d1a690a96f6c984e4c30912', '773bd6df3843d97e7a4a53bca589f93672f30c915c7f59929f0428241b6fefe05bb88a76710ed388', 0, '2025-05-29 15:42:33');
INSERT INTO `oauth_refresh_tokens` VALUES ('58f5dc0f70b2edee37bb43f2ab7f1c64af8a3e564f8e713ab1164886905b3dc8138fbbc54f3c4ec6', '465b4cc962c72fc75f77e7a2e868669a6de13b8a79bce22238aab8a64ab6839e1f7ab5bfc32bbdc2', 0, '2025-08-16 11:59:55');
INSERT INTO `oauth_refresh_tokens` VALUES ('81964890a8fd6ec89fb9b00580b5580facc8050c876d8c8ad79bfb2caedc6ff1378675e095e78af9', '93b9dd63738c1cee5469e7bd8fe5ebe1184ac5417363f8a7a99f465ea7ca1d8c4f04c3fa74ca80f8', 0, '2025-07-23 08:27:27');
INSERT INTO `oauth_refresh_tokens` VALUES ('9574d41f4b3918d913c5ba8166d790346cbac881e7599d024bee07614220489e612266a59fe8cb93', 'c2a3a2227fd0017cfe3528e53a202a7dee8d3fa6a1b9774a820e3013fa73a88c864686a5269c34ec', 0, '2025-05-29 15:42:42');
INSERT INTO `oauth_refresh_tokens` VALUES ('99934d7fdc588ac5a9c6e1c3ceded653d8fe7398fdf6ec1373b0a766f3c211d553132edb6586d158', '5c79f61b72aab111726909657da57aa23470e36a56a1c084e52a66a1c52cef3562b19c8b3d4a062a', 0, '2025-07-10 08:51:58');
INSERT INTO `oauth_refresh_tokens` VALUES ('9f60e42b0a3f2265ece1d103b5cfb8fc018523cc611db4745ac47f510ddce3266966b0a8610a3ca6', 'ab56728040925d36fdee0aabf7809785b0a87b1436424b8eab7e9840abe256d01372e5e010d68065', 0, '2025-06-14 09:45:00');
INSERT INTO `oauth_refresh_tokens` VALUES ('aa4a381dd2d4201305fd24a4d7834a79b8d98e3a385d4247921a1ea58cf2444b2258232e10f4aa76', 'd0ac3678dbc3332f856e6531e3cd7f9587866dd099bb4d46ce043835313fe29ea9cb7dc1b0b172d3', 0, '2025-06-06 09:03:11');
INSERT INTO `oauth_refresh_tokens` VALUES ('ae75bdfa452e0ccd0c3a6849466eb7649003d73c420bd901d535a26453c6df58b089b91edfcdf028', 'd516716612a5a04282502ad05ec361d9b673833e07639709b6186d40c38762ec55c9e19fe4fafc5a', 0, '2025-07-10 08:30:54');
INSERT INTO `oauth_refresh_tokens` VALUES ('b8cf5c6a8f3577d4e6af6ae30ebf9f875fb5e3c14d5f28426a2d241ebe10e716a6c7cacfbf47a857', '948f19ed88121d839345201e07448d22e0e091a34cebaf215a9d6de225dc9922acf4c9a7c5722de2', 0, '2025-07-08 19:03:57');
INSERT INTO `oauth_refresh_tokens` VALUES ('c5270e121d502acc494ea2e9db7c5664169c5ba34ec6fe7def5da8118544286440ee2bd58759b688', '06642d7c727fb4ec48a5d20f3c9007786331aa2ac8ef771d9a507c10ab8db02113af1abfa771eb47', 0, '2025-08-28 14:46:05');
INSERT INTO `oauth_refresh_tokens` VALUES ('cdba3273f63d62fa1e6c596b6ceeac785957c3c0ae2628fa4d375e96b8f09dc749491f89e380ef4d', '1cb3ea466c5a5451600e8d12d080d511ec12475fffa922f71606d6c489451900125c3eb276239557', 0, '2025-07-10 08:30:14');
INSERT INTO `oauth_refresh_tokens` VALUES ('ea283297f3f8b3c55781e2892667f6877d8433314f2d16616d554744a2744ba4780ac2c6347f9cbb', '5bceeca4dbab4e933a6826738bde40a589264ccb6fc2da205b796ab16e6e5c2d980c0adeb9ec92d9', 0, '2025-07-05 14:19:40');
INSERT INTO `oauth_refresh_tokens` VALUES ('fcbca1f79f1158491e0548b1a37673683b27432a9d4fa90c0d9e582f7ead27ac5e8f6c0a293c288c', '591da810a9affd8481f4ca1ba24b64c7cb68f101ddefaa37eeb5afa55c0071470f07359be01f3472', 0, '2025-07-10 09:07:06');
INSERT INTO `oauth_refresh_tokens` VALUES ('ff529a6b41b9673c9f4723a5e3d5df91174ca8da3d0484eeca2797d2f6a5faacade7a0b135024278', 'fee7fab10ec419e53fe4774b993c39b1e9f06759a57831bb1a40699fd3b85be4173b023cfcf2b040', 0, '2025-05-29 15:43:01');

-- ----------------------------
-- Table structure for payment
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `invoice_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `pay` decimal(20, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment
-- ----------------------------
INSERT INTO `payment` VALUES (4, '2071724308144', 5000.00, '2024-08-23 16:07:33', '2024-08-23 16:07:33', NULL);
INSERT INTO `payment` VALUES (5, '7871724302761', 5000.00, '2024-08-26 10:59:39', '2024-08-26 10:59:39', NULL);
INSERT INTO `payment` VALUES (6, '7261724646283', 4000.00, '2024-08-26 11:28:21', '2024-08-26 11:28:21', NULL);
INSERT INTO `payment` VALUES (7, '1491724647004', 10000.00, '2024-08-26 11:37:16', '2024-08-26 13:22:12', NULL);

-- ----------------------------
-- Table structure for payment_notification
-- ----------------------------
DROP TABLE IF EXISTS `payment_notification`;
CREATE TABLE `payment_notification`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `invoice_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `transaction_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `notify_detailed_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment_notification
-- ----------------------------
INSERT INTO `payment_notification` VALUES (1, '2071724308144', 'echannel', 'settlement', '8963f23b-5b0b-418f-a38a-032c916c47bf', '{\"status_code\":\"200\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"8963f23b-5b0b-418f-a38a-032c916c47bf\",\"order_id\":\"318073451\",\"gross_amount\":\"5000.00\",\"payment_type\":\"echannel\",\"transaction_time\":\"2024-08-23 16:07:12\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"bill_key\":\"62201602654\",\"biller_code\":\"70012\",\"pdf_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v1\\/transactions\\/6d4ef75d-d897-4c66-b5dd-7cc1b23cc477\\/pdf\",\"finish_redirect_url\":\"https:\\/\\/tokoecommerce.com\\/my_custom_finish\\/?name=Customer01&order_id=318073451&status_code=200&transaction_status=settlement\"}', '2024-08-23 16:07:33', '2024-08-23 16:07:33', NULL);
INSERT INTO `payment_notification` VALUES (4, '7871724302761', 'echannel', 'pending', 'a046d93a-3945-4d07-acbb-9c3f060991c0', '{\"status_code\":\"201\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"a046d93a-3945-4d07-acbb-9c3f060991c0\",\"order_id\":\"1846706187\",\"gross_amount\":\"5000.00\",\"payment_type\":\"echannel\",\"transaction_time\":\"2024-08-26 10:59:16\",\"transaction_status\":\"pending\",\"fraud_status\":\"accept\",\"bill_key\":\"158906627964\",\"biller_code\":\"70012\",\"pdf_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v1\\/transactions\\/c4e44043-eb62-4609-9bb5-e37b8cfd3ab2\\/pdf\",\"finish_redirect_url\":\"https:\\/\\/tokoecommerce.com\\/my_custom_finish\\/?name=Customer01&order_id=1846706187&status_code=201&transaction_status=pending\"}', '2024-08-26 10:59:39', '2024-08-26 10:59:39', NULL);
INSERT INTO `payment_notification` VALUES (5, '7261724646283', 'echannel', 'pending', '04a65f0e-2ddd-494a-89e3-c67403ea805a', '{\"status_code\":\"201\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"04a65f0e-2ddd-494a-89e3-c67403ea805a\",\"order_id\":\"1625644796\",\"gross_amount\":\"4000.00\",\"payment_type\":\"echannel\",\"transaction_time\":\"2024-08-26 11:27:36\",\"transaction_status\":\"pending\",\"fraud_status\":\"accept\",\"bill_key\":\"360798128009\",\"biller_code\":\"70012\",\"pdf_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v1\\/transactions\\/1c8eb75c-f865-4a6d-8f1f-0511d60ac6d6\\/pdf\",\"finish_redirect_url\":\"https:\\/\\/tokoecommerce.com\\/my_custom_finish\\/?name=Customer01&order_id=1625644796&status_code=201&transaction_status=pending\"}', '2024-08-26 11:28:21', '2024-08-26 11:28:21', NULL);
INSERT INTO `payment_notification` VALUES (6, '1491724647004', 'echannel', 'settlement', 'b5e64ad8-9424-41d8-bebc-fab4c5bc1605', '{\"status_code\":\"200\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"b5e64ad8-9424-41d8-bebc-fab4c5bc1605\",\"order_id\":\"1597475235\",\"gross_amount\":\"10000.00\",\"payment_type\":\"echannel\",\"transaction_time\":\"2024-08-26 13:21:41\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"bill_key\":\"290997421869\",\"biller_code\":\"70012\",\"pdf_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v1\\/transactions\\/01140cb7-e81f-4ebb-a478-7b2eb0b62b18\\/pdf\",\"finish_redirect_url\":\"https:\\/\\/tokoecommerce.com\\/my_custom_finish\\/?name=Customer01&order_id=1597475235&status_code=200&transaction_status=settlement\"}', '2024-08-26 11:37:16', '2024-08-26 13:22:12', NULL);

-- ----------------------------
-- Table structure for post
-- ----------------------------
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` int NOT NULL,
  `alt_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1792470458501803 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post
-- ----------------------------
INSERT INTO `post` VALUES (1792470458501762, 1804704466767430, '{\"id\":{\"post0\":\"Rasanya seperti tinggal di rumah sendiri.\",\"slug\":\"fosia-hotels-&-resort\",\"title\":\"Fosia Hotels & Resort\",\"description_image\":\"\"},\"eng\":{\"post0\":\" It feels like staying in your own home.\",\"slug\":\"fosia-hotels-&-resort\",\"title\":\"Fosia Hotels & Resort\",\"description_image\":\"\"}}', NULL, 'image_posting/1804704466767430/66a30f016f9e7', 'bMjfDVtUb3A6rG2J0SF1Tlw37rIeMM-metaZm9ydHVuYS1ob3RlbDIuanBn-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:50:41', '2024-07-26 09:50:41');
INSERT INTO `post` VALUES (1792470458501763, 1804704466767430, '{\"id\":{\"post0\":\"Hotel untuk seluruh keluarga, sepanjang tahun.\",\"slug\":\"lebih-dari-sekadar-hotel...-sebuah-pengalaman.\",\"title\":\"Lebih dari sekadar hotel... sebuah pengalaman.\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Hotel for the whole family, all year round.\",\"slug\":\"more-than-a-hotel...-an-experience.\",\"title\":\"More than a hotel... an experience.\",\"description_image\":\"\"}}', NULL, 'image_posting/1804704466767430/66a30f7447859', '0z0lPRii5b3vbfB9N0vDvx4p65wphp-metaZm9ydHVuYS1ob3RlbC5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:52:36', '2024-07-26 09:52:36');
INSERT INTO `post` VALUES (1792470458501764, 1805524841159124, '{\"id\":{\"slug\":\"tentang-foshia-hotel-malioboro\",\"title\":\"Tentang Foshia Hotel Malioboro\",\"description_image\":\"\"},\"eng\":{\"slug\":\"about-foshia-hotel-malioboro\",\"title\":\"About Foshia Hotel Malioboro\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524841159124/66a30ff75c08c', 'HONMJFNiGrxa9NTFDlFLaBHv7qsrqg-metaYmdfMy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:54:47', '2024-07-26 09:54:47');
INSERT INTO `post` VALUES (1792470458501765, 1804705090926172, '{\"id\":{\"post0\":\"Anda tidak akan pergi\",\"slug\":\"selamat-datang-di-foshia-hotel\",\"title\":\"Selamat Datang di Foshia Hotel\",\"description_image\":\"\"},\"eng\":{\"post0\":\"You\'ll Never Want To Leave\",\"slug\":\"welcome-to-fosia-hotel\",\"title\":\"Welcome to Fosia Hotel\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:56:42', '2024-07-26 09:56:42');
INSERT INTO `post` VALUES (1792470458501766, 1805523269857973, '{\"id\":{\"slug\":\"ramah-layanan\",\"title\":\"Ramah Layanan\",\"description_image\":\"\"},\"eng\":{\"slug\":\"friendly-service\",\"title\":\"Friendly Service\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:57:42', '2024-07-26 09:57:42');
INSERT INTO `post` VALUES (1792470458501767, 1805523269857973, '{\"id\":{\"slug\":\"makan-siang\",\"title\":\"Makan Siang\",\"description_image\":\"\"},\"eng\":{\"slug\":\"get-breakfast\",\"title\":\"Get Breakfast\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:58:06', '2024-07-26 09:58:06');
INSERT INTO `post` VALUES (1792470458501768, 1805523269857973, '{\"id\":{\"slug\":\"layanan-pemindahan\",\"title\":\"Layanan Pemindahan\",\"description_image\":\"\"},\"eng\":{\"slug\":\"transfer-services\",\"title\":\"Transfer Services\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:58:35', '2024-07-26 09:58:35');
INSERT INTO `post` VALUES (1792470458501769, 1805523269857973, '{\"id\":{\"slug\":\"suites-&-spa\",\"title\":\"Suites & SPA\",\"description_image\":\"\"},\"eng\":{\"slug\":\"\",\"title\":\"\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:59:18', '2024-07-26 09:59:18');
INSERT INTO `post` VALUES (1792470458501770, 1805523269857973, '{\"id\":{\"slug\":\"kamar-yang-nyaman\",\"title\":\"Kamar yang Nyaman\",\"description_image\":\"\"},\"eng\":{\"slug\":\"cozy-rooms\",\"title\":\"Cozy Rooms\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 09:59:55', '2024-07-26 09:59:55');
INSERT INTO `post` VALUES (1792470458501771, 1805523513313374, '{\"id\":{\"post0\":\"Fosia Hotel, Hotel yang Paling Direkomendasikan di Seluruh Dunia\",\"post1\":\"<p>Berawal dari kata Sanskerta \\u201cFortuna,\\u201d yang berarti \\u201ckeberuntungan\\u201d atau \\u201cnasib baik,\\u201d FOSIA mencerminkan komitmen terhadap kesejahteraan emosional dan kemakmuran. Didirikan pada tahun 2023, FOSIA beroperasi sebagai perusahaan induk yang terkemuka di sektor perhotelan, dengan berbagai merek hotel terkemuka. Dengan moto \\u201cTUMBUH MELALUI INOVASI,\\u201d FOSIA berusaha untuk mendefinisikan ulang pengalaman tamu melalui inovasi terobosan dalam layanan, fasilitas, desain interior, dan penawaran acara, memastikan setiap menginap menjadi pengalaman yang unik, berkesan, dan memperkaya.<\\/p>\",\"slug\":\"tentang-foshia-hotel\",\"title\":\"Tentang Foshia Hotel\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Fosia Hotel the Most Recommended Hotel \",\"post1\":\"<p>Derived from the Sanskrit word \\u201cFortuna,\\u201d meaning \\u201cluck\\u201d or \\u201cfortune,\\u201d FOSIA embodies a commitment to emotional well-being and prosperity. Established in 2023, FOSIA operates as a distinguished holding company within the hospitality sector, featuring multiple esteemed hotel brands. Guided by the motto \\u201cGROW THROUGH INNOVATION,\\u201d FOSIA strives to redefine guest experiences through groundbreaking innovations in service, facilities, interior design, and event offerings, ensuring each stay is uniquely memorable and enriching.<\\/p>\",\"slug\":\"about-foshia-hotel\",\"title\":\"About Foshia Hotel\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:04:21', '2024-07-26 10:04:21');
INSERT INTO `post` VALUES (1792470458501772, 1805523722012300, '{\"id\":{\"post0\":\"Pelanggan yang senang\",\"slug\":\"testimoni\",\"title\":\"Testimoni\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Happy Customer\",\"slug\":\"testimony\",\"title\":\"Testimony\",\"description_image\":\"\"}}', NULL, 'image_posting/1805523722012300/66a31283ad1f8', 'OT2Cx6uGRL0HplOQfh65cBGVMYs8ON-metadGVzdGltb255LWltZy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:05:39', '2024-07-26 10:06:23');
INSERT INTO `post` VALUES (1792470458501773, 1805523974676966, '{\"id\":{\"post0\":\"Pelanggan\",\"post1\":\"Hotel bagus sekali\",\"slug\":\"tina-sinaga\",\"title\":\"Tina Sinaga\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Customer\",\"post1\":\"Good Hotel\",\"slug\":\"tina-sinaga\",\"title\":\"Tina Sinaga\",\"description_image\":\"\"}}', NULL, 'image_posting/1805523974676966/66a313d93e802', '14sGolzzrWEZxLkOila2rRVBrUySSL-metaVGFucGEgSnVkdWwuanBn-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:11:21', '2024-07-26 10:12:01');
INSERT INTO `post` VALUES (1792470458501774, 1805523974676966, '{\"id\":{\"post0\":\"Pelanggan\",\"post1\":\"Hotel berkelas\",\"slug\":\"dina-kristiani\",\"title\":\"Dina Kristiani\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Customer\",\"post1\":\"Class Hotel\",\"slug\":\"dina-kristiani\",\"title\":\"Dina Kristiani\",\"description_image\":\"\"}}', NULL, 'image_posting/1805523974676966/66a313f5f0479', 'YsqbKHEKZoYLnPm2msdPIjPp6Qzgri-metaaW1hZ2VzLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:11:50', '2024-07-26 10:11:50');
INSERT INTO `post` VALUES (1792470458501775, 1805609723461713, '{\"id\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C88UYcfyVoW\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"gambar-1\",\"title\":\"Gambar 1\",\"description_image\":\"\"},\"eng\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C88UYcfyVoW\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"picture-1\",\"title\":\"Picture 1\",\"description_image\":\"\"}}', NULL, 'image_posting/1805609723461713/66a3161ef14d1', 'v3cKsKDIi0Zb5yUuUpwwCD3GYt0vUE-metaNDQ5Nzg4Mzk5XzE4MzUyMjE0NDUyMTA5NTA5XzQ4NjUyMjY2NjU4ODg1Njg5ODNfbi5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:21:03', '2024-07-26 10:22:29');
INSERT INTO `post` VALUES (1792470458501776, 1805609723461713, '{\"id\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C9BtAhEyxbw\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"gambar-2\",\"title\":\"Gambar 2\",\"description_image\":\"\"},\"eng\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C9BtAhEyxbw\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"picture-2\",\"title\":\"Picture 2\",\"description_image\":\"\"}}', NULL, 'image_posting/1805609723461713/66a3166a07770', 'IjpGqtWiFdSkDWcGYJKLqb1Ew1RmIz-metaNDUwMDU1MTUyXzE4MzUyNTE3Nzk0MTA5NTA5XzkwMzUyOTA4ODI1NDQ0MTEzNDlfbi5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:22:18', '2024-07-26 10:22:18');
INSERT INTO `post` VALUES (1792470458501777, 1805609723461713, '{\"id\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C8Zl523yBA8\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"gambar-3\",\"title\":\"Gambar 3\",\"description_image\":\"\"},\"eng\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C8Zl523yBA8\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"picture-3\",\"title\":\"Picture 3\",\"description_image\":\"\"}}', NULL, 'image_posting/1805609723461713/66a316a0a1895', 'lOZj8YUi8twZO6uFqbpnb76AtQslJc-metaNDQ4NjY0OTAzXzE2NTE3ODAyMDIzMTM5ODBfNjQyMzM4NzY0NDk4NDA4Mzc4OV9uLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:23:12', '2024-07-26 10:23:12');
INSERT INTO `post` VALUES (1792470458501778, 1805609723461713, '{\"id\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C5dgoEkxb8u\\/?utm_source=ig_web_copy_link\",\"slug\":\"gambar-4\",\"title\":\"Gambar 4\",\"description_image\":\"\"},\"eng\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C5dgoEkxb8u\\/?utm_source=ig_web_copy_link\",\"slug\":\"picture-4\",\"title\":\"Picture 4\",\"description_image\":\"\"}}', NULL, 'image_posting/1805609723461713/66a316f2d8e1f', '5VihzDQ8psb1LMJcqr7Anhx6Kza9bW-metaNDM2OTE1NzY1XzE4MzQwMTQzMDQ2MTA5NTA5Xzg1NTM4OTkxNTc1NzM0OTQzMjBfbi5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:24:34', '2024-07-26 10:24:34');
INSERT INTO `post` VALUES (1792470458501779, 1805609723461713, '{\"id\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C4slv3DydQO\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"gambar-5\",\"title\":\"Gambar 5\",\"description_image\":\"\"},\"eng\":{\"post0\":\"https:\\/\\/www.instagram.com\\/p\\/C4slv3DydQO\\/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==\",\"slug\":\"picture-5\",\"title\":\"Picture 5\",\"description_image\":\"\"}}', NULL, 'image_posting/1805609723461713/66a3172fed8c1', 'tASH5WCspiG9vD3lyW7yDIHHvyTx5D-metaNDM0MDY4MTYxXzExODY2NDc5ODU2NTMzNDlfNzU3NjA5NzQ5MTM1NTg0NzE5OF9uLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:25:36', '2024-07-26 10:25:36');
INSERT INTO `post` VALUES (1792470458501780, 1805524763324126, '{\"id\":{\"slug\":\"ruangan\",\"title\":\"Ruangan\",\"description_image\":\"\"},\"eng\":{\"slug\":\"rooms\",\"title\":\"Rooms\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524763324126/66a3177826577', 'JHql19uEc3tdamFdPlwjvRiao3F73n-metaYmdfMy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:26:48', '2024-07-26 10:26:48');
INSERT INTO `post` VALUES (1792470458501781, 1805520543632017, '{\"id\":{\"post0\":\"Hotel Master\'s Rooms\",\"slug\":\"kamar-fosia-hotel-\",\"title\":\"Kamar Fosia Hotel \",\"description_image\":\"\"},\"eng\":{\"post0\":\"Hotel Master\'s Rooms\",\"slug\":\"fosia-hotel-rooms\",\"title\":\"Fosia Hotel Rooms\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:28:20', '2024-07-26 10:28:20');
INSERT INTO `post` VALUES (1792470458501782, 1805520734980055, '{\"id\":{\"post0\":\"<div class=\\\"col-md-12 room-single mt-4 mb-5 ftco-animate fadeInUp ftco-animated\\\">\\n<h2 class=\\\"mb-4\\\">King Room<\\/h2>\\n<p>Selamat datang di <strong>King Room<\\/strong> kami, oasis kemewahan dan kenyamanan yang dirancang khusus untuk pengalaman menginap yang tak terlupakan. Setiap detail dalam kamar ini dipilih dengan cermat untuk menawarkan keanggunan dan fasilitas premium yang memenuhi ekspektasi tertinggi.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Orang<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Keindahan kota<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<\\/div>\\n<p>Deskripsi ini dirancang untuk memberikan gambaran yang jelas tentang apa yang bisa diharapkan dari sebuah luxury room dan untuk menarik minat calon tamu dengan menonjolkan fitur-fitur mewah dan layanan yang ditawarkan.<\\/p>\\n<\\/div>\",\"post1\":\"1.500.000\",\"slug\":\"king-room\",\"title\":\"King Room\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<div class=\\\"col-md-12 room-single mt-4 mb-5 ftco-animate fadeInUp ftco-animated\\\">\\n<h2 class=\\\"mb-4\\\">King Room<\\/h2>\\n<p>Welcome to our King Room, an oasis of luxury and comfort specifically designed for an unforgettable stay.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Persons<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Beautiful city<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<\\/div>\\n<p>Every detail in this room has been meticulously chosen to offer elegance and premium amenities that meet the highest expectations. This description is crafted to provide a clear picture of what to expect from a luxury room and to attract potential guests by highlighting the lavish features and services offered.<\\/p>\\n<\\/div>\",\"post1\":\"1.500.000\",\"slug\":\"king-room\",\"title\":\"King Room\",\"description_image\":\"\"}}', NULL, 'image_posting/1805520734980055/66a3193165896', 'rjx20RZOzZR00UHxC49YnwxOYsJUTY-metacm9vbS02LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:34:09', '2024-07-30 09:07:53');
INSERT INTO `post` VALUES (1792470458501783, 1805520734980055, '{\"id\":{\"post0\":\"<p>Selamat datang di <strong>Suite Room<\\/strong> kami, sebuah ruang yang dirancang untuk memberikan pengalaman menginap yang luar biasa dan mewah. Suite ini menawarkan kombinasi sempurna antara kenyamanan dan elegansi, menjadikannya pilihan ideal untuk tamu yang mencari pengalaman menginap yang lebih luas dan eksklusif.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Orang<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Pemandangan Kota<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<p>Deskripsi ini dirancang untuk memberikan gambaran menyeluruh tentang fitur dan layanan dari suite room, serta menonjolkan kemewahan dan kenyamanan yang ditawarkan untuk menarik minat calon tamu.<\\/p>\\n<\\/div>\",\"post1\":\"1.200.000\",\"slug\":\"suite-room\",\"title\":\"Suite Room\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Welcome to our Suite Room, a space designed to offer an exceptional and luxurious stay. This suite provides the perfect combination of comfort and elegance, making it an ideal choice for guests seeking a more spacious and exclusive lodging experience.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Orang<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Pemandangan Kota<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<div class=\\\"flex flex-grow flex-col max-w-full\\\">\\n<div class=\\\"min-h-[20px] text-message flex w-full flex-col items-end gap-2 whitespace-pre-wrap break-words [.text-message+&amp;]:mt-5 overflow-x-auto\\\" dir=\\\"auto\\\" data-message-author-role=\\\"assistant\\\" data-message-id=\\\"10dadfd8-e3e8-435c-81c9-a9d01df93bd9\\\">\\n<div class=\\\"flex w-full flex-col gap-1 empty:hidden first:pt-[3px]\\\">\\n<div class=\\\"markdown prose w-full break-words dark:prose-invert dark\\\">\\n<p>This description is crafted to provide a comprehensive overview of the features and services of the suite room, highlighting the luxury and comfort offered to attract potential guests.<\\/p>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<div class=\\\"mt-1 flex gap-3 empty:hidden -ml-2\\\">\\n<div class=\\\"items-center justify-start rounded-xl p-1 flex\\\">\\n<div class=\\\"flex items-center\\\">\\u00a0<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\",\"post1\":\"1.200.000\",\"slug\":\"suite-room\",\"title\":\"Suite Room\",\"description_image\":\"\"}}', NULL, 'image_posting/1805520734980055/66a31a0817cd1', 'fCMUGtnFB2g8MqTN2MhMmgZq1Ue2Le-metacm9vbS0xLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:37:44', '2024-07-26 10:37:44');
INSERT INTO `post` VALUES (1792470458501784, 1805520734980055, '{\"id\":{\"post0\":\"<p>Selamat datang di <strong>Family Room<\\/strong> kami, dirancang khusus untuk memenuhi kebutuhan keluarga yang mencari kenyamanan dan ruang lebih saat menginap. Kamar ini menawarkan lingkungan yang hangat dan ramah keluarga dengan berbagai fasilitas yang memastikan setiap anggota keluarga merasa nyaman dan terawat.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Persons<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Sea View<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<p>Deskripsi ini dirancang untuk memberikan gambaran menyeluruh tentang fitur dan layanan dari Family Room, menonjolkan kenyamanan dan fasilitas yang disediakan untuk memenuhi kebutuhan keluarga dan menarik minat tamu yang bepergian bersama anak-anak.<\\/p>\\n<\\/div>\",\"post1\":\"1.000.000\",\"slug\":\"family-room\",\"title\":\"Family Room\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Welcome to our Family Room, designed specifically to meet the needs of families seeking comfort and extra space during their stay. This room offers a warm and family-friendly environment with various amenities to ensure that every family member feels comfortable and well-cared for.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Persons<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Sea View<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<p>This description is crafted to provide a comprehensive overview of the features and services of the Family Room, highlighting the comfort and amenities provided to meet the needs of families and attract guests traveling with children.<\\/p>\\n<\\/div>\",\"post1\":\"1.000.000\",\"slug\":\"family-room\",\"title\":\"Family Room\",\"description_image\":\"\"}}', NULL, 'image_posting/1805520734980055/66a31aa1bb720', 'aweOWI4dEsv35FEHEKy57JPDaSdwvt-metacm9vbS0yLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:40:17', '2024-07-26 10:40:17');
INSERT INTO `post` VALUES (1792470458501785, 1805520734980055, '{\"id\":{\"post0\":\"<p>Selamat datang di <strong>Deluxe Room<\\/strong> kami, tempat di mana kenyamanan dan gaya berpadu untuk menciptakan pengalaman menginap yang istimewa. Kamar ini menawarkan peningkatan kualitas dan fasilitas dibandingkan dengan kamar standar, menjadikannya pilihan yang ideal untuk tamu yang menginginkan lebih banyak ruang dan kemewahan.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Persons<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Sea View<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<p>Deskripsi ini bertujuan untuk memberikan gambaran menyeluruh tentang fitur dan fasilitas dari Deluxe Room, menyoroti elemen-elemen mewah dan nyaman yang ditawarkan untuk menarik minat calon tamu.<\\/p>\\n<\\/div>\",\"post1\":\"900.000\",\"slug\":\"deluxe-room\",\"title\":\"Deluxe Room\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Welcome to our Deluxe Room, where comfort and style blend to create a special stay experience. This room offers enhanced quality and amenities compared to standard rooms, making it an ideal choice for guests seeking more space and luxury.<\\/p>\\n<div class=\\\"d-md-flex mt-5 mb-5\\\">\\n<ul class=\\\"list\\\">\\n<li>Max: 3 Persons<\\/li>\\n<li>Size: 45 m2<\\/li>\\n<\\/ul>\\n<ul class=\\\"list ml-md-5\\\">\\n<li>View: Sea View<\\/li>\\n<li>Bed: 1<\\/li>\\n<\\/ul>\\n<p>This description aims to provide a comprehensive overview of the features and amenities of the Deluxe Room, highlighting the luxurious and comfortable elements offered to attract potential guests.<\\/p>\\n<\\/div>\",\"post1\":\"900.000\",\"slug\":\"deluxe-room\",\"title\":\"Deluxe Room\",\"description_image\":\"\"}}', NULL, 'image_posting/1805520734980055/66a31b1c098c7', 'syHxkmQ8KR22zlnTmlh6Aup4cKOQmD-metacm9vbS0zLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:42:20', '2024-07-26 10:42:20');
INSERT INTO `post` VALUES (1792470458501786, 1805524900917400, '{\"id\":{\"slug\":\"restoran\",\"title\":\"Restoran\",\"description_image\":\"\"},\"eng\":{\"slug\":\"restaurant\",\"title\":\"Restaurant\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524900917400/66a31b80bcdd6', 'EzrJqMzkcK0p9xvCrNBvpBrjt59muD-metaYmdfMy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:44:00', '2024-07-26 10:44:00');
INSERT INTO `post` VALUES (1792470458501787, 1805521276044259, '{\"id\":{\"post0\":\"Fosia Hotel Restoran\",\"post1\":\"<p>Selamat datang di <strong>Restoran Fosia Hotel<\\/strong> kami, destinasi kuliner yang menawarkan pengalaman makan yang tak terlupakan dalam suasana yang elegan dan menyenangkan. Restoran kami dirancang untuk menyajikan hidangan berkualitas tinggi dengan layanan yang ramah dan profesional, memastikan setiap kunjungan Anda menjadi pengalaman yang memuaskan.<\\/p>\",\"slug\":\"tentang-restoran-fosia-hotel\",\"title\":\"Tentang Restoran Fosia Hotel\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Fosia Hotel Restaurant\",\"post1\":\"<p>Welcome to our Fosia Hotel Restaurant, a culinary destination that offers an unforgettable dining experience in an elegant and pleasant atmosphere. Our restaurant is designed to serve high-quality dishes with friendly and professional service, ensuring that each visit becomes a truly satisfying experience.<\\/p>\",\"slug\":\"tentang-restoran-fosia-hotel\",\"title\":\"Tentang Restoran Fosia Hotel\",\"description_image\":\"\"}}', NULL, 'image_posting/1805521276044259/66a31de02da77', 'U5viWb0FrgtxSI1K9Bsqz98tAZx5IV-metacm9vbS01LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:54:08', '2024-07-31 13:30:15');
INSERT INTO `post` VALUES (1792470458501788, 1805612394885444, '{\"id\":{\"post0\":\"Menu Istimewa Kami\",\"slug\":\"fosia-hotel-resto-menu\",\"title\":\"Fosia Hotel Resto Menu\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Our Special Menu\",\"slug\":\"fosia-hotel-resto-menu\",\"title\":\"Fosia Hotel Resto Menu\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-26 10:57:51', '2024-07-31 08:55:58');
INSERT INTO `post` VALUES (1792470458501789, 1805521412957656, '{\"id\":{\"post0\":\"Filet daging sapi yang lembut dengan saus red wine reduction.\",\"post1\":\"40.000\",\"slug\":\"grilled-filet-mignon\",\"title\":\"Grilled Filet Mignon\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Tender beef filet with a red wine reduction.\",\"post1\":\"40.000\",\"slug\":\"grilled-filet-mignon\",\"title\":\"Grilled Filet Mignon\",\"description_image\":\"\"}}', NULL, 'image_posting/1805521412957656/66a31f88d6053', 'oJSp0rIQGdJtTeh1Rr8WfhZRfsAgx5-metabWVudS00LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:01:12', '2024-07-26 11:23:08');
INSERT INTO `post` VALUES (1792470458501790, 1805521412957656, '{\"id\":{\"post0\":\"Daging lobster dan saus keju krimi di atas pasta.\",\"post1\":\"300.000\",\"slug\":\"chef\\u2019s-special-lobster-mac-&-cheese\",\"title\":\"Chef\\u2019s Special Lobster Mac & Cheese\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Lobster meat and creamy cheese sauce over pasta.\",\"post1\":\"300.000\",\"slug\":\"chef\\u2019s-special-lobster-mac-&-cheese\",\"title\":\"Chef\\u2019s Special Lobster Mac & Cheese\",\"description_image\":\"\"}}', NULL, 'image_posting/1805521412957656/66a3200a1a0b5', '8pdStH9t9OTIacmMnaGRRwBggNFE2b-metabWVudS05LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:03:22', '2024-07-26 11:03:22');
INSERT INTO `post` VALUES (1792470458501791, 1805521412957656, '{\"id\":{\"post0\":\"Daun teh hijau yang lembut dengan aroma bunga jasmine untuk rasa yang ringan dan floral.\",\"post1\":\"20.000\",\"slug\":\"green-tea-with-jasmine\",\"title\":\"Green Tea with Jasmine\",\"description_image\":\"\"},\"eng\":{\"post1\":\"20.000\",\"post0\":\"Soft green tea leaves with the aroma of jasmine flowers for a light and floral taste.\",\"slug\":\"green-tea-with-jasmine\",\"title\":\"Green Tea With Jasmine\",\"description_image\":\"\"}}', NULL, 'image_posting/1805521412957656/66a3248e46143', 'LcGN5H6wq45gVziXUYnzx9xHtOScev-metabWVudS03LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:22:38', '2024-07-31 09:55:21');
INSERT INTO `post` VALUES (1792470458501792, 1805524499252413, '{\"id\":{\"slug\":\"blog\",\"title\":\"Blog\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524499252413/66a324f6c03b5', 'f9h8amOs4fVq82yEfflIuyOwcJAD8g-metaYmdfMy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:24:22', '2024-07-26 11:24:22');
INSERT INTO `post` VALUES (1792470458501793, 1805524670278517, '{\"id\":{\"post0\":\"<p>Blog post konten<\\/p>\",\"post1\":\"2024-07-26\",\"slug\":\"blog-post-1\",\"title\":\"Blog post 1\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Blog post content 1\\u00a0<\\/p>\",\"post1\":\"2024-07-26\",\"slug\":\"blog-post-1\",\"title\":\"Blog post 1\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a3257e1837f', 'Q2SuQ3UwPoHPYC87RB9ThyzzpwBovg-metaaW1hZ2VfMS5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:26:38', '2024-07-26 14:23:08');
INSERT INTO `post` VALUES (1792470458501794, 1805524670278517, '{\"id\":{\"post0\":\"<p>Selamat datang di blog kami! Di sini, kami akan membahas berbagai topik yang berkaitan dengan hotel dan fasilitas, semoga layanan kami dapat membuat anda puas.<\\/p>\",\"post1\":\"2024-07-27\",\"slug\":\"blog-post-2\",\"title\":\"Blog Post 2\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Welcome to our blog! Here, we will discuss various topics related to hotels and their amenities, and we hope our services will meet your expectations.<\\/p>\",\"post1\":\"2024-07-27\",\"slug\":\"blog-post-2\",\"title\":\"Blog Post 2\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a325e9a2daf', 'dSQL4vLBpSv1ZgUvCVzvwvUhGeC2P0-metaaW1hZ2VfMi5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:28:05', '2024-07-26 11:28:25');
INSERT INTO `post` VALUES (1792470458501795, 1805524670278517, '{\"id\":{\"post0\":\"<p>Stay updated on culinary events, food festivals, and special activities in your area. We will provide information on how to participate and what you can expect.<\\/p>\",\"post1\":\"2024-07-28\",\"slug\":\"blog-post-3\",\"title\":\"Blog Post 3\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>Stay updated on culinary events, food festivals, and special activities in your area. We will provide information on how to participate and what you can expect.<\\/p>\",\"post1\":\"2024-07-28\",\"slug\":\"blog-post-3\",\"title\":\"Blog Post 3\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a32630d916d', 'ZnLhv6KHteEuNL0kLUHogI43IZX9x7-metaaW1hZ2VfMy5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-26 11:29:36', '2024-07-26 11:29:36');
INSERT INTO `post` VALUES (1792470458501796, 1805881306889511, '{\"id\":{\"post0\":\"Instagram\",\"slug\":\"foto\",\"title\":\"Foto\",\"description_image\":\"\"},\"eng\":{\"post0\":\"Instagram\",\"slug\":\"photo\",\"title\":\"Photo\",\"description_image\":\"\"}}', NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, 1, '2024-07-29 10:11:10', '2024-07-29 10:11:10');
INSERT INTO `post` VALUES (1792470458501797, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post 4<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-4\",\"title\":\"Blog post 4\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post 4<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-4\",\"title\":\"Blog post 4\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73ce2c01a8', 'YemuSVyhd1O4VO1ftWzRmvP7mEkgCL-metaYWJvdXQtMi5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 13:55:30', '2024-07-29 13:55:30');
INSERT INTO `post` VALUES (1792470458501798, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post 5<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-5\",\"title\":\"Blog post 5\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post 5<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-5\",\"title\":\"Blog post 5\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73cfd307f6', 'v6FxgapajS42iWdSWlmOkfXRnHWweV-metaYWJvdXQtMS5qcGc=-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 13:55:57', '2024-07-29 13:55:57');
INSERT INTO `post` VALUES (1792470458501799, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post 6<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-6\",\"title\":\"blog post 6\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post 6<\\/p>\",\"post1\":\"2024-07-30\",\"slug\":\"blog-post-6\",\"title\":\"blog post 6\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73d66072ff', 'a0XEF06K63z1t3c3ioqMIA0TaLyxKI-metacm9vbS02LmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 13:57:42', '2024-07-29 13:57:42');
INSERT INTO `post` VALUES (1792470458501800, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73df90635c', 'NXNZbhgU5r6sWqP7KIB332ucWrEM9s-metaQWxsLUFib3V0LVVzLmpwZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 14:00:09', '2024-07-29 14:00:09');
INSERT INTO `post` VALUES (1792470458501801, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73e52a510b', 'STUuOobpHZcusnRyfA2SIdvjNqxJjJ-metaV2hhdHNBcHAtSW1hZ2UtMjAyNC0wNy0wMy1hdC0wOS4yNi4zOC1zY2FsZWQuanBlZw==-.jpg', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 14:01:38', '2024-07-29 14:01:38');
INSERT INTO `post` VALUES (1792470458501802, 1805524670278517, '{\"id\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"},\"eng\":{\"post0\":\"<p>blog post<\\/p>\",\"post1\":\"2024-07-31\",\"slug\":\"blog-post-7\",\"title\":\"Blog post 7\",\"description_image\":\"\"}}', NULL, 'image_posting/1805524670278517/66a73e6d321b4', 'E7jOLl5dT5WSWUEsahUWKKFOUDF79h-metaMTI4Nzg4NTk0LWY5MjU1Y2JlLThhOWQtNDE3ZS05NmZhLTNhMDdmMjA2MzMyMi5wbmc=-.png', 2, NULL, NULL, 1, NULL, 1, '2024-07-29 14:02:05', '2024-07-29 14:02:05');

-- ----------------------------
-- Table structure for post_has_category
-- ----------------------------
DROP TABLE IF EXISTS `post_has_category`;
CREATE TABLE `post_has_category`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `tags_id` bigint UNSIGNED NOT NULL,
  `parameter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_has_category
-- ----------------------------
INSERT INTO `post_has_category` VALUES (31, 1792470458501794, 1805625039761164, 'post0', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_has_category` VALUES (32, 1792470458501795, 1805625053969385, 'post0', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_has_category` VALUES (33, 1792470458501793, 1805625020984993, 'post0', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for post_image
-- ----------------------------
DROP TABLE IF EXISTS `post_image`;
CREATE TABLE `post_image`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1805967297538566 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_image
-- ----------------------------
INSERT INTO `post_image` VALUES (1794211298600342, 1792426811806891, 1792427062530363, 'ok', 'ok', 'image_posting/1792426811806891/1792427062530363/65fd35f1ab6ec', 'u34jNon8KToXeLnOzdhGvmU9S8sIBH-metaYTNhNjQ1MzczMGU0NDRlNTg3MmIwMjJlYWFhZjBlZmYucG5n-.png', '\"test\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1794283559547405, 1792426811806891, 1792470458501574, 'This is image', 'this-is-image', 'image_posting/1792426811806891/1792470458501574/65fe432315d33', 'j3uiEo85bJl1WWE16kWNlCh1IZzSXP-metaYTNhNjQ1MzczMGU0NDRlNTg3MmIwMjJlYWFhZjBlZmYucG5n-.png', '\"image description\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805875801242370, 1805521276044259, 1792470458501787, 'Gambar 1', 'gambar-1', 'image_posting/1805521276044259/1792470458501787/66a6f39a40608', 'FYADnUrr24aydcopnm5Sr4pzRHiomM-metacm9vbS0xLmpwZw==-.jpg', '\"gambar 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805875817407525, 1805521276044259, 1792470458501787, 'Gambar 2', 'gambar-2', 'image_posting/1805521276044259/1792470458501787/66a6f3a9adeb4', 'vLiBIQMIc1JAKDmTPkCFRLhoGSCORh-metacm9vbS0yLmpwZw==-.jpg', '\"gambar 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805875830760263, 1805521276044259, 1792470458501787, 'Gambar 3', 'gambar-3', 'image_posting/1805521276044259/1792470458501787/66a6f3b669c7e', 'XaY1TZ14moKNffihYEjKY4VszitSbm-metacm9vbS0zLmpwZw==-.jpg', '\"gambar 3\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805880158479669, 1805523513313374, 1792470458501771, 'Gambar 1', 'gambar-1', 'image_posting/1805523513313374/1792470458501771/66a703d5a2c7a', 'qiedtTZXd2xibcDWVFzWFj1jfZRbm3-metaYWJvdXQtMS5qcGc=-.jpg', '\"gambar 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805880175424652, 1805523513313374, 1792470458501771, 'Gambar 2', 'gambar-2', 'image_posting/1805523513313374/1792470458501771/66a703e5cee1b', 'he5xTFTHkpc4TjLO50xvPIoEQwMl1z-metaYWJvdXQtMi5qcGc=-.jpg', '\"gambar 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966633791983, 1805520734980055, 1792470458501782, 'Ruangan 1', 'ruangan-1', 'image_posting/1805520734980055/1792470458501782/66a845faeba98', '4uUGe8j4gHbTV16bupaYrUDn0ZLrRp-metacm9vbS0zLmpwZw==-.jpg', '\"ruangan 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966647769673, 1805520734980055, 1792470458501782, 'Ruangan 2', 'ruangan-2', 'image_posting/1805520734980055/1792470458501782/66a8460843529', 'j0w24BKbldk520xjfUJ1uTzL1NSZEB-metacm9vbS00LmpwZw==-.jpg', '\"ruangan 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966665171656, 1805520734980055, 1792470458501782, 'Ruangan 3', 'ruangan-3', 'image_posting/1805520734980055/1792470458501782/66a84618dbbce', 'EvDAyhb5ZuL8fg1R5uGSnu41Kz3tpG-metacm9vbS01LmpwZw==-.jpg', '\"ruangan 3\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966860917160, 1805520734980055, 1792470458501783, 'Ruangan 1', 'ruangan-1', 'image_posting/1805520734980055/1792470458501783/66a846d38922b', 'O3AFtIFfrsrhC94lGm10e8NAlWw1ao-metacGV4ZWxzLW1hcmtvLXN0b2prb3ZpYy0xMTg3NzMxLTE3MjY0MzI3LmpwZw==-.jpg', '\"ruangan 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966874688389, 1805520734980055, 1792470458501783, 'Ruangan 2', 'ruangan-2', 'image_posting/1805520734980055/1792470458501783/66a846e0ab617', 'ogDqGdI6d1uF9GapitAGVJzIacxX7u-metacGV4ZWxzLW1hcmtvLXN0b2prb3ZpYy0xMTg3NzMxLTE3MjY0MzI4LmpwZw==-.jpg', '\"ruangan 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966888986128, 1805520734980055, 1792470458501783, 'Ruangan 3', 'ruangan-3', 'image_posting/1805520734980055/1792470458501783/66a846ee4df4b', 'QNIply30qCiemWKEAo5BWHYbfuZZvU-metacGV4ZWxzLXdpbGNsZS1udW5lcy0zODcxMzc3NC0yNzE2NDk4MSgxKS5qcGc=-.jpg', '\"ruangan 3\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805966988620549, 1805520734980055, 1792470458501784, 'Ruangan 1', 'ruangan-1', 'image_posting/1805520734980055/1792470458501784/66a8474d52b28', 'HF9TXX9Gr9K4XIm7MtyIAZR07Brgnc-metacGV4ZWxzLWhleWhvLTc1ODc3MzUuanBn-.jpg', '\"ruangan 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805967000964018, 1805520734980055, 1792470458501784, 'Ruangan 2', 'ruangan-2', 'image_posting/1805520734980055/1792470458501784/66a84759184e4', '0aUeCL0nmqcn1Id9S1bh7YrnSWDzEp-metacGV4ZWxzLW1hcmtvLXN0b2prb3ZpYy0xMTg3NzMxLTE3MjY0Mjc1LmpwZw==-.jpg', '\"ruangan 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805967014896571, 1805520734980055, 1792470458501784, 'Ruangan 3', 'ruangan-3', 'image_posting/1805520734980055/1792470458501784/66a84766619cd', 'AYPLFfG6RUqSLPWNjP4Gs33neDQkVf-metacGV4ZWxzLW5ldG9vLTIyNjk2Nzc2LmpwZw==-.jpg', '\"ruangan 3\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805967161373841, 1805520734980055, 1792470458501785, 'Ruangan 1', 'ruangan-1', 'image_posting/1805520734980055/1792470458501785/66a847f212a84', 'Vd6DkgXcJMz6AUcZHyHgdkGvcVdpaR-metacGV4ZWxzLWhleWhvLTc1MzQyNzUuanBn-.jpg', '\"ruangan 1\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805967177226509, 1805520734980055, 1792470458501785, 'Ruangan 2', 'ruangan-2', 'image_posting/1805520734980055/1792470458501785/66a848013117a', 'fF4SsiYSmBTAw8PYVyk7W4T3n4u5Wf-metacGV4ZWxzLXdpbGNsZS1udW5lcy0zODcxMzc3NC0yNzE2NTA2OC5qcGc=-.jpg', '\"ruangan 2\"', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image` VALUES (1805967297538565, 1805520734980055, 1792470458501785, 'Ruangan 3', 'ruangan-3', 'image_posting/1805520734980055/1792470458501785/66a84873ee2bb', 'oLoHy2wAkcRTqlo9iLGGCrtlFioFzv-metacGV4ZWxzLXdpbGNsZS1udW5lcy0zODcxMzc3NC0yNzE2NTA3MS5qcGc=-.jpg', '\"ruangan 3\"', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for post_image_has_category
-- ----------------------------
DROP TABLE IF EXISTS `post_image_has_category`;
CREATE TABLE `post_image_has_category`  (
  `id_image` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_category_image` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_image`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1805967297538566 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_image_has_category
-- ----------------------------
INSERT INTO `post_image_has_category` VALUES (1805875801242370, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805875817407525, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805875830760263, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805880158479669, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805880175424652, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966633791983, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966647769673, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966665171656, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966860917160, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966874688389, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966888986128, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805966988620549, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805967000964018, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805967014896571, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805967161373841, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805967177226509, 1, NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `post_image_has_category` VALUES (1805967297538565, 1, NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for post_meta
-- ----------------------------
DROP TABLE IF EXISTS `post_meta`;
CREATE TABLE `post_meta`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `post_meta_post_id_foreign`(`post_id` ASC) USING BTREE,
  INDEX `post_meta_key_index`(`key` ASC) USING BTREE,
  CONSTRAINT `post_meta_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_meta
-- ----------------------------

-- ----------------------------
-- Table structure for post_site_configuration
-- ----------------------------
DROP TABLE IF EXISTS `post_site_configuration`;
CREATE TABLE `post_site_configuration`  (
  `id` int NOT NULL,
  `site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `call` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `coordinate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `skype` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_site_configuration
-- ----------------------------
INSERT INTO `post_site_configuration` VALUES (1, 'Personal Pages', 'Ruko Trimukti Square, Jl. Kaliurang Jl. Ngalangan Raya No.Km. 10 No. 8-10, Ngalalangan, Sardonoharjo, Kec. Ngaglik, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55581', 'pemad@gmail.com', '085623343523', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.674843017309!2d110.40198567614!3d-7.717993992299994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a591879ca7533%3A0x6358ca0d5b26c76e!2sPeMad%20International%20TranSearch%20PT%20-%20Translation!5e0!3m2!1sen!2sid!4v1715063798141!5m2!1sen!2sid', 'www.twitter.com/pemad', 'www.facebook.com/pemad', 'www.instagram.com/pemad', 'www.skype.com/pemad', 'www.linkedin.com/pemad', NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for post_video
-- ----------------------------
DROP TABLE IF EXISTS `post_video`;
CREATE TABLE `post_video`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `menu_id` bigint NULL DEFAULT NULL,
  `post_id` bigint NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `link_embed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_by` int NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1805972135173027 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of post_video
-- ----------------------------
INSERT INTO `post_video` VALUES (1805972135173026, 1805520734980055, 1792470458501782, 'Video 1', 'video-1', '\"video 1\"', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/4K6Sh1tsAW4?si=oZ4nrS3Odqdt5ZvJ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', 1, NULL, 1, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for procurement_items
-- ----------------------------
DROP TABLE IF EXISTS `procurement_items`;
CREATE TABLE `procurement_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `procurement_id` smallint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double NOT NULL DEFAULT 1,
  `price` double NULL DEFAULT NULL,
  `total_price` double NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of procurement_items
-- ----------------------------

-- ----------------------------
-- Table structure for procurements
-- ----------------------------
DROP TABLE IF EXISTS `procurements`;
CREATE TABLE `procurements`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total_price` double NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `procurements_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `procurements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of procurements
-- ----------------------------

-- ----------------------------
-- Table structure for ref_countries
-- ----------------------------
DROP TABLE IF EXISTS `ref_countries`;
CREATE TABLE `ref_countries`  (
  `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `native` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `phones` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `continent` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `capital` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `currencies` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `languages` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE,
  INDEX `continent`(`continent` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 251 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_countries
-- ----------------------------
INSERT INTO `ref_countries` VALUES (1, 'AD', 'Andorra', 'Andorra', '[\"376\"]', 'EU', 'Andorra la Vella', '[\"EUR\"]', '[\"ca\"]');
INSERT INTO `ref_countries` VALUES (2, 'AE', 'United Arab Emirates', 'دولة الإمارات العربية المتحدة', '[\"971\"]', 'AS', 'Abu Dhabi', '[\"AED\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (3, 'AF', 'Afghanistan', 'افغانستان', '[\"93\"]', 'AS', 'Kabul', '[\"AFN\"]', '[\"ps\",\"uz\",\"tk\"]');
INSERT INTO `ref_countries` VALUES (4, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', '[\"1268\"]', 'NA', 'Saint John\'s', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (5, 'AI', 'Anguilla', 'Anguilla', '[\"1264\"]', 'NA', 'The Valley', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (6, 'AL', 'Albania', 'Shqipëria', '[\"355\"]', 'EU', 'Tirana', '[\"ALL\"]', '[\"sq\"]');
INSERT INTO `ref_countries` VALUES (7, 'AM', 'Armenia', 'Հայաստան', '[\"374\"]', 'AS', 'Yerevan', '[\"AMD\"]', '[\"hy\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (8, 'AO', 'Angola', 'Angola', '[\"244\"]', 'AF', 'Luanda', '[\"AOA\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (9, 'AQ', 'Antarctica', 'Antarctica', '[\"672\"]', 'AN', NULL, NULL, NULL);
INSERT INTO `ref_countries` VALUES (10, 'AR', 'Argentina', 'Argentina', '[\"54\"]', 'SA', 'Buenos Aires', '[\"ARS\"]', '[\"es\",\"gn\"]');
INSERT INTO `ref_countries` VALUES (11, 'AS', 'American Samoa', 'American Samoa', '[\"1684\"]', 'OC', 'Pago Pago', '[\"USD\"]', '[\"en\",\"sm\"]');
INSERT INTO `ref_countries` VALUES (12, 'AT', 'Austria', 'Österreich', '[\"43\"]', 'EU', 'Vienna', '[\"EUR\"]', '[\"de\"]');
INSERT INTO `ref_countries` VALUES (13, 'AU', 'Australia', 'Australia', '[\"61\"]', 'OC', 'Canberra', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (14, 'AW', 'Aruba', 'Aruba', '[\"297\"]', 'NA', 'Oranjestad', '[\"AWG\"]', '[\"nl\",\"pa\"]');
INSERT INTO `ref_countries` VALUES (15, 'AX', 'Åland', 'Åland', '[\"358\"]', 'EU', 'Mariehamn', '[\"EUR\"]', '[\"sv\"]');
INSERT INTO `ref_countries` VALUES (16, 'AZ', 'Azerbaijan', 'Azərbaycan', '[\"994\"]', 'AS', 'Baku', '[\"AZN\"]', '[\"az\"]');
INSERT INTO `ref_countries` VALUES (17, 'BA', 'Bosnia and Herzegovina', 'Bosna i Hercegovina', '[\"387\"]', 'EU', 'Sarajevo', '[\"BAM\"]', '[\"bs\",\"hr\",\"sr\"]');
INSERT INTO `ref_countries` VALUES (18, 'BB', 'Barbados', 'Barbados', '[\"1246\"]', 'NA', 'Bridgetown', '[\"BBD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (19, 'BD', 'Bangladesh', 'Bangladesh', '[\"880\"]', 'AS', 'Dhaka', '[\"BDT\"]', '[\"bn\"]');
INSERT INTO `ref_countries` VALUES (20, 'BE', 'Belgium', 'België', '[\"32\"]', 'EU', 'Brussels', '[\"EUR\"]', '[\"nl\",\"fr\",\"de\"]');
INSERT INTO `ref_countries` VALUES (21, 'BF', 'Burkina Faso', 'Burkina Faso', '[\"226\"]', 'AF', 'Ouagadougou', '[\"XOF\"]', '[\"fr\",\"ff\"]');
INSERT INTO `ref_countries` VALUES (22, 'BG', 'Bulgaria', 'България', '[\"359\"]', 'EU', 'Sofia', '[\"BGN\"]', '[\"bg\"]');
INSERT INTO `ref_countries` VALUES (23, 'BH', 'Bahrain', '‏البحرين', '[\"973\"]', 'AS', 'Manama', '[\"BHD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (24, 'BI', 'Burundi', 'Burundi', '[\"257\"]', 'AF', 'Bujumbura', '[\"BIF\"]', '[\"fr\",\"rn\"]');
INSERT INTO `ref_countries` VALUES (25, 'BJ', 'Benin', 'Bénin', '[\"229\"]', 'AF', 'Porto-Novo', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (26, 'BL', 'Saint Barthélemy', 'Saint-Barthélemy', '[\"590\"]', 'NA', 'Gustavia', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (27, 'BM', 'Bermuda', 'Bermuda', '[\"1441\"]', 'NA', 'Hamilton', '[\"BMD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (28, 'BN', 'Brunei', 'Negara Brunei Darussalam', '[\"673\"]', 'AS', 'Bandar Seri Begawan', '[\"BND\"]', '[\"ms\"]');
INSERT INTO `ref_countries` VALUES (29, 'BO', 'Bolivia', 'Bolivia', '[\"591\"]', 'SA', 'Sucre', '[\"BOB\",\"BOV\"]', '[\"es\",\"ay\",\"qu\"]');
INSERT INTO `ref_countries` VALUES (30, 'BQ', 'Bonaire', 'Bonaire', '[\"5997\"]', 'NA', 'Kralendijk', '[\"USD\"]', '[\"nl\"]');
INSERT INTO `ref_countries` VALUES (31, 'BR', 'Brazil', 'Brasil', '[\"55\"]', 'SA', 'Brasília', '[\"BRL\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (32, 'BS', 'Bahamas', 'Bahamas', '[\"1242\"]', 'NA', 'Nassau', '[\"BSD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (33, 'BT', 'Bhutan', 'ʼbrug-yul', '[\"975\"]', 'AS', 'Thimphu', '[\"BTN\",\"INR\"]', '[\"dz\"]');
INSERT INTO `ref_countries` VALUES (34, 'BV', 'Bouvet Island', 'Bouvetøya', '[\"47\"]', 'AN', NULL, '[\"NOK\"]', '[\"no\",\"nb\",\"nn\"]');
INSERT INTO `ref_countries` VALUES (35, 'BW', 'Botswana', 'Botswana', '[\"267\"]', 'AF', 'Gaborone', '[\"BWP\"]', '[\"en\",\"tn\"]');
INSERT INTO `ref_countries` VALUES (36, 'BY', 'Belarus', 'Белару́сь', '[\"375\"]', 'EU', 'Minsk', '[\"BYN\"]', '[\"be\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (37, 'BZ', 'Belize', 'Belize', '[\"501\"]', 'NA', 'Belmopan', '[\"BZD\"]', '[\"en\",\"es\"]');
INSERT INTO `ref_countries` VALUES (38, 'CA', 'Canada', 'Canada', '[\"1\"]', 'NA', 'Ottawa', '[\"CAD\"]', '[\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (39, 'CC', 'Cocos [Keeling] Islands', 'Cocos (Keeling) Islands', '[\"61\"]', 'AS', 'West Island', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (40, 'CD', 'Democratic Republic of the Congo', 'République démocratique du Congo', '[\"243\"]', 'AF', 'Kinshasa', '[\"CDF\"]', '[\"fr\",\"ln\",\"kg\",\"sw\",\"lu\"]');
INSERT INTO `ref_countries` VALUES (41, 'CF', 'Central African Republic', 'Ködörösêse tî Bêafrîka', '[\"236\"]', 'AF', 'Bangui', '[\"XAF\"]', '[\"fr\",\"sg\"]');
INSERT INTO `ref_countries` VALUES (42, 'CG', 'Republic of the Congo', 'République du Congo', '[\"242\"]', 'AF', 'Brazzaville', '[\"XAF\"]', '[\"fr\",\"ln\"]');
INSERT INTO `ref_countries` VALUES (43, 'CH', 'Switzerland', 'Schweiz', '[\"41\"]', 'EU', 'Bern', '[\"CHE\",\"CHF\",\"CHW\"]', '[\"de\",\"fr\",\"it\"]');
INSERT INTO `ref_countries` VALUES (44, 'CI', 'Ivory Coast', 'Côte d\'Ivoire', '[\"225\"]', 'AF', 'Yamoussoukro', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (45, 'CK', 'Cook Islands', 'Cook Islands', '[\"682\"]', 'OC', 'Avarua', '[\"NZD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (46, 'CL', 'Chile', 'Chile', '[\"56\"]', 'SA', 'Santiago', '[\"CLF\",\"CLP\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (47, 'CM', 'Cameroon', 'Cameroon', '[\"237\"]', 'AF', 'Yaoundé', '[\"XAF\"]', '[\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (48, 'CN', 'China', '中国', '[\"86\"]', 'AS', 'Beijing', '[\"CNY\"]', '[\"zh\"]');
INSERT INTO `ref_countries` VALUES (49, 'CO', 'Colombia', 'Colombia', '[\"57\"]', 'SA', 'Bogotá', '[\"COP\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (50, 'CR', 'Costa Rica', 'Costa Rica', '[\"506\"]', 'NA', 'San José', '[\"CRC\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (51, 'CU', 'Cuba', 'Cuba', '[\"53\"]', 'NA', 'Havana', '[\"CUC\",\"CUP\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (52, 'CV', 'Cape Verde', 'Cabo Verde', '[\"238\"]', 'AF', 'Praia', '[\"CVE\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (53, 'CW', 'Curacao', 'Curaçao', '[\"5999\"]', 'NA', 'Willemstad', '[\"ANG\"]', '[\"nl\",\"pa\",\"en\"]');
INSERT INTO `ref_countries` VALUES (54, 'CX', 'Christmas Island', 'Christmas Island', '[\"61\"]', 'AS', 'Flying Fish Cove', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (55, 'CY', 'Cyprus', 'Κύπρος', '[\"357\"]', 'EU', 'Nicosia', '[\"EUR\"]', '[\"el\",\"tr\",\"hy\"]');
INSERT INTO `ref_countries` VALUES (56, 'CZ', 'Czech Republic', 'Česká republika', '[\"420\"]', 'EU', 'Prague', '[\"CZK\"]', '[\"cs\",\"sk\"]');
INSERT INTO `ref_countries` VALUES (57, 'DE', 'Germany', 'Deutschland', '[\"49\"]', 'EU', 'Berlin', '[\"EUR\"]', '[\"de\"]');
INSERT INTO `ref_countries` VALUES (58, 'DJ', 'Djibouti', 'Djibouti', '[\"253\"]', 'AF', 'Djibouti', '[\"DJF\"]', '[\"fr\",\"ar\"]');
INSERT INTO `ref_countries` VALUES (59, 'DK', 'Denmark', 'Danmark', '[\"45\"]', 'EU', 'Copenhagen', '[\"DKK\"]', '[\"da\"]');
INSERT INTO `ref_countries` VALUES (60, 'DM', 'Dominica', 'Dominica', '[\"1767\"]', 'NA', 'Roseau', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (61, 'DO', 'Dominican Republic', 'República Dominicana', '[\"1809\",\"1829\",\"1849\"]', 'NA', 'Santo Domingo', '[\"DOP\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (62, 'DZ', 'Algeria', 'الجزائر', '[\"213\"]', 'AF', 'Algiers', '[\"DZD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (63, 'EC', 'Ecuador', 'Ecuador', '[\"593\"]', 'SA', 'Quito', '[\"USD\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (64, 'EE', 'Estonia', 'Eesti', '[\"372\"]', 'EU', 'Tallinn', '[\"EUR\"]', '[\"et\"]');
INSERT INTO `ref_countries` VALUES (65, 'EG', 'Egypt', 'مصر‎', '[\"20\"]', 'AF', 'Cairo', '[\"EGP\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (66, 'EH', 'Western Sahara', 'الصحراء الغربية', '[\"212\"]', 'AF', 'El Aaiún', '[\"MAD\",\"DZD\",\"MRU\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (67, 'ER', 'Eritrea', 'ኤርትራ', '[\"291\"]', 'AF', 'Asmara', '[\"ERN\"]', '[\"ti\",\"ar\",\"en\"]');
INSERT INTO `ref_countries` VALUES (68, 'ES', 'Spain', 'España', '[\"34\"]', 'EU', 'Madrid', '[\"EUR\"]', '[\"es\",\"eu\",\"ca\",\"gl\",\"oc\"]');
INSERT INTO `ref_countries` VALUES (69, 'ET', 'Ethiopia', 'ኢትዮጵያ', '[\"251\"]', 'AF', 'Addis Ababa', '[\"ETB\"]', '[\"am\"]');
INSERT INTO `ref_countries` VALUES (70, 'FI', 'Finland', 'Suomi', '[\"358\"]', 'EU', 'Helsinki', '[\"EUR\"]', '[\"fi\",\"sv\"]');
INSERT INTO `ref_countries` VALUES (71, 'FJ', 'Fiji', 'Fiji', '[\"679\"]', 'OC', 'Suva', '[\"FJD\"]', '[\"en\",\"fj\",\"hi\",\"ur\"]');
INSERT INTO `ref_countries` VALUES (72, 'FK', 'Falkland Islands', 'Falkland Islands', '[\"500\"]', 'SA', 'Stanley', '[\"FKP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (73, 'FM', 'Micronesia', 'Micronesia', '[\"691\"]', 'OC', 'Palikir', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (74, 'FO', 'Faroe Islands', 'Føroyar', '[\"298\"]', 'EU', 'Tórshavn', '[\"DKK\"]', '[\"fo\"]');
INSERT INTO `ref_countries` VALUES (75, 'FR', 'France', 'France', '[\"33\"]', 'EU', 'Paris', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (76, 'GA', 'Gabon', 'Gabon', '[\"241\"]', 'AF', 'Libreville', '[\"XAF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (77, 'GB', 'United Kingdom', 'United Kingdom', '[\"44\"]', 'EU', 'London', '[\"GBP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (78, 'GD', 'Grenada', 'Grenada', '[\"1473\"]', 'NA', 'St. George\'s', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (79, 'GE', 'Georgia', 'საქართველო', '[\"995\"]', 'AS', 'Tbilisi', '[\"GEL\"]', '[\"ka\"]');
INSERT INTO `ref_countries` VALUES (80, 'GF', 'French Guiana', 'Guyane française', '[\"594\"]', 'SA', 'Cayenne', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (81, 'GG', 'Guernsey', 'Guernsey', '[\"44\"]', 'EU', 'St. Peter Port', '[\"GBP\"]', '[\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (82, 'GH', 'Ghana', 'Ghana', '[\"233\"]', 'AF', 'Accra', '[\"GHS\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (83, 'GI', 'Gibraltar', 'Gibraltar', '[\"350\"]', 'EU', 'Gibraltar', '[\"GIP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (84, 'GL', 'Greenland', 'Kalaallit Nunaat', '[\"299\"]', 'NA', 'Nuuk', '[\"DKK\"]', '[\"kl\"]');
INSERT INTO `ref_countries` VALUES (85, 'GM', 'Gambia', 'Gambia', '[\"220\"]', 'AF', 'Banjul', '[\"GMD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (86, 'GN', 'Guinea', 'Guinée', '[\"224\"]', 'AF', 'Conakry', '[\"GNF\"]', '[\"fr\",\"ff\"]');
INSERT INTO `ref_countries` VALUES (87, 'GP', 'Guadeloupe', 'Guadeloupe', '[\"590\"]', 'NA', 'Basse-Terre', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (88, 'GQ', 'Equatorial Guinea', 'Guinea Ecuatorial', '[\"240\"]', 'AF', 'Malabo', '[\"XAF\"]', '[\"es\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (89, 'GR', 'Greece', 'Ελλάδα', '[\"30\"]', 'EU', 'Athens', '[\"EUR\"]', '[\"el\"]');
INSERT INTO `ref_countries` VALUES (90, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia', '[\"500\"]', 'AN', 'King Edward Point', '[\"GBP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (91, 'GT', 'Guatemala', 'Guatemala', '[\"502\"]', 'NA', 'Guatemala City', '[\"GTQ\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (92, 'GU', 'Guam', 'Guam', '[\"1671\"]', 'OC', 'Hagåtña', '[\"USD\"]', '[\"en\",\"ch\",\"es\"]');
INSERT INTO `ref_countries` VALUES (93, 'GW', 'Guinea-Bissau', 'Guiné-Bissau', '[\"245\"]', 'AF', 'Bissau', '[\"XOF\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (94, 'GY', 'Guyana', 'Guyana', '[\"592\"]', 'SA', 'Georgetown', '[\"GYD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (95, 'HK', 'Hong Kong', '香港', '[\"852\"]', 'AS', 'City of Victoria', '[\"HKD\"]', '[\"zh\",\"en\"]');
INSERT INTO `ref_countries` VALUES (96, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', '[\"61\"]', 'AN', NULL, '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (97, 'HN', 'Honduras', 'Honduras', '[\"504\"]', 'NA', 'Tegucigalpa', '[\"HNL\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (98, 'HR', 'Croatia', 'Hrvatska', '[\"385\"]', 'EU', 'Zagreb', '[\"HRK\"]', '[\"hr\"]');
INSERT INTO `ref_countries` VALUES (99, 'HT', 'Haiti', 'Haïti', '[\"509\"]', 'NA', 'Port-au-Prince', '[\"HTG\",\"USD\"]', '[\"fr\",\"ht\"]');
INSERT INTO `ref_countries` VALUES (100, 'HU', 'Hungary', 'Magyarország', '[\"36\"]', 'EU', 'Budapest', '[\"HUF\"]', '[\"hu\"]');
INSERT INTO `ref_countries` VALUES (101, 'ID', 'Indonesia', 'Indonesia', '[\"62\"]', 'AS', 'Jakarta', '[\"IDR\"]', '[\"id\"]');
INSERT INTO `ref_countries` VALUES (102, 'IE', 'Ireland', 'Éire', '[\"353\"]', 'EU', 'Dublin', '[\"EUR\"]', '[\"ga\",\"en\"]');
INSERT INTO `ref_countries` VALUES (103, 'IL', 'Israel', 'יִשְׂרָאֵל', '[\"972\"]', 'AS', 'Jerusalem', '[\"ILS\"]', '[\"he\",\"ar\"]');
INSERT INTO `ref_countries` VALUES (104, 'IM', 'Isle of Man', 'Isle of Man', '[\"44\"]', 'EU', 'Douglas', '[\"GBP\"]', '[\"en\",\"gv\"]');
INSERT INTO `ref_countries` VALUES (105, 'IN', 'India', 'भारत', '[\"91\"]', 'AS', 'New Delhi', '[\"INR\"]', '[\"hi\",\"en\"]');
INSERT INTO `ref_countries` VALUES (106, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', '[\"246\"]', 'AS', 'Diego Garcia', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (107, 'IQ', 'Iraq', 'العراق', '[\"964\"]', 'AS', 'Baghdad', '[\"IQD\"]', '[\"ar\",\"ku\"]');
INSERT INTO `ref_countries` VALUES (108, 'IR', 'Iran', 'ایران', '[\"98\"]', 'AS', 'Tehran', '[\"IRR\"]', '[\"fa\"]');
INSERT INTO `ref_countries` VALUES (109, 'IS', 'Iceland', 'Ísland', '[\"354\"]', 'EU', 'Reykjavik', '[\"ISK\"]', '[\"is\"]');
INSERT INTO `ref_countries` VALUES (110, 'IT', 'Italy', 'Italia', '[\"39\"]', 'EU', 'Rome', '[\"EUR\"]', '[\"it\"]');
INSERT INTO `ref_countries` VALUES (111, 'JE', 'Jersey', 'Jersey', '[\"44\"]', 'EU', 'Saint Helier', '[\"GBP\"]', '[\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (112, 'JM', 'Jamaica', 'Jamaica', '[\"1876\"]', 'NA', 'Kingston', '[\"JMD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (113, 'JO', 'Jordan', 'الأردن', '[\"962\"]', 'AS', 'Amman', '[\"JOD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (114, 'JP', 'Japan', '日本', '[\"81\"]', 'AS', 'Tokyo', '[\"JPY\"]', '[\"ja\"]');
INSERT INTO `ref_countries` VALUES (115, 'KE', 'Kenya', 'Kenya', '[\"254\"]', 'AF', 'Nairobi', '[\"KES\"]', '[\"en\",\"sw\"]');
INSERT INTO `ref_countries` VALUES (116, 'KG', 'Kyrgyzstan', 'Кыргызстан', '[\"996\"]', 'AS', 'Bishkek', '[\"KGS\"]', '[\"ky\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (117, 'KH', 'Cambodia', 'Kâmpŭchéa', '[\"855\"]', 'AS', 'Phnom Penh', '[\"KHR\"]', '[\"km\"]');
INSERT INTO `ref_countries` VALUES (118, 'KI', 'Kiribati', 'Kiribati', '[\"686\"]', 'OC', 'South Tarawa', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (119, 'KM', 'Comoros', 'Komori', '[\"269\"]', 'AF', 'Moroni', '[\"KMF\"]', '[\"ar\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (120, 'KN', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', '[\"1869\"]', 'NA', 'Basseterre', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (121, 'KP', 'North Korea', '북한', '[\"850\"]', 'AS', 'Pyongyang', '[\"KPW\"]', '[\"ko\"]');
INSERT INTO `ref_countries` VALUES (122, 'KR', 'South Korea', '대한민국', '[\"82\"]', 'AS', 'Seoul', '[\"KRW\"]', '[\"ko\"]');
INSERT INTO `ref_countries` VALUES (123, 'KW', 'Kuwait', 'الكويت', '[\"965\"]', 'AS', 'Kuwait City', '[\"KWD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (124, 'KY', 'Cayman Islands', 'Cayman Islands', '[\"1345\"]', 'NA', 'George Town', '[\"KYD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (125, 'KZ', 'Kazakhstan', 'Қазақстан', '[\"76\",\"77\"]', 'AS', 'Astana', '[\"KZT\"]', '[\"kk\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (126, 'LA', 'Laos', 'ສປປລາວ', '[\"856\"]', 'AS', 'Vientiane', '[\"LAK\"]', '[\"lo\"]');
INSERT INTO `ref_countries` VALUES (127, 'LB', 'Lebanon', 'لبنان', '[\"961\"]', 'AS', 'Beirut', '[\"LBP\"]', '[\"ar\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (128, 'LC', 'Saint Lucia', 'Saint Lucia', '[\"1758\"]', 'NA', 'Castries', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (129, 'LI', 'Liechtenstein', 'Liechtenstein', '[\"423\"]', 'EU', 'Vaduz', '[\"CHF\"]', '[\"de\"]');
INSERT INTO `ref_countries` VALUES (130, 'LK', 'Sri Lanka', 'śrī laṃkāva', '[\"94\"]', 'AS', 'Colombo', '[\"LKR\"]', '[\"si\",\"ta\"]');
INSERT INTO `ref_countries` VALUES (131, 'LR', 'Liberia', 'Liberia', '[\"231\"]', 'AF', 'Monrovia', '[\"LRD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (132, 'LS', 'Lesotho', 'Lesotho', '[\"266\"]', 'AF', 'Maseru', '[\"LSL\",\"ZAR\"]', '[\"en\",\"st\"]');
INSERT INTO `ref_countries` VALUES (133, 'LT', 'Lithuania', 'Lietuva', '[\"370\"]', 'EU', 'Vilnius', '[\"EUR\"]', '[\"lt\"]');
INSERT INTO `ref_countries` VALUES (134, 'LU', 'Luxembourg', 'Luxembourg', '[\"352\"]', 'EU', 'Luxembourg', '[\"EUR\"]', '[\"fr\",\"de\",\"lb\"]');
INSERT INTO `ref_countries` VALUES (135, 'LV', 'Latvia', 'Latvija', '[\"371\"]', 'EU', 'Riga', '[\"EUR\"]', '[\"lv\"]');
INSERT INTO `ref_countries` VALUES (136, 'LY', 'Libya', '‏ليبيا', '[\"218\"]', 'AF', 'Tripoli', '[\"LYD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (137, 'MA', 'Morocco', 'المغرب', '[\"212\"]', 'AF', 'Rabat', '[\"MAD\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (138, 'MC', 'Monaco', 'Monaco', '[\"377\"]', 'EU', 'Monaco', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (139, 'MD', 'Moldova', 'Moldova', '[\"373\"]', 'EU', 'Chișinău', '[\"MDL\"]', '[\"ro\"]');
INSERT INTO `ref_countries` VALUES (140, 'ME', 'Montenegro', 'Црна Гора', '[\"382\"]', 'EU', 'Podgorica', '[\"EUR\"]', '[\"sr\",\"bs\",\"sq\",\"hr\"]');
INSERT INTO `ref_countries` VALUES (141, 'MF', 'Saint Martin', 'Saint-Martin', '[\"590\"]', 'NA', 'Marigot', '[\"EUR\"]', '[\"en\",\"fr\",\"nl\"]');
INSERT INTO `ref_countries` VALUES (142, 'MG', 'Madagascar', 'Madagasikara', '[\"261\"]', 'AF', 'Antananarivo', '[\"MGA\"]', '[\"fr\",\"mg\"]');
INSERT INTO `ref_countries` VALUES (143, 'MH', 'Marshall Islands', 'M̧ajeļ', '[\"692\"]', 'OC', 'Majuro', '[\"USD\"]', '[\"en\",\"mh\"]');
INSERT INTO `ref_countries` VALUES (144, 'MK', 'North Macedonia', 'Северна Македонија', '[\"389\"]', 'EU', 'Skopje', '[\"MKD\"]', '[\"mk\"]');
INSERT INTO `ref_countries` VALUES (145, 'ML', 'Mali', 'Mali', '[\"223\"]', 'AF', 'Bamako', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (146, 'MM', 'Myanmar [Burma]', 'မြန်မာ', '[\"95\"]', 'AS', 'Naypyidaw', '[\"MMK\"]', '[\"my\"]');
INSERT INTO `ref_countries` VALUES (147, 'MN', 'Mongolia', 'Монгол улс', '[\"976\"]', 'AS', 'Ulan Bator', '[\"MNT\"]', '[\"mn\"]');
INSERT INTO `ref_countries` VALUES (148, 'MO', 'Macao', '澳門', '[\"853\"]', 'AS', NULL, '[\"MOP\"]', '[\"zh\",\"pt\"]');
INSERT INTO `ref_countries` VALUES (149, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', '[\"1670\"]', 'OC', 'Saipan', '[\"USD\"]', '[\"en\",\"ch\"]');
INSERT INTO `ref_countries` VALUES (150, 'MQ', 'Martinique', 'Martinique', '[\"596\"]', 'NA', 'Fort-de-France', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (151, 'MR', 'Mauritania', 'موريتانيا', '[\"222\"]', 'AF', 'Nouakchott', '[\"MRU\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (152, 'MS', 'Montserrat', 'Montserrat', '[\"1664\"]', 'NA', 'Plymouth', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (153, 'MT', 'Malta', 'Malta', '[\"356\"]', 'EU', 'Valletta', '[\"EUR\"]', '[\"mt\",\"en\"]');
INSERT INTO `ref_countries` VALUES (154, 'MU', 'Mauritius', 'Maurice', '[\"230\"]', 'AF', 'Port Louis', '[\"MUR\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (155, 'MV', 'Maldives', 'Maldives', '[\"960\"]', 'AS', 'Malé', '[\"MVR\"]', '[\"dv\"]');
INSERT INTO `ref_countries` VALUES (156, 'MW', 'Malawi', 'Malawi', '[\"265\"]', 'AF', 'Lilongwe', '[\"MWK\"]', '[\"en\",\"ny\"]');
INSERT INTO `ref_countries` VALUES (157, 'MX', 'Mexico', 'México', '[\"52\"]', 'NA', 'Mexico City', '[\"MXN\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (158, 'MY', 'Malaysia', 'Malaysia', '[\"60\"]', 'AS', 'Kuala Lumpur', '[\"MYR\"]', '[\"ms\"]');
INSERT INTO `ref_countries` VALUES (159, 'MZ', 'Mozambique', 'Moçambique', '[\"258\"]', 'AF', 'Maputo', '[\"MZN\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (160, 'NA', 'Namibia', 'Namibia', '[\"264\"]', 'AF', 'Windhoek', '[\"NAD\",\"ZAR\"]', '[\"en\",\"af\"]');
INSERT INTO `ref_countries` VALUES (161, 'NC', 'New Caledonia', 'Nouvelle-Calédonie', '[\"687\"]', 'OC', 'Nouméa', '[\"XPF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (162, 'NE', 'Niger', 'Niger', '[\"227\"]', 'AF', 'Niamey', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (163, 'NF', 'Norfolk Island', 'Norfolk Island', '[\"672\"]', 'OC', 'Kingston', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (164, 'NG', 'Nigeria', 'Nigeria', '[\"234\"]', 'AF', 'Abuja', '[\"NGN\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (165, 'NI', 'Nicaragua', 'Nicaragua', '[\"505\"]', 'NA', 'Managua', '[\"NIO\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (166, 'NL', 'Netherlands', 'Nederland', '[\"31\"]', 'EU', 'Amsterdam', '[\"EUR\"]', '[\"nl\"]');
INSERT INTO `ref_countries` VALUES (167, 'NO', 'Norway', 'Norge', '[\"47\"]', 'EU', 'Oslo', '[\"NOK\"]', '[\"no\",\"nb\",\"nn\"]');
INSERT INTO `ref_countries` VALUES (168, 'NP', 'Nepal', 'नपल', '[\"977\"]', 'AS', 'Kathmandu', '[\"NPR\"]', '[\"ne\"]');
INSERT INTO `ref_countries` VALUES (169, 'NR', 'Nauru', 'Nauru', '[\"674\"]', 'OC', 'Yaren', '[\"AUD\"]', '[\"en\",\"na\"]');
INSERT INTO `ref_countries` VALUES (170, 'NU', 'Niue', 'Niuē', '[\"683\"]', 'OC', 'Alofi', '[\"NZD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (171, 'NZ', 'New Zealand', 'New Zealand', '[\"64\"]', 'OC', 'Wellington', '[\"NZD\"]', '[\"en\",\"mi\"]');
INSERT INTO `ref_countries` VALUES (172, 'OM', 'Oman', 'عمان', '[\"968\"]', 'AS', 'Muscat', '[\"OMR\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (173, 'PA', 'Panama', 'Panamá', '[\"507\"]', 'NA', 'Panama City', '[\"PAB\",\"USD\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (174, 'PE', 'Peru', 'Perú', '[\"51\"]', 'SA', 'Lima', '[\"PEN\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (175, 'PF', 'French Polynesia', 'Polynésie française', '[\"689\"]', 'OC', 'Papeetē', '[\"XPF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (176, 'PG', 'Papua New Guinea', 'Papua Niugini', '[\"675\"]', 'OC', 'Port Moresby', '[\"PGK\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (177, 'PH', 'Philippines', 'Pilipinas', '[\"63\"]', 'AS', 'Manila', '[\"PHP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (178, 'PK', 'Pakistan', 'Pakistan', '[\"92\"]', 'AS', 'Islamabad', '[\"PKR\"]', '[\"en\",\"ur\"]');
INSERT INTO `ref_countries` VALUES (179, 'PL', 'Poland', 'Polska', '[\"48\"]', 'EU', 'Warsaw', '[\"PLN\"]', '[\"pl\"]');
INSERT INTO `ref_countries` VALUES (180, 'PM', 'Saint Pierre and Miquelon', 'Saint-Pierre-et-Miquelon', '[\"508\"]', 'NA', 'Saint-Pierre', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (181, 'PN', 'Pitcairn Islands', 'Pitcairn Islands', '[\"64\"]', 'OC', 'Adamstown', '[\"NZD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (182, 'PR', 'Puerto Rico', 'Puerto Rico', '[\"1787\",\"1939\"]', 'NA', 'San Juan', '[\"USD\"]', '[\"es\",\"en\"]');
INSERT INTO `ref_countries` VALUES (183, 'PS', 'Palestine', 'فلسطين', '[\"970\"]', 'AS', 'Ramallah', '[\"ILS\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (184, 'PT', 'Portugal', 'Portugal', '[\"351\"]', 'EU', 'Lisbon', '[\"EUR\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (185, 'PW', 'Palau', 'Palau', '[\"680\"]', 'OC', 'Ngerulmud', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (186, 'PY', 'Paraguay', 'Paraguay', '[\"595\"]', 'SA', 'Asunción', '[\"PYG\"]', '[\"es\",\"gn\"]');
INSERT INTO `ref_countries` VALUES (187, 'QA', 'Qatar', 'قطر', '[\"974\"]', 'AS', 'Doha', '[\"QAR\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (188, 'RE', 'Réunion', 'La Réunion', '[\"262\"]', 'AF', 'Saint-Denis', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (189, 'RO', 'Romania', 'România', '[\"40\"]', 'EU', 'Bucharest', '[\"RON\"]', '[\"ro\"]');
INSERT INTO `ref_countries` VALUES (190, 'RS', 'Serbia', 'Србија', '[\"381\"]', 'EU', 'Belgrade', '[\"RSD\"]', '[\"sr\"]');
INSERT INTO `ref_countries` VALUES (191, 'RU', 'Russia', 'Россия', '[\"7\"]', 'EU', 'Moscow', '[\"RUB\"]', '[\"ru\"]');
INSERT INTO `ref_countries` VALUES (192, 'RW', 'Rwanda', 'Rwanda', '[\"250\"]', 'AF', 'Kigali', '[\"RWF\"]', '[\"rw\",\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (193, 'SA', 'Saudi Arabia', 'العربية السعودية', '[\"966\"]', 'AS', 'Riyadh', '[\"SAR\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (194, 'SB', 'Solomon Islands', 'Solomon Islands', '[\"677\"]', 'OC', 'Honiara', '[\"SBD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (195, 'SC', 'Seychelles', 'Seychelles', '[\"248\"]', 'AF', 'Victoria', '[\"SCR\"]', '[\"fr\",\"en\"]');
INSERT INTO `ref_countries` VALUES (196, 'SD', 'Sudan', 'السودان', '[\"249\"]', 'AF', 'Khartoum', '[\"SDG\"]', '[\"ar\",\"en\"]');
INSERT INTO `ref_countries` VALUES (197, 'SE', 'Sweden', 'Sverige', '[\"46\"]', 'EU', 'Stockholm', '[\"SEK\"]', '[\"sv\"]');
INSERT INTO `ref_countries` VALUES (198, 'SG', 'Singapore', 'Singapore', '[\"65\"]', 'AS', 'Singapore', '[\"SGD\"]', '[\"en\",\"ms\",\"ta\",\"zh\"]');
INSERT INTO `ref_countries` VALUES (199, 'SH', 'Saint Helena', 'Saint Helena', '[\"290\"]', 'AF', 'Jamestown', '[\"SHP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (200, 'SI', 'Slovenia', 'Slovenija', '[\"386\"]', 'EU', 'Ljubljana', '[\"EUR\"]', '[\"sl\"]');
INSERT INTO `ref_countries` VALUES (201, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard og Jan Mayen', '[\"4779\"]', 'EU', 'Longyearbyen', '[\"NOK\"]', '[\"no\"]');
INSERT INTO `ref_countries` VALUES (202, 'SK', 'Slovakia', 'Slovensko', '[\"421\"]', 'EU', 'Bratislava', '[\"EUR\"]', '[\"sk\"]');
INSERT INTO `ref_countries` VALUES (203, 'SL', 'Sierra Leone', 'Sierra Leone', '[\"232\"]', 'AF', 'Freetown', '[\"SLL\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (204, 'SM', 'San Marino', 'San Marino', '[\"378\"]', 'EU', 'City of San Marino', '[\"EUR\"]', '[\"it\"]');
INSERT INTO `ref_countries` VALUES (205, 'SN', 'Senegal', 'Sénégal', '[\"221\"]', 'AF', 'Dakar', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (206, 'SO', 'Somalia', 'Soomaaliya', '[\"252\"]', 'AF', 'Mogadishu', '[\"SOS\"]', '[\"so\",\"ar\"]');
INSERT INTO `ref_countries` VALUES (207, 'SR', 'Suriname', 'Suriname', '[\"597\"]', 'SA', 'Paramaribo', '[\"SRD\"]', '[\"nl\"]');
INSERT INTO `ref_countries` VALUES (208, 'SS', 'South Sudan', 'South Sudan', '[\"211\"]', 'AF', 'Juba', '[\"SSP\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (209, 'ST', 'São Tomé and Príncipe', 'São Tomé e Príncipe', '[\"239\"]', 'AF', 'São Tomé', '[\"STN\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (210, 'SV', 'El Salvador', 'El Salvador', '[\"503\"]', 'NA', 'San Salvador', '[\"SVC\",\"USD\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (211, 'SX', 'Sint Maarten', 'Sint Maarten', '[\"1721\"]', 'NA', 'Philipsburg', '[\"ANG\"]', '[\"nl\",\"en\"]');
INSERT INTO `ref_countries` VALUES (212, 'SY', 'Syria', 'سوريا', '[\"963\"]', 'AS', 'Damascus', '[\"SYP\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (213, 'SZ', 'Swaziland', 'Swaziland', '[\"268\"]', 'AF', 'Lobamba', '[\"SZL\"]', '[\"en\",\"ss\"]');
INSERT INTO `ref_countries` VALUES (214, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', '[\"1649\"]', 'NA', 'Cockburn Town', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (215, 'TD', 'Chad', 'Tchad', '[\"235\"]', 'AF', 'N\'Djamena', '[\"XAF\"]', '[\"fr\",\"ar\"]');
INSERT INTO `ref_countries` VALUES (216, 'TF', 'French Southern Territories', 'Territoire des Terres australes et antarctiques fr', '[\"262\"]', 'AN', 'Port-aux-Français', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (217, 'TG', 'Togo', 'Togo', '[\"228\"]', 'AF', 'Lomé', '[\"XOF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (218, 'TH', 'Thailand', 'ประเทศไทย', '[\"66\"]', 'AS', 'Bangkok', '[\"THB\"]', '[\"th\"]');
INSERT INTO `ref_countries` VALUES (219, 'TJ', 'Tajikistan', 'Тоҷикистон', '[\"992\"]', 'AS', 'Dushanbe', '[\"TJS\"]', '[\"tg\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (220, 'TK', 'Tokelau', 'Tokelau', '[\"690\"]', 'OC', 'Fakaofo', '[\"NZD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (221, 'TL', 'East Timor', 'Timor-Leste', '[\"670\"]', 'OC', 'Dili', '[\"USD\"]', '[\"pt\"]');
INSERT INTO `ref_countries` VALUES (222, 'TM', 'Turkmenistan', 'Türkmenistan', '[\"993\"]', 'AS', 'Ashgabat', '[\"TMT\"]', '[\"tk\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (223, 'TN', 'Tunisia', 'تونس', '[\"216\"]', 'AF', 'Tunis', '[\"TND\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (224, 'TO', 'Tonga', 'Tonga', '[\"676\"]', 'OC', 'Nuku\'alofa', '[\"TOP\"]', '[\"en\",\"to\"]');
INSERT INTO `ref_countries` VALUES (225, 'TR', 'Turkey', 'Türkiye', '[\"90\"]', 'AS', 'Ankara', '[\"TRY\"]', '[\"tr\"]');
INSERT INTO `ref_countries` VALUES (226, 'TT', 'Trinidad and Tobago', 'Trinidad and Tobago', '[\"1868\"]', 'NA', 'Port of Spain', '[\"TTD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (227, 'TV', 'Tuvalu', 'Tuvalu', '[\"688\"]', 'OC', 'Funafuti', '[\"AUD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (228, 'TW', 'Taiwan', '臺灣', '[\"886\"]', 'AS', 'Taipei', '[\"TWD\"]', '[\"zh\"]');
INSERT INTO `ref_countries` VALUES (229, 'TZ', 'Tanzania', 'Tanzania', '[\"255\"]', 'AF', 'Dodoma', '[\"TZS\"]', '[\"sw\",\"en\"]');
INSERT INTO `ref_countries` VALUES (230, 'UA', 'Ukraine', 'Україна', '[\"380\"]', 'EU', 'Kyiv', '[\"UAH\"]', '[\"uk\"]');
INSERT INTO `ref_countries` VALUES (231, 'UG', 'Uganda', 'Uganda', '[\"256\"]', 'AF', 'Kampala', '[\"UGX\"]', '[\"en\",\"sw\"]');
INSERT INTO `ref_countries` VALUES (232, 'UM', 'U.S. Minor Outlying Islands', 'United States Minor Outlying Islands', '[\"1\"]', 'OC', NULL, '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (233, 'US', 'United States', 'United States', '[\"1\"]', 'NA', 'Washington D.C.', '[\"USD\",\"USN\",\"USS\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (234, 'UY', 'Uruguay', 'Uruguay', '[\"598\"]', 'SA', 'Montevideo', '[\"UYI\",\"UYU\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (235, 'UZ', 'Uzbekistan', 'O‘zbekiston', '[\"998\"]', 'AS', 'Tashkent', '[\"UZS\"]', '[\"uz\",\"ru\"]');
INSERT INTO `ref_countries` VALUES (236, 'VA', 'Vatican City', 'Vaticano', '[\"379\"]', 'EU', 'Vatican City', '[\"EUR\"]', '[\"it\",\"la\"]');
INSERT INTO `ref_countries` VALUES (237, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', '[\"1784\"]', 'NA', 'Kingstown', '[\"XCD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (238, 'VE', 'Venezuela', 'Venezuela', '[\"58\"]', 'SA', 'Caracas', '[\"VES\"]', '[\"es\"]');
INSERT INTO `ref_countries` VALUES (239, 'VG', 'British Virgin Islands', 'British Virgin Islands', '[\"1284\"]', 'NA', 'Road Town', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (240, 'VI', 'U.S. Virgin Islands', 'United States Virgin Islands', '[\"1340\"]', 'NA', 'Charlotte Amalie', '[\"USD\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (241, 'VN', 'Vietnam', 'Việt Nam', '[\"84\"]', 'AS', 'Hanoi', '[\"VND\"]', '[\"vi\"]');
INSERT INTO `ref_countries` VALUES (242, 'VU', 'Vanuatu', 'Vanuatu', '[\"678\"]', 'OC', 'Port Vila', '[\"VUV\"]', '[\"bi\",\"en\",\"fr\"]');
INSERT INTO `ref_countries` VALUES (243, 'WF', 'Wallis and Futuna', 'Wallis et Futuna', '[\"681\"]', 'OC', 'Mata-Utu', '[\"XPF\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (244, 'WS', 'Samoa', 'Samoa', '[\"685\"]', 'OC', 'Apia', '[\"WST\"]', '[\"sm\",\"en\"]');
INSERT INTO `ref_countries` VALUES (245, 'XK', 'Kosovo', 'Republika e Kosovës', '[\"377\",\"381\",\"383\",\"386\"]', 'EU', 'Pristina', '[\"EUR\"]', '[\"sq\",\"sr\"]');
INSERT INTO `ref_countries` VALUES (246, 'YE', 'Yemen', 'اليَمَن', '[\"967\"]', 'AS', 'Sana\'a', '[\"YER\"]', '[\"ar\"]');
INSERT INTO `ref_countries` VALUES (247, 'YT', 'Mayotte', 'Mayotte', '[\"262\"]', 'AF', 'Mamoudzou', '[\"EUR\"]', '[\"fr\"]');
INSERT INTO `ref_countries` VALUES (248, 'ZA', 'South Africa', 'South Africa', '[\"27\"]', 'AF', 'Pretoria', '[\"ZAR\"]', '[\"af\",\"en\",\"nr\",\"st\",\"ss\",\"tn\",\"ts\",\"ve\",\"xh\",\"zu\"]');
INSERT INTO `ref_countries` VALUES (249, 'ZM', 'Zambia', 'Zambia', '[\"260\"]', 'AF', 'Lusaka', '[\"ZMW\"]', '[\"en\"]');
INSERT INTO `ref_countries` VALUES (250, 'ZW', 'Zimbabwe', 'Zimbabwe', '[\"263\"]', 'AF', 'Harare', '[\"USD\",\"ZAR\",\"BWP\",\"GBP\",\"AUD\",\"CNY\",\"INR\",\"JP\"]', '[\"en\",\"sn\",\"nd\"]');

-- ----------------------------
-- Table structure for ref_country_states
-- ----------------------------
DROP TABLE IF EXISTS `ref_country_states`;
CREATE TABLE `ref_country_states`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` smallint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `country_id`(`country_id` ASC) USING BTREE,
  CONSTRAINT `ref_country_states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `ref_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3667 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_country_states
-- ----------------------------
INSERT INTO `ref_country_states` VALUES (1, 1, 'Andorra la Vella');
INSERT INTO `ref_country_states` VALUES (2, 1, 'Canillo');
INSERT INTO `ref_country_states` VALUES (3, 1, 'Encamp');
INSERT INTO `ref_country_states` VALUES (4, 1, 'Escaldes-Engordany');
INSERT INTO `ref_country_states` VALUES (5, 1, 'La Massana');
INSERT INTO `ref_country_states` VALUES (6, 1, 'Ordino');
INSERT INTO `ref_country_states` VALUES (7, 1, 'Sant Julia de Loria');
INSERT INTO `ref_country_states` VALUES (8, 2, 'Abu Dhabi');
INSERT INTO `ref_country_states` VALUES (9, 2, '\'Ajman');
INSERT INTO `ref_country_states` VALUES (10, 2, 'Al Fujayrah');
INSERT INTO `ref_country_states` VALUES (11, 2, 'Sharjah');
INSERT INTO `ref_country_states` VALUES (12, 2, 'Dubai');
INSERT INTO `ref_country_states` VALUES (13, 2, 'Ra\'s al Khaymah');
INSERT INTO `ref_country_states` VALUES (14, 2, 'Umm al Qaywayn');
INSERT INTO `ref_country_states` VALUES (15, 3, 'Badakhshan');
INSERT INTO `ref_country_states` VALUES (16, 3, 'Badghis');
INSERT INTO `ref_country_states` VALUES (17, 3, 'Baghlan');
INSERT INTO `ref_country_states` VALUES (18, 3, 'Balkh');
INSERT INTO `ref_country_states` VALUES (19, 3, 'Bamian');
INSERT INTO `ref_country_states` VALUES (20, 3, 'Daykondi');
INSERT INTO `ref_country_states` VALUES (21, 3, 'Farah');
INSERT INTO `ref_country_states` VALUES (22, 3, 'Faryab');
INSERT INTO `ref_country_states` VALUES (23, 3, 'Ghazni');
INSERT INTO `ref_country_states` VALUES (24, 3, 'Ghowr');
INSERT INTO `ref_country_states` VALUES (25, 3, 'Helmand');
INSERT INTO `ref_country_states` VALUES (26, 3, 'Herat');
INSERT INTO `ref_country_states` VALUES (27, 3, 'Jowzjan');
INSERT INTO `ref_country_states` VALUES (28, 3, 'Kabul');
INSERT INTO `ref_country_states` VALUES (29, 3, 'Kandahar');
INSERT INTO `ref_country_states` VALUES (30, 3, 'Kapisa');
INSERT INTO `ref_country_states` VALUES (31, 3, 'Khost');
INSERT INTO `ref_country_states` VALUES (32, 3, 'Konar');
INSERT INTO `ref_country_states` VALUES (33, 3, 'Kondoz');
INSERT INTO `ref_country_states` VALUES (34, 3, 'Laghman');
INSERT INTO `ref_country_states` VALUES (35, 3, 'Lowgar');
INSERT INTO `ref_country_states` VALUES (36, 3, 'Nangarhar');
INSERT INTO `ref_country_states` VALUES (37, 3, 'Nimruz');
INSERT INTO `ref_country_states` VALUES (38, 3, 'Nurestan');
INSERT INTO `ref_country_states` VALUES (39, 3, 'Oruzgan');
INSERT INTO `ref_country_states` VALUES (40, 3, 'Paktia');
INSERT INTO `ref_country_states` VALUES (41, 3, 'Paktika');
INSERT INTO `ref_country_states` VALUES (42, 3, 'Panjshir');
INSERT INTO `ref_country_states` VALUES (43, 3, 'Parvan');
INSERT INTO `ref_country_states` VALUES (44, 3, 'Samangan');
INSERT INTO `ref_country_states` VALUES (45, 3, 'Sar-e Pol');
INSERT INTO `ref_country_states` VALUES (46, 3, 'Takhar');
INSERT INTO `ref_country_states` VALUES (47, 3, 'Vardak');
INSERT INTO `ref_country_states` VALUES (48, 3, 'Zabol');
INSERT INTO `ref_country_states` VALUES (49, 4, 'Barbuda');
INSERT INTO `ref_country_states` VALUES (50, 4, 'Redonda');
INSERT INTO `ref_country_states` VALUES (51, 4, 'Saint George');
INSERT INTO `ref_country_states` VALUES (52, 4, 'Saint John');
INSERT INTO `ref_country_states` VALUES (53, 4, 'Saint Mary');
INSERT INTO `ref_country_states` VALUES (54, 4, 'Saint Paul');
INSERT INTO `ref_country_states` VALUES (55, 4, 'Saint Peter');
INSERT INTO `ref_country_states` VALUES (56, 4, 'Saint Philip');
INSERT INTO `ref_country_states` VALUES (57, 6, 'Berat');
INSERT INTO `ref_country_states` VALUES (58, 6, 'Dibres');
INSERT INTO `ref_country_states` VALUES (59, 6, 'Durres');
INSERT INTO `ref_country_states` VALUES (60, 6, 'Elbasan');
INSERT INTO `ref_country_states` VALUES (61, 6, 'Fier');
INSERT INTO `ref_country_states` VALUES (62, 6, 'Gjirokastre');
INSERT INTO `ref_country_states` VALUES (63, 6, 'Korce');
INSERT INTO `ref_country_states` VALUES (64, 6, 'Kukes');
INSERT INTO `ref_country_states` VALUES (65, 6, 'Lezhe');
INSERT INTO `ref_country_states` VALUES (66, 6, 'Shkoder');
INSERT INTO `ref_country_states` VALUES (67, 6, 'Tirane');
INSERT INTO `ref_country_states` VALUES (68, 6, 'Vlore');
INSERT INTO `ref_country_states` VALUES (69, 7, 'Aragatsotn');
INSERT INTO `ref_country_states` VALUES (70, 7, 'Ararat');
INSERT INTO `ref_country_states` VALUES (71, 7, 'Armavir');
INSERT INTO `ref_country_states` VALUES (72, 7, 'Geghark\'unik\'');
INSERT INTO `ref_country_states` VALUES (73, 7, 'Kotayk\'');
INSERT INTO `ref_country_states` VALUES (74, 7, 'Lorri');
INSERT INTO `ref_country_states` VALUES (75, 7, 'Shirak');
INSERT INTO `ref_country_states` VALUES (76, 7, 'Syunik\'');
INSERT INTO `ref_country_states` VALUES (77, 7, 'Tavush');
INSERT INTO `ref_country_states` VALUES (78, 7, 'Vayots\' Dzor');
INSERT INTO `ref_country_states` VALUES (79, 7, 'Yerevan');
INSERT INTO `ref_country_states` VALUES (80, 8, 'Bengo');
INSERT INTO `ref_country_states` VALUES (81, 8, 'Benguela');
INSERT INTO `ref_country_states` VALUES (82, 8, 'Bie');
INSERT INTO `ref_country_states` VALUES (83, 8, 'Cabinda');
INSERT INTO `ref_country_states` VALUES (84, 8, 'Cuando Cubango');
INSERT INTO `ref_country_states` VALUES (85, 8, 'Cuanza Norte');
INSERT INTO `ref_country_states` VALUES (86, 8, 'Cuanza Sul');
INSERT INTO `ref_country_states` VALUES (87, 8, 'Cunene');
INSERT INTO `ref_country_states` VALUES (88, 8, 'Huambo');
INSERT INTO `ref_country_states` VALUES (89, 8, 'Huila');
INSERT INTO `ref_country_states` VALUES (90, 8, 'Luanda');
INSERT INTO `ref_country_states` VALUES (91, 8, 'Lunda Norte');
INSERT INTO `ref_country_states` VALUES (92, 8, 'Lunda Sul');
INSERT INTO `ref_country_states` VALUES (93, 8, 'Malanje');
INSERT INTO `ref_country_states` VALUES (94, 8, 'Moxico');
INSERT INTO `ref_country_states` VALUES (95, 8, 'Namibe');
INSERT INTO `ref_country_states` VALUES (96, 8, 'Uige');
INSERT INTO `ref_country_states` VALUES (97, 8, 'Zaire');
INSERT INTO `ref_country_states` VALUES (98, 10, 'Buenos Aires');
INSERT INTO `ref_country_states` VALUES (99, 10, 'Buenos Aires Capital');
INSERT INTO `ref_country_states` VALUES (100, 10, 'Catamarca');
INSERT INTO `ref_country_states` VALUES (101, 10, 'Chaco');
INSERT INTO `ref_country_states` VALUES (102, 10, 'Chubut');
INSERT INTO `ref_country_states` VALUES (103, 10, 'Cordoba');
INSERT INTO `ref_country_states` VALUES (104, 10, 'Corrientes');
INSERT INTO `ref_country_states` VALUES (105, 10, 'Entre Rios');
INSERT INTO `ref_country_states` VALUES (106, 10, 'Formosa');
INSERT INTO `ref_country_states` VALUES (107, 10, 'Jujuy');
INSERT INTO `ref_country_states` VALUES (108, 10, 'La Pampa');
INSERT INTO `ref_country_states` VALUES (109, 10, 'La Rioja');
INSERT INTO `ref_country_states` VALUES (110, 10, 'Mendoza');
INSERT INTO `ref_country_states` VALUES (111, 10, 'Misiones');
INSERT INTO `ref_country_states` VALUES (112, 10, 'Neuquen');
INSERT INTO `ref_country_states` VALUES (113, 10, 'Rio Negro');
INSERT INTO `ref_country_states` VALUES (114, 10, 'Salta');
INSERT INTO `ref_country_states` VALUES (115, 10, 'San Juan');
INSERT INTO `ref_country_states` VALUES (116, 10, 'San Luis');
INSERT INTO `ref_country_states` VALUES (117, 10, 'Santa Cruz');
INSERT INTO `ref_country_states` VALUES (118, 10, 'Santa Fe');
INSERT INTO `ref_country_states` VALUES (119, 10, 'Santiago del Estero');
INSERT INTO `ref_country_states` VALUES (120, 10, 'Tierra del Fuego');
INSERT INTO `ref_country_states` VALUES (121, 10, 'Tucuman');
INSERT INTO `ref_country_states` VALUES (122, 12, 'Burgenland');
INSERT INTO `ref_country_states` VALUES (123, 12, 'Kaernten');
INSERT INTO `ref_country_states` VALUES (124, 12, 'Niederoesterreich');
INSERT INTO `ref_country_states` VALUES (125, 12, 'Oberoesterreich');
INSERT INTO `ref_country_states` VALUES (126, 12, 'Salzburg');
INSERT INTO `ref_country_states` VALUES (127, 12, 'Steiermark');
INSERT INTO `ref_country_states` VALUES (128, 12, 'Tirol');
INSERT INTO `ref_country_states` VALUES (129, 12, 'Vorarlberg');
INSERT INTO `ref_country_states` VALUES (130, 12, 'Wien');
INSERT INTO `ref_country_states` VALUES (131, 13, 'Australian Capital Territory');
INSERT INTO `ref_country_states` VALUES (132, 13, 'New South Wales');
INSERT INTO `ref_country_states` VALUES (133, 13, 'Northern Territory');
INSERT INTO `ref_country_states` VALUES (134, 13, 'Queensland');
INSERT INTO `ref_country_states` VALUES (135, 13, 'South Australia');
INSERT INTO `ref_country_states` VALUES (136, 13, 'Tasmania');
INSERT INTO `ref_country_states` VALUES (137, 13, 'Victoria');
INSERT INTO `ref_country_states` VALUES (138, 13, 'Western Australia');
INSERT INTO `ref_country_states` VALUES (139, 16, 'Abseron Rayonu');
INSERT INTO `ref_country_states` VALUES (140, 16, 'Agcabadi Rayonu');
INSERT INTO `ref_country_states` VALUES (141, 16, 'Agdam Rayonu');
INSERT INTO `ref_country_states` VALUES (142, 16, 'Agdas Rayonu');
INSERT INTO `ref_country_states` VALUES (143, 16, 'Agstafa Rayonu');
INSERT INTO `ref_country_states` VALUES (144, 16, 'Agsu Rayonu');
INSERT INTO `ref_country_states` VALUES (145, 16, 'Astara Rayonu');
INSERT INTO `ref_country_states` VALUES (146, 16, 'Balakan Rayonu');
INSERT INTO `ref_country_states` VALUES (147, 16, 'Barda Rayonu');
INSERT INTO `ref_country_states` VALUES (148, 16, 'Beylaqan Rayonu');
INSERT INTO `ref_country_states` VALUES (149, 16, 'Bilasuvar Rayonu');
INSERT INTO `ref_country_states` VALUES (150, 16, 'Cabrayil Rayonu');
INSERT INTO `ref_country_states` VALUES (151, 16, 'Calilabad Rayonu');
INSERT INTO `ref_country_states` VALUES (152, 16, 'Daskasan Rayonu');
INSERT INTO `ref_country_states` VALUES (153, 16, 'Davaci Rayonu');
INSERT INTO `ref_country_states` VALUES (154, 16, 'Fuzuli Rayonu');
INSERT INTO `ref_country_states` VALUES (155, 16, 'Gadabay Rayonu');
INSERT INTO `ref_country_states` VALUES (156, 16, 'Goranboy Rayonu');
INSERT INTO `ref_country_states` VALUES (157, 16, 'Goycay Rayonu');
INSERT INTO `ref_country_states` VALUES (158, 16, 'Haciqabul Rayonu');
INSERT INTO `ref_country_states` VALUES (159, 16, 'Imisli Rayonu');
INSERT INTO `ref_country_states` VALUES (160, 16, 'Ismayilli Rayonu');
INSERT INTO `ref_country_states` VALUES (161, 16, 'Kalbacar Rayonu');
INSERT INTO `ref_country_states` VALUES (162, 16, 'Kurdamir Rayonu');
INSERT INTO `ref_country_states` VALUES (163, 16, 'Lacin Rayonu');
INSERT INTO `ref_country_states` VALUES (164, 16, 'Lankaran Rayonu');
INSERT INTO `ref_country_states` VALUES (165, 16, 'Lerik Rayonu');
INSERT INTO `ref_country_states` VALUES (166, 16, 'Masalli Rayonu');
INSERT INTO `ref_country_states` VALUES (167, 16, 'Neftcala Rayonu');
INSERT INTO `ref_country_states` VALUES (168, 16, 'Oguz Rayonu');
INSERT INTO `ref_country_states` VALUES (169, 16, 'Qabala Rayonu');
INSERT INTO `ref_country_states` VALUES (170, 16, 'Qax Rayonu');
INSERT INTO `ref_country_states` VALUES (171, 16, 'Qazax Rayonu');
INSERT INTO `ref_country_states` VALUES (172, 16, 'Qobustan Rayonu');
INSERT INTO `ref_country_states` VALUES (173, 16, 'Quba Rayonu');
INSERT INTO `ref_country_states` VALUES (174, 16, 'Qubadli Rayonu');
INSERT INTO `ref_country_states` VALUES (175, 16, 'Qusar Rayonu');
INSERT INTO `ref_country_states` VALUES (176, 16, 'Saatli Rayonu');
INSERT INTO `ref_country_states` VALUES (177, 16, 'Sabirabad Rayonu');
INSERT INTO `ref_country_states` VALUES (178, 16, 'Saki Rayonu');
INSERT INTO `ref_country_states` VALUES (179, 16, 'Salyan Rayonu');
INSERT INTO `ref_country_states` VALUES (180, 16, 'Samaxi Rayonu');
INSERT INTO `ref_country_states` VALUES (181, 16, 'Samkir Rayonu');
INSERT INTO `ref_country_states` VALUES (182, 16, 'Samux Rayonu');
INSERT INTO `ref_country_states` VALUES (183, 16, 'Siyazan Rayonu');
INSERT INTO `ref_country_states` VALUES (184, 16, 'Susa Rayonu');
INSERT INTO `ref_country_states` VALUES (185, 16, 'Tartar Rayonu');
INSERT INTO `ref_country_states` VALUES (186, 16, 'Tovuz Rayonu');
INSERT INTO `ref_country_states` VALUES (187, 16, 'Ucar Rayonu');
INSERT INTO `ref_country_states` VALUES (188, 16, 'Xacmaz Rayonu');
INSERT INTO `ref_country_states` VALUES (189, 16, 'Xanlar Rayonu');
INSERT INTO `ref_country_states` VALUES (190, 16, 'Xizi Rayonu');
INSERT INTO `ref_country_states` VALUES (191, 16, 'Xocali Rayonu');
INSERT INTO `ref_country_states` VALUES (192, 16, 'Xocavand Rayonu');
INSERT INTO `ref_country_states` VALUES (193, 16, 'Yardimli Rayonu');
INSERT INTO `ref_country_states` VALUES (194, 16, 'Yevlax Rayonu');
INSERT INTO `ref_country_states` VALUES (195, 16, 'Zangilan Rayonu');
INSERT INTO `ref_country_states` VALUES (196, 16, 'Zaqatala Rayonu');
INSERT INTO `ref_country_states` VALUES (197, 16, 'Zardab Rayonu');
INSERT INTO `ref_country_states` VALUES (198, 16, 'Ali Bayramli Sahari');
INSERT INTO `ref_country_states` VALUES (199, 16, 'Baki Sahari');
INSERT INTO `ref_country_states` VALUES (200, 16, 'Ganca Sahari');
INSERT INTO `ref_country_states` VALUES (201, 16, 'Lankaran Sahari');
INSERT INTO `ref_country_states` VALUES (202, 16, 'Mingacevir Sahari');
INSERT INTO `ref_country_states` VALUES (203, 16, 'Naftalan Sahari');
INSERT INTO `ref_country_states` VALUES (204, 16, 'Saki Sahari');
INSERT INTO `ref_country_states` VALUES (205, 16, 'Sumqayit Sahari');
INSERT INTO `ref_country_states` VALUES (206, 16, 'Susa Sahari');
INSERT INTO `ref_country_states` VALUES (207, 16, 'Xankandi Sahari');
INSERT INTO `ref_country_states` VALUES (208, 16, 'Yevlax Sahari');
INSERT INTO `ref_country_states` VALUES (209, 16, 'Naxcivan Muxtar');
INSERT INTO `ref_country_states` VALUES (210, 17, 'Una-Sana [Federation]');
INSERT INTO `ref_country_states` VALUES (211, 17, 'Posavina [Federation]');
INSERT INTO `ref_country_states` VALUES (212, 17, 'Tuzla [Federation]');
INSERT INTO `ref_country_states` VALUES (213, 17, 'Zenica-Doboj [Federation]');
INSERT INTO `ref_country_states` VALUES (214, 17, 'Bosnian Podrinje [Federation]');
INSERT INTO `ref_country_states` VALUES (215, 17, 'Central Bosnia [Federation]');
INSERT INTO `ref_country_states` VALUES (216, 17, 'Herzegovina-Neretva [Federation]');
INSERT INTO `ref_country_states` VALUES (217, 17, 'West Herzegovina [Federation]');
INSERT INTO `ref_country_states` VALUES (218, 17, 'Sarajevo [Federation]');
INSERT INTO `ref_country_states` VALUES (219, 17, ' West Bosnia [Federation]');
INSERT INTO `ref_country_states` VALUES (220, 17, 'Banja Luka [RS]');
INSERT INTO `ref_country_states` VALUES (221, 17, 'Bijeljina [RS]');
INSERT INTO `ref_country_states` VALUES (222, 17, 'Doboj [RS]');
INSERT INTO `ref_country_states` VALUES (223, 17, 'Fo?a [RS]');
INSERT INTO `ref_country_states` VALUES (224, 17, 'Sarajevo-Romanija [RS]');
INSERT INTO `ref_country_states` VALUES (225, 17, 'Trebinje [RS]');
INSERT INTO `ref_country_states` VALUES (226, 17, 'Vlasenica [RS]');
INSERT INTO `ref_country_states` VALUES (227, 18, 'Christ Church');
INSERT INTO `ref_country_states` VALUES (228, 18, 'Saint Andrew');
INSERT INTO `ref_country_states` VALUES (229, 18, 'Saint George');
INSERT INTO `ref_country_states` VALUES (230, 18, 'Saint James');
INSERT INTO `ref_country_states` VALUES (231, 18, 'Saint John');
INSERT INTO `ref_country_states` VALUES (232, 18, 'Saint Joseph');
INSERT INTO `ref_country_states` VALUES (233, 18, 'Saint Lucy');
INSERT INTO `ref_country_states` VALUES (234, 18, 'Saint Michael');
INSERT INTO `ref_country_states` VALUES (235, 18, 'Saint Peter');
INSERT INTO `ref_country_states` VALUES (236, 18, 'Saint Philip');
INSERT INTO `ref_country_states` VALUES (237, 18, 'Saint Thomas');
INSERT INTO `ref_country_states` VALUES (238, 19, 'Barisal');
INSERT INTO `ref_country_states` VALUES (239, 19, 'Chittagong');
INSERT INTO `ref_country_states` VALUES (240, 19, 'Dhaka');
INSERT INTO `ref_country_states` VALUES (241, 19, 'Khulna');
INSERT INTO `ref_country_states` VALUES (242, 19, 'Rajshahi');
INSERT INTO `ref_country_states` VALUES (243, 19, 'Sylhet');
INSERT INTO `ref_country_states` VALUES (244, 20, 'Antwerpen');
INSERT INTO `ref_country_states` VALUES (245, 20, 'Brabant Wallon');
INSERT INTO `ref_country_states` VALUES (246, 20, 'Brussels');
INSERT INTO `ref_country_states` VALUES (247, 20, 'Flanders');
INSERT INTO `ref_country_states` VALUES (248, 20, 'Hainaut');
INSERT INTO `ref_country_states` VALUES (249, 20, 'Liege');
INSERT INTO `ref_country_states` VALUES (250, 20, 'Limburg');
INSERT INTO `ref_country_states` VALUES (251, 20, 'Luxembourg');
INSERT INTO `ref_country_states` VALUES (252, 20, 'Namur');
INSERT INTO `ref_country_states` VALUES (253, 20, 'Oost-Vlaanderen');
INSERT INTO `ref_country_states` VALUES (254, 20, 'Vlaams-Brabant');
INSERT INTO `ref_country_states` VALUES (255, 20, 'Wallonia');
INSERT INTO `ref_country_states` VALUES (256, 20, 'West-Vlaanderen');
INSERT INTO `ref_country_states` VALUES (257, 21, 'Bale');
INSERT INTO `ref_country_states` VALUES (258, 21, 'Bam');
INSERT INTO `ref_country_states` VALUES (259, 21, 'Banwa');
INSERT INTO `ref_country_states` VALUES (260, 21, 'Bazega');
INSERT INTO `ref_country_states` VALUES (261, 21, 'Bougouriba');
INSERT INTO `ref_country_states` VALUES (262, 21, 'Boulgou');
INSERT INTO `ref_country_states` VALUES (263, 21, 'Boulkiemde');
INSERT INTO `ref_country_states` VALUES (264, 21, 'Comoe');
INSERT INTO `ref_country_states` VALUES (265, 21, 'Ganzourgou');
INSERT INTO `ref_country_states` VALUES (266, 21, 'Gnagna');
INSERT INTO `ref_country_states` VALUES (267, 21, 'Gourma');
INSERT INTO `ref_country_states` VALUES (268, 21, 'Houet');
INSERT INTO `ref_country_states` VALUES (269, 21, 'Ioba');
INSERT INTO `ref_country_states` VALUES (270, 21, 'Kadiogo');
INSERT INTO `ref_country_states` VALUES (271, 21, 'Kenedougou');
INSERT INTO `ref_country_states` VALUES (272, 21, 'Komondjari');
INSERT INTO `ref_country_states` VALUES (273, 21, 'Kompienga');
INSERT INTO `ref_country_states` VALUES (274, 21, 'Kossi');
INSERT INTO `ref_country_states` VALUES (275, 21, 'Koulpelogo');
INSERT INTO `ref_country_states` VALUES (276, 21, 'Kouritenga');
INSERT INTO `ref_country_states` VALUES (277, 21, 'Kourweogo');
INSERT INTO `ref_country_states` VALUES (278, 21, 'Leraba');
INSERT INTO `ref_country_states` VALUES (279, 21, 'Loroum');
INSERT INTO `ref_country_states` VALUES (280, 21, 'Mouhoun');
INSERT INTO `ref_country_states` VALUES (281, 21, 'Namentenga');
INSERT INTO `ref_country_states` VALUES (282, 21, 'Nahouri');
INSERT INTO `ref_country_states` VALUES (283, 21, 'Nayala');
INSERT INTO `ref_country_states` VALUES (284, 21, 'Noumbiel');
INSERT INTO `ref_country_states` VALUES (285, 21, 'Oubritenga');
INSERT INTO `ref_country_states` VALUES (286, 21, 'Oudalan');
INSERT INTO `ref_country_states` VALUES (287, 21, 'Passore');
INSERT INTO `ref_country_states` VALUES (288, 21, 'Poni');
INSERT INTO `ref_country_states` VALUES (289, 21, 'Sanguie');
INSERT INTO `ref_country_states` VALUES (290, 21, 'Sanmatenga');
INSERT INTO `ref_country_states` VALUES (291, 21, 'Seno');
INSERT INTO `ref_country_states` VALUES (292, 21, 'Sissili');
INSERT INTO `ref_country_states` VALUES (293, 21, 'Soum');
INSERT INTO `ref_country_states` VALUES (294, 21, 'Sourou');
INSERT INTO `ref_country_states` VALUES (295, 21, 'Tapoa');
INSERT INTO `ref_country_states` VALUES (296, 21, 'Tuy');
INSERT INTO `ref_country_states` VALUES (297, 21, 'Yagha');
INSERT INTO `ref_country_states` VALUES (298, 21, 'Yatenga');
INSERT INTO `ref_country_states` VALUES (299, 21, 'Ziro');
INSERT INTO `ref_country_states` VALUES (300, 21, 'Zondoma');
INSERT INTO `ref_country_states` VALUES (301, 21, 'Zoundweogo');
INSERT INTO `ref_country_states` VALUES (302, 22, 'Blagoevgrad');
INSERT INTO `ref_country_states` VALUES (303, 22, 'Burgas');
INSERT INTO `ref_country_states` VALUES (304, 22, 'Dobrich');
INSERT INTO `ref_country_states` VALUES (305, 22, 'Gabrovo');
INSERT INTO `ref_country_states` VALUES (306, 22, 'Khaskovo');
INSERT INTO `ref_country_states` VALUES (307, 22, 'Kurdzhali');
INSERT INTO `ref_country_states` VALUES (308, 22, 'Kyustendil');
INSERT INTO `ref_country_states` VALUES (309, 22, 'Lovech');
INSERT INTO `ref_country_states` VALUES (310, 22, 'Montana');
INSERT INTO `ref_country_states` VALUES (311, 22, 'Pazardzhik');
INSERT INTO `ref_country_states` VALUES (312, 22, 'Pernik');
INSERT INTO `ref_country_states` VALUES (313, 22, 'Pleven');
INSERT INTO `ref_country_states` VALUES (314, 22, 'Plovdiv');
INSERT INTO `ref_country_states` VALUES (315, 22, 'Razgrad');
INSERT INTO `ref_country_states` VALUES (316, 22, 'Ruse');
INSERT INTO `ref_country_states` VALUES (317, 22, 'Shumen');
INSERT INTO `ref_country_states` VALUES (318, 22, 'Silistra');
INSERT INTO `ref_country_states` VALUES (319, 22, 'Sliven');
INSERT INTO `ref_country_states` VALUES (320, 22, 'Smolyan');
INSERT INTO `ref_country_states` VALUES (321, 22, 'Sofiya');
INSERT INTO `ref_country_states` VALUES (322, 22, 'Sofiya-Grad');
INSERT INTO `ref_country_states` VALUES (323, 22, 'Stara Zagora');
INSERT INTO `ref_country_states` VALUES (324, 22, 'Turgovishte');
INSERT INTO `ref_country_states` VALUES (325, 22, 'Varna');
INSERT INTO `ref_country_states` VALUES (326, 22, 'Veliko Turnovo');
INSERT INTO `ref_country_states` VALUES (327, 22, 'Vidin');
INSERT INTO `ref_country_states` VALUES (328, 22, 'Vratsa');
INSERT INTO `ref_country_states` VALUES (329, 22, 'Yambol');
INSERT INTO `ref_country_states` VALUES (330, 23, 'Al Hadd');
INSERT INTO `ref_country_states` VALUES (331, 23, 'Al Manamah');
INSERT INTO `ref_country_states` VALUES (332, 23, 'Al Mintaqah al Gharbiyah');
INSERT INTO `ref_country_states` VALUES (333, 23, 'Al Mintaqah al Wusta');
INSERT INTO `ref_country_states` VALUES (334, 23, 'Al Mintaqah ash Shamaliyah');
INSERT INTO `ref_country_states` VALUES (335, 23, 'Al Muharraq');
INSERT INTO `ref_country_states` VALUES (336, 23, 'Ar Rifa\' wa al Mintaqah al Janubiyah');
INSERT INTO `ref_country_states` VALUES (337, 23, 'Jidd Hafs');
INSERT INTO `ref_country_states` VALUES (338, 23, 'Madinat Hamad');
INSERT INTO `ref_country_states` VALUES (339, 23, 'Madinat \'Isa');
INSERT INTO `ref_country_states` VALUES (340, 23, 'Juzur Hawar');
INSERT INTO `ref_country_states` VALUES (341, 23, 'Sitrah');
INSERT INTO `ref_country_states` VALUES (342, 24, 'Bubanza');
INSERT INTO `ref_country_states` VALUES (343, 24, 'Bujumbura Mairie');
INSERT INTO `ref_country_states` VALUES (344, 24, 'Bujumbura Rural');
INSERT INTO `ref_country_states` VALUES (345, 24, 'Bururi');
INSERT INTO `ref_country_states` VALUES (346, 24, 'Cankuzo');
INSERT INTO `ref_country_states` VALUES (347, 24, 'Cibitoke');
INSERT INTO `ref_country_states` VALUES (348, 24, 'Gitega');
INSERT INTO `ref_country_states` VALUES (349, 24, 'Karuzi');
INSERT INTO `ref_country_states` VALUES (350, 24, 'Kayanza');
INSERT INTO `ref_country_states` VALUES (351, 24, 'Kirundo');
INSERT INTO `ref_country_states` VALUES (352, 24, 'Makamba');
INSERT INTO `ref_country_states` VALUES (353, 24, 'Muramvya');
INSERT INTO `ref_country_states` VALUES (354, 24, 'Muyinga');
INSERT INTO `ref_country_states` VALUES (355, 24, 'Mwaro');
INSERT INTO `ref_country_states` VALUES (356, 24, 'Ngozi');
INSERT INTO `ref_country_states` VALUES (357, 24, 'Rutana');
INSERT INTO `ref_country_states` VALUES (358, 24, 'Ruyigi');
INSERT INTO `ref_country_states` VALUES (359, 25, 'Alibori');
INSERT INTO `ref_country_states` VALUES (360, 25, 'Atakora');
INSERT INTO `ref_country_states` VALUES (361, 25, 'Atlantique');
INSERT INTO `ref_country_states` VALUES (362, 25, 'Borgou');
INSERT INTO `ref_country_states` VALUES (363, 25, 'Collines');
INSERT INTO `ref_country_states` VALUES (364, 25, 'Donga');
INSERT INTO `ref_country_states` VALUES (365, 25, 'Kouffo');
INSERT INTO `ref_country_states` VALUES (366, 25, 'Littoral');
INSERT INTO `ref_country_states` VALUES (367, 25, 'Mono');
INSERT INTO `ref_country_states` VALUES (368, 25, 'Oueme');
INSERT INTO `ref_country_states` VALUES (369, 25, 'Plateau');
INSERT INTO `ref_country_states` VALUES (370, 25, 'Zou');
INSERT INTO `ref_country_states` VALUES (371, 27, 'Devonshire');
INSERT INTO `ref_country_states` VALUES (372, 27, 'Hamilton');
INSERT INTO `ref_country_states` VALUES (373, 27, 'Hamilton');
INSERT INTO `ref_country_states` VALUES (374, 27, 'Paget');
INSERT INTO `ref_country_states` VALUES (375, 27, 'Pembroke');
INSERT INTO `ref_country_states` VALUES (376, 27, 'Saint George');
INSERT INTO `ref_country_states` VALUES (377, 27, 'Saint George\'s');
INSERT INTO `ref_country_states` VALUES (378, 27, 'Sandys');
INSERT INTO `ref_country_states` VALUES (379, 27, 'Smith\'s');
INSERT INTO `ref_country_states` VALUES (380, 27, 'Southampton');
INSERT INTO `ref_country_states` VALUES (381, 27, 'Warwick');
INSERT INTO `ref_country_states` VALUES (382, 28, 'Belait');
INSERT INTO `ref_country_states` VALUES (383, 28, 'Brunei and Muara');
INSERT INTO `ref_country_states` VALUES (384, 28, 'Temburong');
INSERT INTO `ref_country_states` VALUES (385, 28, 'Tutong');
INSERT INTO `ref_country_states` VALUES (386, 29, 'Chuquisaca');
INSERT INTO `ref_country_states` VALUES (387, 29, 'Cochabamba');
INSERT INTO `ref_country_states` VALUES (388, 29, 'Beni');
INSERT INTO `ref_country_states` VALUES (389, 29, 'La Paz');
INSERT INTO `ref_country_states` VALUES (390, 29, 'Oruro');
INSERT INTO `ref_country_states` VALUES (391, 29, 'Pando');
INSERT INTO `ref_country_states` VALUES (392, 29, 'Potosi');
INSERT INTO `ref_country_states` VALUES (393, 29, 'Santa Cruz');
INSERT INTO `ref_country_states` VALUES (394, 29, 'Tarija');
INSERT INTO `ref_country_states` VALUES (395, 31, 'Acre');
INSERT INTO `ref_country_states` VALUES (396, 31, 'Alagoas');
INSERT INTO `ref_country_states` VALUES (397, 31, 'Amapa');
INSERT INTO `ref_country_states` VALUES (398, 31, 'Amazonas');
INSERT INTO `ref_country_states` VALUES (399, 31, 'Bahia');
INSERT INTO `ref_country_states` VALUES (400, 31, 'Ceara');
INSERT INTO `ref_country_states` VALUES (401, 31, 'Distrito Federal');
INSERT INTO `ref_country_states` VALUES (402, 31, 'Espirito Santo');
INSERT INTO `ref_country_states` VALUES (403, 31, 'Goias');
INSERT INTO `ref_country_states` VALUES (404, 31, 'Maranhao');
INSERT INTO `ref_country_states` VALUES (405, 31, 'Mato Grosso');
INSERT INTO `ref_country_states` VALUES (406, 31, 'Mato Grosso do Sul');
INSERT INTO `ref_country_states` VALUES (407, 31, 'Minas Gerais');
INSERT INTO `ref_country_states` VALUES (408, 31, 'Para');
INSERT INTO `ref_country_states` VALUES (409, 31, 'Paraiba');
INSERT INTO `ref_country_states` VALUES (410, 31, 'Parana');
INSERT INTO `ref_country_states` VALUES (411, 31, 'Pernambuco');
INSERT INTO `ref_country_states` VALUES (412, 31, 'Piaui');
INSERT INTO `ref_country_states` VALUES (413, 31, 'Rio de Janeiro');
INSERT INTO `ref_country_states` VALUES (414, 31, 'Rio Grande do Norte');
INSERT INTO `ref_country_states` VALUES (415, 31, 'Rio Grande do Sul');
INSERT INTO `ref_country_states` VALUES (416, 31, 'Rondonia');
INSERT INTO `ref_country_states` VALUES (417, 31, 'Roraima');
INSERT INTO `ref_country_states` VALUES (418, 31, 'Santa Catarina');
INSERT INTO `ref_country_states` VALUES (419, 31, 'Sao Paulo');
INSERT INTO `ref_country_states` VALUES (420, 31, 'Sergipe');
INSERT INTO `ref_country_states` VALUES (421, 31, 'Tocantins');
INSERT INTO `ref_country_states` VALUES (422, 32, 'Acklins and Crooked Islands');
INSERT INTO `ref_country_states` VALUES (423, 32, 'Bimini');
INSERT INTO `ref_country_states` VALUES (424, 32, 'Cat Island');
INSERT INTO `ref_country_states` VALUES (425, 32, 'Exuma');
INSERT INTO `ref_country_states` VALUES (426, 32, 'Freeport');
INSERT INTO `ref_country_states` VALUES (427, 32, 'Fresh Creek');
INSERT INTO `ref_country_states` VALUES (428, 32, 'Governor\'s Harbour');
INSERT INTO `ref_country_states` VALUES (429, 32, 'Green Turtle Cay');
INSERT INTO `ref_country_states` VALUES (430, 32, 'Harbour Island');
INSERT INTO `ref_country_states` VALUES (431, 32, 'High Rock');
INSERT INTO `ref_country_states` VALUES (432, 32, 'Inagua');
INSERT INTO `ref_country_states` VALUES (433, 32, 'Kemps Bay');
INSERT INTO `ref_country_states` VALUES (434, 32, 'Long Island');
INSERT INTO `ref_country_states` VALUES (435, 32, 'Marsh Harbour');
INSERT INTO `ref_country_states` VALUES (436, 32, 'Mayaguana');
INSERT INTO `ref_country_states` VALUES (437, 32, 'New Providence');
INSERT INTO `ref_country_states` VALUES (438, 32, 'Nichollstown and Berry Islands');
INSERT INTO `ref_country_states` VALUES (439, 32, 'Ragged Island');
INSERT INTO `ref_country_states` VALUES (440, 32, 'Rock Sound');
INSERT INTO `ref_country_states` VALUES (441, 32, 'Sandy Point');
INSERT INTO `ref_country_states` VALUES (442, 32, 'San Salvador and Rum Cay');
INSERT INTO `ref_country_states` VALUES (443, 33, 'Bumthang');
INSERT INTO `ref_country_states` VALUES (444, 33, 'Chukha');
INSERT INTO `ref_country_states` VALUES (445, 33, 'Dagana');
INSERT INTO `ref_country_states` VALUES (446, 33, 'Gasa');
INSERT INTO `ref_country_states` VALUES (447, 33, 'Haa');
INSERT INTO `ref_country_states` VALUES (448, 33, 'Lhuntse');
INSERT INTO `ref_country_states` VALUES (449, 33, 'Mongar');
INSERT INTO `ref_country_states` VALUES (450, 33, 'Paro');
INSERT INTO `ref_country_states` VALUES (451, 33, 'Pemagatshel');
INSERT INTO `ref_country_states` VALUES (452, 33, 'Punakha');
INSERT INTO `ref_country_states` VALUES (453, 33, 'Samdrup Jongkhar');
INSERT INTO `ref_country_states` VALUES (454, 33, 'Samtse');
INSERT INTO `ref_country_states` VALUES (455, 33, 'Sarpang');
INSERT INTO `ref_country_states` VALUES (456, 33, 'Thimphu');
INSERT INTO `ref_country_states` VALUES (457, 33, 'Trashigang');
INSERT INTO `ref_country_states` VALUES (458, 33, 'Trashiyangste');
INSERT INTO `ref_country_states` VALUES (459, 33, 'Trongsa');
INSERT INTO `ref_country_states` VALUES (460, 33, 'Tsirang');
INSERT INTO `ref_country_states` VALUES (461, 33, 'Wangdue Phodrang');
INSERT INTO `ref_country_states` VALUES (462, 33, 'Zhemgang');
INSERT INTO `ref_country_states` VALUES (463, 35, 'Central');
INSERT INTO `ref_country_states` VALUES (464, 35, 'Ghanzi');
INSERT INTO `ref_country_states` VALUES (465, 35, 'Kgalagadi');
INSERT INTO `ref_country_states` VALUES (466, 35, 'Kgatleng');
INSERT INTO `ref_country_states` VALUES (467, 35, 'Kweneng');
INSERT INTO `ref_country_states` VALUES (468, 35, 'North East');
INSERT INTO `ref_country_states` VALUES (469, 35, 'North West');
INSERT INTO `ref_country_states` VALUES (470, 35, 'South East');
INSERT INTO `ref_country_states` VALUES (471, 35, 'Southern');
INSERT INTO `ref_country_states` VALUES (472, 36, 'Brest');
INSERT INTO `ref_country_states` VALUES (473, 36, 'Homyel');
INSERT INTO `ref_country_states` VALUES (474, 36, 'Horad Minsk');
INSERT INTO `ref_country_states` VALUES (475, 36, 'Hrodna');
INSERT INTO `ref_country_states` VALUES (476, 36, 'Mahilyow');
INSERT INTO `ref_country_states` VALUES (477, 36, 'Minsk');
INSERT INTO `ref_country_states` VALUES (478, 36, 'Vitsyebsk');
INSERT INTO `ref_country_states` VALUES (479, 37, 'Belize');
INSERT INTO `ref_country_states` VALUES (480, 37, 'Cayo');
INSERT INTO `ref_country_states` VALUES (481, 37, 'Corozal');
INSERT INTO `ref_country_states` VALUES (482, 37, 'Orange Walk');
INSERT INTO `ref_country_states` VALUES (483, 37, 'Stann Creek');
INSERT INTO `ref_country_states` VALUES (484, 37, 'Toledo');
INSERT INTO `ref_country_states` VALUES (485, 38, 'Alberta');
INSERT INTO `ref_country_states` VALUES (486, 38, 'British Columbia');
INSERT INTO `ref_country_states` VALUES (487, 38, 'Manitoba');
INSERT INTO `ref_country_states` VALUES (488, 38, 'New Brunswick');
INSERT INTO `ref_country_states` VALUES (489, 38, 'Newfoundland and Labrador');
INSERT INTO `ref_country_states` VALUES (490, 38, 'Northwest Territories');
INSERT INTO `ref_country_states` VALUES (491, 38, 'Nova Scotia');
INSERT INTO `ref_country_states` VALUES (492, 38, 'Nunavut');
INSERT INTO `ref_country_states` VALUES (493, 38, 'Ontario');
INSERT INTO `ref_country_states` VALUES (494, 38, 'Prince Edward Island');
INSERT INTO `ref_country_states` VALUES (495, 38, 'Quebec');
INSERT INTO `ref_country_states` VALUES (496, 38, 'Saskatchewan');
INSERT INTO `ref_country_states` VALUES (497, 38, 'Yukon Territory');
INSERT INTO `ref_country_states` VALUES (498, 41, 'Bamingui-Bangoran');
INSERT INTO `ref_country_states` VALUES (499, 41, 'Bangui');
INSERT INTO `ref_country_states` VALUES (500, 41, 'Basse-Kotto');
INSERT INTO `ref_country_states` VALUES (501, 41, 'Haute-Kotto');
INSERT INTO `ref_country_states` VALUES (502, 41, 'Haut-Mbomou');
INSERT INTO `ref_country_states` VALUES (503, 41, 'Kemo');
INSERT INTO `ref_country_states` VALUES (504, 41, 'Lobaye');
INSERT INTO `ref_country_states` VALUES (505, 41, 'Mambere-Kadei');
INSERT INTO `ref_country_states` VALUES (506, 41, 'Mbomou');
INSERT INTO `ref_country_states` VALUES (507, 41, 'Nana-Grebizi');
INSERT INTO `ref_country_states` VALUES (508, 41, 'Nana-Mambere');
INSERT INTO `ref_country_states` VALUES (509, 41, 'Ombella-Mpoko');
INSERT INTO `ref_country_states` VALUES (510, 41, 'Ouaka');
INSERT INTO `ref_country_states` VALUES (511, 41, 'Ouham');
INSERT INTO `ref_country_states` VALUES (512, 41, 'Ouham-Pende');
INSERT INTO `ref_country_states` VALUES (513, 41, 'Sangha-Mbaere');
INSERT INTO `ref_country_states` VALUES (514, 41, 'Vakaga');
INSERT INTO `ref_country_states` VALUES (515, 43, 'Aargau');
INSERT INTO `ref_country_states` VALUES (516, 43, 'Appenzell Ausser-Rhoden');
INSERT INTO `ref_country_states` VALUES (517, 43, 'Appenzell Inner-Rhoden');
INSERT INTO `ref_country_states` VALUES (518, 43, 'Basel-Landschaft');
INSERT INTO `ref_country_states` VALUES (519, 43, 'Basel-Stadt');
INSERT INTO `ref_country_states` VALUES (520, 43, 'Bern');
INSERT INTO `ref_country_states` VALUES (521, 43, 'Fribourg');
INSERT INTO `ref_country_states` VALUES (522, 43, 'Geneve');
INSERT INTO `ref_country_states` VALUES (523, 43, 'Glarus');
INSERT INTO `ref_country_states` VALUES (524, 43, 'Graubunden');
INSERT INTO `ref_country_states` VALUES (525, 43, 'Jura');
INSERT INTO `ref_country_states` VALUES (526, 43, 'Luzern');
INSERT INTO `ref_country_states` VALUES (527, 43, 'Neuchatel');
INSERT INTO `ref_country_states` VALUES (528, 43, 'Nidwalden');
INSERT INTO `ref_country_states` VALUES (529, 43, 'Obwalden');
INSERT INTO `ref_country_states` VALUES (530, 43, 'Sankt Gallen');
INSERT INTO `ref_country_states` VALUES (531, 43, 'Schaffhausen');
INSERT INTO `ref_country_states` VALUES (532, 43, 'Schwyz');
INSERT INTO `ref_country_states` VALUES (533, 43, 'Solothurn');
INSERT INTO `ref_country_states` VALUES (534, 43, 'Thurgau');
INSERT INTO `ref_country_states` VALUES (535, 43, 'Ticino');
INSERT INTO `ref_country_states` VALUES (536, 43, 'Uri');
INSERT INTO `ref_country_states` VALUES (537, 43, 'Valais');
INSERT INTO `ref_country_states` VALUES (538, 43, 'Vaud');
INSERT INTO `ref_country_states` VALUES (539, 43, 'Zug');
INSERT INTO `ref_country_states` VALUES (540, 43, 'Zurich');
INSERT INTO `ref_country_states` VALUES (541, 46, 'Aysen');
INSERT INTO `ref_country_states` VALUES (542, 46, 'Antofagasta');
INSERT INTO `ref_country_states` VALUES (543, 46, 'Araucania');
INSERT INTO `ref_country_states` VALUES (544, 46, 'Atacama');
INSERT INTO `ref_country_states` VALUES (545, 46, 'Bio-Bio');
INSERT INTO `ref_country_states` VALUES (546, 46, 'Coquimbo');
INSERT INTO `ref_country_states` VALUES (547, 46, 'O\'Higgins');
INSERT INTO `ref_country_states` VALUES (548, 46, 'Los Lagos');
INSERT INTO `ref_country_states` VALUES (549, 46, 'Magallanes y la Antartica Chilena');
INSERT INTO `ref_country_states` VALUES (550, 46, 'Maule');
INSERT INTO `ref_country_states` VALUES (551, 46, 'Santiago Region Metropolitana');
INSERT INTO `ref_country_states` VALUES (552, 46, 'Tarapaca');
INSERT INTO `ref_country_states` VALUES (553, 46, 'Valparaiso');
INSERT INTO `ref_country_states` VALUES (554, 47, 'Adamaoua');
INSERT INTO `ref_country_states` VALUES (555, 47, 'Centre');
INSERT INTO `ref_country_states` VALUES (556, 47, 'Est');
INSERT INTO `ref_country_states` VALUES (557, 47, 'Extreme-Nord');
INSERT INTO `ref_country_states` VALUES (558, 47, 'Littoral');
INSERT INTO `ref_country_states` VALUES (559, 47, 'Nord');
INSERT INTO `ref_country_states` VALUES (560, 47, 'Nord-Ouest');
INSERT INTO `ref_country_states` VALUES (561, 47, 'Ouest');
INSERT INTO `ref_country_states` VALUES (562, 47, 'Sud');
INSERT INTO `ref_country_states` VALUES (563, 47, 'Sud-Ouest');
INSERT INTO `ref_country_states` VALUES (564, 48, 'Anhui');
INSERT INTO `ref_country_states` VALUES (565, 48, 'Fujian');
INSERT INTO `ref_country_states` VALUES (566, 48, 'Gansu');
INSERT INTO `ref_country_states` VALUES (567, 48, 'Guangdong');
INSERT INTO `ref_country_states` VALUES (568, 48, 'Guizhou');
INSERT INTO `ref_country_states` VALUES (569, 48, 'Hainan');
INSERT INTO `ref_country_states` VALUES (570, 48, 'Hebei');
INSERT INTO `ref_country_states` VALUES (571, 48, 'Heilongjiang');
INSERT INTO `ref_country_states` VALUES (572, 48, 'Henan');
INSERT INTO `ref_country_states` VALUES (573, 48, 'Hubei');
INSERT INTO `ref_country_states` VALUES (574, 48, 'Hunan');
INSERT INTO `ref_country_states` VALUES (575, 48, 'Jiangsu');
INSERT INTO `ref_country_states` VALUES (576, 48, 'Jiangxi');
INSERT INTO `ref_country_states` VALUES (577, 48, 'Jilin');
INSERT INTO `ref_country_states` VALUES (578, 48, 'Liaoning');
INSERT INTO `ref_country_states` VALUES (579, 48, 'Qinghai');
INSERT INTO `ref_country_states` VALUES (580, 48, 'Shaanxi');
INSERT INTO `ref_country_states` VALUES (581, 48, 'Shandong');
INSERT INTO `ref_country_states` VALUES (582, 48, 'Shanxi');
INSERT INTO `ref_country_states` VALUES (583, 48, 'Sichuan');
INSERT INTO `ref_country_states` VALUES (584, 48, 'Yunnan');
INSERT INTO `ref_country_states` VALUES (585, 48, 'Zhejiang');
INSERT INTO `ref_country_states` VALUES (586, 48, 'Guangxi');
INSERT INTO `ref_country_states` VALUES (587, 48, 'Nei Mongol');
INSERT INTO `ref_country_states` VALUES (588, 48, 'Ningxia');
INSERT INTO `ref_country_states` VALUES (589, 48, 'Xinjiang');
INSERT INTO `ref_country_states` VALUES (590, 48, 'Xizang (Tibet)');
INSERT INTO `ref_country_states` VALUES (591, 48, 'Beijing');
INSERT INTO `ref_country_states` VALUES (592, 48, 'Chongqing');
INSERT INTO `ref_country_states` VALUES (593, 48, 'Shanghai');
INSERT INTO `ref_country_states` VALUES (594, 48, 'Tianjin');
INSERT INTO `ref_country_states` VALUES (595, 49, 'Amazonas');
INSERT INTO `ref_country_states` VALUES (596, 49, 'Antioquia');
INSERT INTO `ref_country_states` VALUES (597, 49, 'Arauca');
INSERT INTO `ref_country_states` VALUES (598, 49, 'Atlantico');
INSERT INTO `ref_country_states` VALUES (599, 49, 'Bogota District Capital');
INSERT INTO `ref_country_states` VALUES (600, 49, 'Bolivar');
INSERT INTO `ref_country_states` VALUES (601, 49, 'Boyaca');
INSERT INTO `ref_country_states` VALUES (602, 49, 'Caldas');
INSERT INTO `ref_country_states` VALUES (603, 49, 'Caqueta');
INSERT INTO `ref_country_states` VALUES (604, 49, 'Casanare');
INSERT INTO `ref_country_states` VALUES (605, 49, 'Cauca');
INSERT INTO `ref_country_states` VALUES (606, 49, 'Cesar');
INSERT INTO `ref_country_states` VALUES (607, 49, 'Choco');
INSERT INTO `ref_country_states` VALUES (608, 49, 'Cordoba');
INSERT INTO `ref_country_states` VALUES (609, 49, 'Cundinamarca');
INSERT INTO `ref_country_states` VALUES (610, 49, 'Guainia');
INSERT INTO `ref_country_states` VALUES (611, 49, 'Guaviare');
INSERT INTO `ref_country_states` VALUES (612, 49, 'Huila');
INSERT INTO `ref_country_states` VALUES (613, 49, 'La Guajira');
INSERT INTO `ref_country_states` VALUES (614, 49, 'Magdalena');
INSERT INTO `ref_country_states` VALUES (615, 49, 'Meta');
INSERT INTO `ref_country_states` VALUES (616, 49, 'Narino');
INSERT INTO `ref_country_states` VALUES (617, 49, 'Norte de Santander');
INSERT INTO `ref_country_states` VALUES (618, 49, 'Putumayo');
INSERT INTO `ref_country_states` VALUES (619, 49, 'Quindio');
INSERT INTO `ref_country_states` VALUES (620, 49, 'Risaralda');
INSERT INTO `ref_country_states` VALUES (621, 49, 'San Andres & Providencia');
INSERT INTO `ref_country_states` VALUES (622, 49, 'Santander');
INSERT INTO `ref_country_states` VALUES (623, 49, 'Sucre');
INSERT INTO `ref_country_states` VALUES (624, 49, 'Tolima');
INSERT INTO `ref_country_states` VALUES (625, 49, 'Valle del Cauca');
INSERT INTO `ref_country_states` VALUES (626, 49, 'Vaupes');
INSERT INTO `ref_country_states` VALUES (627, 49, 'Vichada');
INSERT INTO `ref_country_states` VALUES (628, 50, 'Alajuela');
INSERT INTO `ref_country_states` VALUES (629, 50, 'Cartago');
INSERT INTO `ref_country_states` VALUES (630, 50, 'Guanacaste');
INSERT INTO `ref_country_states` VALUES (631, 50, 'Heredia');
INSERT INTO `ref_country_states` VALUES (632, 50, 'Limon');
INSERT INTO `ref_country_states` VALUES (633, 50, 'Puntarenas');
INSERT INTO `ref_country_states` VALUES (634, 50, 'San Jose');
INSERT INTO `ref_country_states` VALUES (635, 51, 'Camaguey');
INSERT INTO `ref_country_states` VALUES (636, 51, 'Ciego de Avila');
INSERT INTO `ref_country_states` VALUES (637, 51, 'Cienfuegos');
INSERT INTO `ref_country_states` VALUES (638, 51, 'Ciudad de La Habana');
INSERT INTO `ref_country_states` VALUES (639, 51, 'Granma');
INSERT INTO `ref_country_states` VALUES (640, 51, 'Guantanamo');
INSERT INTO `ref_country_states` VALUES (641, 51, 'Holguin');
INSERT INTO `ref_country_states` VALUES (642, 51, 'Isla de la Juventud');
INSERT INTO `ref_country_states` VALUES (643, 51, 'La Habana');
INSERT INTO `ref_country_states` VALUES (644, 51, 'Las Tunas');
INSERT INTO `ref_country_states` VALUES (645, 51, 'Matanzas');
INSERT INTO `ref_country_states` VALUES (646, 51, 'Pinar del Rio');
INSERT INTO `ref_country_states` VALUES (647, 51, 'Sancti Spiritus');
INSERT INTO `ref_country_states` VALUES (648, 51, 'Santiago de Cuba');
INSERT INTO `ref_country_states` VALUES (649, 51, 'Villa Clara');
INSERT INTO `ref_country_states` VALUES (650, 52, 'Santo Antão');
INSERT INTO `ref_country_states` VALUES (651, 52, 'São Vicente');
INSERT INTO `ref_country_states` VALUES (652, 52, 'Santa Luzia,');
INSERT INTO `ref_country_states` VALUES (653, 52, 'São Nicolau');
INSERT INTO `ref_country_states` VALUES (654, 52, 'Sal');
INSERT INTO `ref_country_states` VALUES (655, 52, 'Boa Vista');
INSERT INTO `ref_country_states` VALUES (656, 52, 'Maio');
INSERT INTO `ref_country_states` VALUES (657, 52, 'Santiago');
INSERT INTO `ref_country_states` VALUES (658, 52, 'Fogo');
INSERT INTO `ref_country_states` VALUES (659, 52, 'Brava');
INSERT INTO `ref_country_states` VALUES (660, 55, 'Famagusta');
INSERT INTO `ref_country_states` VALUES (661, 55, 'Kyrenia');
INSERT INTO `ref_country_states` VALUES (662, 55, 'Larnaca');
INSERT INTO `ref_country_states` VALUES (663, 55, 'Limassol');
INSERT INTO `ref_country_states` VALUES (664, 55, 'Nicosia');
INSERT INTO `ref_country_states` VALUES (665, 55, 'Paphos');
INSERT INTO `ref_country_states` VALUES (666, 56, 'Jihocesky Kraj');
INSERT INTO `ref_country_states` VALUES (667, 56, 'Jihomoravsky Kraj');
INSERT INTO `ref_country_states` VALUES (668, 56, 'Karlovarsky Kraj');
INSERT INTO `ref_country_states` VALUES (669, 56, 'Kralovehradecky Kraj');
INSERT INTO `ref_country_states` VALUES (670, 56, 'Liberecky Kraj');
INSERT INTO `ref_country_states` VALUES (671, 56, 'Moravskoslezsky Kraj');
INSERT INTO `ref_country_states` VALUES (672, 56, 'Olomoucky Kraj');
INSERT INTO `ref_country_states` VALUES (673, 56, 'Pardubicky Kraj');
INSERT INTO `ref_country_states` VALUES (674, 56, 'Plzensky Kraj');
INSERT INTO `ref_country_states` VALUES (675, 56, 'Praha');
INSERT INTO `ref_country_states` VALUES (676, 56, 'Stredocesky Kraj');
INSERT INTO `ref_country_states` VALUES (677, 56, 'Ustecky Kraj');
INSERT INTO `ref_country_states` VALUES (678, 56, 'Vysocina');
INSERT INTO `ref_country_states` VALUES (679, 56, 'Zlinsky Kraj');
INSERT INTO `ref_country_states` VALUES (680, 57, 'Baden-Wuerttemberg');
INSERT INTO `ref_country_states` VALUES (681, 57, 'Bayern');
INSERT INTO `ref_country_states` VALUES (682, 57, 'Berlin');
INSERT INTO `ref_country_states` VALUES (683, 57, 'Brandenburg');
INSERT INTO `ref_country_states` VALUES (684, 57, 'Bremen');
INSERT INTO `ref_country_states` VALUES (685, 57, 'Hamburg');
INSERT INTO `ref_country_states` VALUES (686, 57, 'Hessen');
INSERT INTO `ref_country_states` VALUES (687, 57, 'Mecklenburg-Vorpommern');
INSERT INTO `ref_country_states` VALUES (688, 57, 'Niedersachsen');
INSERT INTO `ref_country_states` VALUES (689, 57, 'Nordrhein-Westfalen');
INSERT INTO `ref_country_states` VALUES (690, 57, 'Rheinland-Pfalz');
INSERT INTO `ref_country_states` VALUES (691, 57, 'Saarland');
INSERT INTO `ref_country_states` VALUES (692, 57, 'Sachsen');
INSERT INTO `ref_country_states` VALUES (693, 57, 'Sachsen-Anhalt');
INSERT INTO `ref_country_states` VALUES (694, 57, 'Schleswig-Holstein');
INSERT INTO `ref_country_states` VALUES (695, 57, 'Thueringen');
INSERT INTO `ref_country_states` VALUES (696, 58, 'Ali Sabih');
INSERT INTO `ref_country_states` VALUES (697, 58, 'Dikhil');
INSERT INTO `ref_country_states` VALUES (698, 58, 'Djibouti');
INSERT INTO `ref_country_states` VALUES (699, 58, 'Obock');
INSERT INTO `ref_country_states` VALUES (700, 58, 'Tadjoura');
INSERT INTO `ref_country_states` VALUES (701, 59, 'Arhus');
INSERT INTO `ref_country_states` VALUES (702, 59, 'Bornholm');
INSERT INTO `ref_country_states` VALUES (703, 59, 'Frederiksberg');
INSERT INTO `ref_country_states` VALUES (704, 59, 'Frederiksborg');
INSERT INTO `ref_country_states` VALUES (705, 59, 'Fyn');
INSERT INTO `ref_country_states` VALUES (706, 59, 'Kobenhavn');
INSERT INTO `ref_country_states` VALUES (707, 59, 'Kobenhavns');
INSERT INTO `ref_country_states` VALUES (708, 59, 'Nordjylland');
INSERT INTO `ref_country_states` VALUES (709, 59, 'Ribe');
INSERT INTO `ref_country_states` VALUES (710, 59, 'Ringkobing');
INSERT INTO `ref_country_states` VALUES (711, 59, 'Roskilde');
INSERT INTO `ref_country_states` VALUES (712, 59, 'Sonderjylland');
INSERT INTO `ref_country_states` VALUES (713, 59, 'Storstrom');
INSERT INTO `ref_country_states` VALUES (714, 59, 'Vejle');
INSERT INTO `ref_country_states` VALUES (715, 59, 'Vestsjalland');
INSERT INTO `ref_country_states` VALUES (716, 59, 'Viborg');
INSERT INTO `ref_country_states` VALUES (717, 60, 'Saint Andrew');
INSERT INTO `ref_country_states` VALUES (718, 60, 'Saint David');
INSERT INTO `ref_country_states` VALUES (719, 60, 'Saint George');
INSERT INTO `ref_country_states` VALUES (720, 60, 'Saint John');
INSERT INTO `ref_country_states` VALUES (721, 60, 'Saint Joseph');
INSERT INTO `ref_country_states` VALUES (722, 60, 'Saint Luke');
INSERT INTO `ref_country_states` VALUES (723, 60, 'Saint Mark');
INSERT INTO `ref_country_states` VALUES (724, 60, 'Saint Patrick');
INSERT INTO `ref_country_states` VALUES (725, 60, 'Saint Paul');
INSERT INTO `ref_country_states` VALUES (726, 60, 'Saint Peter');
INSERT INTO `ref_country_states` VALUES (727, 61, 'Azua');
INSERT INTO `ref_country_states` VALUES (728, 61, 'Baoruco');
INSERT INTO `ref_country_states` VALUES (729, 61, 'Barahona');
INSERT INTO `ref_country_states` VALUES (730, 61, 'Dajabon');
INSERT INTO `ref_country_states` VALUES (731, 61, 'Distrito Nacional');
INSERT INTO `ref_country_states` VALUES (732, 61, 'Duarte');
INSERT INTO `ref_country_states` VALUES (733, 61, 'Elias Pina');
INSERT INTO `ref_country_states` VALUES (734, 61, 'El Seibo');
INSERT INTO `ref_country_states` VALUES (735, 61, 'Espaillat');
INSERT INTO `ref_country_states` VALUES (736, 61, 'Hato Mayor');
INSERT INTO `ref_country_states` VALUES (737, 61, 'Independencia');
INSERT INTO `ref_country_states` VALUES (738, 61, 'La Altagracia');
INSERT INTO `ref_country_states` VALUES (739, 61, 'La Romana');
INSERT INTO `ref_country_states` VALUES (740, 61, 'La Vega');
INSERT INTO `ref_country_states` VALUES (741, 61, 'Maria Trinidad Sanchez');
INSERT INTO `ref_country_states` VALUES (742, 61, 'Monsenor Nouel');
INSERT INTO `ref_country_states` VALUES (743, 61, 'Monte Cristi');
INSERT INTO `ref_country_states` VALUES (744, 61, 'Monte Plata');
INSERT INTO `ref_country_states` VALUES (745, 61, 'Pedernales');
INSERT INTO `ref_country_states` VALUES (746, 61, 'Peravia');
INSERT INTO `ref_country_states` VALUES (747, 61, 'Puerto Plata');
INSERT INTO `ref_country_states` VALUES (748, 61, 'Salcedo');
INSERT INTO `ref_country_states` VALUES (749, 61, 'Samana');
INSERT INTO `ref_country_states` VALUES (750, 61, 'Sanchez Ramirez');
INSERT INTO `ref_country_states` VALUES (751, 61, 'San Cristobal');
INSERT INTO `ref_country_states` VALUES (752, 61, 'San Jose de Ocoa');
INSERT INTO `ref_country_states` VALUES (753, 61, 'San Juan');
INSERT INTO `ref_country_states` VALUES (754, 61, 'San Pedro de Macoris');
INSERT INTO `ref_country_states` VALUES (755, 61, 'Santiago');
INSERT INTO `ref_country_states` VALUES (756, 61, 'Santiago Rodriguez');
INSERT INTO `ref_country_states` VALUES (757, 61, 'Santo Domingo');
INSERT INTO `ref_country_states` VALUES (758, 61, 'Valverde');
INSERT INTO `ref_country_states` VALUES (759, 62, 'Adrar');
INSERT INTO `ref_country_states` VALUES (760, 62, 'Ain Defla');
INSERT INTO `ref_country_states` VALUES (761, 62, 'Ain Temouchent');
INSERT INTO `ref_country_states` VALUES (762, 62, 'Alger');
INSERT INTO `ref_country_states` VALUES (763, 62, 'Annaba');
INSERT INTO `ref_country_states` VALUES (764, 62, 'Batna');
INSERT INTO `ref_country_states` VALUES (765, 62, 'Bechar');
INSERT INTO `ref_country_states` VALUES (766, 62, 'Bejaia');
INSERT INTO `ref_country_states` VALUES (767, 62, 'Biskra');
INSERT INTO `ref_country_states` VALUES (768, 62, 'Blida');
INSERT INTO `ref_country_states` VALUES (769, 62, 'Bordj Bou Arreridj');
INSERT INTO `ref_country_states` VALUES (770, 62, 'Bouira');
INSERT INTO `ref_country_states` VALUES (771, 62, 'Boumerdes');
INSERT INTO `ref_country_states` VALUES (772, 62, 'Chlef');
INSERT INTO `ref_country_states` VALUES (773, 62, 'Constantine');
INSERT INTO `ref_country_states` VALUES (774, 62, 'Djelfa');
INSERT INTO `ref_country_states` VALUES (775, 62, 'El Bayadh');
INSERT INTO `ref_country_states` VALUES (776, 62, 'El Oued');
INSERT INTO `ref_country_states` VALUES (777, 62, 'El Tarf');
INSERT INTO `ref_country_states` VALUES (778, 62, 'Ghardaia');
INSERT INTO `ref_country_states` VALUES (779, 62, 'Guelma');
INSERT INTO `ref_country_states` VALUES (780, 62, 'Illizi');
INSERT INTO `ref_country_states` VALUES (781, 62, 'Jijel');
INSERT INTO `ref_country_states` VALUES (782, 62, 'Khenchela');
INSERT INTO `ref_country_states` VALUES (783, 62, 'Laghouat');
INSERT INTO `ref_country_states` VALUES (784, 62, 'Muaskar');
INSERT INTO `ref_country_states` VALUES (785, 62, 'Medea');
INSERT INTO `ref_country_states` VALUES (786, 62, 'Mila');
INSERT INTO `ref_country_states` VALUES (787, 62, 'Mostaganem');
INSERT INTO `ref_country_states` VALUES (788, 62, 'M\'Sila');
INSERT INTO `ref_country_states` VALUES (789, 62, 'Naama');
INSERT INTO `ref_country_states` VALUES (790, 62, 'Oran');
INSERT INTO `ref_country_states` VALUES (791, 62, 'Ouargla');
INSERT INTO `ref_country_states` VALUES (792, 62, 'Oum el Bouaghi');
INSERT INTO `ref_country_states` VALUES (793, 62, 'Relizane');
INSERT INTO `ref_country_states` VALUES (794, 62, 'Saida');
INSERT INTO `ref_country_states` VALUES (795, 62, 'Setif');
INSERT INTO `ref_country_states` VALUES (796, 62, 'Sidi Bel Abbes');
INSERT INTO `ref_country_states` VALUES (797, 62, 'Skikda');
INSERT INTO `ref_country_states` VALUES (798, 62, 'Souk Ahras');
INSERT INTO `ref_country_states` VALUES (799, 62, 'Tamanghasset');
INSERT INTO `ref_country_states` VALUES (800, 62, 'Tebessa');
INSERT INTO `ref_country_states` VALUES (801, 62, 'Tiaret');
INSERT INTO `ref_country_states` VALUES (802, 62, 'Tindouf');
INSERT INTO `ref_country_states` VALUES (803, 62, 'Tipaza');
INSERT INTO `ref_country_states` VALUES (804, 62, 'Tissemsilt');
INSERT INTO `ref_country_states` VALUES (805, 62, 'Tizi Ouzou');
INSERT INTO `ref_country_states` VALUES (806, 62, 'Tlemcen');
INSERT INTO `ref_country_states` VALUES (807, 63, 'Azuay');
INSERT INTO `ref_country_states` VALUES (808, 63, 'Bolivar');
INSERT INTO `ref_country_states` VALUES (809, 63, 'Canar');
INSERT INTO `ref_country_states` VALUES (810, 63, 'Carchi');
INSERT INTO `ref_country_states` VALUES (811, 63, 'Chimborazo');
INSERT INTO `ref_country_states` VALUES (812, 63, 'Cotopaxi');
INSERT INTO `ref_country_states` VALUES (813, 63, 'El Oro');
INSERT INTO `ref_country_states` VALUES (814, 63, 'Esmeraldas');
INSERT INTO `ref_country_states` VALUES (815, 63, 'Galapagos');
INSERT INTO `ref_country_states` VALUES (816, 63, 'Guayas');
INSERT INTO `ref_country_states` VALUES (817, 63, 'Imbabura');
INSERT INTO `ref_country_states` VALUES (818, 63, 'Loja');
INSERT INTO `ref_country_states` VALUES (819, 63, 'Los Rios');
INSERT INTO `ref_country_states` VALUES (820, 63, 'Manabi');
INSERT INTO `ref_country_states` VALUES (821, 63, 'Morona-Santiago');
INSERT INTO `ref_country_states` VALUES (822, 63, 'Napo');
INSERT INTO `ref_country_states` VALUES (823, 63, 'Orellana');
INSERT INTO `ref_country_states` VALUES (824, 63, 'Pastaza');
INSERT INTO `ref_country_states` VALUES (825, 63, 'Pichincha');
INSERT INTO `ref_country_states` VALUES (826, 63, 'Sucumbios');
INSERT INTO `ref_country_states` VALUES (827, 63, 'Tungurahua');
INSERT INTO `ref_country_states` VALUES (828, 63, 'Zamora-Chinchipe');
INSERT INTO `ref_country_states` VALUES (829, 64, 'Harjumaa (Tallinn)');
INSERT INTO `ref_country_states` VALUES (830, 64, 'Hiiumaa (Kardla)');
INSERT INTO `ref_country_states` VALUES (831, 64, 'Ida-Virumaa (Johvi)');
INSERT INTO `ref_country_states` VALUES (832, 64, 'Jarvamaa (Paide)');
INSERT INTO `ref_country_states` VALUES (833, 64, 'Jogevamaa (Jogeva)');
INSERT INTO `ref_country_states` VALUES (834, 64, 'Laanemaa (Haapsalu)');
INSERT INTO `ref_country_states` VALUES (835, 64, 'Laane-Virumaa (Rakvere)');
INSERT INTO `ref_country_states` VALUES (836, 64, 'Parnumaa (Parnu)');
INSERT INTO `ref_country_states` VALUES (837, 64, 'Polvamaa (Polva)');
INSERT INTO `ref_country_states` VALUES (838, 64, 'Raplamaa (Rapla)');
INSERT INTO `ref_country_states` VALUES (839, 64, 'Saaremaa (Kuressaare)');
INSERT INTO `ref_country_states` VALUES (840, 64, 'Tartumaa (Tartu)');
INSERT INTO `ref_country_states` VALUES (841, 64, 'Valgamaa (Valga)');
INSERT INTO `ref_country_states` VALUES (842, 64, 'Viljandimaa (Viljandi)');
INSERT INTO `ref_country_states` VALUES (843, 64, 'Vorumaa (Voru)');
INSERT INTO `ref_country_states` VALUES (844, 65, 'Ad Daqahliyah');
INSERT INTO `ref_country_states` VALUES (845, 65, 'Al Bahr al Ahmar');
INSERT INTO `ref_country_states` VALUES (846, 65, 'Al Buhayrah');
INSERT INTO `ref_country_states` VALUES (847, 65, 'Al Fayyum');
INSERT INTO `ref_country_states` VALUES (848, 65, 'Al Gharbiyah');
INSERT INTO `ref_country_states` VALUES (849, 65, 'Al Iskandariyah');
INSERT INTO `ref_country_states` VALUES (850, 65, 'Al Isma\'iliyah');
INSERT INTO `ref_country_states` VALUES (851, 65, 'Al Jizah');
INSERT INTO `ref_country_states` VALUES (852, 65, 'Al Minufiyah');
INSERT INTO `ref_country_states` VALUES (853, 65, 'Al Minya');
INSERT INTO `ref_country_states` VALUES (854, 65, 'Al Qahirah');
INSERT INTO `ref_country_states` VALUES (855, 65, 'Al Qalyubiyah');
INSERT INTO `ref_country_states` VALUES (856, 65, 'Al Wadi al Jadid');
INSERT INTO `ref_country_states` VALUES (857, 65, 'Ash Sharqiyah');
INSERT INTO `ref_country_states` VALUES (858, 65, 'As Suways');
INSERT INTO `ref_country_states` VALUES (859, 65, 'Aswan');
INSERT INTO `ref_country_states` VALUES (860, 65, 'Asyut');
INSERT INTO `ref_country_states` VALUES (861, 65, 'Bani Suwayf');
INSERT INTO `ref_country_states` VALUES (862, 65, 'Bur Sa\'id');
INSERT INTO `ref_country_states` VALUES (863, 65, 'Dumyat');
INSERT INTO `ref_country_states` VALUES (864, 65, 'Janub Sina\'');
INSERT INTO `ref_country_states` VALUES (865, 65, 'Kafr ash Shaykh');
INSERT INTO `ref_country_states` VALUES (866, 65, 'Matruh');
INSERT INTO `ref_country_states` VALUES (867, 65, 'Qina');
INSERT INTO `ref_country_states` VALUES (868, 65, 'Shamal Sina\'');
INSERT INTO `ref_country_states` VALUES (869, 65, 'Suhaj');
INSERT INTO `ref_country_states` VALUES (870, 67, 'Anseba');
INSERT INTO `ref_country_states` VALUES (871, 67, 'Debub');
INSERT INTO `ref_country_states` VALUES (872, 67, 'Debubawi K\'eyih Bahri');
INSERT INTO `ref_country_states` VALUES (873, 67, 'Gash Barka');
INSERT INTO `ref_country_states` VALUES (874, 67, 'Ma\'akel');
INSERT INTO `ref_country_states` VALUES (875, 67, 'Semenawi Keyih Bahri');
INSERT INTO `ref_country_states` VALUES (876, 68, 'Andalucia');
INSERT INTO `ref_country_states` VALUES (877, 68, 'Aragon');
INSERT INTO `ref_country_states` VALUES (878, 68, 'Asturias');
INSERT INTO `ref_country_states` VALUES (879, 68, 'Baleares');
INSERT INTO `ref_country_states` VALUES (880, 68, 'Ceuta');
INSERT INTO `ref_country_states` VALUES (881, 68, 'Canarias');
INSERT INTO `ref_country_states` VALUES (882, 68, 'Cantabria');
INSERT INTO `ref_country_states` VALUES (883, 68, 'Castilla-La Mancha');
INSERT INTO `ref_country_states` VALUES (884, 68, 'Castilla y Leon');
INSERT INTO `ref_country_states` VALUES (885, 68, 'Cataluna');
INSERT INTO `ref_country_states` VALUES (886, 68, 'Comunidad Valenciana');
INSERT INTO `ref_country_states` VALUES (887, 68, 'Extremadura');
INSERT INTO `ref_country_states` VALUES (888, 68, 'Galicia');
INSERT INTO `ref_country_states` VALUES (889, 68, 'La Rioja');
INSERT INTO `ref_country_states` VALUES (890, 68, 'Madrid');
INSERT INTO `ref_country_states` VALUES (891, 68, 'Melilla');
INSERT INTO `ref_country_states` VALUES (892, 68, 'Murcia');
INSERT INTO `ref_country_states` VALUES (893, 68, 'Navarra');
INSERT INTO `ref_country_states` VALUES (894, 68, 'Pais Vasco');
INSERT INTO `ref_country_states` VALUES (895, 69, 'Addis Ababa');
INSERT INTO `ref_country_states` VALUES (896, 69, 'Afar');
INSERT INTO `ref_country_states` VALUES (897, 69, 'Amhara');
INSERT INTO `ref_country_states` VALUES (898, 69, 'Binshangul Gumuz');
INSERT INTO `ref_country_states` VALUES (899, 69, 'Dire Dawa');
INSERT INTO `ref_country_states` VALUES (900, 69, 'Gambela Hizboch');
INSERT INTO `ref_country_states` VALUES (901, 69, 'Harari');
INSERT INTO `ref_country_states` VALUES (902, 69, 'Oromia');
INSERT INTO `ref_country_states` VALUES (903, 69, 'Somali');
INSERT INTO `ref_country_states` VALUES (904, 69, 'Tigray');
INSERT INTO `ref_country_states` VALUES (905, 69, 'Southern Nations, Nationalities, and Peoples Region');
INSERT INTO `ref_country_states` VALUES (906, 70, 'Aland');
INSERT INTO `ref_country_states` VALUES (907, 70, 'Etela-Suomen Laani');
INSERT INTO `ref_country_states` VALUES (908, 70, 'Ita-Suomen Laani');
INSERT INTO `ref_country_states` VALUES (909, 70, 'Lansi-Suomen Laani');
INSERT INTO `ref_country_states` VALUES (910, 70, 'Lappi');
INSERT INTO `ref_country_states` VALUES (911, 70, 'Oulun Laani');
INSERT INTO `ref_country_states` VALUES (912, 71, 'Central (Suva)');
INSERT INTO `ref_country_states` VALUES (913, 71, 'Eastern (Levuka)');
INSERT INTO `ref_country_states` VALUES (914, 71, 'Northern (Labasa)');
INSERT INTO `ref_country_states` VALUES (915, 71, 'Rotuma');
INSERT INTO `ref_country_states` VALUES (916, 71, 'Western (Lautoka)');
INSERT INTO `ref_country_states` VALUES (917, 73, 'Chuuk');
INSERT INTO `ref_country_states` VALUES (918, 73, 'Kosrae');
INSERT INTO `ref_country_states` VALUES (919, 73, 'Pohnpei');
INSERT INTO `ref_country_states` VALUES (920, 73, 'Yap');
INSERT INTO `ref_country_states` VALUES (921, 75, 'Alsace');
INSERT INTO `ref_country_states` VALUES (922, 75, 'Aquitaine');
INSERT INTO `ref_country_states` VALUES (923, 75, 'Auvergne');
INSERT INTO `ref_country_states` VALUES (924, 75, 'Basse-Normandie');
INSERT INTO `ref_country_states` VALUES (925, 75, 'Bourgogne');
INSERT INTO `ref_country_states` VALUES (926, 75, 'Bretagne');
INSERT INTO `ref_country_states` VALUES (927, 75, 'Centre');
INSERT INTO `ref_country_states` VALUES (928, 75, 'Champagne-Ardenne');
INSERT INTO `ref_country_states` VALUES (929, 75, 'Corse');
INSERT INTO `ref_country_states` VALUES (930, 75, 'Franche-Comte');
INSERT INTO `ref_country_states` VALUES (931, 75, 'Haute-Normandie');
INSERT INTO `ref_country_states` VALUES (932, 75, 'Ile-de-France');
INSERT INTO `ref_country_states` VALUES (933, 75, 'Languedoc-Roussillon');
INSERT INTO `ref_country_states` VALUES (934, 75, 'Limousin');
INSERT INTO `ref_country_states` VALUES (935, 75, 'Lorraine');
INSERT INTO `ref_country_states` VALUES (936, 75, 'Midi-Pyrenees');
INSERT INTO `ref_country_states` VALUES (937, 75, 'Nord-Pas-de-Calais');
INSERT INTO `ref_country_states` VALUES (938, 75, 'Pays de la Loire');
INSERT INTO `ref_country_states` VALUES (939, 75, 'Picardie');
INSERT INTO `ref_country_states` VALUES (940, 75, 'Poitou-Charentes');
INSERT INTO `ref_country_states` VALUES (941, 75, 'Provence-Alpes-Cote d\'Azur');
INSERT INTO `ref_country_states` VALUES (942, 75, 'Rhone-Alpes');
INSERT INTO `ref_country_states` VALUES (943, 76, 'Estuaire');
INSERT INTO `ref_country_states` VALUES (944, 76, 'Haut-Ogooue');
INSERT INTO `ref_country_states` VALUES (945, 76, 'Moyen-Ogooue');
INSERT INTO `ref_country_states` VALUES (946, 76, 'Ngounie');
INSERT INTO `ref_country_states` VALUES (947, 76, 'Nyanga');
INSERT INTO `ref_country_states` VALUES (948, 76, 'Ogooue-Ivindo');
INSERT INTO `ref_country_states` VALUES (949, 76, 'Ogooue-Lolo');
INSERT INTO `ref_country_states` VALUES (950, 76, 'Ogooue-Maritime');
INSERT INTO `ref_country_states` VALUES (951, 76, 'Woleu-Ntem');
INSERT INTO `ref_country_states` VALUES (952, 77, 'Aberconwy and Colwyn');
INSERT INTO `ref_country_states` VALUES (953, 77, 'Aberdeen City');
INSERT INTO `ref_country_states` VALUES (954, 77, 'Aberdeenshire');
INSERT INTO `ref_country_states` VALUES (955, 77, 'Anglesey');
INSERT INTO `ref_country_states` VALUES (956, 77, 'Angus');
INSERT INTO `ref_country_states` VALUES (957, 77, 'Antrim');
INSERT INTO `ref_country_states` VALUES (958, 77, 'Argyll and Bute');
INSERT INTO `ref_country_states` VALUES (959, 77, 'Armagh');
INSERT INTO `ref_country_states` VALUES (960, 77, 'Avon');
INSERT INTO `ref_country_states` VALUES (961, 77, 'Ayrshire');
INSERT INTO `ref_country_states` VALUES (962, 77, 'Bath and NE Somerset');
INSERT INTO `ref_country_states` VALUES (963, 77, 'Bedfordshire');
INSERT INTO `ref_country_states` VALUES (964, 77, 'Belfast');
INSERT INTO `ref_country_states` VALUES (965, 77, 'Berkshire');
INSERT INTO `ref_country_states` VALUES (966, 77, 'Berwickshire');
INSERT INTO `ref_country_states` VALUES (967, 77, 'BFPO');
INSERT INTO `ref_country_states` VALUES (968, 77, 'Blaenau Gwent');
INSERT INTO `ref_country_states` VALUES (969, 77, 'Buckinghamshire');
INSERT INTO `ref_country_states` VALUES (970, 77, 'Caernarfonshire');
INSERT INTO `ref_country_states` VALUES (971, 77, 'Caerphilly');
INSERT INTO `ref_country_states` VALUES (972, 77, 'Caithness');
INSERT INTO `ref_country_states` VALUES (973, 77, 'Cambridgeshire');
INSERT INTO `ref_country_states` VALUES (974, 77, 'Cardiff');
INSERT INTO `ref_country_states` VALUES (975, 77, 'Cardiganshire');
INSERT INTO `ref_country_states` VALUES (976, 77, 'Carmarthenshire');
INSERT INTO `ref_country_states` VALUES (977, 77, 'Ceredigion');
INSERT INTO `ref_country_states` VALUES (978, 77, 'Channel Islands');
INSERT INTO `ref_country_states` VALUES (979, 77, 'Cheshire');
INSERT INTO `ref_country_states` VALUES (980, 77, 'City of Bristol');
INSERT INTO `ref_country_states` VALUES (981, 77, 'Clackmannanshire');
INSERT INTO `ref_country_states` VALUES (982, 77, 'Clwyd');
INSERT INTO `ref_country_states` VALUES (983, 77, 'Conwy');
INSERT INTO `ref_country_states` VALUES (984, 77, 'Cornwall/Scilly');
INSERT INTO `ref_country_states` VALUES (985, 77, 'Cumbria');
INSERT INTO `ref_country_states` VALUES (986, 77, 'Denbighshire');
INSERT INTO `ref_country_states` VALUES (987, 77, 'Derbyshire');
INSERT INTO `ref_country_states` VALUES (988, 77, 'Derry/Londonderry');
INSERT INTO `ref_country_states` VALUES (989, 77, 'Devon');
INSERT INTO `ref_country_states` VALUES (990, 77, 'Dorset');
INSERT INTO `ref_country_states` VALUES (991, 77, 'Down');
INSERT INTO `ref_country_states` VALUES (992, 77, 'Dumfries and Galloway');
INSERT INTO `ref_country_states` VALUES (993, 77, 'Dunbartonshire');
INSERT INTO `ref_country_states` VALUES (994, 77, 'Dundee');
INSERT INTO `ref_country_states` VALUES (995, 77, 'Durham');
INSERT INTO `ref_country_states` VALUES (996, 77, 'Dyfed');
INSERT INTO `ref_country_states` VALUES (997, 77, 'East Ayrshire');
INSERT INTO `ref_country_states` VALUES (998, 77, 'East Dunbartonshire');
INSERT INTO `ref_country_states` VALUES (999, 77, 'East Lothian');
INSERT INTO `ref_country_states` VALUES (1000, 77, 'East Renfrewshire');
INSERT INTO `ref_country_states` VALUES (1001, 77, 'East Riding Yorkshire');
INSERT INTO `ref_country_states` VALUES (1002, 77, 'East Sussex');
INSERT INTO `ref_country_states` VALUES (1003, 77, 'Edinburgh');
INSERT INTO `ref_country_states` VALUES (1004, 77, 'England');
INSERT INTO `ref_country_states` VALUES (1005, 77, 'Essex');
INSERT INTO `ref_country_states` VALUES (1006, 77, 'Falkirk');
INSERT INTO `ref_country_states` VALUES (1007, 77, 'Fermanagh');
INSERT INTO `ref_country_states` VALUES (1008, 77, 'Fife');
INSERT INTO `ref_country_states` VALUES (1009, 77, 'Flintshire');
INSERT INTO `ref_country_states` VALUES (1010, 77, 'Glasgow');
INSERT INTO `ref_country_states` VALUES (1011, 77, 'Gloucestershire');
INSERT INTO `ref_country_states` VALUES (1012, 77, 'Greater London');
INSERT INTO `ref_country_states` VALUES (1013, 77, 'Greater Manchester');
INSERT INTO `ref_country_states` VALUES (1014, 77, 'Gwent');
INSERT INTO `ref_country_states` VALUES (1015, 77, 'Gwynedd');
INSERT INTO `ref_country_states` VALUES (1016, 77, 'Hampshire');
INSERT INTO `ref_country_states` VALUES (1017, 77, 'Hartlepool');
INSERT INTO `ref_country_states` VALUES (1018, 77, 'Hereford and Worcester');
INSERT INTO `ref_country_states` VALUES (1019, 77, 'Hertfordshire');
INSERT INTO `ref_country_states` VALUES (1020, 77, 'Highlands');
INSERT INTO `ref_country_states` VALUES (1021, 77, 'Inverclyde');
INSERT INTO `ref_country_states` VALUES (1022, 77, 'Inverness-Shire');
INSERT INTO `ref_country_states` VALUES (1023, 77, 'Isle of Man');
INSERT INTO `ref_country_states` VALUES (1024, 77, 'Isle of Wight');
INSERT INTO `ref_country_states` VALUES (1025, 77, 'Kent');
INSERT INTO `ref_country_states` VALUES (1026, 77, 'Kincardinshire');
INSERT INTO `ref_country_states` VALUES (1027, 77, 'Kingston Upon Hull');
INSERT INTO `ref_country_states` VALUES (1028, 77, 'Kinross-Shire');
INSERT INTO `ref_country_states` VALUES (1029, 77, 'Kirklees');
INSERT INTO `ref_country_states` VALUES (1030, 77, 'Lanarkshire');
INSERT INTO `ref_country_states` VALUES (1031, 77, 'Lancashire');
INSERT INTO `ref_country_states` VALUES (1032, 77, 'Leicestershire');
INSERT INTO `ref_country_states` VALUES (1033, 77, 'Lincolnshire');
INSERT INTO `ref_country_states` VALUES (1034, 77, 'Londonderry');
INSERT INTO `ref_country_states` VALUES (1035, 77, 'Merseyside');
INSERT INTO `ref_country_states` VALUES (1036, 77, 'Merthyr Tydfil');
INSERT INTO `ref_country_states` VALUES (1037, 77, 'Mid Glamorgan');
INSERT INTO `ref_country_states` VALUES (1038, 77, 'Mid Lothian');
INSERT INTO `ref_country_states` VALUES (1039, 77, 'Middlesex');
INSERT INTO `ref_country_states` VALUES (1040, 77, 'Monmouthshire');
INSERT INTO `ref_country_states` VALUES (1041, 77, 'Moray');
INSERT INTO `ref_country_states` VALUES (1042, 77, 'Neath & Port Talbot');
INSERT INTO `ref_country_states` VALUES (1043, 77, 'Newport');
INSERT INTO `ref_country_states` VALUES (1044, 77, 'Norfolk');
INSERT INTO `ref_country_states` VALUES (1045, 77, 'North Ayrshire');
INSERT INTO `ref_country_states` VALUES (1046, 77, 'North East Lincolnshire');
INSERT INTO `ref_country_states` VALUES (1047, 77, 'North Lanarkshire');
INSERT INTO `ref_country_states` VALUES (1048, 77, 'North Lincolnshire');
INSERT INTO `ref_country_states` VALUES (1049, 77, 'North Somerset');
INSERT INTO `ref_country_states` VALUES (1050, 77, 'North Yorkshire');
INSERT INTO `ref_country_states` VALUES (1051, 77, 'Northamptonshire');
INSERT INTO `ref_country_states` VALUES (1052, 77, 'Northern Ireland');
INSERT INTO `ref_country_states` VALUES (1053, 77, 'Northumberland');
INSERT INTO `ref_country_states` VALUES (1054, 77, 'Nottinghamshire');
INSERT INTO `ref_country_states` VALUES (1055, 77, 'Orkney and Shetland Isles');
INSERT INTO `ref_country_states` VALUES (1056, 77, 'Oxfordshire');
INSERT INTO `ref_country_states` VALUES (1057, 77, 'Pembrokeshire');
INSERT INTO `ref_country_states` VALUES (1058, 77, 'Perth and Kinross');
INSERT INTO `ref_country_states` VALUES (1059, 77, 'Powys');
INSERT INTO `ref_country_states` VALUES (1060, 77, 'Redcar and Cleveland');
INSERT INTO `ref_country_states` VALUES (1061, 77, 'Renfrewshire');
INSERT INTO `ref_country_states` VALUES (1062, 77, 'Rhonda Cynon Taff');
INSERT INTO `ref_country_states` VALUES (1063, 77, 'Rutland');
INSERT INTO `ref_country_states` VALUES (1064, 77, 'Scottish Borders');
INSERT INTO `ref_country_states` VALUES (1065, 77, 'Shetland');
INSERT INTO `ref_country_states` VALUES (1066, 77, 'Shropshire');
INSERT INTO `ref_country_states` VALUES (1067, 77, 'Somerset');
INSERT INTO `ref_country_states` VALUES (1068, 77, 'South Ayrshire');
INSERT INTO `ref_country_states` VALUES (1069, 77, 'South Glamorgan');
INSERT INTO `ref_country_states` VALUES (1070, 77, 'South Gloucesteshire');
INSERT INTO `ref_country_states` VALUES (1071, 77, 'South Lanarkshire');
INSERT INTO `ref_country_states` VALUES (1072, 77, 'South Yorkshire');
INSERT INTO `ref_country_states` VALUES (1073, 77, 'Staffordshire');
INSERT INTO `ref_country_states` VALUES (1074, 77, 'Stirling');
INSERT INTO `ref_country_states` VALUES (1075, 77, 'Stockton On Tees');
INSERT INTO `ref_country_states` VALUES (1076, 77, 'Suffolk');
INSERT INTO `ref_country_states` VALUES (1077, 77, 'Surrey');
INSERT INTO `ref_country_states` VALUES (1078, 77, 'Swansea');
INSERT INTO `ref_country_states` VALUES (1079, 77, 'Torfaen');
INSERT INTO `ref_country_states` VALUES (1080, 77, 'Tyne and Wear');
INSERT INTO `ref_country_states` VALUES (1081, 77, 'Tyrone');
INSERT INTO `ref_country_states` VALUES (1082, 77, 'Vale Of Glamorgan');
INSERT INTO `ref_country_states` VALUES (1083, 77, 'Wales');
INSERT INTO `ref_country_states` VALUES (1084, 77, 'Warwickshire');
INSERT INTO `ref_country_states` VALUES (1085, 77, 'West Berkshire');
INSERT INTO `ref_country_states` VALUES (1086, 77, 'West Dunbartonshire');
INSERT INTO `ref_country_states` VALUES (1087, 77, 'West Glamorgan');
INSERT INTO `ref_country_states` VALUES (1088, 77, 'West Lothian');
INSERT INTO `ref_country_states` VALUES (1089, 77, 'West Midlands');
INSERT INTO `ref_country_states` VALUES (1090, 77, 'West Sussex');
INSERT INTO `ref_country_states` VALUES (1091, 77, 'West Yorkshire');
INSERT INTO `ref_country_states` VALUES (1092, 77, 'Western Isles');
INSERT INTO `ref_country_states` VALUES (1093, 77, 'Wiltshire');
INSERT INTO `ref_country_states` VALUES (1094, 77, 'Wirral');
INSERT INTO `ref_country_states` VALUES (1095, 77, 'Worcestershire');
INSERT INTO `ref_country_states` VALUES (1096, 77, 'Wrexham');
INSERT INTO `ref_country_states` VALUES (1097, 77, 'York');
INSERT INTO `ref_country_states` VALUES (1098, 78, 'Carriacou and Petit Martinique');
INSERT INTO `ref_country_states` VALUES (1099, 78, 'Saint Andrew');
INSERT INTO `ref_country_states` VALUES (1100, 78, 'Saint David');
INSERT INTO `ref_country_states` VALUES (1101, 78, 'Saint George');
INSERT INTO `ref_country_states` VALUES (1102, 78, 'Saint John');
INSERT INTO `ref_country_states` VALUES (1103, 78, 'Saint Mark');
INSERT INTO `ref_country_states` VALUES (1104, 78, 'Saint Patrick');
INSERT INTO `ref_country_states` VALUES (1105, 79, 'Abkhazia');
INSERT INTO `ref_country_states` VALUES (1106, 79, 'Adjara');
INSERT INTO `ref_country_states` VALUES (1107, 79, 'Guria');
INSERT INTO `ref_country_states` VALUES (1108, 79, 'Imereti');
INSERT INTO `ref_country_states` VALUES (1109, 79, 'Kakheti');
INSERT INTO `ref_country_states` VALUES (1110, 79, 'Kvemo Kartli');
INSERT INTO `ref_country_states` VALUES (1111, 79, 'Mtskheta-Mtianeti');
INSERT INTO `ref_country_states` VALUES (1112, 79, 'Racha-Lechkhumi and Kvemo Svaneti');
INSERT INTO `ref_country_states` VALUES (1113, 79, 'Samegrelo-Zemo Svaneti');
INSERT INTO `ref_country_states` VALUES (1114, 79, 'Samtskhe-Javakheti');
INSERT INTO `ref_country_states` VALUES (1115, 79, 'Shida Kartli');
INSERT INTO `ref_country_states` VALUES (1116, 79, 'Tbilisi');
INSERT INTO `ref_country_states` VALUES (1117, 80, 'Awala-Yalimapo');
INSERT INTO `ref_country_states` VALUES (1118, 80, 'Mana');
INSERT INTO `ref_country_states` VALUES (1119, 80, 'Saint-Laurent-du-Maroni');
INSERT INTO `ref_country_states` VALUES (1120, 80, 'Apatou');
INSERT INTO `ref_country_states` VALUES (1121, 80, 'Grand-Santi');
INSERT INTO `ref_country_states` VALUES (1122, 80, 'Papaïchton');
INSERT INTO `ref_country_states` VALUES (1123, 80, 'Saül');
INSERT INTO `ref_country_states` VALUES (1124, 80, 'Maripasoula');
INSERT INTO `ref_country_states` VALUES (1125, 80, 'Camopi');
INSERT INTO `ref_country_states` VALUES (1126, 80, 'Saint-Georges');
INSERT INTO `ref_country_states` VALUES (1127, 80, 'Ouanary');
INSERT INTO `ref_country_states` VALUES (1128, 80, 'Régina');
INSERT INTO `ref_country_states` VALUES (1129, 80, 'Roura');
INSERT INTO `ref_country_states` VALUES (1130, 80, 'Saint-Élie');
INSERT INTO `ref_country_states` VALUES (1131, 80, 'Iracoubo');
INSERT INTO `ref_country_states` VALUES (1132, 80, 'Sinnamary');
INSERT INTO `ref_country_states` VALUES (1133, 80, 'Kourou');
INSERT INTO `ref_country_states` VALUES (1134, 80, 'Macouria');
INSERT INTO `ref_country_states` VALUES (1135, 80, 'Montsinéry-Tonnegrande');
INSERT INTO `ref_country_states` VALUES (1136, 80, 'Matoury');
INSERT INTO `ref_country_states` VALUES (1137, 80, 'Cayenne');
INSERT INTO `ref_country_states` VALUES (1138, 80, 'Remire-Montjoly');
INSERT INTO `ref_country_states` VALUES (1139, 82, 'Ashanti');
INSERT INTO `ref_country_states` VALUES (1140, 82, 'Brong-Ahafo');
INSERT INTO `ref_country_states` VALUES (1141, 82, 'Central');
INSERT INTO `ref_country_states` VALUES (1142, 82, 'Eastern');
INSERT INTO `ref_country_states` VALUES (1143, 82, 'Greater Accra');
INSERT INTO `ref_country_states` VALUES (1144, 82, 'Northern');
INSERT INTO `ref_country_states` VALUES (1145, 82, 'Upper East');
INSERT INTO `ref_country_states` VALUES (1146, 82, 'Upper West');
INSERT INTO `ref_country_states` VALUES (1147, 82, 'Volta');
INSERT INTO `ref_country_states` VALUES (1148, 82, 'Western');
INSERT INTO `ref_country_states` VALUES (1149, 84, 'Avannaa (Nordgronland)');
INSERT INTO `ref_country_states` VALUES (1150, 84, 'Tunu (Ostgronland)');
INSERT INTO `ref_country_states` VALUES (1151, 84, 'Kitaa (Vestgronland)');
INSERT INTO `ref_country_states` VALUES (1152, 85, 'Banjul');
INSERT INTO `ref_country_states` VALUES (1153, 85, 'Central River');
INSERT INTO `ref_country_states` VALUES (1154, 85, 'Lower River');
INSERT INTO `ref_country_states` VALUES (1155, 85, 'North Bank');
INSERT INTO `ref_country_states` VALUES (1156, 85, 'Upper River');
INSERT INTO `ref_country_states` VALUES (1157, 85, 'Western');
INSERT INTO `ref_country_states` VALUES (1158, 86, 'Beyla');
INSERT INTO `ref_country_states` VALUES (1159, 86, 'Boffa');
INSERT INTO `ref_country_states` VALUES (1160, 86, 'Boke');
INSERT INTO `ref_country_states` VALUES (1161, 86, 'Conakry');
INSERT INTO `ref_country_states` VALUES (1162, 86, 'Coyah');
INSERT INTO `ref_country_states` VALUES (1163, 86, 'Dabola');
INSERT INTO `ref_country_states` VALUES (1164, 86, 'Dalaba');
INSERT INTO `ref_country_states` VALUES (1165, 86, 'Dinguiraye');
INSERT INTO `ref_country_states` VALUES (1166, 86, 'Dubreka');
INSERT INTO `ref_country_states` VALUES (1167, 86, 'Faranah');
INSERT INTO `ref_country_states` VALUES (1168, 86, 'Forecariah');
INSERT INTO `ref_country_states` VALUES (1169, 86, 'Fria');
INSERT INTO `ref_country_states` VALUES (1170, 86, 'Gaoual');
INSERT INTO `ref_country_states` VALUES (1171, 86, 'Gueckedou');
INSERT INTO `ref_country_states` VALUES (1172, 86, 'Kankan');
INSERT INTO `ref_country_states` VALUES (1173, 86, 'Kerouane');
INSERT INTO `ref_country_states` VALUES (1174, 86, 'Kindia');
INSERT INTO `ref_country_states` VALUES (1175, 86, 'Kissidougou');
INSERT INTO `ref_country_states` VALUES (1176, 86, 'Koubia');
INSERT INTO `ref_country_states` VALUES (1177, 86, 'Koundara');
INSERT INTO `ref_country_states` VALUES (1178, 86, 'Kouroussa');
INSERT INTO `ref_country_states` VALUES (1179, 86, 'Labe');
INSERT INTO `ref_country_states` VALUES (1180, 86, 'Lelouma');
INSERT INTO `ref_country_states` VALUES (1181, 86, 'Lola');
INSERT INTO `ref_country_states` VALUES (1182, 86, 'Macenta');
INSERT INTO `ref_country_states` VALUES (1183, 86, 'Mali');
INSERT INTO `ref_country_states` VALUES (1184, 86, 'Mamou');
INSERT INTO `ref_country_states` VALUES (1185, 86, 'Mandiana');
INSERT INTO `ref_country_states` VALUES (1186, 86, 'Nzerekore');
INSERT INTO `ref_country_states` VALUES (1187, 86, 'Pita');
INSERT INTO `ref_country_states` VALUES (1188, 86, 'Siguiri');
INSERT INTO `ref_country_states` VALUES (1189, 86, 'Telimele');
INSERT INTO `ref_country_states` VALUES (1190, 86, 'Tougue');
INSERT INTO `ref_country_states` VALUES (1191, 86, 'Yomou');
INSERT INTO `ref_country_states` VALUES (1192, 88, 'Annobon');
INSERT INTO `ref_country_states` VALUES (1193, 88, 'Bioko Norte');
INSERT INTO `ref_country_states` VALUES (1194, 88, 'Bioko Sur');
INSERT INTO `ref_country_states` VALUES (1195, 88, 'Centro Sur');
INSERT INTO `ref_country_states` VALUES (1196, 88, 'Kie-Ntem');
INSERT INTO `ref_country_states` VALUES (1197, 88, 'Litoral');
INSERT INTO `ref_country_states` VALUES (1198, 88, 'Wele-Nzas');
INSERT INTO `ref_country_states` VALUES (1199, 89, 'Agion Oros');
INSERT INTO `ref_country_states` VALUES (1200, 89, 'Achaia');
INSERT INTO `ref_country_states` VALUES (1201, 89, 'Aitolia kai Akarmania');
INSERT INTO `ref_country_states` VALUES (1202, 89, 'Argolis');
INSERT INTO `ref_country_states` VALUES (1203, 89, 'Arkadia');
INSERT INTO `ref_country_states` VALUES (1204, 89, 'Arta');
INSERT INTO `ref_country_states` VALUES (1205, 89, 'Attiki');
INSERT INTO `ref_country_states` VALUES (1206, 89, 'Chalkidiki');
INSERT INTO `ref_country_states` VALUES (1207, 89, 'Chanion');
INSERT INTO `ref_country_states` VALUES (1208, 89, 'Chios');
INSERT INTO `ref_country_states` VALUES (1209, 89, 'Dodekanisos');
INSERT INTO `ref_country_states` VALUES (1210, 89, 'Drama');
INSERT INTO `ref_country_states` VALUES (1211, 89, 'Evros');
INSERT INTO `ref_country_states` VALUES (1212, 89, 'Evrytania');
INSERT INTO `ref_country_states` VALUES (1213, 89, 'Evvoia');
INSERT INTO `ref_country_states` VALUES (1214, 89, 'Florina');
INSERT INTO `ref_country_states` VALUES (1215, 89, 'Fokidos');
INSERT INTO `ref_country_states` VALUES (1216, 89, 'Fthiotis');
INSERT INTO `ref_country_states` VALUES (1217, 89, 'Grevena');
INSERT INTO `ref_country_states` VALUES (1218, 89, 'Ileia');
INSERT INTO `ref_country_states` VALUES (1219, 89, 'Imathia');
INSERT INTO `ref_country_states` VALUES (1220, 89, 'Ioannina');
INSERT INTO `ref_country_states` VALUES (1221, 89, 'Irakleion');
INSERT INTO `ref_country_states` VALUES (1222, 89, 'Karditsa');
INSERT INTO `ref_country_states` VALUES (1223, 89, 'Kastoria');
INSERT INTO `ref_country_states` VALUES (1224, 89, 'Kavala');
INSERT INTO `ref_country_states` VALUES (1225, 89, 'Kefallinia');
INSERT INTO `ref_country_states` VALUES (1226, 89, 'Kerkyra');
INSERT INTO `ref_country_states` VALUES (1227, 89, 'Kilkis');
INSERT INTO `ref_country_states` VALUES (1228, 89, 'Korinthia');
INSERT INTO `ref_country_states` VALUES (1229, 89, 'Kozani');
INSERT INTO `ref_country_states` VALUES (1230, 89, 'Kyklades');
INSERT INTO `ref_country_states` VALUES (1231, 89, 'Lakonia');
INSERT INTO `ref_country_states` VALUES (1232, 89, 'Larisa');
INSERT INTO `ref_country_states` VALUES (1233, 89, 'Lasithi');
INSERT INTO `ref_country_states` VALUES (1234, 89, 'Lefkas');
INSERT INTO `ref_country_states` VALUES (1235, 89, 'Lesvos');
INSERT INTO `ref_country_states` VALUES (1236, 89, 'Magnisia');
INSERT INTO `ref_country_states` VALUES (1237, 89, 'Messinia');
INSERT INTO `ref_country_states` VALUES (1238, 89, 'Pella');
INSERT INTO `ref_country_states` VALUES (1239, 89, 'Pieria');
INSERT INTO `ref_country_states` VALUES (1240, 89, 'Preveza');
INSERT INTO `ref_country_states` VALUES (1241, 89, 'Rethynnis');
INSERT INTO `ref_country_states` VALUES (1242, 89, 'Rodopi');
INSERT INTO `ref_country_states` VALUES (1243, 89, 'Samos');
INSERT INTO `ref_country_states` VALUES (1244, 89, 'Serrai');
INSERT INTO `ref_country_states` VALUES (1245, 89, 'Thesprotia');
INSERT INTO `ref_country_states` VALUES (1246, 89, 'Thessaloniki');
INSERT INTO `ref_country_states` VALUES (1247, 89, 'Trikala');
INSERT INTO `ref_country_states` VALUES (1248, 89, 'Voiotia');
INSERT INTO `ref_country_states` VALUES (1249, 89, 'Xanthi');
INSERT INTO `ref_country_states` VALUES (1250, 89, 'Zakynthos');
INSERT INTO `ref_country_states` VALUES (1251, 91, 'Alta Verapaz');
INSERT INTO `ref_country_states` VALUES (1252, 91, 'Baja Verapaz');
INSERT INTO `ref_country_states` VALUES (1253, 91, 'Chimaltenango');
INSERT INTO `ref_country_states` VALUES (1254, 91, 'Chiquimula');
INSERT INTO `ref_country_states` VALUES (1255, 91, 'El Progreso');
INSERT INTO `ref_country_states` VALUES (1256, 91, 'Escuintla');
INSERT INTO `ref_country_states` VALUES (1257, 91, 'Guatemala');
INSERT INTO `ref_country_states` VALUES (1258, 91, 'Huehuetenango');
INSERT INTO `ref_country_states` VALUES (1259, 91, 'Izabal');
INSERT INTO `ref_country_states` VALUES (1260, 91, 'Jalapa');
INSERT INTO `ref_country_states` VALUES (1261, 91, 'Jutiapa');
INSERT INTO `ref_country_states` VALUES (1262, 91, 'Peten');
INSERT INTO `ref_country_states` VALUES (1263, 91, 'Quetzaltenango');
INSERT INTO `ref_country_states` VALUES (1264, 91, 'Quiche');
INSERT INTO `ref_country_states` VALUES (1265, 91, 'Retalhuleu');
INSERT INTO `ref_country_states` VALUES (1266, 91, 'Sacatepequez');
INSERT INTO `ref_country_states` VALUES (1267, 91, 'San Marcos');
INSERT INTO `ref_country_states` VALUES (1268, 91, 'Santa Rosa');
INSERT INTO `ref_country_states` VALUES (1269, 91, 'Solola');
INSERT INTO `ref_country_states` VALUES (1270, 91, 'Suchitepequez');
INSERT INTO `ref_country_states` VALUES (1271, 91, 'Totonicapan');
INSERT INTO `ref_country_states` VALUES (1272, 91, 'Zacapa');
INSERT INTO `ref_country_states` VALUES (1273, 93, 'Bafata');
INSERT INTO `ref_country_states` VALUES (1274, 93, 'Biombo');
INSERT INTO `ref_country_states` VALUES (1275, 93, 'Bissau');
INSERT INTO `ref_country_states` VALUES (1276, 93, 'Bolama');
INSERT INTO `ref_country_states` VALUES (1277, 93, 'Cacheu');
INSERT INTO `ref_country_states` VALUES (1278, 93, 'Gabu');
INSERT INTO `ref_country_states` VALUES (1279, 93, 'Oio');
INSERT INTO `ref_country_states` VALUES (1280, 93, 'Quinara');
INSERT INTO `ref_country_states` VALUES (1281, 93, 'Tombali');
INSERT INTO `ref_country_states` VALUES (1282, 94, 'Barima-Waini');
INSERT INTO `ref_country_states` VALUES (1283, 94, 'Cuyuni-Mazaruni');
INSERT INTO `ref_country_states` VALUES (1284, 94, 'Demerara-Mahaica');
INSERT INTO `ref_country_states` VALUES (1285, 94, 'East Berbice-Corentyne');
INSERT INTO `ref_country_states` VALUES (1286, 94, 'Essequibo Islands-West Demerara');
INSERT INTO `ref_country_states` VALUES (1287, 94, 'Mahaica-Berbice');
INSERT INTO `ref_country_states` VALUES (1288, 94, 'Pomeroon-Supenaam');
INSERT INTO `ref_country_states` VALUES (1289, 94, 'Potaro-Siparuni');
INSERT INTO `ref_country_states` VALUES (1290, 94, 'Upper Demerara-Berbice');
INSERT INTO `ref_country_states` VALUES (1291, 94, 'Upper Takutu-Upper Essequibo');
INSERT INTO `ref_country_states` VALUES (1292, 95, 'Islands');
INSERT INTO `ref_country_states` VALUES (1293, 95, 'Kwai Tsing');
INSERT INTO `ref_country_states` VALUES (1294, 95, 'North');
INSERT INTO `ref_country_states` VALUES (1295, 95, 'Sai Kung');
INSERT INTO `ref_country_states` VALUES (1296, 95, 'Sha Tin');
INSERT INTO `ref_country_states` VALUES (1297, 95, 'Tai Po');
INSERT INTO `ref_country_states` VALUES (1298, 95, 'Tsuen Wan');
INSERT INTO `ref_country_states` VALUES (1299, 95, 'Tuen Mun');
INSERT INTO `ref_country_states` VALUES (1300, 95, 'Yuen Long');
INSERT INTO `ref_country_states` VALUES (1301, 95, 'Kowloon City');
INSERT INTO `ref_country_states` VALUES (1302, 95, 'Kwun Tong');
INSERT INTO `ref_country_states` VALUES (1303, 95, 'Sham Shui Po');
INSERT INTO `ref_country_states` VALUES (1304, 95, 'Wong Tai Sin');
INSERT INTO `ref_country_states` VALUES (1305, 95, 'Yau Tsim Mong');
INSERT INTO `ref_country_states` VALUES (1306, 95, 'Central and Western');
INSERT INTO `ref_country_states` VALUES (1307, 95, 'Eastern');
INSERT INTO `ref_country_states` VALUES (1308, 95, 'Southern');
INSERT INTO `ref_country_states` VALUES (1309, 95, 'Wan Chai');
INSERT INTO `ref_country_states` VALUES (1310, 97, 'Atlantida');
INSERT INTO `ref_country_states` VALUES (1311, 97, 'Choluteca');
INSERT INTO `ref_country_states` VALUES (1312, 97, 'Colon');
INSERT INTO `ref_country_states` VALUES (1313, 97, 'Comayagua');
INSERT INTO `ref_country_states` VALUES (1314, 97, 'Copan');
INSERT INTO `ref_country_states` VALUES (1315, 97, 'Cortes');
INSERT INTO `ref_country_states` VALUES (1316, 97, 'El Paraiso');
INSERT INTO `ref_country_states` VALUES (1317, 97, 'Francisco Morazan');
INSERT INTO `ref_country_states` VALUES (1318, 97, 'Gracias a Dios');
INSERT INTO `ref_country_states` VALUES (1319, 97, 'Intibuca');
INSERT INTO `ref_country_states` VALUES (1320, 97, 'Islas de la Bahia');
INSERT INTO `ref_country_states` VALUES (1321, 97, 'La Paz');
INSERT INTO `ref_country_states` VALUES (1322, 97, 'Lempira');
INSERT INTO `ref_country_states` VALUES (1323, 97, 'Ocotepeque');
INSERT INTO `ref_country_states` VALUES (1324, 97, 'Olancho');
INSERT INTO `ref_country_states` VALUES (1325, 97, 'Santa Barbara');
INSERT INTO `ref_country_states` VALUES (1326, 97, 'Valle');
INSERT INTO `ref_country_states` VALUES (1327, 97, 'Yoro');
INSERT INTO `ref_country_states` VALUES (1328, 98, 'Bjelovarsko-Bilogorska');
INSERT INTO `ref_country_states` VALUES (1329, 98, 'Brodsko-Posavska');
INSERT INTO `ref_country_states` VALUES (1330, 98, 'Dubrovacko-Neretvanska');
INSERT INTO `ref_country_states` VALUES (1331, 98, 'Istarska');
INSERT INTO `ref_country_states` VALUES (1332, 98, 'Karlovacka');
INSERT INTO `ref_country_states` VALUES (1333, 98, 'Koprivnicko-Krizevacka');
INSERT INTO `ref_country_states` VALUES (1334, 98, 'Krapinsko-Zagorska');
INSERT INTO `ref_country_states` VALUES (1335, 98, 'Licko-Senjska');
INSERT INTO `ref_country_states` VALUES (1336, 98, 'Medimurska');
INSERT INTO `ref_country_states` VALUES (1337, 98, 'Osjecko-Baranjska');
INSERT INTO `ref_country_states` VALUES (1338, 98, 'Pozesko-Slavonska');
INSERT INTO `ref_country_states` VALUES (1339, 98, 'Primorsko-Goranska');
INSERT INTO `ref_country_states` VALUES (1340, 98, 'Sibensko-Kninska');
INSERT INTO `ref_country_states` VALUES (1341, 98, 'Sisacko-Moslavacka');
INSERT INTO `ref_country_states` VALUES (1342, 98, 'Splitsko-Dalmatinska');
INSERT INTO `ref_country_states` VALUES (1343, 98, 'Varazdinska');
INSERT INTO `ref_country_states` VALUES (1344, 98, 'Viroviticko-Podravska');
INSERT INTO `ref_country_states` VALUES (1345, 98, 'Vukovarsko-Srijemska');
INSERT INTO `ref_country_states` VALUES (1346, 98, 'Zadarska');
INSERT INTO `ref_country_states` VALUES (1347, 98, 'Zagreb');
INSERT INTO `ref_country_states` VALUES (1348, 98, 'Zagrebacka');
INSERT INTO `ref_country_states` VALUES (1349, 99, 'Artibonite');
INSERT INTO `ref_country_states` VALUES (1350, 99, 'Centre');
INSERT INTO `ref_country_states` VALUES (1351, 99, 'Grand \'Anse');
INSERT INTO `ref_country_states` VALUES (1352, 99, 'Nord');
INSERT INTO `ref_country_states` VALUES (1353, 99, 'Nord-Est');
INSERT INTO `ref_country_states` VALUES (1354, 99, 'Nord-Ouest');
INSERT INTO `ref_country_states` VALUES (1355, 99, 'Ouest');
INSERT INTO `ref_country_states` VALUES (1356, 99, 'Sud');
INSERT INTO `ref_country_states` VALUES (1357, 99, 'Sud-Est');
INSERT INTO `ref_country_states` VALUES (1358, 100, 'Bacs-Kiskun');
INSERT INTO `ref_country_states` VALUES (1359, 100, 'Baranya');
INSERT INTO `ref_country_states` VALUES (1360, 100, 'Bekes');
INSERT INTO `ref_country_states` VALUES (1361, 100, 'Borsod-Abauj-Zemplen');
INSERT INTO `ref_country_states` VALUES (1362, 100, 'Csongrad');
INSERT INTO `ref_country_states` VALUES (1363, 100, 'Fejer');
INSERT INTO `ref_country_states` VALUES (1364, 100, 'Gyor-Moson-Sopron');
INSERT INTO `ref_country_states` VALUES (1365, 100, 'Hajdu-Bihar');
INSERT INTO `ref_country_states` VALUES (1366, 100, 'Heves');
INSERT INTO `ref_country_states` VALUES (1367, 100, 'Jasz-Nagykun-Szolnok');
INSERT INTO `ref_country_states` VALUES (1368, 100, 'Komarom-Esztergom');
INSERT INTO `ref_country_states` VALUES (1369, 100, 'Nograd');
INSERT INTO `ref_country_states` VALUES (1370, 100, 'Pest');
INSERT INTO `ref_country_states` VALUES (1371, 100, 'Somogy');
INSERT INTO `ref_country_states` VALUES (1372, 100, 'Szabolcs-Szatmar-Bereg');
INSERT INTO `ref_country_states` VALUES (1373, 100, 'Tolna');
INSERT INTO `ref_country_states` VALUES (1374, 100, 'Vas');
INSERT INTO `ref_country_states` VALUES (1375, 100, 'Veszprem');
INSERT INTO `ref_country_states` VALUES (1376, 100, 'Zala');
INSERT INTO `ref_country_states` VALUES (1377, 101, 'Aceh');
INSERT INTO `ref_country_states` VALUES (1378, 101, 'Bali');
INSERT INTO `ref_country_states` VALUES (1379, 101, 'Banten');
INSERT INTO `ref_country_states` VALUES (1380, 101, 'Bengkulu');
INSERT INTO `ref_country_states` VALUES (1381, 101, 'Gorontalo');
INSERT INTO `ref_country_states` VALUES (1382, 101, 'Irian Jaya Barat');
INSERT INTO `ref_country_states` VALUES (1383, 101, 'Jakarta Raya');
INSERT INTO `ref_country_states` VALUES (1384, 101, 'Jambi');
INSERT INTO `ref_country_states` VALUES (1385, 101, 'Jawa Barat');
INSERT INTO `ref_country_states` VALUES (1386, 101, 'Jawa Tengah');
INSERT INTO `ref_country_states` VALUES (1387, 101, 'Jawa Timur');
INSERT INTO `ref_country_states` VALUES (1388, 101, 'Kalimantan Barat');
INSERT INTO `ref_country_states` VALUES (1389, 101, 'Kalimantan Selatan');
INSERT INTO `ref_country_states` VALUES (1390, 101, 'Kalimantan Tengah');
INSERT INTO `ref_country_states` VALUES (1391, 101, 'Kalimantan Timur');
INSERT INTO `ref_country_states` VALUES (1392, 101, 'Kepulauan Bangka Belitung');
INSERT INTO `ref_country_states` VALUES (1393, 101, 'Kepulauan Riau');
INSERT INTO `ref_country_states` VALUES (1394, 101, 'Lampung');
INSERT INTO `ref_country_states` VALUES (1395, 101, 'Maluku');
INSERT INTO `ref_country_states` VALUES (1396, 101, 'Maluku Utara');
INSERT INTO `ref_country_states` VALUES (1397, 101, 'Nusa Tenggara Barat');
INSERT INTO `ref_country_states` VALUES (1398, 101, 'Nusa Tenggara Timur');
INSERT INTO `ref_country_states` VALUES (1399, 101, 'Papua');
INSERT INTO `ref_country_states` VALUES (1400, 101, 'Riau');
INSERT INTO `ref_country_states` VALUES (1401, 101, 'Sulawesi Barat');
INSERT INTO `ref_country_states` VALUES (1402, 101, 'Sulawesi Selatan');
INSERT INTO `ref_country_states` VALUES (1403, 101, 'Sulawesi Tengah');
INSERT INTO `ref_country_states` VALUES (1404, 101, 'Sulawesi Tenggara');
INSERT INTO `ref_country_states` VALUES (1405, 101, 'Sulawesi Utara');
INSERT INTO `ref_country_states` VALUES (1406, 101, 'Sumatera Barat');
INSERT INTO `ref_country_states` VALUES (1407, 101, 'Sumatera Selatan');
INSERT INTO `ref_country_states` VALUES (1408, 101, 'Sumatera Utara');
INSERT INTO `ref_country_states` VALUES (1409, 101, 'DI Yogyakarta');
INSERT INTO `ref_country_states` VALUES (1410, 102, 'Carlow');
INSERT INTO `ref_country_states` VALUES (1411, 102, 'Cavan');
INSERT INTO `ref_country_states` VALUES (1412, 102, 'Clare');
INSERT INTO `ref_country_states` VALUES (1413, 102, 'Cork');
INSERT INTO `ref_country_states` VALUES (1414, 102, 'Donegal');
INSERT INTO `ref_country_states` VALUES (1415, 102, 'Dublin');
INSERT INTO `ref_country_states` VALUES (1416, 102, 'Galway');
INSERT INTO `ref_country_states` VALUES (1417, 102, 'Kerry');
INSERT INTO `ref_country_states` VALUES (1418, 102, 'Kildare');
INSERT INTO `ref_country_states` VALUES (1419, 102, 'Kilkenny');
INSERT INTO `ref_country_states` VALUES (1420, 102, 'Laois');
INSERT INTO `ref_country_states` VALUES (1421, 102, 'Leitrim');
INSERT INTO `ref_country_states` VALUES (1422, 102, 'Limerick');
INSERT INTO `ref_country_states` VALUES (1423, 102, 'Longford');
INSERT INTO `ref_country_states` VALUES (1424, 102, 'Louth');
INSERT INTO `ref_country_states` VALUES (1425, 102, 'Mayo');
INSERT INTO `ref_country_states` VALUES (1426, 102, 'Meath');
INSERT INTO `ref_country_states` VALUES (1427, 102, 'Monaghan');
INSERT INTO `ref_country_states` VALUES (1428, 102, 'Offaly');
INSERT INTO `ref_country_states` VALUES (1429, 102, 'Roscommon');
INSERT INTO `ref_country_states` VALUES (1430, 102, 'Sligo');
INSERT INTO `ref_country_states` VALUES (1431, 102, 'Tipperary');
INSERT INTO `ref_country_states` VALUES (1432, 102, 'Waterford');
INSERT INTO `ref_country_states` VALUES (1433, 102, 'Westmeath');
INSERT INTO `ref_country_states` VALUES (1434, 102, 'Wexford');
INSERT INTO `ref_country_states` VALUES (1435, 102, 'Wicklow');
INSERT INTO `ref_country_states` VALUES (1436, 103, 'Central');
INSERT INTO `ref_country_states` VALUES (1437, 103, 'Haifa');
INSERT INTO `ref_country_states` VALUES (1438, 103, 'Jerusalem');
INSERT INTO `ref_country_states` VALUES (1439, 103, 'Northern');
INSERT INTO `ref_country_states` VALUES (1440, 103, 'Southern');
INSERT INTO `ref_country_states` VALUES (1441, 103, 'Tel Aviv');
INSERT INTO `ref_country_states` VALUES (1442, 105, 'Andaman and Nicobar Islands');
INSERT INTO `ref_country_states` VALUES (1443, 105, 'Andhra Pradesh');
INSERT INTO `ref_country_states` VALUES (1444, 105, 'Arunachal Pradesh');
INSERT INTO `ref_country_states` VALUES (1445, 105, 'Assam');
INSERT INTO `ref_country_states` VALUES (1446, 105, 'Bihar');
INSERT INTO `ref_country_states` VALUES (1447, 105, 'Chandigarh');
INSERT INTO `ref_country_states` VALUES (1448, 105, 'Chhattisgarh');
INSERT INTO `ref_country_states` VALUES (1449, 105, 'Dadra and Nagar Haveli');
INSERT INTO `ref_country_states` VALUES (1450, 105, 'Daman and Diu');
INSERT INTO `ref_country_states` VALUES (1451, 105, 'Delhi');
INSERT INTO `ref_country_states` VALUES (1452, 105, 'Goa');
INSERT INTO `ref_country_states` VALUES (1453, 105, 'Gujarat');
INSERT INTO `ref_country_states` VALUES (1454, 105, 'Haryana');
INSERT INTO `ref_country_states` VALUES (1455, 105, 'Himachal Pradesh');
INSERT INTO `ref_country_states` VALUES (1456, 105, 'Jammu and Kashmir');
INSERT INTO `ref_country_states` VALUES (1457, 105, 'Jharkhand');
INSERT INTO `ref_country_states` VALUES (1458, 105, 'Karnataka');
INSERT INTO `ref_country_states` VALUES (1459, 105, 'Kerala');
INSERT INTO `ref_country_states` VALUES (1460, 105, 'Ladakh');
INSERT INTO `ref_country_states` VALUES (1461, 105, 'Lakshadweep');
INSERT INTO `ref_country_states` VALUES (1462, 105, 'Madhya Pradesh');
INSERT INTO `ref_country_states` VALUES (1463, 105, 'Maharashtra');
INSERT INTO `ref_country_states` VALUES (1464, 105, 'Manipur');
INSERT INTO `ref_country_states` VALUES (1465, 105, 'Meghalaya');
INSERT INTO `ref_country_states` VALUES (1466, 105, 'Mizoram');
INSERT INTO `ref_country_states` VALUES (1467, 105, 'Nagaland');
INSERT INTO `ref_country_states` VALUES (1468, 105, 'Orissa');
INSERT INTO `ref_country_states` VALUES (1469, 105, 'Pondicherry');
INSERT INTO `ref_country_states` VALUES (1470, 105, 'Punjab');
INSERT INTO `ref_country_states` VALUES (1471, 105, 'Rajasthan');
INSERT INTO `ref_country_states` VALUES (1472, 105, 'Sikkim');
INSERT INTO `ref_country_states` VALUES (1473, 105, 'Tamil Nadu');
INSERT INTO `ref_country_states` VALUES (1474, 105, 'Telangana');
INSERT INTO `ref_country_states` VALUES (1475, 105, 'Tripura');
INSERT INTO `ref_country_states` VALUES (1476, 105, 'Uttaranchal');
INSERT INTO `ref_country_states` VALUES (1477, 105, 'Uttar Pradesh');
INSERT INTO `ref_country_states` VALUES (1478, 105, 'West Bengal');
INSERT INTO `ref_country_states` VALUES (1479, 107, 'Al Anbar');
INSERT INTO `ref_country_states` VALUES (1480, 107, 'Al Basrah');
INSERT INTO `ref_country_states` VALUES (1481, 107, 'Al Muthanna');
INSERT INTO `ref_country_states` VALUES (1482, 107, 'Al Qadisiyah');
INSERT INTO `ref_country_states` VALUES (1483, 107, 'An Najaf');
INSERT INTO `ref_country_states` VALUES (1484, 107, 'Arbil');
INSERT INTO `ref_country_states` VALUES (1485, 107, 'As Sulaymaniyah');
INSERT INTO `ref_country_states` VALUES (1486, 107, 'At Ta\'mim');
INSERT INTO `ref_country_states` VALUES (1487, 107, 'Babil');
INSERT INTO `ref_country_states` VALUES (1488, 107, 'Baghdad');
INSERT INTO `ref_country_states` VALUES (1489, 107, 'Dahuk');
INSERT INTO `ref_country_states` VALUES (1490, 107, 'Dhi Qar');
INSERT INTO `ref_country_states` VALUES (1491, 107, 'Diyala');
INSERT INTO `ref_country_states` VALUES (1492, 107, 'Karbala\'');
INSERT INTO `ref_country_states` VALUES (1493, 107, 'Maysan');
INSERT INTO `ref_country_states` VALUES (1494, 107, 'Ninawa');
INSERT INTO `ref_country_states` VALUES (1495, 107, 'Salah ad Din');
INSERT INTO `ref_country_states` VALUES (1496, 107, 'Wasit');
INSERT INTO `ref_country_states` VALUES (1497, 108, 'Ardabil');
INSERT INTO `ref_country_states` VALUES (1498, 108, 'Azarbayjan-e Gharbi');
INSERT INTO `ref_country_states` VALUES (1499, 108, 'Azarbayjan-e Sharqi');
INSERT INTO `ref_country_states` VALUES (1500, 108, 'Bushehr');
INSERT INTO `ref_country_states` VALUES (1501, 108, 'Chahar Mahall va Bakhtiari');
INSERT INTO `ref_country_states` VALUES (1502, 108, 'Esfahan');
INSERT INTO `ref_country_states` VALUES (1503, 108, 'Fars');
INSERT INTO `ref_country_states` VALUES (1504, 108, 'Gilan');
INSERT INTO `ref_country_states` VALUES (1505, 108, 'Golestan');
INSERT INTO `ref_country_states` VALUES (1506, 108, 'Hamadan');
INSERT INTO `ref_country_states` VALUES (1507, 108, 'Hormozgan');
INSERT INTO `ref_country_states` VALUES (1508, 108, 'Ilam');
INSERT INTO `ref_country_states` VALUES (1509, 108, 'Kerman');
INSERT INTO `ref_country_states` VALUES (1510, 108, 'Kermanshah');
INSERT INTO `ref_country_states` VALUES (1511, 108, 'Khorasan-e Janubi');
INSERT INTO `ref_country_states` VALUES (1512, 108, 'Khorasan-e Razavi');
INSERT INTO `ref_country_states` VALUES (1513, 108, 'Khorasan-e Shemali');
INSERT INTO `ref_country_states` VALUES (1514, 108, 'Khuzestan');
INSERT INTO `ref_country_states` VALUES (1515, 108, 'Kohgiluyeh va Buyer Ahmad');
INSERT INTO `ref_country_states` VALUES (1516, 108, 'Kordestan');
INSERT INTO `ref_country_states` VALUES (1517, 108, 'Lorestan');
INSERT INTO `ref_country_states` VALUES (1518, 108, 'Markazi');
INSERT INTO `ref_country_states` VALUES (1519, 108, 'Mazandaran');
INSERT INTO `ref_country_states` VALUES (1520, 108, 'Qazvin');
INSERT INTO `ref_country_states` VALUES (1521, 108, 'Qom');
INSERT INTO `ref_country_states` VALUES (1522, 108, 'Semnan');
INSERT INTO `ref_country_states` VALUES (1523, 108, 'Sistan va Baluchestan');
INSERT INTO `ref_country_states` VALUES (1524, 108, 'Tehran');
INSERT INTO `ref_country_states` VALUES (1525, 108, 'Yazd');
INSERT INTO `ref_country_states` VALUES (1526, 108, 'Zanjan');
INSERT INTO `ref_country_states` VALUES (1527, 109, 'Austurland');
INSERT INTO `ref_country_states` VALUES (1528, 109, 'Hofudhborgarsvaedhi');
INSERT INTO `ref_country_states` VALUES (1529, 109, 'Nordhurland Eystra');
INSERT INTO `ref_country_states` VALUES (1530, 109, 'Nordhurland Vestra');
INSERT INTO `ref_country_states` VALUES (1531, 109, 'Sudhurland');
INSERT INTO `ref_country_states` VALUES (1532, 109, 'Sudhurnes');
INSERT INTO `ref_country_states` VALUES (1533, 109, 'Vestfirdhir');
INSERT INTO `ref_country_states` VALUES (1534, 109, 'Vesturland');
INSERT INTO `ref_country_states` VALUES (1535, 110, 'Abruzzo');
INSERT INTO `ref_country_states` VALUES (1536, 110, 'Basilicata');
INSERT INTO `ref_country_states` VALUES (1537, 110, 'Calabria');
INSERT INTO `ref_country_states` VALUES (1538, 110, 'Campania');
INSERT INTO `ref_country_states` VALUES (1539, 110, 'Emilia-Romagna');
INSERT INTO `ref_country_states` VALUES (1540, 110, 'Friuli-Venezia Giulia');
INSERT INTO `ref_country_states` VALUES (1541, 110, 'Lazio');
INSERT INTO `ref_country_states` VALUES (1542, 110, 'Liguria');
INSERT INTO `ref_country_states` VALUES (1543, 110, 'Lombardia');
INSERT INTO `ref_country_states` VALUES (1544, 110, 'Marche');
INSERT INTO `ref_country_states` VALUES (1545, 110, 'Molise');
INSERT INTO `ref_country_states` VALUES (1546, 110, 'Piemonte');
INSERT INTO `ref_country_states` VALUES (1547, 110, 'Puglia');
INSERT INTO `ref_country_states` VALUES (1548, 110, 'Sardegna');
INSERT INTO `ref_country_states` VALUES (1549, 110, 'Sicilia');
INSERT INTO `ref_country_states` VALUES (1550, 110, 'Toscana');
INSERT INTO `ref_country_states` VALUES (1551, 110, 'Trentino-Alto Adige');
INSERT INTO `ref_country_states` VALUES (1552, 110, 'Umbria');
INSERT INTO `ref_country_states` VALUES (1553, 110, 'Valle d\'Aosta');
INSERT INTO `ref_country_states` VALUES (1554, 110, 'Veneto');
INSERT INTO `ref_country_states` VALUES (1555, 112, 'Clarendon');
INSERT INTO `ref_country_states` VALUES (1556, 112, 'Hanover');
INSERT INTO `ref_country_states` VALUES (1557, 112, 'Kingston');
INSERT INTO `ref_country_states` VALUES (1558, 112, 'Manchester');
INSERT INTO `ref_country_states` VALUES (1559, 112, 'Portland');
INSERT INTO `ref_country_states` VALUES (1560, 112, 'Saint Andrew');
INSERT INTO `ref_country_states` VALUES (1561, 112, 'Saint Ann');
INSERT INTO `ref_country_states` VALUES (1562, 112, 'Saint Catherine');
INSERT INTO `ref_country_states` VALUES (1563, 112, 'Saint Elizabeth');
INSERT INTO `ref_country_states` VALUES (1564, 112, 'Saint James');
INSERT INTO `ref_country_states` VALUES (1565, 112, 'Saint Mary');
INSERT INTO `ref_country_states` VALUES (1566, 112, 'Saint Thomas');
INSERT INTO `ref_country_states` VALUES (1567, 112, 'Trelawny');
INSERT INTO `ref_country_states` VALUES (1568, 112, 'Westmoreland');
INSERT INTO `ref_country_states` VALUES (1569, 113, 'Ajlun');
INSERT INTO `ref_country_states` VALUES (1570, 113, 'Al \'Aqabah');
INSERT INTO `ref_country_states` VALUES (1571, 113, 'Al Balqa\'');
INSERT INTO `ref_country_states` VALUES (1572, 113, 'Al Karak');
INSERT INTO `ref_country_states` VALUES (1573, 113, 'Al Mafraq');
INSERT INTO `ref_country_states` VALUES (1574, 113, '\'Amman');
INSERT INTO `ref_country_states` VALUES (1575, 113, 'At Tafilah');
INSERT INTO `ref_country_states` VALUES (1576, 113, 'Az Zarqa\'');
INSERT INTO `ref_country_states` VALUES (1577, 113, 'Irbid');
INSERT INTO `ref_country_states` VALUES (1578, 113, 'Jarash');
INSERT INTO `ref_country_states` VALUES (1579, 113, 'Ma\'an');
INSERT INTO `ref_country_states` VALUES (1580, 113, 'Madaba');
INSERT INTO `ref_country_states` VALUES (1581, 114, 'Aichi');
INSERT INTO `ref_country_states` VALUES (1582, 114, 'Akita');
INSERT INTO `ref_country_states` VALUES (1583, 114, 'Aomori');
INSERT INTO `ref_country_states` VALUES (1584, 114, 'Chiba');
INSERT INTO `ref_country_states` VALUES (1585, 114, 'Ehime');
INSERT INTO `ref_country_states` VALUES (1586, 114, 'Fukui');
INSERT INTO `ref_country_states` VALUES (1587, 114, 'Fukuoka');
INSERT INTO `ref_country_states` VALUES (1588, 114, 'Fukushima');
INSERT INTO `ref_country_states` VALUES (1589, 114, 'Gifu');
INSERT INTO `ref_country_states` VALUES (1590, 114, 'Gumma');
INSERT INTO `ref_country_states` VALUES (1591, 114, 'Hiroshima');
INSERT INTO `ref_country_states` VALUES (1592, 114, 'Hokkaido');
INSERT INTO `ref_country_states` VALUES (1593, 114, 'Hyogo');
INSERT INTO `ref_country_states` VALUES (1594, 114, 'Ibaraki');
INSERT INTO `ref_country_states` VALUES (1595, 114, 'Ishikawa');
INSERT INTO `ref_country_states` VALUES (1596, 114, 'Iwate');
INSERT INTO `ref_country_states` VALUES (1597, 114, 'Kagawa');
INSERT INTO `ref_country_states` VALUES (1598, 114, 'Kagoshima');
INSERT INTO `ref_country_states` VALUES (1599, 114, 'Kanagawa');
INSERT INTO `ref_country_states` VALUES (1600, 114, 'Kochi');
INSERT INTO `ref_country_states` VALUES (1601, 114, 'Kumamoto');
INSERT INTO `ref_country_states` VALUES (1602, 114, 'Kyoto');
INSERT INTO `ref_country_states` VALUES (1603, 114, 'Mie');
INSERT INTO `ref_country_states` VALUES (1604, 114, 'Miyagi');
INSERT INTO `ref_country_states` VALUES (1605, 114, 'Miyazaki');
INSERT INTO `ref_country_states` VALUES (1606, 114, 'Nagano');
INSERT INTO `ref_country_states` VALUES (1607, 114, 'Nagasaki');
INSERT INTO `ref_country_states` VALUES (1608, 114, 'Nara');
INSERT INTO `ref_country_states` VALUES (1609, 114, 'Niigata');
INSERT INTO `ref_country_states` VALUES (1610, 114, 'Oita');
INSERT INTO `ref_country_states` VALUES (1611, 114, 'Okayama');
INSERT INTO `ref_country_states` VALUES (1612, 114, 'Okinawa');
INSERT INTO `ref_country_states` VALUES (1613, 114, 'Osaka');
INSERT INTO `ref_country_states` VALUES (1614, 114, 'Saga');
INSERT INTO `ref_country_states` VALUES (1615, 114, 'Saitama');
INSERT INTO `ref_country_states` VALUES (1616, 114, 'Shiga');
INSERT INTO `ref_country_states` VALUES (1617, 114, 'Shimane');
INSERT INTO `ref_country_states` VALUES (1618, 114, 'Shizuoka');
INSERT INTO `ref_country_states` VALUES (1619, 114, 'Tochigi');
INSERT INTO `ref_country_states` VALUES (1620, 114, 'Tokushima');
INSERT INTO `ref_country_states` VALUES (1621, 114, 'Tokyo');
INSERT INTO `ref_country_states` VALUES (1622, 114, 'Tottori');
INSERT INTO `ref_country_states` VALUES (1623, 114, 'Toyama');
INSERT INTO `ref_country_states` VALUES (1624, 114, 'Wakayama');
INSERT INTO `ref_country_states` VALUES (1625, 114, 'Yamagata');
INSERT INTO `ref_country_states` VALUES (1626, 114, 'Yamaguchi');
INSERT INTO `ref_country_states` VALUES (1627, 114, 'Yamanashi');
INSERT INTO `ref_country_states` VALUES (1628, 115, 'Central');
INSERT INTO `ref_country_states` VALUES (1629, 115, 'Coast');
INSERT INTO `ref_country_states` VALUES (1630, 115, 'Eastern');
INSERT INTO `ref_country_states` VALUES (1631, 115, 'Nairobi Area');
INSERT INTO `ref_country_states` VALUES (1632, 115, 'North Eastern');
INSERT INTO `ref_country_states` VALUES (1633, 115, 'Nyanza');
INSERT INTO `ref_country_states` VALUES (1634, 115, 'Rift Valley');
INSERT INTO `ref_country_states` VALUES (1635, 115, 'Western');
INSERT INTO `ref_country_states` VALUES (1636, 116, 'Batken Oblasty');
INSERT INTO `ref_country_states` VALUES (1637, 116, 'Bishkek Shaary');
INSERT INTO `ref_country_states` VALUES (1638, 116, 'Chuy Oblasty');
INSERT INTO `ref_country_states` VALUES (1639, 116, 'Jalal-Abad Oblasty');
INSERT INTO `ref_country_states` VALUES (1640, 116, 'Naryn Oblasty');
INSERT INTO `ref_country_states` VALUES (1641, 116, 'Osh Oblasty');
INSERT INTO `ref_country_states` VALUES (1642, 116, 'Talas Oblasty');
INSERT INTO `ref_country_states` VALUES (1643, 116, 'Ysyk-Kol Oblasty');
INSERT INTO `ref_country_states` VALUES (1644, 117, 'Banteay Mean Chey');
INSERT INTO `ref_country_states` VALUES (1645, 117, 'Batdambang');
INSERT INTO `ref_country_states` VALUES (1646, 117, 'Kampong Cham');
INSERT INTO `ref_country_states` VALUES (1647, 117, 'Kampong Chhnang');
INSERT INTO `ref_country_states` VALUES (1648, 117, 'Kampong Spoe');
INSERT INTO `ref_country_states` VALUES (1649, 117, 'Kampong Thum');
INSERT INTO `ref_country_states` VALUES (1650, 117, 'Kampot');
INSERT INTO `ref_country_states` VALUES (1651, 117, 'Kandal');
INSERT INTO `ref_country_states` VALUES (1652, 117, 'Koh Kong');
INSERT INTO `ref_country_states` VALUES (1653, 117, 'Kracheh');
INSERT INTO `ref_country_states` VALUES (1654, 117, 'Mondol Kiri');
INSERT INTO `ref_country_states` VALUES (1655, 117, 'Otdar Mean Chey');
INSERT INTO `ref_country_states` VALUES (1656, 117, 'Pouthisat');
INSERT INTO `ref_country_states` VALUES (1657, 117, 'Preah Vihear');
INSERT INTO `ref_country_states` VALUES (1658, 117, 'Prey Veng');
INSERT INTO `ref_country_states` VALUES (1659, 117, 'Rotanakir');
INSERT INTO `ref_country_states` VALUES (1660, 117, 'Siem Reab');
INSERT INTO `ref_country_states` VALUES (1661, 117, 'Stoeng Treng');
INSERT INTO `ref_country_states` VALUES (1662, 117, 'Svay Rieng');
INSERT INTO `ref_country_states` VALUES (1663, 117, 'Takao');
INSERT INTO `ref_country_states` VALUES (1664, 117, 'Keb');
INSERT INTO `ref_country_states` VALUES (1665, 117, 'Pailin');
INSERT INTO `ref_country_states` VALUES (1666, 117, 'Phnom Penh');
INSERT INTO `ref_country_states` VALUES (1667, 117, 'Preah Seihanu');
INSERT INTO `ref_country_states` VALUES (1668, 118, 'Banaba');
INSERT INTO `ref_country_states` VALUES (1669, 118, 'Tarawa');
INSERT INTO `ref_country_states` VALUES (1670, 118, 'Northern Gilbert Islands');
INSERT INTO `ref_country_states` VALUES (1671, 118, 'Central Gilbert Island');
INSERT INTO `ref_country_states` VALUES (1672, 118, 'Southern Gilbert Islands');
INSERT INTO `ref_country_states` VALUES (1673, 118, 'Line Islands');
INSERT INTO `ref_country_states` VALUES (1674, 119, 'Grande Comore (Njazidja)');
INSERT INTO `ref_country_states` VALUES (1675, 119, 'Anjouan (Nzwani)');
INSERT INTO `ref_country_states` VALUES (1676, 119, 'Moheli (Mwali)');
INSERT INTO `ref_country_states` VALUES (1677, 123, 'Al Ahmadi');
INSERT INTO `ref_country_states` VALUES (1678, 123, 'Al Farwaniyah');
INSERT INTO `ref_country_states` VALUES (1679, 123, 'Al Asimah');
INSERT INTO `ref_country_states` VALUES (1680, 123, 'Al Jahra');
INSERT INTO `ref_country_states` VALUES (1681, 123, 'Hawalli');
INSERT INTO `ref_country_states` VALUES (1682, 123, 'Mubarak Al-Kabeer');
INSERT INTO `ref_country_states` VALUES (1683, 125, 'Almaty Oblysy');
INSERT INTO `ref_country_states` VALUES (1684, 125, 'Almaty Qalasy');
INSERT INTO `ref_country_states` VALUES (1685, 125, 'Aqmola Oblysy');
INSERT INTO `ref_country_states` VALUES (1686, 125, 'Aqtobe Oblysy');
INSERT INTO `ref_country_states` VALUES (1687, 125, 'Astana Qalasy');
INSERT INTO `ref_country_states` VALUES (1688, 125, 'Atyrau Oblysy');
INSERT INTO `ref_country_states` VALUES (1689, 125, 'Batys Qazaqstan Oblysy');
INSERT INTO `ref_country_states` VALUES (1690, 125, 'Bayqongyr Qalasy');
INSERT INTO `ref_country_states` VALUES (1691, 125, 'Mangghystau Oblysy');
INSERT INTO `ref_country_states` VALUES (1692, 125, 'Ongtustik Qazaqstan Oblysy');
INSERT INTO `ref_country_states` VALUES (1693, 125, 'Pavlodar Oblysy');
INSERT INTO `ref_country_states` VALUES (1694, 125, 'Qaraghandy Oblysy');
INSERT INTO `ref_country_states` VALUES (1695, 125, 'Qostanay Oblysy');
INSERT INTO `ref_country_states` VALUES (1696, 125, 'Qyzylorda Oblysy');
INSERT INTO `ref_country_states` VALUES (1697, 125, 'Shyghys Qazaqstan Oblysy');
INSERT INTO `ref_country_states` VALUES (1698, 125, 'Soltustik Qazaqstan Oblysy');
INSERT INTO `ref_country_states` VALUES (1699, 125, 'Zhambyl Oblysy');
INSERT INTO `ref_country_states` VALUES (1700, 126, 'Attapu');
INSERT INTO `ref_country_states` VALUES (1701, 126, 'Bokeo');
INSERT INTO `ref_country_states` VALUES (1702, 126, 'Bolikhamxai');
INSERT INTO `ref_country_states` VALUES (1703, 126, 'Champasak');
INSERT INTO `ref_country_states` VALUES (1704, 126, 'Houaphan');
INSERT INTO `ref_country_states` VALUES (1705, 126, 'Khammouan');
INSERT INTO `ref_country_states` VALUES (1706, 126, 'Louangnamtha');
INSERT INTO `ref_country_states` VALUES (1707, 126, 'Louangphrabang');
INSERT INTO `ref_country_states` VALUES (1708, 126, 'Oudomxai');
INSERT INTO `ref_country_states` VALUES (1709, 126, 'Phongsali');
INSERT INTO `ref_country_states` VALUES (1710, 126, 'Salavan');
INSERT INTO `ref_country_states` VALUES (1711, 126, 'Savannakhet');
INSERT INTO `ref_country_states` VALUES (1712, 126, 'Viangchan');
INSERT INTO `ref_country_states` VALUES (1713, 126, 'Viangchan');
INSERT INTO `ref_country_states` VALUES (1714, 126, 'Xaignabouli');
INSERT INTO `ref_country_states` VALUES (1715, 126, 'Xaisomboun');
INSERT INTO `ref_country_states` VALUES (1716, 126, 'Xekong');
INSERT INTO `ref_country_states` VALUES (1717, 126, 'Xiangkhoang');
INSERT INTO `ref_country_states` VALUES (1718, 127, 'Beyrouth');
INSERT INTO `ref_country_states` VALUES (1719, 127, 'Beqaa');
INSERT INTO `ref_country_states` VALUES (1720, 127, 'Liban-Nord');
INSERT INTO `ref_country_states` VALUES (1721, 127, 'Liban-Sud');
INSERT INTO `ref_country_states` VALUES (1722, 127, 'Mont-Liban');
INSERT INTO `ref_country_states` VALUES (1723, 127, 'Nabatiye');
INSERT INTO `ref_country_states` VALUES (1724, 129, 'Balzers');
INSERT INTO `ref_country_states` VALUES (1725, 129, 'Eschen');
INSERT INTO `ref_country_states` VALUES (1726, 129, 'Gamprin');
INSERT INTO `ref_country_states` VALUES (1727, 129, 'Mauren');
INSERT INTO `ref_country_states` VALUES (1728, 129, 'Planken');
INSERT INTO `ref_country_states` VALUES (1729, 129, 'Ruggell');
INSERT INTO `ref_country_states` VALUES (1730, 129, 'Schaan');
INSERT INTO `ref_country_states` VALUES (1731, 129, 'Schellenberg');
INSERT INTO `ref_country_states` VALUES (1732, 129, 'Triesen');
INSERT INTO `ref_country_states` VALUES (1733, 129, 'Triesenberg');
INSERT INTO `ref_country_states` VALUES (1734, 129, 'Vaduz');
INSERT INTO `ref_country_states` VALUES (1735, 130, 'Central');
INSERT INTO `ref_country_states` VALUES (1736, 130, 'North Central');
INSERT INTO `ref_country_states` VALUES (1737, 130, 'Northern');
INSERT INTO `ref_country_states` VALUES (1738, 130, 'Eastern');
INSERT INTO `ref_country_states` VALUES (1739, 130, 'North Western');
INSERT INTO `ref_country_states` VALUES (1740, 130, 'Sabaragamuwa');
INSERT INTO `ref_country_states` VALUES (1741, 130, 'Southern');
INSERT INTO `ref_country_states` VALUES (1742, 130, 'Uva');
INSERT INTO `ref_country_states` VALUES (1743, 130, 'Western');
INSERT INTO `ref_country_states` VALUES (1744, 131, 'Bomi');
INSERT INTO `ref_country_states` VALUES (1745, 131, 'Bong');
INSERT INTO `ref_country_states` VALUES (1746, 131, 'Gbarpolu');
INSERT INTO `ref_country_states` VALUES (1747, 131, 'Grand Bassa');
INSERT INTO `ref_country_states` VALUES (1748, 131, 'Grand Cape Mount');
INSERT INTO `ref_country_states` VALUES (1749, 131, 'Grand Gedeh');
INSERT INTO `ref_country_states` VALUES (1750, 131, 'Grand Kru');
INSERT INTO `ref_country_states` VALUES (1751, 131, 'Lofa');
INSERT INTO `ref_country_states` VALUES (1752, 131, 'Margibi');
INSERT INTO `ref_country_states` VALUES (1753, 131, 'Maryland');
INSERT INTO `ref_country_states` VALUES (1754, 131, 'Montserrado');
INSERT INTO `ref_country_states` VALUES (1755, 131, 'Nimba');
INSERT INTO `ref_country_states` VALUES (1756, 131, 'River Cess');
INSERT INTO `ref_country_states` VALUES (1757, 131, 'River Gee');
INSERT INTO `ref_country_states` VALUES (1758, 131, 'Sinoe');
INSERT INTO `ref_country_states` VALUES (1759, 132, 'Berea');
INSERT INTO `ref_country_states` VALUES (1760, 132, 'Butha-Buthe');
INSERT INTO `ref_country_states` VALUES (1761, 132, 'Leribe');
INSERT INTO `ref_country_states` VALUES (1762, 132, 'Mafeteng');
INSERT INTO `ref_country_states` VALUES (1763, 132, 'Maseru');
INSERT INTO `ref_country_states` VALUES (1764, 132, 'Mohale\'s Hoek');
INSERT INTO `ref_country_states` VALUES (1765, 132, 'Mokhotlong');
INSERT INTO `ref_country_states` VALUES (1766, 132, 'Qacha\'s Nek');
INSERT INTO `ref_country_states` VALUES (1767, 132, 'Quthing');
INSERT INTO `ref_country_states` VALUES (1768, 132, 'Thaba-Tseka');
INSERT INTO `ref_country_states` VALUES (1769, 133, 'Alytaus');
INSERT INTO `ref_country_states` VALUES (1770, 133, 'Kauno');
INSERT INTO `ref_country_states` VALUES (1771, 133, 'Klaipedos');
INSERT INTO `ref_country_states` VALUES (1772, 133, 'Marijampoles');
INSERT INTO `ref_country_states` VALUES (1773, 133, 'Panevezio');
INSERT INTO `ref_country_states` VALUES (1774, 133, 'Siauliu');
INSERT INTO `ref_country_states` VALUES (1775, 133, 'Taurages');
INSERT INTO `ref_country_states` VALUES (1776, 133, 'Telsiu');
INSERT INTO `ref_country_states` VALUES (1777, 133, 'Utenos');
INSERT INTO `ref_country_states` VALUES (1778, 133, 'Vilniaus');
INSERT INTO `ref_country_states` VALUES (1779, 134, 'Diekirch');
INSERT INTO `ref_country_states` VALUES (1780, 134, 'Grevenmacher');
INSERT INTO `ref_country_states` VALUES (1781, 134, 'Luxembourg');
INSERT INTO `ref_country_states` VALUES (1782, 135, 'Aizkraukles Rajons');
INSERT INTO `ref_country_states` VALUES (1783, 135, 'Aluksnes Rajons');
INSERT INTO `ref_country_states` VALUES (1784, 135, 'Balvu Rajons');
INSERT INTO `ref_country_states` VALUES (1785, 135, 'Bauskas Rajons');
INSERT INTO `ref_country_states` VALUES (1786, 135, 'Cesu Rajons');
INSERT INTO `ref_country_states` VALUES (1787, 135, 'Daugavpils');
INSERT INTO `ref_country_states` VALUES (1788, 135, 'Daugavpils Rajons');
INSERT INTO `ref_country_states` VALUES (1789, 135, 'Dobeles Rajons');
INSERT INTO `ref_country_states` VALUES (1790, 135, 'Gulbenes Rajons');
INSERT INTO `ref_country_states` VALUES (1791, 135, 'Jekabpils Rajons');
INSERT INTO `ref_country_states` VALUES (1792, 135, 'Jelgava');
INSERT INTO `ref_country_states` VALUES (1793, 135, 'Jelgavas Rajons');
INSERT INTO `ref_country_states` VALUES (1794, 135, 'Jurmala');
INSERT INTO `ref_country_states` VALUES (1795, 135, 'Kraslavas Rajons');
INSERT INTO `ref_country_states` VALUES (1796, 135, 'Kuldigas Rajons');
INSERT INTO `ref_country_states` VALUES (1797, 135, 'Liepaja');
INSERT INTO `ref_country_states` VALUES (1798, 135, 'Liepajas Rajons');
INSERT INTO `ref_country_states` VALUES (1799, 135, 'Limbazu Rajons');
INSERT INTO `ref_country_states` VALUES (1800, 135, 'Ludzas Rajons');
INSERT INTO `ref_country_states` VALUES (1801, 135, 'Madonas Rajons');
INSERT INTO `ref_country_states` VALUES (1802, 135, 'Ogres Rajons');
INSERT INTO `ref_country_states` VALUES (1803, 135, 'Preilu Rajons');
INSERT INTO `ref_country_states` VALUES (1804, 135, 'Rezekne');
INSERT INTO `ref_country_states` VALUES (1805, 135, 'Rezeknes Rajons');
INSERT INTO `ref_country_states` VALUES (1806, 135, 'Riga');
INSERT INTO `ref_country_states` VALUES (1807, 135, 'Rigas Rajons');
INSERT INTO `ref_country_states` VALUES (1808, 135, 'Saldus Rajons');
INSERT INTO `ref_country_states` VALUES (1809, 135, 'Talsu Rajons');
INSERT INTO `ref_country_states` VALUES (1810, 135, 'Tukuma Rajons');
INSERT INTO `ref_country_states` VALUES (1811, 135, 'Valkas Rajons');
INSERT INTO `ref_country_states` VALUES (1812, 135, 'Valmieras Rajons');
INSERT INTO `ref_country_states` VALUES (1813, 135, 'Ventspils');
INSERT INTO `ref_country_states` VALUES (1814, 135, 'Ventspils Rajons');
INSERT INTO `ref_country_states` VALUES (1815, 136, 'Ajdabiya');
INSERT INTO `ref_country_states` VALUES (1816, 136, 'Al \'Aziziyah');
INSERT INTO `ref_country_states` VALUES (1817, 136, 'Al Fatih');
INSERT INTO `ref_country_states` VALUES (1818, 136, 'Al Jabal al Akhdar');
INSERT INTO `ref_country_states` VALUES (1819, 136, 'Al Jufrah');
INSERT INTO `ref_country_states` VALUES (1820, 136, 'Al Khums');
INSERT INTO `ref_country_states` VALUES (1821, 136, 'Al Kufrah');
INSERT INTO `ref_country_states` VALUES (1822, 136, 'An Nuqat al Khams');
INSERT INTO `ref_country_states` VALUES (1823, 136, 'Ash Shati\'');
INSERT INTO `ref_country_states` VALUES (1824, 136, 'Awbari');
INSERT INTO `ref_country_states` VALUES (1825, 136, 'Az Zawiyah');
INSERT INTO `ref_country_states` VALUES (1826, 136, 'Banghazi');
INSERT INTO `ref_country_states` VALUES (1827, 136, 'Darnah');
INSERT INTO `ref_country_states` VALUES (1828, 136, 'Ghadamis');
INSERT INTO `ref_country_states` VALUES (1829, 136, 'Gharyan');
INSERT INTO `ref_country_states` VALUES (1830, 136, 'Misratah');
INSERT INTO `ref_country_states` VALUES (1831, 136, 'Murzuq');
INSERT INTO `ref_country_states` VALUES (1832, 136, 'Sabha');
INSERT INTO `ref_country_states` VALUES (1833, 136, 'Sawfajjin');
INSERT INTO `ref_country_states` VALUES (1834, 136, 'Surt');
INSERT INTO `ref_country_states` VALUES (1835, 136, 'Tarabulus');
INSERT INTO `ref_country_states` VALUES (1836, 136, 'Tarhunah');
INSERT INTO `ref_country_states` VALUES (1837, 136, 'Tubruq');
INSERT INTO `ref_country_states` VALUES (1838, 136, 'Yafran');
INSERT INTO `ref_country_states` VALUES (1839, 136, 'Zlitan');
INSERT INTO `ref_country_states` VALUES (1840, 137, 'Agadir');
INSERT INTO `ref_country_states` VALUES (1841, 137, 'Al Hoceima');
INSERT INTO `ref_country_states` VALUES (1842, 137, 'Azilal');
INSERT INTO `ref_country_states` VALUES (1843, 137, 'Beni Mellal');
INSERT INTO `ref_country_states` VALUES (1844, 137, 'Ben Slimane');
INSERT INTO `ref_country_states` VALUES (1845, 137, 'Boulemane');
INSERT INTO `ref_country_states` VALUES (1846, 137, 'Casablanca');
INSERT INTO `ref_country_states` VALUES (1847, 137, 'Chaouen');
INSERT INTO `ref_country_states` VALUES (1848, 137, 'El Jadida');
INSERT INTO `ref_country_states` VALUES (1849, 137, 'El Kelaa des Sraghna');
INSERT INTO `ref_country_states` VALUES (1850, 137, 'Er Rachidia');
INSERT INTO `ref_country_states` VALUES (1851, 137, 'Essaouira');
INSERT INTO `ref_country_states` VALUES (1852, 137, 'Fes');
INSERT INTO `ref_country_states` VALUES (1853, 137, 'Figuig');
INSERT INTO `ref_country_states` VALUES (1854, 137, 'Guelmim');
INSERT INTO `ref_country_states` VALUES (1855, 137, 'Ifrane');
INSERT INTO `ref_country_states` VALUES (1856, 137, 'Kenitra');
INSERT INTO `ref_country_states` VALUES (1857, 137, 'Khemisset');
INSERT INTO `ref_country_states` VALUES (1858, 137, 'Khenifra');
INSERT INTO `ref_country_states` VALUES (1859, 137, 'Khouribga');
INSERT INTO `ref_country_states` VALUES (1860, 137, 'Laayoune');
INSERT INTO `ref_country_states` VALUES (1861, 137, 'Larache');
INSERT INTO `ref_country_states` VALUES (1862, 137, 'Marrakech');
INSERT INTO `ref_country_states` VALUES (1863, 137, 'Meknes');
INSERT INTO `ref_country_states` VALUES (1864, 137, 'Nador');
INSERT INTO `ref_country_states` VALUES (1865, 137, 'Ouarzazate');
INSERT INTO `ref_country_states` VALUES (1866, 137, 'Oujda');
INSERT INTO `ref_country_states` VALUES (1867, 137, 'Rabat-Sale');
INSERT INTO `ref_country_states` VALUES (1868, 137, 'Safi');
INSERT INTO `ref_country_states` VALUES (1869, 137, 'Settat');
INSERT INTO `ref_country_states` VALUES (1870, 137, 'Sidi Kacem');
INSERT INTO `ref_country_states` VALUES (1871, 137, 'Tangier');
INSERT INTO `ref_country_states` VALUES (1872, 137, 'Tan-Tan');
INSERT INTO `ref_country_states` VALUES (1873, 137, 'Taounate');
INSERT INTO `ref_country_states` VALUES (1874, 137, 'Taroudannt');
INSERT INTO `ref_country_states` VALUES (1875, 137, 'Tata');
INSERT INTO `ref_country_states` VALUES (1876, 137, 'Taza');
INSERT INTO `ref_country_states` VALUES (1877, 137, 'Tetouan');
INSERT INTO `ref_country_states` VALUES (1878, 137, 'Tiznit');
INSERT INTO `ref_country_states` VALUES (1879, 138, 'Monaco-Ville');
INSERT INTO `ref_country_states` VALUES (1880, 138, 'La Condamine');
INSERT INTO `ref_country_states` VALUES (1881, 138, 'Monte Carlo');
INSERT INTO `ref_country_states` VALUES (1882, 138, 'Fontvieille');
INSERT INTO `ref_country_states` VALUES (1883, 139, 'Anenii Noi');
INSERT INTO `ref_country_states` VALUES (1884, 139, 'Basarabeasca');
INSERT INTO `ref_country_states` VALUES (1885, 139, 'Briceni');
INSERT INTO `ref_country_states` VALUES (1886, 139, 'Cahul');
INSERT INTO `ref_country_states` VALUES (1887, 139, 'Cantemir');
INSERT INTO `ref_country_states` VALUES (1888, 139, 'Calarasi');
INSERT INTO `ref_country_states` VALUES (1889, 139, 'Causeni');
INSERT INTO `ref_country_states` VALUES (1890, 139, 'Cimislia');
INSERT INTO `ref_country_states` VALUES (1891, 139, 'Criuleni');
INSERT INTO `ref_country_states` VALUES (1892, 139, 'Donduseni');
INSERT INTO `ref_country_states` VALUES (1893, 139, 'Drochia');
INSERT INTO `ref_country_states` VALUES (1894, 139, 'Dubasari');
INSERT INTO `ref_country_states` VALUES (1895, 139, 'Edinet');
INSERT INTO `ref_country_states` VALUES (1896, 139, 'Falesti');
INSERT INTO `ref_country_states` VALUES (1897, 139, 'Floresti');
INSERT INTO `ref_country_states` VALUES (1898, 139, 'Glodeni');
INSERT INTO `ref_country_states` VALUES (1899, 139, 'Hincesti');
INSERT INTO `ref_country_states` VALUES (1900, 139, 'Ialoveni');
INSERT INTO `ref_country_states` VALUES (1901, 139, 'Leova');
INSERT INTO `ref_country_states` VALUES (1902, 139, 'Nisporeni');
INSERT INTO `ref_country_states` VALUES (1903, 139, 'Ocnita');
INSERT INTO `ref_country_states` VALUES (1904, 139, 'Orhei');
INSERT INTO `ref_country_states` VALUES (1905, 139, 'Rezina');
INSERT INTO `ref_country_states` VALUES (1906, 139, 'Riscani');
INSERT INTO `ref_country_states` VALUES (1907, 139, 'Singerei');
INSERT INTO `ref_country_states` VALUES (1908, 139, 'Soldanesti');
INSERT INTO `ref_country_states` VALUES (1909, 139, 'Soroca');
INSERT INTO `ref_country_states` VALUES (1910, 139, 'Stefan-Voda');
INSERT INTO `ref_country_states` VALUES (1911, 139, 'Straseni');
INSERT INTO `ref_country_states` VALUES (1912, 139, 'Taraclia');
INSERT INTO `ref_country_states` VALUES (1913, 139, 'Telenesti');
INSERT INTO `ref_country_states` VALUES (1914, 139, 'Ungheni');
INSERT INTO `ref_country_states` VALUES (1915, 139, 'Balti');
INSERT INTO `ref_country_states` VALUES (1916, 139, 'Bender');
INSERT INTO `ref_country_states` VALUES (1917, 139, 'Chisinau');
INSERT INTO `ref_country_states` VALUES (1918, 139, 'Gagauzia');
INSERT INTO `ref_country_states` VALUES (1919, 139, 'Stinga Nistrului');
INSERT INTO `ref_country_states` VALUES (1920, 142, 'Antananarivo');
INSERT INTO `ref_country_states` VALUES (1921, 142, 'Antsiranana');
INSERT INTO `ref_country_states` VALUES (1922, 142, 'Fianarantsoa');
INSERT INTO `ref_country_states` VALUES (1923, 142, 'Mahajanga');
INSERT INTO `ref_country_states` VALUES (1924, 142, 'Toamasina');
INSERT INTO `ref_country_states` VALUES (1925, 142, 'Toliara');
INSERT INTO `ref_country_states` VALUES (1926, 143, 'Ailuk');
INSERT INTO `ref_country_states` VALUES (1927, 143, 'Ailinglaplap');
INSERT INTO `ref_country_states` VALUES (1928, 143, 'Arno');
INSERT INTO `ref_country_states` VALUES (1929, 143, 'Aur');
INSERT INTO `ref_country_states` VALUES (1930, 143, 'Ebon');
INSERT INTO `ref_country_states` VALUES (1931, 143, 'Enewetak');
INSERT INTO `ref_country_states` VALUES (1932, 143, 'Jabat');
INSERT INTO `ref_country_states` VALUES (1933, 143, 'Jaluit');
INSERT INTO `ref_country_states` VALUES (1934, 143, 'Kili');
INSERT INTO `ref_country_states` VALUES (1935, 143, 'Kwajalein');
INSERT INTO `ref_country_states` VALUES (1936, 143, 'Lae');
INSERT INTO `ref_country_states` VALUES (1937, 143, 'Lib');
INSERT INTO `ref_country_states` VALUES (1938, 143, 'Likiep');
INSERT INTO `ref_country_states` VALUES (1939, 143, 'Majuro');
INSERT INTO `ref_country_states` VALUES (1940, 143, 'Maloelap');
INSERT INTO `ref_country_states` VALUES (1941, 143, 'Mejit');
INSERT INTO `ref_country_states` VALUES (1942, 143, 'Mili');
INSERT INTO `ref_country_states` VALUES (1943, 143, 'Namorik');
INSERT INTO `ref_country_states` VALUES (1944, 143, 'Namu');
INSERT INTO `ref_country_states` VALUES (1945, 143, 'Rongelap');
INSERT INTO `ref_country_states` VALUES (1946, 143, 'Ujae');
INSERT INTO `ref_country_states` VALUES (1947, 143, 'Utirik');
INSERT INTO `ref_country_states` VALUES (1948, 143, 'Wotho');
INSERT INTO `ref_country_states` VALUES (1949, 143, 'Wotje');
INSERT INTO `ref_country_states` VALUES (1950, 143, 'Ailinginae');
INSERT INTO `ref_country_states` VALUES (1951, 143, 'Bikar');
INSERT INTO `ref_country_states` VALUES (1952, 143, 'Bikini');
INSERT INTO `ref_country_states` VALUES (1953, 143, 'Bokak');
INSERT INTO `ref_country_states` VALUES (1954, 143, 'Erikub');
INSERT INTO `ref_country_states` VALUES (1955, 143, 'Jemo');
INSERT INTO `ref_country_states` VALUES (1956, 143, 'Rongrik');
INSERT INTO `ref_country_states` VALUES (1957, 143, 'Toke');
INSERT INTO `ref_country_states` VALUES (1958, 143, 'Ujelang');
INSERT INTO `ref_country_states` VALUES (1959, 145, 'Bamako (Capital)');
INSERT INTO `ref_country_states` VALUES (1960, 145, 'Gao');
INSERT INTO `ref_country_states` VALUES (1961, 145, 'Kayes');
INSERT INTO `ref_country_states` VALUES (1962, 145, 'Kidal');
INSERT INTO `ref_country_states` VALUES (1963, 145, 'Koulikoro');
INSERT INTO `ref_country_states` VALUES (1964, 145, 'Mopti');
INSERT INTO `ref_country_states` VALUES (1965, 145, 'Segou');
INSERT INTO `ref_country_states` VALUES (1966, 145, 'Sikasso');
INSERT INTO `ref_country_states` VALUES (1967, 145, 'Tombouctou');
INSERT INTO `ref_country_states` VALUES (1968, 147, 'Arhangay');
INSERT INTO `ref_country_states` VALUES (1969, 147, 'Bayanhongor');
INSERT INTO `ref_country_states` VALUES (1970, 147, 'Bayan-Olgiy');
INSERT INTO `ref_country_states` VALUES (1971, 147, 'Bulgan');
INSERT INTO `ref_country_states` VALUES (1972, 147, 'Darhan Uul');
INSERT INTO `ref_country_states` VALUES (1973, 147, 'Dornod');
INSERT INTO `ref_country_states` VALUES (1974, 147, 'Dornogovi');
INSERT INTO `ref_country_states` VALUES (1975, 147, 'Dundgovi');
INSERT INTO `ref_country_states` VALUES (1976, 147, 'Dzavhan');
INSERT INTO `ref_country_states` VALUES (1977, 147, 'Govi-Altay');
INSERT INTO `ref_country_states` VALUES (1978, 147, 'Govi-Sumber');
INSERT INTO `ref_country_states` VALUES (1979, 147, 'Hentiy');
INSERT INTO `ref_country_states` VALUES (1980, 147, 'Hovd');
INSERT INTO `ref_country_states` VALUES (1981, 147, 'Hovsgol');
INSERT INTO `ref_country_states` VALUES (1982, 147, 'Omnogovi');
INSERT INTO `ref_country_states` VALUES (1983, 147, 'Orhon');
INSERT INTO `ref_country_states` VALUES (1984, 147, 'Ovorhangay');
INSERT INTO `ref_country_states` VALUES (1985, 147, 'Selenge');
INSERT INTO `ref_country_states` VALUES (1986, 147, 'Suhbaatar');
INSERT INTO `ref_country_states` VALUES (1987, 147, 'Tov');
INSERT INTO `ref_country_states` VALUES (1988, 147, 'Ulaanbaatar');
INSERT INTO `ref_country_states` VALUES (1989, 147, 'Uvs');
INSERT INTO `ref_country_states` VALUES (1990, 151, 'Adrar');
INSERT INTO `ref_country_states` VALUES (1991, 151, 'Assaba');
INSERT INTO `ref_country_states` VALUES (1992, 151, 'Brakna');
INSERT INTO `ref_country_states` VALUES (1993, 151, 'Dakhlet Nouadhibou');
INSERT INTO `ref_country_states` VALUES (1994, 151, 'Gorgol');
INSERT INTO `ref_country_states` VALUES (1995, 151, 'Guidimaka');
INSERT INTO `ref_country_states` VALUES (1996, 151, 'Hodh Ech Chargui');
INSERT INTO `ref_country_states` VALUES (1997, 151, 'Hodh El Gharbi');
INSERT INTO `ref_country_states` VALUES (1998, 151, 'Inchiri');
INSERT INTO `ref_country_states` VALUES (1999, 151, 'Nouakchott');
INSERT INTO `ref_country_states` VALUES (2000, 151, 'Tagant');
INSERT INTO `ref_country_states` VALUES (2001, 151, 'Tiris Zemmour');
INSERT INTO `ref_country_states` VALUES (2002, 151, 'Trarza');
INSERT INTO `ref_country_states` VALUES (2003, 153, 'Southern Harbour');
INSERT INTO `ref_country_states` VALUES (2004, 153, 'Northern Harbour');
INSERT INTO `ref_country_states` VALUES (2005, 153, 'Western District');
INSERT INTO `ref_country_states` VALUES (2006, 153, 'Northern District');
INSERT INTO `ref_country_states` VALUES (2007, 153, 'Gozo and Comino');
INSERT INTO `ref_country_states` VALUES (2008, 154, 'Agalega Islands');
INSERT INTO `ref_country_states` VALUES (2009, 154, 'Black River');
INSERT INTO `ref_country_states` VALUES (2010, 154, 'Cargados Carajos Shoals');
INSERT INTO `ref_country_states` VALUES (2011, 154, 'Flacq');
INSERT INTO `ref_country_states` VALUES (2012, 154, 'Grand Port');
INSERT INTO `ref_country_states` VALUES (2013, 154, 'Moka');
INSERT INTO `ref_country_states` VALUES (2014, 154, 'Pamplemousses');
INSERT INTO `ref_country_states` VALUES (2015, 154, 'Plaines Wilhems');
INSERT INTO `ref_country_states` VALUES (2016, 154, 'Port Louis');
INSERT INTO `ref_country_states` VALUES (2017, 154, 'Riviere du Rempart');
INSERT INTO `ref_country_states` VALUES (2018, 154, 'Rodrigues');
INSERT INTO `ref_country_states` VALUES (2019, 154, 'Savanne');
INSERT INTO `ref_country_states` VALUES (2020, 155, 'Alifu');
INSERT INTO `ref_country_states` VALUES (2021, 155, 'Baa');
INSERT INTO `ref_country_states` VALUES (2022, 155, 'Dhaalu');
INSERT INTO `ref_country_states` VALUES (2023, 155, 'Faafu');
INSERT INTO `ref_country_states` VALUES (2024, 155, 'Gaafu Alifu');
INSERT INTO `ref_country_states` VALUES (2025, 155, 'Gaafu Dhaalu');
INSERT INTO `ref_country_states` VALUES (2026, 155, 'Gnaviyani');
INSERT INTO `ref_country_states` VALUES (2027, 155, 'Haa Alifu');
INSERT INTO `ref_country_states` VALUES (2028, 155, 'Haa Dhaalu');
INSERT INTO `ref_country_states` VALUES (2029, 155, 'Kaafu');
INSERT INTO `ref_country_states` VALUES (2030, 155, 'Laamu');
INSERT INTO `ref_country_states` VALUES (2031, 155, 'Lhaviyani');
INSERT INTO `ref_country_states` VALUES (2032, 155, 'Maale');
INSERT INTO `ref_country_states` VALUES (2033, 155, 'Meemu');
INSERT INTO `ref_country_states` VALUES (2034, 155, 'Noonu');
INSERT INTO `ref_country_states` VALUES (2035, 155, 'Raa');
INSERT INTO `ref_country_states` VALUES (2036, 155, 'Seenu');
INSERT INTO `ref_country_states` VALUES (2037, 155, 'Shaviyani');
INSERT INTO `ref_country_states` VALUES (2038, 155, 'Thaa');
INSERT INTO `ref_country_states` VALUES (2039, 155, 'Vaavu');
INSERT INTO `ref_country_states` VALUES (2040, 156, 'Balaka');
INSERT INTO `ref_country_states` VALUES (2041, 156, 'Blantyre');
INSERT INTO `ref_country_states` VALUES (2042, 156, 'Chikwawa');
INSERT INTO `ref_country_states` VALUES (2043, 156, 'Chiradzulu');
INSERT INTO `ref_country_states` VALUES (2044, 156, 'Chitipa');
INSERT INTO `ref_country_states` VALUES (2045, 156, 'Dedza');
INSERT INTO `ref_country_states` VALUES (2046, 156, 'Dowa');
INSERT INTO `ref_country_states` VALUES (2047, 156, 'Karonga');
INSERT INTO `ref_country_states` VALUES (2048, 156, 'Kasungu');
INSERT INTO `ref_country_states` VALUES (2049, 156, 'Likoma');
INSERT INTO `ref_country_states` VALUES (2050, 156, 'Lilongwe');
INSERT INTO `ref_country_states` VALUES (2051, 156, 'Machinga');
INSERT INTO `ref_country_states` VALUES (2052, 156, 'Mangochi');
INSERT INTO `ref_country_states` VALUES (2053, 156, 'Mchinji');
INSERT INTO `ref_country_states` VALUES (2054, 156, 'Mulanje');
INSERT INTO `ref_country_states` VALUES (2055, 156, 'Mwanza');
INSERT INTO `ref_country_states` VALUES (2056, 156, 'Mzimba');
INSERT INTO `ref_country_states` VALUES (2057, 156, 'Ntcheu');
INSERT INTO `ref_country_states` VALUES (2058, 156, 'Nkhata Bay');
INSERT INTO `ref_country_states` VALUES (2059, 156, 'Nkhotakota');
INSERT INTO `ref_country_states` VALUES (2060, 156, 'Nsanje');
INSERT INTO `ref_country_states` VALUES (2061, 156, 'Ntchisi');
INSERT INTO `ref_country_states` VALUES (2062, 156, 'Phalombe');
INSERT INTO `ref_country_states` VALUES (2063, 156, 'Rumphi');
INSERT INTO `ref_country_states` VALUES (2064, 156, 'Salima');
INSERT INTO `ref_country_states` VALUES (2065, 156, 'Thyolo');
INSERT INTO `ref_country_states` VALUES (2066, 156, 'Zomba');
INSERT INTO `ref_country_states` VALUES (2067, 157, 'Aguascalientes');
INSERT INTO `ref_country_states` VALUES (2068, 157, 'Baja California');
INSERT INTO `ref_country_states` VALUES (2069, 157, 'Baja California Sur');
INSERT INTO `ref_country_states` VALUES (2070, 157, 'Campeche');
INSERT INTO `ref_country_states` VALUES (2071, 157, 'Chiapas');
INSERT INTO `ref_country_states` VALUES (2072, 157, 'Chihuahua');
INSERT INTO `ref_country_states` VALUES (2073, 157, 'Coahuila de Zaragoza');
INSERT INTO `ref_country_states` VALUES (2074, 157, 'Colima');
INSERT INTO `ref_country_states` VALUES (2075, 157, 'Distrito Federal');
INSERT INTO `ref_country_states` VALUES (2076, 157, 'Durango');
INSERT INTO `ref_country_states` VALUES (2077, 157, 'Guanajuato');
INSERT INTO `ref_country_states` VALUES (2078, 157, 'Guerrero');
INSERT INTO `ref_country_states` VALUES (2079, 157, 'Hidalgo');
INSERT INTO `ref_country_states` VALUES (2080, 157, 'Jalisco');
INSERT INTO `ref_country_states` VALUES (2081, 157, 'Mexico');
INSERT INTO `ref_country_states` VALUES (2082, 157, 'Michoacan de Ocampo');
INSERT INTO `ref_country_states` VALUES (2083, 157, 'Morelos');
INSERT INTO `ref_country_states` VALUES (2084, 157, 'Nayarit');
INSERT INTO `ref_country_states` VALUES (2085, 157, 'Nuevo Leon');
INSERT INTO `ref_country_states` VALUES (2086, 157, 'Oaxaca');
INSERT INTO `ref_country_states` VALUES (2087, 157, 'Puebla');
INSERT INTO `ref_country_states` VALUES (2088, 157, 'Queretaro de Arteaga');
INSERT INTO `ref_country_states` VALUES (2089, 157, 'Quintana Roo');
INSERT INTO `ref_country_states` VALUES (2090, 157, 'San Luis Potosi');
INSERT INTO `ref_country_states` VALUES (2091, 157, 'Sinaloa');
INSERT INTO `ref_country_states` VALUES (2092, 157, 'Sonora');
INSERT INTO `ref_country_states` VALUES (2093, 157, 'Tabasco');
INSERT INTO `ref_country_states` VALUES (2094, 157, 'Tamaulipas');
INSERT INTO `ref_country_states` VALUES (2095, 157, 'Tlaxcala');
INSERT INTO `ref_country_states` VALUES (2096, 157, 'Veracruz-Llave');
INSERT INTO `ref_country_states` VALUES (2097, 157, 'Yucatan');
INSERT INTO `ref_country_states` VALUES (2098, 157, 'Zacatecas');
INSERT INTO `ref_country_states` VALUES (2099, 158, 'Johor');
INSERT INTO `ref_country_states` VALUES (2100, 158, 'Kedah');
INSERT INTO `ref_country_states` VALUES (2101, 158, 'Kelantan');
INSERT INTO `ref_country_states` VALUES (2102, 158, 'Kuala Lumpur');
INSERT INTO `ref_country_states` VALUES (2103, 158, 'Labuan');
INSERT INTO `ref_country_states` VALUES (2104, 158, 'Malacca');
INSERT INTO `ref_country_states` VALUES (2105, 158, 'Negeri Sembilan');
INSERT INTO `ref_country_states` VALUES (2106, 158, 'Pahang');
INSERT INTO `ref_country_states` VALUES (2107, 158, 'Perak');
INSERT INTO `ref_country_states` VALUES (2108, 158, 'Perlis');
INSERT INTO `ref_country_states` VALUES (2109, 158, 'Penang');
INSERT INTO `ref_country_states` VALUES (2110, 158, 'Sabah');
INSERT INTO `ref_country_states` VALUES (2111, 158, 'Sarawak');
INSERT INTO `ref_country_states` VALUES (2112, 158, 'Selangor');
INSERT INTO `ref_country_states` VALUES (2113, 158, 'Terengganu');
INSERT INTO `ref_country_states` VALUES (2114, 159, 'Cabo Delgado');
INSERT INTO `ref_country_states` VALUES (2115, 159, 'Gaza');
INSERT INTO `ref_country_states` VALUES (2116, 159, 'Inhambane');
INSERT INTO `ref_country_states` VALUES (2117, 159, 'Manica');
INSERT INTO `ref_country_states` VALUES (2118, 159, 'Maputo');
INSERT INTO `ref_country_states` VALUES (2119, 159, 'Cidade de Maputo');
INSERT INTO `ref_country_states` VALUES (2120, 159, 'Nampula');
INSERT INTO `ref_country_states` VALUES (2121, 159, 'Niassa');
INSERT INTO `ref_country_states` VALUES (2122, 159, 'Sofala');
INSERT INTO `ref_country_states` VALUES (2123, 159, 'Tete');
INSERT INTO `ref_country_states` VALUES (2124, 159, 'Zambezia');
INSERT INTO `ref_country_states` VALUES (2125, 160, 'Caprivi');
INSERT INTO `ref_country_states` VALUES (2126, 160, 'Erongo');
INSERT INTO `ref_country_states` VALUES (2127, 160, 'Hardap');
INSERT INTO `ref_country_states` VALUES (2128, 160, 'Karas');
INSERT INTO `ref_country_states` VALUES (2129, 160, 'Khomas');
INSERT INTO `ref_country_states` VALUES (2130, 160, 'Kunene');
INSERT INTO `ref_country_states` VALUES (2131, 160, 'Ohangwena');
INSERT INTO `ref_country_states` VALUES (2132, 160, 'Okavango');
INSERT INTO `ref_country_states` VALUES (2133, 160, 'Omaheke');
INSERT INTO `ref_country_states` VALUES (2134, 160, 'Omusati');
INSERT INTO `ref_country_states` VALUES (2135, 160, 'Oshana');
INSERT INTO `ref_country_states` VALUES (2136, 160, 'Oshikoto');
INSERT INTO `ref_country_states` VALUES (2137, 160, 'Otjozondjupa');
INSERT INTO `ref_country_states` VALUES (2138, 162, 'Agadez');
INSERT INTO `ref_country_states` VALUES (2139, 162, 'Diffa');
INSERT INTO `ref_country_states` VALUES (2140, 162, 'Dosso');
INSERT INTO `ref_country_states` VALUES (2141, 162, 'Maradi');
INSERT INTO `ref_country_states` VALUES (2142, 162, 'Niamey');
INSERT INTO `ref_country_states` VALUES (2143, 162, 'Tahoua');
INSERT INTO `ref_country_states` VALUES (2144, 162, 'Tillaberi');
INSERT INTO `ref_country_states` VALUES (2145, 162, 'Zinder');
INSERT INTO `ref_country_states` VALUES (2146, 164, 'Abia');
INSERT INTO `ref_country_states` VALUES (2147, 164, 'Abuja Federal Capital');
INSERT INTO `ref_country_states` VALUES (2148, 164, 'Adamawa');
INSERT INTO `ref_country_states` VALUES (2149, 164, 'Akwa Ibom');
INSERT INTO `ref_country_states` VALUES (2150, 164, 'Anambra');
INSERT INTO `ref_country_states` VALUES (2151, 164, 'Bauchi');
INSERT INTO `ref_country_states` VALUES (2152, 164, 'Bayelsa');
INSERT INTO `ref_country_states` VALUES (2153, 164, 'Benue');
INSERT INTO `ref_country_states` VALUES (2154, 164, 'Borno');
INSERT INTO `ref_country_states` VALUES (2155, 164, 'Cross River');
INSERT INTO `ref_country_states` VALUES (2156, 164, 'Delta');
INSERT INTO `ref_country_states` VALUES (2157, 164, 'Ebonyi');
INSERT INTO `ref_country_states` VALUES (2158, 164, 'Edo');
INSERT INTO `ref_country_states` VALUES (2159, 164, 'Ekiti');
INSERT INTO `ref_country_states` VALUES (2160, 164, 'Enugu');
INSERT INTO `ref_country_states` VALUES (2161, 164, 'Gombe');
INSERT INTO `ref_country_states` VALUES (2162, 164, 'Imo');
INSERT INTO `ref_country_states` VALUES (2163, 164, 'Jigawa');
INSERT INTO `ref_country_states` VALUES (2164, 164, 'Kaduna');
INSERT INTO `ref_country_states` VALUES (2165, 164, 'Kano');
INSERT INTO `ref_country_states` VALUES (2166, 164, 'Katsina');
INSERT INTO `ref_country_states` VALUES (2167, 164, 'Kebbi');
INSERT INTO `ref_country_states` VALUES (2168, 164, 'Kogi');
INSERT INTO `ref_country_states` VALUES (2169, 164, 'Kwara');
INSERT INTO `ref_country_states` VALUES (2170, 164, 'Lagos');
INSERT INTO `ref_country_states` VALUES (2171, 164, 'Nassarawa');
INSERT INTO `ref_country_states` VALUES (2172, 164, 'Niger');
INSERT INTO `ref_country_states` VALUES (2173, 164, 'Ogun');
INSERT INTO `ref_country_states` VALUES (2174, 164, 'Ondo');
INSERT INTO `ref_country_states` VALUES (2175, 164, 'Osun');
INSERT INTO `ref_country_states` VALUES (2176, 164, 'Oyo');
INSERT INTO `ref_country_states` VALUES (2177, 164, 'Plateau');
INSERT INTO `ref_country_states` VALUES (2178, 164, 'Rivers');
INSERT INTO `ref_country_states` VALUES (2179, 164, 'Sokoto');
INSERT INTO `ref_country_states` VALUES (2180, 164, 'Taraba');
INSERT INTO `ref_country_states` VALUES (2181, 164, 'Yobe');
INSERT INTO `ref_country_states` VALUES (2182, 164, 'Zamfara');
INSERT INTO `ref_country_states` VALUES (2183, 165, 'Atlantico Norte');
INSERT INTO `ref_country_states` VALUES (2184, 165, 'Atlantico Sur');
INSERT INTO `ref_country_states` VALUES (2185, 165, 'Boaco');
INSERT INTO `ref_country_states` VALUES (2186, 165, 'Carazo');
INSERT INTO `ref_country_states` VALUES (2187, 165, 'Chinandega');
INSERT INTO `ref_country_states` VALUES (2188, 165, 'Chontales');
INSERT INTO `ref_country_states` VALUES (2189, 165, 'Esteli');
INSERT INTO `ref_country_states` VALUES (2190, 165, 'Granada');
INSERT INTO `ref_country_states` VALUES (2191, 165, 'Jinotega');
INSERT INTO `ref_country_states` VALUES (2192, 165, 'Leon');
INSERT INTO `ref_country_states` VALUES (2193, 165, 'Madriz');
INSERT INTO `ref_country_states` VALUES (2194, 165, 'Managua');
INSERT INTO `ref_country_states` VALUES (2195, 165, 'Masaya');
INSERT INTO `ref_country_states` VALUES (2196, 165, 'Matagalpa');
INSERT INTO `ref_country_states` VALUES (2197, 165, 'Nueva Segovia');
INSERT INTO `ref_country_states` VALUES (2198, 165, 'Rio San Juan');
INSERT INTO `ref_country_states` VALUES (2199, 165, 'Rivas');
INSERT INTO `ref_country_states` VALUES (2200, 166, 'Drenthe');
INSERT INTO `ref_country_states` VALUES (2201, 166, 'Flevoland');
INSERT INTO `ref_country_states` VALUES (2202, 166, 'Friesland');
INSERT INTO `ref_country_states` VALUES (2203, 166, 'Gelderland');
INSERT INTO `ref_country_states` VALUES (2204, 166, 'Groningen');
INSERT INTO `ref_country_states` VALUES (2205, 166, 'Limburg');
INSERT INTO `ref_country_states` VALUES (2206, 166, 'Noord-Brabant');
INSERT INTO `ref_country_states` VALUES (2207, 166, 'Noord-Holland');
INSERT INTO `ref_country_states` VALUES (2208, 166, 'Overijssel');
INSERT INTO `ref_country_states` VALUES (2209, 166, 'Utrecht');
INSERT INTO `ref_country_states` VALUES (2210, 166, 'Zeeland');
INSERT INTO `ref_country_states` VALUES (2211, 166, 'Zuid-Holland');
INSERT INTO `ref_country_states` VALUES (2212, 167, 'Akershus');
INSERT INTO `ref_country_states` VALUES (2213, 167, 'Aust-Agder');
INSERT INTO `ref_country_states` VALUES (2214, 167, 'Buskerud');
INSERT INTO `ref_country_states` VALUES (2215, 167, 'Finnmark');
INSERT INTO `ref_country_states` VALUES (2216, 167, 'Hedmark');
INSERT INTO `ref_country_states` VALUES (2217, 167, 'Hordaland');
INSERT INTO `ref_country_states` VALUES (2218, 167, 'More og Romsdal');
INSERT INTO `ref_country_states` VALUES (2219, 167, 'Nordland');
INSERT INTO `ref_country_states` VALUES (2220, 167, 'Nord-Trondelag');
INSERT INTO `ref_country_states` VALUES (2221, 167, 'Oppland');
INSERT INTO `ref_country_states` VALUES (2222, 167, 'Oslo');
INSERT INTO `ref_country_states` VALUES (2223, 167, 'Ostfold');
INSERT INTO `ref_country_states` VALUES (2224, 167, 'Rogaland');
INSERT INTO `ref_country_states` VALUES (2225, 167, 'Sogn og Fjordane');
INSERT INTO `ref_country_states` VALUES (2226, 167, 'Sor-Trondelag');
INSERT INTO `ref_country_states` VALUES (2227, 167, 'Telemark');
INSERT INTO `ref_country_states` VALUES (2228, 167, 'Troms');
INSERT INTO `ref_country_states` VALUES (2229, 167, 'Vest-Agder');
INSERT INTO `ref_country_states` VALUES (2230, 167, 'Vestfold');
INSERT INTO `ref_country_states` VALUES (2231, 168, 'Bagmati');
INSERT INTO `ref_country_states` VALUES (2232, 168, 'Bheri');
INSERT INTO `ref_country_states` VALUES (2233, 168, 'Dhawalagiri');
INSERT INTO `ref_country_states` VALUES (2234, 168, 'Gandaki');
INSERT INTO `ref_country_states` VALUES (2235, 168, 'Janakpur');
INSERT INTO `ref_country_states` VALUES (2236, 168, 'Karnali');
INSERT INTO `ref_country_states` VALUES (2237, 168, 'Kosi');
INSERT INTO `ref_country_states` VALUES (2238, 168, 'Lumbini');
INSERT INTO `ref_country_states` VALUES (2239, 168, 'Mahakali');
INSERT INTO `ref_country_states` VALUES (2240, 168, 'Mechi');
INSERT INTO `ref_country_states` VALUES (2241, 168, 'Narayani');
INSERT INTO `ref_country_states` VALUES (2242, 168, 'Rapti');
INSERT INTO `ref_country_states` VALUES (2243, 168, 'Sagarmatha');
INSERT INTO `ref_country_states` VALUES (2244, 168, 'Seti');
INSERT INTO `ref_country_states` VALUES (2245, 169, 'Aiwo');
INSERT INTO `ref_country_states` VALUES (2246, 169, 'Anabar');
INSERT INTO `ref_country_states` VALUES (2247, 169, 'Anetan');
INSERT INTO `ref_country_states` VALUES (2248, 169, 'Anibare');
INSERT INTO `ref_country_states` VALUES (2249, 171, 'Auckland');
INSERT INTO `ref_country_states` VALUES (2250, 171, 'Bay of Plenty');
INSERT INTO `ref_country_states` VALUES (2251, 171, 'Canterbury');
INSERT INTO `ref_country_states` VALUES (2252, 171, 'Chatham Islands');
INSERT INTO `ref_country_states` VALUES (2253, 171, 'Gisborne');
INSERT INTO `ref_country_states` VALUES (2254, 171, 'Hawke\'s Bay');
INSERT INTO `ref_country_states` VALUES (2255, 171, 'Manawatu-Wanganui');
INSERT INTO `ref_country_states` VALUES (2256, 171, 'Marlborough');
INSERT INTO `ref_country_states` VALUES (2257, 171, 'Nelson');
INSERT INTO `ref_country_states` VALUES (2258, 171, 'Northland');
INSERT INTO `ref_country_states` VALUES (2259, 171, 'Otago');
INSERT INTO `ref_country_states` VALUES (2260, 171, 'Southland');
INSERT INTO `ref_country_states` VALUES (2261, 171, 'Taranaki');
INSERT INTO `ref_country_states` VALUES (2262, 171, 'Tasman');
INSERT INTO `ref_country_states` VALUES (2263, 171, 'Waikato');
INSERT INTO `ref_country_states` VALUES (2264, 171, 'Wellington');
INSERT INTO `ref_country_states` VALUES (2265, 171, 'West Coast');
INSERT INTO `ref_country_states` VALUES (2266, 172, 'Ad Dakhiliyah');
INSERT INTO `ref_country_states` VALUES (2267, 172, 'Al Batinah');
INSERT INTO `ref_country_states` VALUES (2268, 172, 'Al Wusta');
INSERT INTO `ref_country_states` VALUES (2269, 172, 'Ash Sharqiyah');
INSERT INTO `ref_country_states` VALUES (2270, 172, 'Az Zahirah');
INSERT INTO `ref_country_states` VALUES (2271, 172, 'Masqat');
INSERT INTO `ref_country_states` VALUES (2272, 172, 'Musandam');
INSERT INTO `ref_country_states` VALUES (2273, 172, 'Dhofar');
INSERT INTO `ref_country_states` VALUES (2274, 173, 'Bocas del Toro');
INSERT INTO `ref_country_states` VALUES (2275, 173, 'Chiriqui');
INSERT INTO `ref_country_states` VALUES (2276, 173, 'Cocle');
INSERT INTO `ref_country_states` VALUES (2277, 173, 'Colon');
INSERT INTO `ref_country_states` VALUES (2278, 173, 'Darien');
INSERT INTO `ref_country_states` VALUES (2279, 173, 'Herrera');
INSERT INTO `ref_country_states` VALUES (2280, 173, 'Los Santos');
INSERT INTO `ref_country_states` VALUES (2281, 173, 'Panama');
INSERT INTO `ref_country_states` VALUES (2282, 173, 'San Blas');
INSERT INTO `ref_country_states` VALUES (2283, 173, 'Veraguas');
INSERT INTO `ref_country_states` VALUES (2284, 174, 'Amazonas');
INSERT INTO `ref_country_states` VALUES (2285, 174, 'Ancash');
INSERT INTO `ref_country_states` VALUES (2286, 174, 'Apurimac');
INSERT INTO `ref_country_states` VALUES (2287, 174, 'Arequipa');
INSERT INTO `ref_country_states` VALUES (2288, 174, 'Ayacucho');
INSERT INTO `ref_country_states` VALUES (2289, 174, 'Cajamarca');
INSERT INTO `ref_country_states` VALUES (2290, 174, 'Callao');
INSERT INTO `ref_country_states` VALUES (2291, 174, 'Cusco');
INSERT INTO `ref_country_states` VALUES (2292, 174, 'Huancavelica');
INSERT INTO `ref_country_states` VALUES (2293, 174, 'Huanuco');
INSERT INTO `ref_country_states` VALUES (2294, 174, 'Ica');
INSERT INTO `ref_country_states` VALUES (2295, 174, 'Junin');
INSERT INTO `ref_country_states` VALUES (2296, 174, 'La Libertad');
INSERT INTO `ref_country_states` VALUES (2297, 174, 'Lambayeque');
INSERT INTO `ref_country_states` VALUES (2298, 174, 'Lima');
INSERT INTO `ref_country_states` VALUES (2299, 174, 'Loreto');
INSERT INTO `ref_country_states` VALUES (2300, 174, 'Madre de Dios');
INSERT INTO `ref_country_states` VALUES (2301, 174, 'Moquegua');
INSERT INTO `ref_country_states` VALUES (2302, 174, 'Pasco');
INSERT INTO `ref_country_states` VALUES (2303, 174, 'Piura');
INSERT INTO `ref_country_states` VALUES (2304, 174, 'Puno');
INSERT INTO `ref_country_states` VALUES (2305, 174, 'San Martin');
INSERT INTO `ref_country_states` VALUES (2306, 174, 'Tacna');
INSERT INTO `ref_country_states` VALUES (2307, 174, 'Tumbes');
INSERT INTO `ref_country_states` VALUES (2308, 174, 'Ucayali');
INSERT INTO `ref_country_states` VALUES (2309, 175, 'Marquesas Islands');
INSERT INTO `ref_country_states` VALUES (2310, 175, 'Leeward Islands');
INSERT INTO `ref_country_states` VALUES (2311, 175, 'Windward Islands');
INSERT INTO `ref_country_states` VALUES (2312, 175, 'Tuāmotu-Gambier ');
INSERT INTO `ref_country_states` VALUES (2313, 175, 'Austral Islands');
INSERT INTO `ref_country_states` VALUES (2314, 176, 'Bougainville');
INSERT INTO `ref_country_states` VALUES (2315, 176, 'Central');
INSERT INTO `ref_country_states` VALUES (2316, 176, 'Chimbu');
INSERT INTO `ref_country_states` VALUES (2317, 176, 'Eastern Highlands');
INSERT INTO `ref_country_states` VALUES (2318, 176, 'East New Britain');
INSERT INTO `ref_country_states` VALUES (2319, 176, 'East Sepik');
INSERT INTO `ref_country_states` VALUES (2320, 176, 'Enga');
INSERT INTO `ref_country_states` VALUES (2321, 176, 'Gulf');
INSERT INTO `ref_country_states` VALUES (2322, 176, 'Madang');
INSERT INTO `ref_country_states` VALUES (2323, 176, 'Manus');
INSERT INTO `ref_country_states` VALUES (2324, 176, 'Milne Bay');
INSERT INTO `ref_country_states` VALUES (2325, 176, 'Morobe');
INSERT INTO `ref_country_states` VALUES (2326, 176, 'National Capital');
INSERT INTO `ref_country_states` VALUES (2327, 176, 'New Ireland');
INSERT INTO `ref_country_states` VALUES (2328, 176, 'Northern');
INSERT INTO `ref_country_states` VALUES (2329, 176, 'Sandaun');
INSERT INTO `ref_country_states` VALUES (2330, 176, 'Southern Highlands');
INSERT INTO `ref_country_states` VALUES (2331, 176, 'Western');
INSERT INTO `ref_country_states` VALUES (2332, 176, 'Western Highlands');
INSERT INTO `ref_country_states` VALUES (2333, 176, 'West New Britain');
INSERT INTO `ref_country_states` VALUES (2334, 177, 'Abra');
INSERT INTO `ref_country_states` VALUES (2335, 177, 'Agusan del Norte');
INSERT INTO `ref_country_states` VALUES (2336, 177, 'Agusan del Sur');
INSERT INTO `ref_country_states` VALUES (2337, 177, 'Aklan');
INSERT INTO `ref_country_states` VALUES (2338, 177, 'Albay');
INSERT INTO `ref_country_states` VALUES (2339, 177, 'Antique');
INSERT INTO `ref_country_states` VALUES (2340, 177, 'Apayao');
INSERT INTO `ref_country_states` VALUES (2341, 177, 'Aurora');
INSERT INTO `ref_country_states` VALUES (2342, 177, 'Basilan');
INSERT INTO `ref_country_states` VALUES (2343, 177, 'Bataan');
INSERT INTO `ref_country_states` VALUES (2344, 177, 'Batanes');
INSERT INTO `ref_country_states` VALUES (2345, 177, 'Batangas');
INSERT INTO `ref_country_states` VALUES (2346, 177, 'Biliran');
INSERT INTO `ref_country_states` VALUES (2347, 177, 'Benguet');
INSERT INTO `ref_country_states` VALUES (2348, 177, 'Bohol');
INSERT INTO `ref_country_states` VALUES (2349, 177, 'Bukidnon');
INSERT INTO `ref_country_states` VALUES (2350, 177, 'Bulacan');
INSERT INTO `ref_country_states` VALUES (2351, 177, 'Cagayan');
INSERT INTO `ref_country_states` VALUES (2352, 177, 'Camarines Norte');
INSERT INTO `ref_country_states` VALUES (2353, 177, 'Camarines Sur');
INSERT INTO `ref_country_states` VALUES (2354, 177, 'Camiguin');
INSERT INTO `ref_country_states` VALUES (2355, 177, 'Capiz');
INSERT INTO `ref_country_states` VALUES (2356, 177, 'Catanduanes');
INSERT INTO `ref_country_states` VALUES (2357, 177, 'Cavite');
INSERT INTO `ref_country_states` VALUES (2358, 177, 'Cebu');
INSERT INTO `ref_country_states` VALUES (2359, 177, 'Compostela');
INSERT INTO `ref_country_states` VALUES (2360, 177, 'Davao del Norte');
INSERT INTO `ref_country_states` VALUES (2361, 177, 'Davao del Sur');
INSERT INTO `ref_country_states` VALUES (2362, 177, 'Davao Oriental');
INSERT INTO `ref_country_states` VALUES (2363, 177, 'Eastern Samar');
INSERT INTO `ref_country_states` VALUES (2364, 177, 'Guimaras');
INSERT INTO `ref_country_states` VALUES (2365, 177, 'Ifugao');
INSERT INTO `ref_country_states` VALUES (2366, 177, 'Ilocos Norte');
INSERT INTO `ref_country_states` VALUES (2367, 177, 'Ilocos Sur');
INSERT INTO `ref_country_states` VALUES (2368, 177, 'Iloilo');
INSERT INTO `ref_country_states` VALUES (2369, 177, 'Isabela');
INSERT INTO `ref_country_states` VALUES (2370, 177, 'Kalinga');
INSERT INTO `ref_country_states` VALUES (2371, 177, 'Laguna');
INSERT INTO `ref_country_states` VALUES (2372, 177, 'Lanao del Norte');
INSERT INTO `ref_country_states` VALUES (2373, 177, 'Lanao del Sur');
INSERT INTO `ref_country_states` VALUES (2374, 177, 'La Union');
INSERT INTO `ref_country_states` VALUES (2375, 177, 'Leyte');
INSERT INTO `ref_country_states` VALUES (2376, 177, 'Maguindanao');
INSERT INTO `ref_country_states` VALUES (2377, 177, 'Marinduque');
INSERT INTO `ref_country_states` VALUES (2378, 177, 'Masbate');
INSERT INTO `ref_country_states` VALUES (2379, 177, 'Mindoro Occidental');
INSERT INTO `ref_country_states` VALUES (2380, 177, 'Mindoro Oriental');
INSERT INTO `ref_country_states` VALUES (2381, 177, 'Misamis Occidental');
INSERT INTO `ref_country_states` VALUES (2382, 177, 'Misamis Oriental');
INSERT INTO `ref_country_states` VALUES (2383, 177, 'Mountain Province');
INSERT INTO `ref_country_states` VALUES (2384, 177, 'Negros Occidental');
INSERT INTO `ref_country_states` VALUES (2385, 177, 'Negros Oriental');
INSERT INTO `ref_country_states` VALUES (2386, 177, 'North Cotabato');
INSERT INTO `ref_country_states` VALUES (2387, 177, 'Northern Samar');
INSERT INTO `ref_country_states` VALUES (2388, 177, 'Nueva Ecija');
INSERT INTO `ref_country_states` VALUES (2389, 177, 'Nueva Vizcaya');
INSERT INTO `ref_country_states` VALUES (2390, 177, 'Palawan');
INSERT INTO `ref_country_states` VALUES (2391, 177, 'Pampanga');
INSERT INTO `ref_country_states` VALUES (2392, 177, 'Pangasinan');
INSERT INTO `ref_country_states` VALUES (2393, 177, 'Quezon');
INSERT INTO `ref_country_states` VALUES (2394, 177, 'Quirino');
INSERT INTO `ref_country_states` VALUES (2395, 177, 'Rizal');
INSERT INTO `ref_country_states` VALUES (2396, 177, 'Romblon');
INSERT INTO `ref_country_states` VALUES (2397, 177, 'Samar');
INSERT INTO `ref_country_states` VALUES (2398, 177, 'Sarangani');
INSERT INTO `ref_country_states` VALUES (2399, 177, 'Siquijor');
INSERT INTO `ref_country_states` VALUES (2400, 177, 'Sorsogon');
INSERT INTO `ref_country_states` VALUES (2401, 177, 'South Cotabato');
INSERT INTO `ref_country_states` VALUES (2402, 177, 'Southern Leyte');
INSERT INTO `ref_country_states` VALUES (2403, 177, 'Sultan Kudarat');
INSERT INTO `ref_country_states` VALUES (2404, 177, 'Sulu');
INSERT INTO `ref_country_states` VALUES (2405, 177, 'Surigao del Norte');
INSERT INTO `ref_country_states` VALUES (2406, 177, 'Surigao del Sur');
INSERT INTO `ref_country_states` VALUES (2407, 177, 'Tarlac');
INSERT INTO `ref_country_states` VALUES (2408, 177, 'Tawi-Tawi');
INSERT INTO `ref_country_states` VALUES (2409, 177, 'Zambales');
INSERT INTO `ref_country_states` VALUES (2410, 177, 'Zamboanga del Norte');
INSERT INTO `ref_country_states` VALUES (2411, 177, 'Zamboanga del Sur');
INSERT INTO `ref_country_states` VALUES (2412, 177, 'Zamboanga Sibugay');
INSERT INTO `ref_country_states` VALUES (2413, 178, 'Balochistan');
INSERT INTO `ref_country_states` VALUES (2414, 178, 'North-West Frontier Province');
INSERT INTO `ref_country_states` VALUES (2415, 178, 'Punjab');
INSERT INTO `ref_country_states` VALUES (2416, 178, 'Sindh');
INSERT INTO `ref_country_states` VALUES (2417, 178, 'Islamabad Capital Territory');
INSERT INTO `ref_country_states` VALUES (2418, 178, 'Federally Administered Tribal Areas');
INSERT INTO `ref_country_states` VALUES (2419, 179, 'Greater Poland (Wielkopolskie)');
INSERT INTO `ref_country_states` VALUES (2420, 179, 'Kuyavian-Pomeranian (Kujawsko-Pomorskie)');
INSERT INTO `ref_country_states` VALUES (2421, 179, 'Lesser Poland (Malopolskie)');
INSERT INTO `ref_country_states` VALUES (2422, 179, 'Lodz (Lodzkie)');
INSERT INTO `ref_country_states` VALUES (2423, 179, 'Lower Silesian (Dolnoslaskie)');
INSERT INTO `ref_country_states` VALUES (2424, 179, 'Lublin (Lubelskie)');
INSERT INTO `ref_country_states` VALUES (2425, 179, 'Lubusz (Lubuskie)');
INSERT INTO `ref_country_states` VALUES (2426, 179, 'Masovian (Mazowieckie)');
INSERT INTO `ref_country_states` VALUES (2427, 179, 'Opole (Opolskie)');
INSERT INTO `ref_country_states` VALUES (2428, 179, 'Podlasie (Podlaskie)');
INSERT INTO `ref_country_states` VALUES (2429, 179, 'Pomeranian (Pomorskie)');
INSERT INTO `ref_country_states` VALUES (2430, 179, 'Silesian (Slaskie)');
INSERT INTO `ref_country_states` VALUES (2431, 179, 'Subcarpathian (Podkarpackie)');
INSERT INTO `ref_country_states` VALUES (2432, 179, 'Swietokrzyskie (Swietokrzyskie)');
INSERT INTO `ref_country_states` VALUES (2433, 179, 'Warmian-Masurian (Warminsko-Mazurskie)');
INSERT INTO `ref_country_states` VALUES (2434, 179, 'West Pomeranian (Zachodniopomorskie)');
INSERT INTO `ref_country_states` VALUES (2435, 184, 'Aveiro');
INSERT INTO `ref_country_states` VALUES (2436, 184, 'Acores');
INSERT INTO `ref_country_states` VALUES (2437, 184, 'Beja');
INSERT INTO `ref_country_states` VALUES (2438, 184, 'Braga');
INSERT INTO `ref_country_states` VALUES (2439, 184, 'Braganca');
INSERT INTO `ref_country_states` VALUES (2440, 184, 'Castelo Branco');
INSERT INTO `ref_country_states` VALUES (2441, 184, 'Coimbra');
INSERT INTO `ref_country_states` VALUES (2442, 184, 'Evora');
INSERT INTO `ref_country_states` VALUES (2443, 184, 'Faro');
INSERT INTO `ref_country_states` VALUES (2444, 184, 'Guarda');
INSERT INTO `ref_country_states` VALUES (2445, 184, 'Leiria');
INSERT INTO `ref_country_states` VALUES (2446, 184, 'Lisboa');
INSERT INTO `ref_country_states` VALUES (2447, 184, 'Madeira');
INSERT INTO `ref_country_states` VALUES (2448, 184, 'Portalegre');
INSERT INTO `ref_country_states` VALUES (2449, 184, 'Porto');
INSERT INTO `ref_country_states` VALUES (2450, 184, 'Santarem');
INSERT INTO `ref_country_states` VALUES (2451, 184, 'Setubal');
INSERT INTO `ref_country_states` VALUES (2452, 184, 'Viana do Castelo');
INSERT INTO `ref_country_states` VALUES (2453, 184, 'Vila Real');
INSERT INTO `ref_country_states` VALUES (2454, 184, 'Viseu');
INSERT INTO `ref_country_states` VALUES (2455, 186, 'Alto Paraguay');
INSERT INTO `ref_country_states` VALUES (2456, 186, 'Alto Parana');
INSERT INTO `ref_country_states` VALUES (2457, 186, 'Amambay');
INSERT INTO `ref_country_states` VALUES (2458, 186, 'Asuncion');
INSERT INTO `ref_country_states` VALUES (2459, 186, 'Boqueron');
INSERT INTO `ref_country_states` VALUES (2460, 186, 'Caaguazu');
INSERT INTO `ref_country_states` VALUES (2461, 186, 'Caazapa');
INSERT INTO `ref_country_states` VALUES (2462, 186, 'Canindeyu');
INSERT INTO `ref_country_states` VALUES (2463, 186, 'Central');
INSERT INTO `ref_country_states` VALUES (2464, 186, 'Concepcion');
INSERT INTO `ref_country_states` VALUES (2465, 186, 'Cordillera');
INSERT INTO `ref_country_states` VALUES (2466, 186, 'Guaira');
INSERT INTO `ref_country_states` VALUES (2467, 186, 'Itapua');
INSERT INTO `ref_country_states` VALUES (2468, 186, 'Misiones');
INSERT INTO `ref_country_states` VALUES (2469, 186, 'Neembucu');
INSERT INTO `ref_country_states` VALUES (2470, 186, 'Paraguari');
INSERT INTO `ref_country_states` VALUES (2471, 186, 'Presidente Hayes');
INSERT INTO `ref_country_states` VALUES (2472, 186, 'San Pedro');
INSERT INTO `ref_country_states` VALUES (2473, 187, 'Ad Dawhah');
INSERT INTO `ref_country_states` VALUES (2474, 187, 'Al Ghuwayriyah');
INSERT INTO `ref_country_states` VALUES (2475, 187, 'Al Jumayliyah');
INSERT INTO `ref_country_states` VALUES (2476, 187, 'Al Khawr');
INSERT INTO `ref_country_states` VALUES (2477, 187, 'Al Wakrah');
INSERT INTO `ref_country_states` VALUES (2478, 187, 'Ar Rayyan');
INSERT INTO `ref_country_states` VALUES (2479, 187, 'Jarayan al Batinah');
INSERT INTO `ref_country_states` VALUES (2480, 187, 'Madinat ash Shamal');
INSERT INTO `ref_country_states` VALUES (2481, 187, 'Umm Sa\'id');
INSERT INTO `ref_country_states` VALUES (2482, 187, 'Umm Salal');
INSERT INTO `ref_country_states` VALUES (2483, 189, 'Alba');
INSERT INTO `ref_country_states` VALUES (2484, 189, 'Arad');
INSERT INTO `ref_country_states` VALUES (2485, 189, 'Arges');
INSERT INTO `ref_country_states` VALUES (2486, 189, 'Bacau');
INSERT INTO `ref_country_states` VALUES (2487, 189, 'Bihor');
INSERT INTO `ref_country_states` VALUES (2488, 189, 'Bistrita-Nasaud');
INSERT INTO `ref_country_states` VALUES (2489, 189, 'Botosani');
INSERT INTO `ref_country_states` VALUES (2490, 189, 'Braila');
INSERT INTO `ref_country_states` VALUES (2491, 189, 'Brasov');
INSERT INTO `ref_country_states` VALUES (2492, 189, 'Bucuresti');
INSERT INTO `ref_country_states` VALUES (2493, 189, 'Buzau');
INSERT INTO `ref_country_states` VALUES (2494, 189, 'Calarasi');
INSERT INTO `ref_country_states` VALUES (2495, 189, 'Caras-Severin');
INSERT INTO `ref_country_states` VALUES (2496, 189, 'Cluj');
INSERT INTO `ref_country_states` VALUES (2497, 189, 'Constanta');
INSERT INTO `ref_country_states` VALUES (2498, 189, 'Covasna');
INSERT INTO `ref_country_states` VALUES (2499, 189, 'Dimbovita');
INSERT INTO `ref_country_states` VALUES (2500, 189, 'Dolj');
INSERT INTO `ref_country_states` VALUES (2501, 189, 'Galati');
INSERT INTO `ref_country_states` VALUES (2502, 189, 'Gorj');
INSERT INTO `ref_country_states` VALUES (2503, 189, 'Giurgiu');
INSERT INTO `ref_country_states` VALUES (2504, 189, 'Harghita');
INSERT INTO `ref_country_states` VALUES (2505, 189, 'Hunedoara');
INSERT INTO `ref_country_states` VALUES (2506, 189, 'Ialomita');
INSERT INTO `ref_country_states` VALUES (2507, 189, 'Iasi');
INSERT INTO `ref_country_states` VALUES (2508, 189, 'Ilfov');
INSERT INTO `ref_country_states` VALUES (2509, 189, 'Maramures');
INSERT INTO `ref_country_states` VALUES (2510, 189, 'Mehedinti');
INSERT INTO `ref_country_states` VALUES (2511, 189, 'Mures');
INSERT INTO `ref_country_states` VALUES (2512, 189, 'Neamt');
INSERT INTO `ref_country_states` VALUES (2513, 189, 'Olt');
INSERT INTO `ref_country_states` VALUES (2514, 189, 'Prahova');
INSERT INTO `ref_country_states` VALUES (2515, 189, 'Salaj');
INSERT INTO `ref_country_states` VALUES (2516, 189, 'Satu Mare');
INSERT INTO `ref_country_states` VALUES (2517, 189, 'Sibiu');
INSERT INTO `ref_country_states` VALUES (2518, 189, 'Suceava');
INSERT INTO `ref_country_states` VALUES (2519, 189, 'Teleorman');
INSERT INTO `ref_country_states` VALUES (2520, 189, 'Timis');
INSERT INTO `ref_country_states` VALUES (2521, 189, 'Tulcea');
INSERT INTO `ref_country_states` VALUES (2522, 189, 'Vaslui');
INSERT INTO `ref_country_states` VALUES (2523, 189, 'Vilcea');
INSERT INTO `ref_country_states` VALUES (2524, 189, 'Vrancea');
INSERT INTO `ref_country_states` VALUES (2525, 190, 'Valjevo');
INSERT INTO `ref_country_states` VALUES (2526, 190, 'Šabac');
INSERT INTO `ref_country_states` VALUES (2527, 190, 'Čačak');
INSERT INTO `ref_country_states` VALUES (2528, 190, 'Jagodina');
INSERT INTO `ref_country_states` VALUES (2529, 190, 'Kruševac');
INSERT INTO `ref_country_states` VALUES (2530, 190, 'Kraljevo');
INSERT INTO `ref_country_states` VALUES (2531, 190, 'Kragujevac');
INSERT INTO `ref_country_states` VALUES (2532, 190, 'Užice');
INSERT INTO `ref_country_states` VALUES (2533, 190, 'Bor');
INSERT INTO `ref_country_states` VALUES (2534, 190, 'Požarevac');
INSERT INTO `ref_country_states` VALUES (2535, 190, 'Leskovac');
INSERT INTO `ref_country_states` VALUES (2536, 190, 'Niš');
INSERT INTO `ref_country_states` VALUES (2537, 190, 'Vranje');
INSERT INTO `ref_country_states` VALUES (2538, 190, 'Pirot');
INSERT INTO `ref_country_states` VALUES (2539, 190, 'Smederevo');
INSERT INTO `ref_country_states` VALUES (2540, 190, 'Prokuplje');
INSERT INTO `ref_country_states` VALUES (2541, 190, 'Zaječar');
INSERT INTO `ref_country_states` VALUES (2542, 190, 'Zrenjanin');
INSERT INTO `ref_country_states` VALUES (2543, 190, 'Subotica');
INSERT INTO `ref_country_states` VALUES (2544, 190, 'Kikinda');
INSERT INTO `ref_country_states` VALUES (2545, 190, 'Novi Sad');
INSERT INTO `ref_country_states` VALUES (2546, 190, 'Pančevo');
INSERT INTO `ref_country_states` VALUES (2547, 190, 'Sremska Mitrovica');
INSERT INTO `ref_country_states` VALUES (2548, 190, 'Sombor');
INSERT INTO `ref_country_states` VALUES (2549, 191, 'Amur');
INSERT INTO `ref_country_states` VALUES (2550, 191, 'Arkhangel\'sk');
INSERT INTO `ref_country_states` VALUES (2551, 191, 'Astrakhan\'');
INSERT INTO `ref_country_states` VALUES (2552, 191, 'Belgorod');
INSERT INTO `ref_country_states` VALUES (2553, 191, 'Bryansk');
INSERT INTO `ref_country_states` VALUES (2554, 191, 'Chelyabinsk');
INSERT INTO `ref_country_states` VALUES (2555, 191, 'Chita');
INSERT INTO `ref_country_states` VALUES (2556, 191, 'Irkutsk');
INSERT INTO `ref_country_states` VALUES (2557, 191, 'Ivanovo');
INSERT INTO `ref_country_states` VALUES (2558, 191, 'Kaliningrad');
INSERT INTO `ref_country_states` VALUES (2559, 191, 'Kaluga');
INSERT INTO `ref_country_states` VALUES (2560, 191, 'Kamchatka');
INSERT INTO `ref_country_states` VALUES (2561, 191, 'Kemerovo');
INSERT INTO `ref_country_states` VALUES (2562, 191, 'Kirov');
INSERT INTO `ref_country_states` VALUES (2563, 191, 'Kostroma');
INSERT INTO `ref_country_states` VALUES (2564, 191, 'Kurgan');
INSERT INTO `ref_country_states` VALUES (2565, 191, 'Kursk');
INSERT INTO `ref_country_states` VALUES (2566, 191, 'Leningrad');
INSERT INTO `ref_country_states` VALUES (2567, 191, 'Lipetsk');
INSERT INTO `ref_country_states` VALUES (2568, 191, 'Magadan');
INSERT INTO `ref_country_states` VALUES (2569, 191, 'Moscow');
INSERT INTO `ref_country_states` VALUES (2570, 191, 'Murmansk');
INSERT INTO `ref_country_states` VALUES (2571, 191, 'Nizhniy Novgorod');
INSERT INTO `ref_country_states` VALUES (2572, 191, 'Novgorod');
INSERT INTO `ref_country_states` VALUES (2573, 191, 'Novosibirsk');
INSERT INTO `ref_country_states` VALUES (2574, 191, 'Omsk');
INSERT INTO `ref_country_states` VALUES (2575, 191, 'Orenburg');
INSERT INTO `ref_country_states` VALUES (2576, 191, 'Orel');
INSERT INTO `ref_country_states` VALUES (2577, 191, 'Penza');
INSERT INTO `ref_country_states` VALUES (2578, 191, 'Perm\'');
INSERT INTO `ref_country_states` VALUES (2579, 191, 'Pskov');
INSERT INTO `ref_country_states` VALUES (2580, 191, 'Rostov');
INSERT INTO `ref_country_states` VALUES (2581, 191, 'Ryazan\'');
INSERT INTO `ref_country_states` VALUES (2582, 191, 'Sakhalin');
INSERT INTO `ref_country_states` VALUES (2583, 191, 'Samara');
INSERT INTO `ref_country_states` VALUES (2584, 191, 'Saratov');
INSERT INTO `ref_country_states` VALUES (2585, 191, 'Smolensk');
INSERT INTO `ref_country_states` VALUES (2586, 191, 'Sverdlovsk');
INSERT INTO `ref_country_states` VALUES (2587, 191, 'Tambov');
INSERT INTO `ref_country_states` VALUES (2588, 191, 'Tomsk');
INSERT INTO `ref_country_states` VALUES (2589, 191, 'Tula');
INSERT INTO `ref_country_states` VALUES (2590, 191, 'Tver\'');
INSERT INTO `ref_country_states` VALUES (2591, 191, 'Tyumen\'');
INSERT INTO `ref_country_states` VALUES (2592, 191, 'Ul\'yanovsk');
INSERT INTO `ref_country_states` VALUES (2593, 191, 'Vladimir');
INSERT INTO `ref_country_states` VALUES (2594, 191, 'Volgograd');
INSERT INTO `ref_country_states` VALUES (2595, 191, 'Vologda');
INSERT INTO `ref_country_states` VALUES (2596, 191, 'Voronezh');
INSERT INTO `ref_country_states` VALUES (2597, 191, 'Yaroslavl\'');
INSERT INTO `ref_country_states` VALUES (2598, 191, 'Adygeya');
INSERT INTO `ref_country_states` VALUES (2599, 191, 'Altay');
INSERT INTO `ref_country_states` VALUES (2600, 191, 'Bashkortostan');
INSERT INTO `ref_country_states` VALUES (2601, 191, 'Buryatiya');
INSERT INTO `ref_country_states` VALUES (2602, 191, 'Chechnya');
INSERT INTO `ref_country_states` VALUES (2603, 191, 'Chuvashiya');
INSERT INTO `ref_country_states` VALUES (2604, 191, 'Dagestan');
INSERT INTO `ref_country_states` VALUES (2605, 191, 'Ingushetiya');
INSERT INTO `ref_country_states` VALUES (2606, 191, 'Kabardino-Balkariya');
INSERT INTO `ref_country_states` VALUES (2607, 191, 'Kalmykiya');
INSERT INTO `ref_country_states` VALUES (2608, 191, 'Karachayevo-Cherkesiya');
INSERT INTO `ref_country_states` VALUES (2609, 191, 'Kareliya');
INSERT INTO `ref_country_states` VALUES (2610, 191, 'Khakasiya');
INSERT INTO `ref_country_states` VALUES (2611, 191, 'Komi');
INSERT INTO `ref_country_states` VALUES (2612, 191, 'Mariy-El');
INSERT INTO `ref_country_states` VALUES (2613, 191, 'Mordoviya');
INSERT INTO `ref_country_states` VALUES (2614, 191, 'Sakha');
INSERT INTO `ref_country_states` VALUES (2615, 191, 'North Ossetia');
INSERT INTO `ref_country_states` VALUES (2616, 191, 'Tatarstan');
INSERT INTO `ref_country_states` VALUES (2617, 191, 'Tyva');
INSERT INTO `ref_country_states` VALUES (2618, 191, 'Udmurtiya');
INSERT INTO `ref_country_states` VALUES (2619, 191, 'Aga Buryat');
INSERT INTO `ref_country_states` VALUES (2620, 191, 'Chukotka');
INSERT INTO `ref_country_states` VALUES (2621, 191, 'Evenk');
INSERT INTO `ref_country_states` VALUES (2622, 191, 'Khanty-Mansi');
INSERT INTO `ref_country_states` VALUES (2623, 191, 'Komi-Permyak');
INSERT INTO `ref_country_states` VALUES (2624, 191, 'Koryak');
INSERT INTO `ref_country_states` VALUES (2625, 191, 'Nenets');
INSERT INTO `ref_country_states` VALUES (2626, 191, 'Taymyr');
INSERT INTO `ref_country_states` VALUES (2627, 191, 'Ust\'-Orda Buryat');
INSERT INTO `ref_country_states` VALUES (2628, 191, 'Yamalo-Nenets');
INSERT INTO `ref_country_states` VALUES (2629, 191, 'Altay');
INSERT INTO `ref_country_states` VALUES (2630, 191, 'Khabarovsk');
INSERT INTO `ref_country_states` VALUES (2631, 191, 'Krasnodar');
INSERT INTO `ref_country_states` VALUES (2632, 191, 'Krasnoyarsk');
INSERT INTO `ref_country_states` VALUES (2633, 191, 'Primorskiy');
INSERT INTO `ref_country_states` VALUES (2634, 191, 'Stavropol\'');
INSERT INTO `ref_country_states` VALUES (2635, 191, 'Moscow');
INSERT INTO `ref_country_states` VALUES (2636, 191, 'St. Petersburg');
INSERT INTO `ref_country_states` VALUES (2637, 191, 'Yevrey');
INSERT INTO `ref_country_states` VALUES (2638, 192, 'Butare');
INSERT INTO `ref_country_states` VALUES (2639, 192, 'Byumba');
INSERT INTO `ref_country_states` VALUES (2640, 192, 'Cyangugu');
INSERT INTO `ref_country_states` VALUES (2641, 192, 'Gikongoro');
INSERT INTO `ref_country_states` VALUES (2642, 192, 'Gisenyi');
INSERT INTO `ref_country_states` VALUES (2643, 192, 'Gitarama');
INSERT INTO `ref_country_states` VALUES (2644, 192, 'Kibungo');
INSERT INTO `ref_country_states` VALUES (2645, 192, 'Kibuye');
INSERT INTO `ref_country_states` VALUES (2646, 192, 'Kigali Rurale');
INSERT INTO `ref_country_states` VALUES (2647, 192, 'Kigali-ville');
INSERT INTO `ref_country_states` VALUES (2648, 192, 'Umutara');
INSERT INTO `ref_country_states` VALUES (2649, 192, 'Ruhengeri');
INSERT INTO `ref_country_states` VALUES (2650, 193, 'Al Bahah');
INSERT INTO `ref_country_states` VALUES (2651, 193, 'Al Hudud ash Shamaliyah');
INSERT INTO `ref_country_states` VALUES (2652, 193, 'Al Jawf');
INSERT INTO `ref_country_states` VALUES (2653, 193, 'Al Madinah');
INSERT INTO `ref_country_states` VALUES (2654, 193, 'Al Qasim');
INSERT INTO `ref_country_states` VALUES (2655, 193, 'Ar Riyad');
INSERT INTO `ref_country_states` VALUES (2656, 193, 'Ash Sharqiyah');
INSERT INTO `ref_country_states` VALUES (2657, 193, '\'Asir');
INSERT INTO `ref_country_states` VALUES (2658, 193, 'Ha\'il');
INSERT INTO `ref_country_states` VALUES (2659, 193, 'Jizan');
INSERT INTO `ref_country_states` VALUES (2660, 193, 'Makkah');
INSERT INTO `ref_country_states` VALUES (2661, 193, 'Najran');
INSERT INTO `ref_country_states` VALUES (2662, 193, 'Tabuk');
INSERT INTO `ref_country_states` VALUES (2663, 194, 'Central');
INSERT INTO `ref_country_states` VALUES (2664, 194, 'Choiseul');
INSERT INTO `ref_country_states` VALUES (2665, 194, 'Guadalcanal');
INSERT INTO `ref_country_states` VALUES (2666, 194, 'Honiara');
INSERT INTO `ref_country_states` VALUES (2667, 194, 'Isabel');
INSERT INTO `ref_country_states` VALUES (2668, 194, 'Makira');
INSERT INTO `ref_country_states` VALUES (2669, 194, 'Malaita');
INSERT INTO `ref_country_states` VALUES (2670, 194, 'Rennell and Bellona');
INSERT INTO `ref_country_states` VALUES (2671, 194, 'Temotu');
INSERT INTO `ref_country_states` VALUES (2672, 194, 'Western');
INSERT INTO `ref_country_states` VALUES (2673, 195, 'Anse aux Pins');
INSERT INTO `ref_country_states` VALUES (2674, 195, 'Anse Boileau');
INSERT INTO `ref_country_states` VALUES (2675, 195, 'Anse Etoile');
INSERT INTO `ref_country_states` VALUES (2676, 195, 'Anse Louis');
INSERT INTO `ref_country_states` VALUES (2677, 195, 'Anse Royale');
INSERT INTO `ref_country_states` VALUES (2678, 195, 'Baie Lazare');
INSERT INTO `ref_country_states` VALUES (2679, 195, 'Baie Sainte Anne');
INSERT INTO `ref_country_states` VALUES (2680, 195, 'Beau Vallon');
INSERT INTO `ref_country_states` VALUES (2681, 195, 'Bel Air');
INSERT INTO `ref_country_states` VALUES (2682, 195, 'Bel Ombre');
INSERT INTO `ref_country_states` VALUES (2683, 195, 'Cascade');
INSERT INTO `ref_country_states` VALUES (2684, 195, 'Glacis');
INSERT INTO `ref_country_states` VALUES (2685, 195, 'Grand\' Anse');
INSERT INTO `ref_country_states` VALUES (2686, 195, 'Grand\' Anse');
INSERT INTO `ref_country_states` VALUES (2687, 195, 'La Digue');
INSERT INTO `ref_country_states` VALUES (2688, 195, 'La Riviere Anglaise');
INSERT INTO `ref_country_states` VALUES (2689, 195, 'Mont Buxton');
INSERT INTO `ref_country_states` VALUES (2690, 195, 'Mont Fleuri');
INSERT INTO `ref_country_states` VALUES (2691, 195, 'Plaisance');
INSERT INTO `ref_country_states` VALUES (2692, 195, 'Pointe La Rue');
INSERT INTO `ref_country_states` VALUES (2693, 195, 'Port Glaud');
INSERT INTO `ref_country_states` VALUES (2694, 195, 'Saint Louis');
INSERT INTO `ref_country_states` VALUES (2695, 195, 'Takamaka');
INSERT INTO `ref_country_states` VALUES (2696, 196, 'A\'ali an Nil');
INSERT INTO `ref_country_states` VALUES (2697, 196, 'Al Bahr al Ahmar');
INSERT INTO `ref_country_states` VALUES (2698, 196, 'Al Buhayrat');
INSERT INTO `ref_country_states` VALUES (2699, 196, 'Al Jazirah');
INSERT INTO `ref_country_states` VALUES (2700, 196, 'Al Khartum');
INSERT INTO `ref_country_states` VALUES (2701, 196, 'Al Qadarif');
INSERT INTO `ref_country_states` VALUES (2702, 196, 'Al Wahdah');
INSERT INTO `ref_country_states` VALUES (2703, 196, 'An Nil al Abyad');
INSERT INTO `ref_country_states` VALUES (2704, 196, 'An Nil al Azraq');
INSERT INTO `ref_country_states` VALUES (2705, 196, 'Ash Shamaliyah');
INSERT INTO `ref_country_states` VALUES (2706, 196, 'Bahr al Jabal');
INSERT INTO `ref_country_states` VALUES (2707, 196, 'Gharb al Istiwa\'iyah');
INSERT INTO `ref_country_states` VALUES (2708, 196, 'Gharb Bahr al Ghazal');
INSERT INTO `ref_country_states` VALUES (2709, 196, 'Gharb Darfur');
INSERT INTO `ref_country_states` VALUES (2710, 196, 'Gharb Kurdufan');
INSERT INTO `ref_country_states` VALUES (2711, 196, 'Janub Darfur');
INSERT INTO `ref_country_states` VALUES (2712, 196, 'Janub Kurdufan');
INSERT INTO `ref_country_states` VALUES (2713, 196, 'Junqali');
INSERT INTO `ref_country_states` VALUES (2714, 196, 'Kassala');
INSERT INTO `ref_country_states` VALUES (2715, 196, 'Nahr an Nil');
INSERT INTO `ref_country_states` VALUES (2716, 196, 'Shamal Bahr al Ghazal');
INSERT INTO `ref_country_states` VALUES (2717, 196, 'Shamal Darfur');
INSERT INTO `ref_country_states` VALUES (2718, 196, 'Shamal Kurdufan');
INSERT INTO `ref_country_states` VALUES (2719, 196, 'Sharq al Istiwa\'iyah');
INSERT INTO `ref_country_states` VALUES (2720, 196, 'Sinnar');
INSERT INTO `ref_country_states` VALUES (2721, 196, 'Warab');
INSERT INTO `ref_country_states` VALUES (2722, 197, 'Blekinge');
INSERT INTO `ref_country_states` VALUES (2723, 197, 'Dalarna');
INSERT INTO `ref_country_states` VALUES (2724, 197, 'Gävleborg');
INSERT INTO `ref_country_states` VALUES (2725, 197, 'Gotland');
INSERT INTO `ref_country_states` VALUES (2726, 197, 'Halland');
INSERT INTO `ref_country_states` VALUES (2727, 197, 'Jämtland');
INSERT INTO `ref_country_states` VALUES (2728, 197, 'Jönköping');
INSERT INTO `ref_country_states` VALUES (2729, 197, 'Kalmar');
INSERT INTO `ref_country_states` VALUES (2730, 197, 'Kronoberg');
INSERT INTO `ref_country_states` VALUES (2731, 197, 'Norrbotten');
INSERT INTO `ref_country_states` VALUES (2732, 197, 'Örebro');
INSERT INTO `ref_country_states` VALUES (2733, 197, 'Östergötland');
INSERT INTO `ref_country_states` VALUES (2734, 197, 'Skåne');
INSERT INTO `ref_country_states` VALUES (2735, 197, 'Södermanland');
INSERT INTO `ref_country_states` VALUES (2736, 197, 'Stockholm');
INSERT INTO `ref_country_states` VALUES (2737, 197, 'Uppsala');
INSERT INTO `ref_country_states` VALUES (2738, 197, 'Värmland');
INSERT INTO `ref_country_states` VALUES (2739, 197, 'Västerbotten');
INSERT INTO `ref_country_states` VALUES (2740, 197, 'Västernorrland');
INSERT INTO `ref_country_states` VALUES (2741, 197, 'Västmanland');
INSERT INTO `ref_country_states` VALUES (2742, 197, 'Västra Götaland');
INSERT INTO `ref_country_states` VALUES (2743, 198, 'Aljunied Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2744, 198, 'Ang Mo Kio Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2745, 198, 'Bishan–Toa Payoh Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2746, 198, 'Chua Chu Kang Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2747, 198, 'East Coast Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2748, 198, 'Holland–Bukit Timah Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2749, 198, 'Jalan Besar Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2750, 198, 'Jurong Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2751, 198, 'Marine Parade Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2752, 198, 'Marsiling–Yew Tee Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2753, 198, 'Nee Soon Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2754, 198, 'Pasir Ris–Punggol Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2755, 198, 'Sengkang Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2756, 198, 'Tampines Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2757, 198, 'Tanjong Pagar Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2758, 198, 'West Coast Group Representation Constituency');
INSERT INTO `ref_country_states` VALUES (2759, 200, 'Ajdovscina');
INSERT INTO `ref_country_states` VALUES (2760, 200, 'Beltinci');
INSERT INTO `ref_country_states` VALUES (2761, 200, 'Benedikt');
INSERT INTO `ref_country_states` VALUES (2762, 200, 'Bistrica ob Sotli');
INSERT INTO `ref_country_states` VALUES (2763, 200, 'Bled');
INSERT INTO `ref_country_states` VALUES (2764, 200, 'Bloke');
INSERT INTO `ref_country_states` VALUES (2765, 200, 'Bohinj');
INSERT INTO `ref_country_states` VALUES (2766, 200, 'Borovnica');
INSERT INTO `ref_country_states` VALUES (2767, 200, 'Bovec');
INSERT INTO `ref_country_states` VALUES (2768, 200, 'Braslovce');
INSERT INTO `ref_country_states` VALUES (2769, 200, 'Brda');
INSERT INTO `ref_country_states` VALUES (2770, 200, 'Brezice');
INSERT INTO `ref_country_states` VALUES (2771, 200, 'Brezovica');
INSERT INTO `ref_country_states` VALUES (2772, 200, 'Cankova');
INSERT INTO `ref_country_states` VALUES (2773, 200, 'Celje');
INSERT INTO `ref_country_states` VALUES (2774, 200, 'Cerklje na Gorenjskem');
INSERT INTO `ref_country_states` VALUES (2775, 200, 'Cerknica');
INSERT INTO `ref_country_states` VALUES (2776, 200, 'Cerkno');
INSERT INTO `ref_country_states` VALUES (2777, 200, 'Cerkvenjak');
INSERT INTO `ref_country_states` VALUES (2778, 200, 'Crensovci');
INSERT INTO `ref_country_states` VALUES (2779, 200, 'Crna na Koroskem');
INSERT INTO `ref_country_states` VALUES (2780, 200, 'Crnomelj');
INSERT INTO `ref_country_states` VALUES (2781, 200, 'Destrnik');
INSERT INTO `ref_country_states` VALUES (2782, 200, 'Divaca');
INSERT INTO `ref_country_states` VALUES (2783, 200, 'Dobje');
INSERT INTO `ref_country_states` VALUES (2784, 200, 'Dobrepolje');
INSERT INTO `ref_country_states` VALUES (2785, 200, 'Dobrna');
INSERT INTO `ref_country_states` VALUES (2786, 200, 'Dobrova-Horjul-Polhov Gradec');
INSERT INTO `ref_country_states` VALUES (2787, 200, 'Dobrovnik-Dobronak');
INSERT INTO `ref_country_states` VALUES (2788, 200, 'Dolenjske Toplice');
INSERT INTO `ref_country_states` VALUES (2789, 200, 'Dol pri Ljubljani');
INSERT INTO `ref_country_states` VALUES (2790, 200, 'Domzale');
INSERT INTO `ref_country_states` VALUES (2791, 200, 'Dornava');
INSERT INTO `ref_country_states` VALUES (2792, 200, 'Dravograd');
INSERT INTO `ref_country_states` VALUES (2793, 200, 'Duplek');
INSERT INTO `ref_country_states` VALUES (2794, 200, 'Gorenja Vas-Poljane');
INSERT INTO `ref_country_states` VALUES (2795, 200, 'Gorisnica');
INSERT INTO `ref_country_states` VALUES (2796, 200, 'Gornja Radgona');
INSERT INTO `ref_country_states` VALUES (2797, 200, 'Gornji Grad');
INSERT INTO `ref_country_states` VALUES (2798, 200, 'Gornji Petrovci');
INSERT INTO `ref_country_states` VALUES (2799, 200, 'Grad');
INSERT INTO `ref_country_states` VALUES (2800, 200, 'Grosuplje');
INSERT INTO `ref_country_states` VALUES (2801, 200, 'Hajdina');
INSERT INTO `ref_country_states` VALUES (2802, 200, 'Hoce-Slivnica');
INSERT INTO `ref_country_states` VALUES (2803, 200, 'Hodos-Hodos');
INSERT INTO `ref_country_states` VALUES (2804, 200, 'Horjul');
INSERT INTO `ref_country_states` VALUES (2805, 200, 'Hrastnik');
INSERT INTO `ref_country_states` VALUES (2806, 200, 'Hrpelje-Kozina');
INSERT INTO `ref_country_states` VALUES (2807, 200, 'Idrija');
INSERT INTO `ref_country_states` VALUES (2808, 200, 'Ig');
INSERT INTO `ref_country_states` VALUES (2809, 200, 'Ilirska Bistrica');
INSERT INTO `ref_country_states` VALUES (2810, 200, 'Ivancna Gorica');
INSERT INTO `ref_country_states` VALUES (2811, 200, 'Izola-Isola');
INSERT INTO `ref_country_states` VALUES (2812, 200, 'Jesenice');
INSERT INTO `ref_country_states` VALUES (2813, 200, 'Jezersko');
INSERT INTO `ref_country_states` VALUES (2814, 200, 'Jursinci');
INSERT INTO `ref_country_states` VALUES (2815, 200, 'Kamnik');
INSERT INTO `ref_country_states` VALUES (2816, 200, 'Kanal');
INSERT INTO `ref_country_states` VALUES (2817, 200, 'Kidricevo');
INSERT INTO `ref_country_states` VALUES (2818, 200, 'Kobarid');
INSERT INTO `ref_country_states` VALUES (2819, 200, 'Kobilje');
INSERT INTO `ref_country_states` VALUES (2820, 200, 'Kocevje');
INSERT INTO `ref_country_states` VALUES (2821, 200, 'Komen');
INSERT INTO `ref_country_states` VALUES (2822, 200, 'Komenda');
INSERT INTO `ref_country_states` VALUES (2823, 200, 'Koper-Capodistria');
INSERT INTO `ref_country_states` VALUES (2824, 200, 'Kostel');
INSERT INTO `ref_country_states` VALUES (2825, 200, 'Kozje');
INSERT INTO `ref_country_states` VALUES (2826, 200, 'Kranj');
INSERT INTO `ref_country_states` VALUES (2827, 200, 'Kranjska Gora');
INSERT INTO `ref_country_states` VALUES (2828, 200, 'Krizevci');
INSERT INTO `ref_country_states` VALUES (2829, 200, 'Krsko');
INSERT INTO `ref_country_states` VALUES (2830, 200, 'Kungota');
INSERT INTO `ref_country_states` VALUES (2831, 200, 'Kuzma');
INSERT INTO `ref_country_states` VALUES (2832, 200, 'Lasko');
INSERT INTO `ref_country_states` VALUES (2833, 200, 'Lenart');
INSERT INTO `ref_country_states` VALUES (2834, 200, 'Lendava-Lendva');
INSERT INTO `ref_country_states` VALUES (2835, 200, 'Litija');
INSERT INTO `ref_country_states` VALUES (2836, 200, 'Ljubljana');
INSERT INTO `ref_country_states` VALUES (2837, 200, 'Ljubno');
INSERT INTO `ref_country_states` VALUES (2838, 200, 'Ljutomer');
INSERT INTO `ref_country_states` VALUES (2839, 200, 'Logatec');
INSERT INTO `ref_country_states` VALUES (2840, 200, 'Loska Dolina');
INSERT INTO `ref_country_states` VALUES (2841, 200, 'Loski Potok');
INSERT INTO `ref_country_states` VALUES (2842, 200, 'Lovrenc na Pohorju');
INSERT INTO `ref_country_states` VALUES (2843, 200, 'Luce');
INSERT INTO `ref_country_states` VALUES (2844, 200, 'Lukovica');
INSERT INTO `ref_country_states` VALUES (2845, 200, 'Majsperk');
INSERT INTO `ref_country_states` VALUES (2846, 200, 'Maribor');
INSERT INTO `ref_country_states` VALUES (2847, 200, 'Markovci');
INSERT INTO `ref_country_states` VALUES (2848, 200, 'Medvode');
INSERT INTO `ref_country_states` VALUES (2849, 200, 'Menges');
INSERT INTO `ref_country_states` VALUES (2850, 200, 'Metlika');
INSERT INTO `ref_country_states` VALUES (2851, 200, 'Mezica');
INSERT INTO `ref_country_states` VALUES (2852, 200, 'Miklavz na Dravskem Polju');
INSERT INTO `ref_country_states` VALUES (2853, 200, 'Miren-Kostanjevica');
INSERT INTO `ref_country_states` VALUES (2854, 200, 'Mirna Pec');
INSERT INTO `ref_country_states` VALUES (2855, 200, 'Mislinja');
INSERT INTO `ref_country_states` VALUES (2856, 200, 'Moravce');
INSERT INTO `ref_country_states` VALUES (2857, 200, 'Moravske Toplice');
INSERT INTO `ref_country_states` VALUES (2858, 200, 'Mozirje');
INSERT INTO `ref_country_states` VALUES (2859, 200, 'Murska Sobota');
INSERT INTO `ref_country_states` VALUES (2860, 200, 'Muta');
INSERT INTO `ref_country_states` VALUES (2861, 200, 'Naklo');
INSERT INTO `ref_country_states` VALUES (2862, 200, 'Nazarje');
INSERT INTO `ref_country_states` VALUES (2863, 200, 'Nova Gorica');
INSERT INTO `ref_country_states` VALUES (2864, 200, 'Novo Mesto');
INSERT INTO `ref_country_states` VALUES (2865, 200, 'Odranci');
INSERT INTO `ref_country_states` VALUES (2866, 200, 'Oplotnica');
INSERT INTO `ref_country_states` VALUES (2867, 200, 'Ormoz');
INSERT INTO `ref_country_states` VALUES (2868, 200, 'Osilnica');
INSERT INTO `ref_country_states` VALUES (2869, 200, 'Pesnica');
INSERT INTO `ref_country_states` VALUES (2870, 200, 'Piran-Pirano');
INSERT INTO `ref_country_states` VALUES (2871, 200, 'Pivka');
INSERT INTO `ref_country_states` VALUES (2872, 200, 'Podcetrtek');
INSERT INTO `ref_country_states` VALUES (2873, 200, 'Podlehnik');
INSERT INTO `ref_country_states` VALUES (2874, 200, 'Podvelka');
INSERT INTO `ref_country_states` VALUES (2875, 200, 'Polzela');
INSERT INTO `ref_country_states` VALUES (2876, 200, 'Postojna');
INSERT INTO `ref_country_states` VALUES (2877, 200, 'Prebold');
INSERT INTO `ref_country_states` VALUES (2878, 200, 'Preddvor');
INSERT INTO `ref_country_states` VALUES (2879, 200, 'Prevalje');
INSERT INTO `ref_country_states` VALUES (2880, 200, 'Ptuj');
INSERT INTO `ref_country_states` VALUES (2881, 200, 'Puconci');
INSERT INTO `ref_country_states` VALUES (2882, 200, 'Race-Fram');
INSERT INTO `ref_country_states` VALUES (2883, 200, 'Radece');
INSERT INTO `ref_country_states` VALUES (2884, 200, 'Radenci');
INSERT INTO `ref_country_states` VALUES (2885, 200, 'Radlje ob Dravi');
INSERT INTO `ref_country_states` VALUES (2886, 200, 'Radovljica');
INSERT INTO `ref_country_states` VALUES (2887, 200, 'Ravne na Koroskem');
INSERT INTO `ref_country_states` VALUES (2888, 200, 'Razkrizje');
INSERT INTO `ref_country_states` VALUES (2889, 200, 'Ribnica');
INSERT INTO `ref_country_states` VALUES (2890, 200, 'Ribnica na Pohorju');
INSERT INTO `ref_country_states` VALUES (2891, 200, 'Rogasovci');
INSERT INTO `ref_country_states` VALUES (2892, 200, 'Rogaska Slatina');
INSERT INTO `ref_country_states` VALUES (2893, 200, 'Rogatec');
INSERT INTO `ref_country_states` VALUES (2894, 200, 'Ruse');
INSERT INTO `ref_country_states` VALUES (2895, 200, 'Salovci');
INSERT INTO `ref_country_states` VALUES (2896, 200, 'Selnica ob Dravi');
INSERT INTO `ref_country_states` VALUES (2897, 200, 'Semic');
INSERT INTO `ref_country_states` VALUES (2898, 200, 'Sempeter-Vrtojba');
INSERT INTO `ref_country_states` VALUES (2899, 200, 'Sencur');
INSERT INTO `ref_country_states` VALUES (2900, 200, 'Sentilj');
INSERT INTO `ref_country_states` VALUES (2901, 200, 'Sentjernej');
INSERT INTO `ref_country_states` VALUES (2902, 200, 'Sentjur pri Celju');
INSERT INTO `ref_country_states` VALUES (2903, 200, 'Sevnica');
INSERT INTO `ref_country_states` VALUES (2904, 200, 'Sezana');
INSERT INTO `ref_country_states` VALUES (2905, 200, 'Skocjan');
INSERT INTO `ref_country_states` VALUES (2906, 200, 'Skofja Loka');
INSERT INTO `ref_country_states` VALUES (2907, 200, 'Skofljica');
INSERT INTO `ref_country_states` VALUES (2908, 200, 'Slovenj Gradec');
INSERT INTO `ref_country_states` VALUES (2909, 200, 'Slovenska Bistrica');
INSERT INTO `ref_country_states` VALUES (2910, 200, 'Slovenske Konjice');
INSERT INTO `ref_country_states` VALUES (2911, 200, 'Smarje pri Jelsah');
INSERT INTO `ref_country_states` VALUES (2912, 200, 'Smartno ob Paki');
INSERT INTO `ref_country_states` VALUES (2913, 200, 'Smartno pri Litiji');
INSERT INTO `ref_country_states` VALUES (2914, 200, 'Sodrazica');
INSERT INTO `ref_country_states` VALUES (2915, 200, 'Solcava');
INSERT INTO `ref_country_states` VALUES (2916, 200, 'Sostanj');
INSERT INTO `ref_country_states` VALUES (2917, 200, 'Starse');
INSERT INTO `ref_country_states` VALUES (2918, 200, 'Store');
INSERT INTO `ref_country_states` VALUES (2919, 200, 'Sveta Ana');
INSERT INTO `ref_country_states` VALUES (2920, 200, 'Sveti Andraz v Slovenskih Goricah');
INSERT INTO `ref_country_states` VALUES (2921, 200, 'Sveti Jurij');
INSERT INTO `ref_country_states` VALUES (2922, 200, 'Tabor');
INSERT INTO `ref_country_states` VALUES (2923, 200, 'Tisina');
INSERT INTO `ref_country_states` VALUES (2924, 200, 'Tolmin');
INSERT INTO `ref_country_states` VALUES (2925, 200, 'Trbovlje');
INSERT INTO `ref_country_states` VALUES (2926, 200, 'Trebnje');
INSERT INTO `ref_country_states` VALUES (2927, 200, 'Trnovska Vas');
INSERT INTO `ref_country_states` VALUES (2928, 200, 'Trzic');
INSERT INTO `ref_country_states` VALUES (2929, 200, 'Trzin');
INSERT INTO `ref_country_states` VALUES (2930, 200, 'Turnisce');
INSERT INTO `ref_country_states` VALUES (2931, 200, 'Velenje');
INSERT INTO `ref_country_states` VALUES (2932, 200, 'Velika Polana');
INSERT INTO `ref_country_states` VALUES (2933, 200, 'Velike Lasce');
INSERT INTO `ref_country_states` VALUES (2934, 200, 'Verzej');
INSERT INTO `ref_country_states` VALUES (2935, 200, 'Videm');
INSERT INTO `ref_country_states` VALUES (2936, 200, 'Vipava');
INSERT INTO `ref_country_states` VALUES (2937, 200, 'Vitanje');
INSERT INTO `ref_country_states` VALUES (2938, 200, 'Vodice');
INSERT INTO `ref_country_states` VALUES (2939, 200, 'Vojnik');
INSERT INTO `ref_country_states` VALUES (2940, 200, 'Vransko');
INSERT INTO `ref_country_states` VALUES (2941, 200, 'Vrhnika');
INSERT INTO `ref_country_states` VALUES (2942, 200, 'Vuzenica');
INSERT INTO `ref_country_states` VALUES (2943, 200, 'Zagorje ob Savi');
INSERT INTO `ref_country_states` VALUES (2944, 200, 'Zalec');
INSERT INTO `ref_country_states` VALUES (2945, 200, 'Zavrc');
INSERT INTO `ref_country_states` VALUES (2946, 200, 'Zelezniki');
INSERT INTO `ref_country_states` VALUES (2947, 200, 'Zetale');
INSERT INTO `ref_country_states` VALUES (2948, 200, 'Ziri');
INSERT INTO `ref_country_states` VALUES (2949, 200, 'Zirovnica');
INSERT INTO `ref_country_states` VALUES (2950, 200, 'Zuzemberk');
INSERT INTO `ref_country_states` VALUES (2951, 200, 'Zrece');
INSERT INTO `ref_country_states` VALUES (2952, 202, 'Banskobystricky');
INSERT INTO `ref_country_states` VALUES (2953, 202, 'Bratislavsky');
INSERT INTO `ref_country_states` VALUES (2954, 202, 'Kosicky');
INSERT INTO `ref_country_states` VALUES (2955, 202, 'Nitriansky');
INSERT INTO `ref_country_states` VALUES (2956, 202, 'Presovsky');
INSERT INTO `ref_country_states` VALUES (2957, 202, 'Trenciansky');
INSERT INTO `ref_country_states` VALUES (2958, 202, 'Trnavsky');
INSERT INTO `ref_country_states` VALUES (2959, 202, 'Zilinsky');
INSERT INTO `ref_country_states` VALUES (2960, 203, 'Eastern Province');
INSERT INTO `ref_country_states` VALUES (2961, 203, 'Northern Province');
INSERT INTO `ref_country_states` VALUES (2962, 203, 'Southern Province');
INSERT INTO `ref_country_states` VALUES (2963, 203, 'North West Province');
INSERT INTO `ref_country_states` VALUES (2964, 203, 'Western Area');
INSERT INTO `ref_country_states` VALUES (2965, 204, 'Acquaviva');
INSERT INTO `ref_country_states` VALUES (2966, 204, 'Borgo Maggiore');
INSERT INTO `ref_country_states` VALUES (2967, 204, 'Chiesanuova');
INSERT INTO `ref_country_states` VALUES (2968, 204, 'Domagnano');
INSERT INTO `ref_country_states` VALUES (2969, 204, 'Faetano');
INSERT INTO `ref_country_states` VALUES (2970, 204, 'Fiorentino');
INSERT INTO `ref_country_states` VALUES (2971, 204, 'Montegiardino');
INSERT INTO `ref_country_states` VALUES (2972, 204, 'San Marino Citta');
INSERT INTO `ref_country_states` VALUES (2973, 204, 'Serravalle');
INSERT INTO `ref_country_states` VALUES (2974, 205, 'Dakar');
INSERT INTO `ref_country_states` VALUES (2975, 205, 'Diourbel');
INSERT INTO `ref_country_states` VALUES (2976, 205, 'Fatick');
INSERT INTO `ref_country_states` VALUES (2977, 205, 'Kaolack');
INSERT INTO `ref_country_states` VALUES (2978, 205, 'Kolda');
INSERT INTO `ref_country_states` VALUES (2979, 205, 'Louga');
INSERT INTO `ref_country_states` VALUES (2980, 205, 'Matam');
INSERT INTO `ref_country_states` VALUES (2981, 205, 'Saint-Louis');
INSERT INTO `ref_country_states` VALUES (2982, 205, 'Tambacounda');
INSERT INTO `ref_country_states` VALUES (2983, 205, 'Thies');
INSERT INTO `ref_country_states` VALUES (2984, 205, 'Ziguinchor');
INSERT INTO `ref_country_states` VALUES (2985, 206, 'Awdal');
INSERT INTO `ref_country_states` VALUES (2986, 206, 'Bakool');
INSERT INTO `ref_country_states` VALUES (2987, 206, 'Banaadir');
INSERT INTO `ref_country_states` VALUES (2988, 206, 'Bari');
INSERT INTO `ref_country_states` VALUES (2989, 206, 'Bay');
INSERT INTO `ref_country_states` VALUES (2990, 206, 'Galguduud');
INSERT INTO `ref_country_states` VALUES (2991, 206, 'Gedo');
INSERT INTO `ref_country_states` VALUES (2992, 206, 'Hiiraan');
INSERT INTO `ref_country_states` VALUES (2993, 206, 'Jubbada Dhexe');
INSERT INTO `ref_country_states` VALUES (2994, 206, 'Jubbada Hoose');
INSERT INTO `ref_country_states` VALUES (2995, 206, 'Mudug');
INSERT INTO `ref_country_states` VALUES (2996, 206, 'Nugaal');
INSERT INTO `ref_country_states` VALUES (2997, 206, 'Sanaag');
INSERT INTO `ref_country_states` VALUES (2998, 206, 'Shabeellaha Dhexe');
INSERT INTO `ref_country_states` VALUES (2999, 206, 'Shabeellaha Hoose');
INSERT INTO `ref_country_states` VALUES (3000, 206, 'Sool');
INSERT INTO `ref_country_states` VALUES (3001, 206, 'Togdheer');
INSERT INTO `ref_country_states` VALUES (3002, 206, 'Woqooyi Galbeed');
INSERT INTO `ref_country_states` VALUES (3003, 207, 'Brokopondo');
INSERT INTO `ref_country_states` VALUES (3004, 207, 'Commewijne');
INSERT INTO `ref_country_states` VALUES (3005, 207, 'Coronie');
INSERT INTO `ref_country_states` VALUES (3006, 207, 'Marowijne');
INSERT INTO `ref_country_states` VALUES (3007, 207, 'Nickerie');
INSERT INTO `ref_country_states` VALUES (3008, 207, 'Para');
INSERT INTO `ref_country_states` VALUES (3009, 207, 'Paramaribo');
INSERT INTO `ref_country_states` VALUES (3010, 207, 'Saramacca');
INSERT INTO `ref_country_states` VALUES (3011, 207, 'Sipaliwini');
INSERT INTO `ref_country_states` VALUES (3012, 207, 'Wanica');
INSERT INTO `ref_country_states` VALUES (3013, 210, 'Ahuachapan');
INSERT INTO `ref_country_states` VALUES (3014, 210, 'Cabanas');
INSERT INTO `ref_country_states` VALUES (3015, 210, 'Chalatenango');
INSERT INTO `ref_country_states` VALUES (3016, 210, 'Cuscatlan');
INSERT INTO `ref_country_states` VALUES (3017, 210, 'La Libertad');
INSERT INTO `ref_country_states` VALUES (3018, 210, 'La Paz');
INSERT INTO `ref_country_states` VALUES (3019, 210, 'La Union');
INSERT INTO `ref_country_states` VALUES (3020, 210, 'Morazan');
INSERT INTO `ref_country_states` VALUES (3021, 210, 'San Miguel');
INSERT INTO `ref_country_states` VALUES (3022, 210, 'San Salvador');
INSERT INTO `ref_country_states` VALUES (3023, 210, 'Santa Ana');
INSERT INTO `ref_country_states` VALUES (3024, 210, 'San Vicente');
INSERT INTO `ref_country_states` VALUES (3025, 210, 'Sonsonate');
INSERT INTO `ref_country_states` VALUES (3026, 210, 'Usulutan');
INSERT INTO `ref_country_states` VALUES (3027, 212, 'Al Hasakah');
INSERT INTO `ref_country_states` VALUES (3028, 212, 'Al Ladhiqiyah');
INSERT INTO `ref_country_states` VALUES (3029, 212, 'Al Qunaytirah');
INSERT INTO `ref_country_states` VALUES (3030, 212, 'Ar Raqqah');
INSERT INTO `ref_country_states` VALUES (3031, 212, 'As Suwayda\'');
INSERT INTO `ref_country_states` VALUES (3032, 212, 'Dar\'a');
INSERT INTO `ref_country_states` VALUES (3033, 212, 'Dayr az Zawr');
INSERT INTO `ref_country_states` VALUES (3034, 212, 'Dimashq');
INSERT INTO `ref_country_states` VALUES (3035, 212, 'Halab');
INSERT INTO `ref_country_states` VALUES (3036, 212, 'Hamah');
INSERT INTO `ref_country_states` VALUES (3037, 212, 'Hims');
INSERT INTO `ref_country_states` VALUES (3038, 212, 'Idlib');
INSERT INTO `ref_country_states` VALUES (3039, 212, 'Rif Dimashq');
INSERT INTO `ref_country_states` VALUES (3040, 212, 'Tartus');
INSERT INTO `ref_country_states` VALUES (3041, 213, 'Hhohho');
INSERT INTO `ref_country_states` VALUES (3042, 213, 'Lubombo');
INSERT INTO `ref_country_states` VALUES (3043, 213, 'Manzini');
INSERT INTO `ref_country_states` VALUES (3044, 213, 'Shiselweni');
INSERT INTO `ref_country_states` VALUES (3045, 215, 'Batha');
INSERT INTO `ref_country_states` VALUES (3046, 215, 'Biltine');
INSERT INTO `ref_country_states` VALUES (3047, 215, 'Borkou-Ennedi-Tibesti');
INSERT INTO `ref_country_states` VALUES (3048, 215, 'Chari-Baguirmi');
INSERT INTO `ref_country_states` VALUES (3049, 215, 'Guéra');
INSERT INTO `ref_country_states` VALUES (3050, 215, 'Kanem');
INSERT INTO `ref_country_states` VALUES (3051, 215, 'Lac');
INSERT INTO `ref_country_states` VALUES (3052, 215, 'Logone Occidental');
INSERT INTO `ref_country_states` VALUES (3053, 215, 'Logone Oriental');
INSERT INTO `ref_country_states` VALUES (3054, 215, 'Mayo-Kebbi');
INSERT INTO `ref_country_states` VALUES (3055, 215, 'Moyen-Chari');
INSERT INTO `ref_country_states` VALUES (3056, 215, 'Ouaddaï');
INSERT INTO `ref_country_states` VALUES (3057, 215, 'Salamat');
INSERT INTO `ref_country_states` VALUES (3058, 215, 'Tandjile');
INSERT INTO `ref_country_states` VALUES (3059, 217, 'Kara');
INSERT INTO `ref_country_states` VALUES (3060, 217, 'Plateaux');
INSERT INTO `ref_country_states` VALUES (3061, 217, 'Savanes');
INSERT INTO `ref_country_states` VALUES (3062, 217, 'Centrale');
INSERT INTO `ref_country_states` VALUES (3063, 217, 'Maritime');
INSERT INTO `ref_country_states` VALUES (3064, 218, 'Amnat Charoen');
INSERT INTO `ref_country_states` VALUES (3065, 218, 'Ang Thong');
INSERT INTO `ref_country_states` VALUES (3066, 218, 'Buriram');
INSERT INTO `ref_country_states` VALUES (3067, 218, 'Chachoengsao');
INSERT INTO `ref_country_states` VALUES (3068, 218, 'Chai Nat');
INSERT INTO `ref_country_states` VALUES (3069, 218, 'Chaiyaphum');
INSERT INTO `ref_country_states` VALUES (3070, 218, 'Chanthaburi');
INSERT INTO `ref_country_states` VALUES (3071, 218, 'Chiang Mai');
INSERT INTO `ref_country_states` VALUES (3072, 218, 'Chiang Rai');
INSERT INTO `ref_country_states` VALUES (3073, 218, 'Chon Buri');
INSERT INTO `ref_country_states` VALUES (3074, 218, 'Chumphon');
INSERT INTO `ref_country_states` VALUES (3075, 218, 'Kalasin');
INSERT INTO `ref_country_states` VALUES (3076, 218, 'Kamphaeng Phet');
INSERT INTO `ref_country_states` VALUES (3077, 218, 'Kanchanaburi');
INSERT INTO `ref_country_states` VALUES (3078, 218, 'Khon Kaen');
INSERT INTO `ref_country_states` VALUES (3079, 218, 'Krabi');
INSERT INTO `ref_country_states` VALUES (3080, 218, 'Krung Thep Mahanakhon');
INSERT INTO `ref_country_states` VALUES (3081, 218, 'Lampang');
INSERT INTO `ref_country_states` VALUES (3082, 218, 'Lamphun');
INSERT INTO `ref_country_states` VALUES (3083, 218, 'Loei');
INSERT INTO `ref_country_states` VALUES (3084, 218, 'Lop Buri');
INSERT INTO `ref_country_states` VALUES (3085, 218, 'Mae Hong Son');
INSERT INTO `ref_country_states` VALUES (3086, 218, 'Maha Sarakham');
INSERT INTO `ref_country_states` VALUES (3087, 218, 'Mukdahan');
INSERT INTO `ref_country_states` VALUES (3088, 218, 'Nakhon Nayok');
INSERT INTO `ref_country_states` VALUES (3089, 218, 'Nakhon Pathom');
INSERT INTO `ref_country_states` VALUES (3090, 218, 'Nakhon Phanom');
INSERT INTO `ref_country_states` VALUES (3091, 218, 'Nakhon Ratchasima');
INSERT INTO `ref_country_states` VALUES (3092, 218, 'Nakhon Sawan');
INSERT INTO `ref_country_states` VALUES (3093, 218, 'Nakhon Si Thammarat');
INSERT INTO `ref_country_states` VALUES (3094, 218, 'Nan');
INSERT INTO `ref_country_states` VALUES (3095, 218, 'Narathiwat');
INSERT INTO `ref_country_states` VALUES (3096, 218, 'Nong Bua Lamphu');
INSERT INTO `ref_country_states` VALUES (3097, 218, 'Nong Khai');
INSERT INTO `ref_country_states` VALUES (3098, 218, 'Nonthaburi');
INSERT INTO `ref_country_states` VALUES (3099, 218, 'Pathum Thani');
INSERT INTO `ref_country_states` VALUES (3100, 218, 'Pattani');
INSERT INTO `ref_country_states` VALUES (3101, 218, 'Phangnga');
INSERT INTO `ref_country_states` VALUES (3102, 218, 'Phatthalung');
INSERT INTO `ref_country_states` VALUES (3103, 218, 'Phayao');
INSERT INTO `ref_country_states` VALUES (3104, 218, 'Phetchabun');
INSERT INTO `ref_country_states` VALUES (3105, 218, 'Phetchaburi');
INSERT INTO `ref_country_states` VALUES (3106, 218, 'Phichit');
INSERT INTO `ref_country_states` VALUES (3107, 218, 'Phitsanulok');
INSERT INTO `ref_country_states` VALUES (3108, 218, 'Phra Nakhon Si Ayutthaya');
INSERT INTO `ref_country_states` VALUES (3109, 218, 'Phrae');
INSERT INTO `ref_country_states` VALUES (3110, 218, 'Phuket');
INSERT INTO `ref_country_states` VALUES (3111, 218, 'Prachin Buri');
INSERT INTO `ref_country_states` VALUES (3112, 218, 'Prachuap Khiri Khan');
INSERT INTO `ref_country_states` VALUES (3113, 218, 'Ranong');
INSERT INTO `ref_country_states` VALUES (3114, 218, 'Ratchaburi');
INSERT INTO `ref_country_states` VALUES (3115, 218, 'Rayong');
INSERT INTO `ref_country_states` VALUES (3116, 218, 'Roi Et');
INSERT INTO `ref_country_states` VALUES (3117, 218, 'Sa Kaeo');
INSERT INTO `ref_country_states` VALUES (3118, 218, 'Sakon Nakhon');
INSERT INTO `ref_country_states` VALUES (3119, 218, 'Samut Prakan');
INSERT INTO `ref_country_states` VALUES (3120, 218, 'Samut Sakhon');
INSERT INTO `ref_country_states` VALUES (3121, 218, 'Samut Songkhram');
INSERT INTO `ref_country_states` VALUES (3122, 218, 'Sara Buri');
INSERT INTO `ref_country_states` VALUES (3123, 218, 'Satun');
INSERT INTO `ref_country_states` VALUES (3124, 218, 'Sing Buri');
INSERT INTO `ref_country_states` VALUES (3125, 218, 'Sisaket');
INSERT INTO `ref_country_states` VALUES (3126, 218, 'Songkhla');
INSERT INTO `ref_country_states` VALUES (3127, 218, 'Sukhothai');
INSERT INTO `ref_country_states` VALUES (3128, 218, 'Suphan Buri');
INSERT INTO `ref_country_states` VALUES (3129, 218, 'Surat Thani');
INSERT INTO `ref_country_states` VALUES (3130, 218, 'Surin');
INSERT INTO `ref_country_states` VALUES (3131, 218, 'Tak');
INSERT INTO `ref_country_states` VALUES (3132, 218, 'Trang');
INSERT INTO `ref_country_states` VALUES (3133, 218, 'Trat');
INSERT INTO `ref_country_states` VALUES (3134, 218, 'Ubon Ratchathani');
INSERT INTO `ref_country_states` VALUES (3135, 218, 'Udon Thani');
INSERT INTO `ref_country_states` VALUES (3136, 218, 'Uthai Thani');
INSERT INTO `ref_country_states` VALUES (3137, 218, 'Uttaradit');
INSERT INTO `ref_country_states` VALUES (3138, 218, 'Yala');
INSERT INTO `ref_country_states` VALUES (3139, 218, 'Yasothon');
INSERT INTO `ref_country_states` VALUES (3140, 219, 'Sughd Region');
INSERT INTO `ref_country_states` VALUES (3141, 219, 'Districts of Republican Subordination');
INSERT INTO `ref_country_states` VALUES (3142, 219, 'Khatlon Region');
INSERT INTO `ref_country_states` VALUES (3143, 219, 'Gorno-Badakhshan Autonomous Region');
INSERT INTO `ref_country_states` VALUES (3144, 219, 'Dushanbe');
INSERT INTO `ref_country_states` VALUES (3145, 221, 'Aileu');
INSERT INTO `ref_country_states` VALUES (3146, 221, 'Ainaro');
INSERT INTO `ref_country_states` VALUES (3147, 221, 'Baucau');
INSERT INTO `ref_country_states` VALUES (3148, 221, 'Bobonaro');
INSERT INTO `ref_country_states` VALUES (3149, 221, 'Cova-Lima');
INSERT INTO `ref_country_states` VALUES (3150, 221, 'Dili');
INSERT INTO `ref_country_states` VALUES (3151, 221, 'Ermera');
INSERT INTO `ref_country_states` VALUES (3152, 221, 'Lautem');
INSERT INTO `ref_country_states` VALUES (3153, 221, 'Liquica');
INSERT INTO `ref_country_states` VALUES (3154, 221, 'Manatuto');
INSERT INTO `ref_country_states` VALUES (3155, 221, 'Manufahi');
INSERT INTO `ref_country_states` VALUES (3156, 221, 'Oecussi');
INSERT INTO `ref_country_states` VALUES (3157, 221, 'Viqueque');
INSERT INTO `ref_country_states` VALUES (3158, 222, 'Ahal Welayaty (Ashgabat)');
INSERT INTO `ref_country_states` VALUES (3159, 222, 'Balkan Welayaty (Balkanabat)');
INSERT INTO `ref_country_states` VALUES (3160, 222, 'Dashoguz Welayaty');
INSERT INTO `ref_country_states` VALUES (3161, 222, 'Lebap Welayaty (Turkmenabat)');
INSERT INTO `ref_country_states` VALUES (3162, 222, 'Mary Welayaty');
INSERT INTO `ref_country_states` VALUES (3163, 223, 'Ariana (Aryanah)');
INSERT INTO `ref_country_states` VALUES (3164, 223, 'Beja (Bajah)');
INSERT INTO `ref_country_states` VALUES (3165, 223, 'Ben Arous (Bin \'Arus)');
INSERT INTO `ref_country_states` VALUES (3166, 223, 'Bizerte (Banzart)');
INSERT INTO `ref_country_states` VALUES (3167, 223, 'Gabes (Qabis)');
INSERT INTO `ref_country_states` VALUES (3168, 223, 'Gafsa (Qafsah)');
INSERT INTO `ref_country_states` VALUES (3169, 223, 'Jendouba (Jundubah)');
INSERT INTO `ref_country_states` VALUES (3170, 223, 'Kairouan (Al Qayrawan)');
INSERT INTO `ref_country_states` VALUES (3171, 223, 'Kasserine (Al Qasrayn)');
INSERT INTO `ref_country_states` VALUES (3172, 223, 'Kebili (Qibili)');
INSERT INTO `ref_country_states` VALUES (3173, 223, 'Kef (Al Kaf)');
INSERT INTO `ref_country_states` VALUES (3174, 223, 'Mahdia (Al Mahdiyah)');
INSERT INTO `ref_country_states` VALUES (3175, 223, 'Manouba (Manubah)');
INSERT INTO `ref_country_states` VALUES (3176, 223, 'Medenine (Madanin)');
INSERT INTO `ref_country_states` VALUES (3177, 223, 'Monastir (Al Munastir)');
INSERT INTO `ref_country_states` VALUES (3178, 223, 'Nabeul (Nabul)');
INSERT INTO `ref_country_states` VALUES (3179, 223, 'Sfax (Safaqis)');
INSERT INTO `ref_country_states` VALUES (3180, 223, 'Sidi Bou Zid (Sidi Bu Zayd)');
INSERT INTO `ref_country_states` VALUES (3181, 223, 'Siliana (Silyanah)');
INSERT INTO `ref_country_states` VALUES (3182, 223, 'Sousse (Susah)');
INSERT INTO `ref_country_states` VALUES (3183, 223, 'Tataouine (Tatawin)');
INSERT INTO `ref_country_states` VALUES (3184, 223, 'Tozeur (Tawzar)');
INSERT INTO `ref_country_states` VALUES (3185, 223, 'Tunis');
INSERT INTO `ref_country_states` VALUES (3186, 223, 'Zaghouan (Zaghwan)');
INSERT INTO `ref_country_states` VALUES (3187, 224, 'Tongatapu');
INSERT INTO `ref_country_states` VALUES (3188, 224, 'Vavaʻu');
INSERT INTO `ref_country_states` VALUES (3189, 224, 'Haʻapai');
INSERT INTO `ref_country_states` VALUES (3190, 224, 'ʻEua');
INSERT INTO `ref_country_states` VALUES (3191, 224, 'Ongo Niua');
INSERT INTO `ref_country_states` VALUES (3192, 224, 'Tonga');
INSERT INTO `ref_country_states` VALUES (3193, 225, 'Adana');
INSERT INTO `ref_country_states` VALUES (3194, 225, 'Adiyaman');
INSERT INTO `ref_country_states` VALUES (3195, 225, 'Afyonkarahisar');
INSERT INTO `ref_country_states` VALUES (3196, 225, 'Agri');
INSERT INTO `ref_country_states` VALUES (3197, 225, 'Aksaray');
INSERT INTO `ref_country_states` VALUES (3198, 225, 'Amasya');
INSERT INTO `ref_country_states` VALUES (3199, 225, 'Ankara');
INSERT INTO `ref_country_states` VALUES (3200, 225, 'Antalya');
INSERT INTO `ref_country_states` VALUES (3201, 225, 'Ardahan');
INSERT INTO `ref_country_states` VALUES (3202, 225, 'Artvin');
INSERT INTO `ref_country_states` VALUES (3203, 225, 'Aydin');
INSERT INTO `ref_country_states` VALUES (3204, 225, 'Balikesir');
INSERT INTO `ref_country_states` VALUES (3205, 225, 'Bartin');
INSERT INTO `ref_country_states` VALUES (3206, 225, 'Batman');
INSERT INTO `ref_country_states` VALUES (3207, 225, 'Bayburt');
INSERT INTO `ref_country_states` VALUES (3208, 225, 'Bilecik');
INSERT INTO `ref_country_states` VALUES (3209, 225, 'Bingol');
INSERT INTO `ref_country_states` VALUES (3210, 225, 'Bitlis');
INSERT INTO `ref_country_states` VALUES (3211, 225, 'Bolu');
INSERT INTO `ref_country_states` VALUES (3212, 225, 'Burdur');
INSERT INTO `ref_country_states` VALUES (3213, 225, 'Bursa');
INSERT INTO `ref_country_states` VALUES (3214, 225, 'Canakkale');
INSERT INTO `ref_country_states` VALUES (3215, 225, 'Cankiri');
INSERT INTO `ref_country_states` VALUES (3216, 225, 'Corum');
INSERT INTO `ref_country_states` VALUES (3217, 225, 'Denizli');
INSERT INTO `ref_country_states` VALUES (3218, 225, 'Diyarbakir');
INSERT INTO `ref_country_states` VALUES (3219, 225, 'Duzce');
INSERT INTO `ref_country_states` VALUES (3220, 225, 'Edirne');
INSERT INTO `ref_country_states` VALUES (3221, 225, 'Elazig');
INSERT INTO `ref_country_states` VALUES (3222, 225, 'Erzincan');
INSERT INTO `ref_country_states` VALUES (3223, 225, 'Erzurum');
INSERT INTO `ref_country_states` VALUES (3224, 225, 'Eskisehir');
INSERT INTO `ref_country_states` VALUES (3225, 225, 'Gaziantep');
INSERT INTO `ref_country_states` VALUES (3226, 225, 'Giresun');
INSERT INTO `ref_country_states` VALUES (3227, 225, 'Gumushane');
INSERT INTO `ref_country_states` VALUES (3228, 225, 'Hakkari');
INSERT INTO `ref_country_states` VALUES (3229, 225, 'Hatay');
INSERT INTO `ref_country_states` VALUES (3230, 225, 'Igdir');
INSERT INTO `ref_country_states` VALUES (3231, 225, 'Isparta');
INSERT INTO `ref_country_states` VALUES (3232, 225, 'Istanbul');
INSERT INTO `ref_country_states` VALUES (3233, 225, 'Izmir');
INSERT INTO `ref_country_states` VALUES (3234, 225, 'Kahramanmaras');
INSERT INTO `ref_country_states` VALUES (3235, 225, 'Karabuk');
INSERT INTO `ref_country_states` VALUES (3236, 225, 'Karaman');
INSERT INTO `ref_country_states` VALUES (3237, 225, 'Kars');
INSERT INTO `ref_country_states` VALUES (3238, 225, 'Kastamonu');
INSERT INTO `ref_country_states` VALUES (3239, 225, 'Kayseri');
INSERT INTO `ref_country_states` VALUES (3240, 225, 'Kilis');
INSERT INTO `ref_country_states` VALUES (3241, 225, 'Kirikkale');
INSERT INTO `ref_country_states` VALUES (3242, 225, 'Kirklareli');
INSERT INTO `ref_country_states` VALUES (3243, 225, 'Kirsehir');
INSERT INTO `ref_country_states` VALUES (3244, 225, 'Kocaeli');
INSERT INTO `ref_country_states` VALUES (3245, 225, 'Konya');
INSERT INTO `ref_country_states` VALUES (3246, 225, 'Kutahya');
INSERT INTO `ref_country_states` VALUES (3247, 225, 'Malatya');
INSERT INTO `ref_country_states` VALUES (3248, 225, 'Manisa');
INSERT INTO `ref_country_states` VALUES (3249, 225, 'Mardin');
INSERT INTO `ref_country_states` VALUES (3250, 225, 'Mersin');
INSERT INTO `ref_country_states` VALUES (3251, 225, 'Mugla');
INSERT INTO `ref_country_states` VALUES (3252, 225, 'Mus');
INSERT INTO `ref_country_states` VALUES (3253, 225, 'Nevsehir');
INSERT INTO `ref_country_states` VALUES (3254, 225, 'Nigde');
INSERT INTO `ref_country_states` VALUES (3255, 225, 'Ordu');
INSERT INTO `ref_country_states` VALUES (3256, 225, 'Osmaniye');
INSERT INTO `ref_country_states` VALUES (3257, 225, 'Rize');
INSERT INTO `ref_country_states` VALUES (3258, 225, 'Sakarya');
INSERT INTO `ref_country_states` VALUES (3259, 225, 'Samsun');
INSERT INTO `ref_country_states` VALUES (3260, 225, 'Sanliurfa');
INSERT INTO `ref_country_states` VALUES (3261, 225, 'Siirt');
INSERT INTO `ref_country_states` VALUES (3262, 225, 'Sinop');
INSERT INTO `ref_country_states` VALUES (3263, 225, 'Sirnak');
INSERT INTO `ref_country_states` VALUES (3264, 225, 'Sivas');
INSERT INTO `ref_country_states` VALUES (3265, 225, 'Tekirdag');
INSERT INTO `ref_country_states` VALUES (3266, 225, 'Tokat');
INSERT INTO `ref_country_states` VALUES (3267, 225, 'Trabzon');
INSERT INTO `ref_country_states` VALUES (3268, 225, 'Tunceli');
INSERT INTO `ref_country_states` VALUES (3269, 225, 'Usak');
INSERT INTO `ref_country_states` VALUES (3270, 225, 'Van');
INSERT INTO `ref_country_states` VALUES (3271, 225, 'Yalova');
INSERT INTO `ref_country_states` VALUES (3272, 225, 'Yozgat');
INSERT INTO `ref_country_states` VALUES (3273, 225, 'Zonguldak');
INSERT INTO `ref_country_states` VALUES (3274, 226, 'Couva');
INSERT INTO `ref_country_states` VALUES (3275, 226, 'Diego Martin');
INSERT INTO `ref_country_states` VALUES (3276, 226, 'Mayaro');
INSERT INTO `ref_country_states` VALUES (3277, 226, 'Penal');
INSERT INTO `ref_country_states` VALUES (3278, 226, 'Princes Town');
INSERT INTO `ref_country_states` VALUES (3279, 226, 'Sangre Grande');
INSERT INTO `ref_country_states` VALUES (3280, 226, 'San Juan');
INSERT INTO `ref_country_states` VALUES (3281, 226, 'Siparia');
INSERT INTO `ref_country_states` VALUES (3282, 226, 'Tunapuna');
INSERT INTO `ref_country_states` VALUES (3283, 226, 'Port-of-Spain');
INSERT INTO `ref_country_states` VALUES (3284, 226, 'San Fernando');
INSERT INTO `ref_country_states` VALUES (3285, 226, 'Arima');
INSERT INTO `ref_country_states` VALUES (3286, 226, 'Point Fortin');
INSERT INTO `ref_country_states` VALUES (3287, 226, 'Chaguanas');
INSERT INTO `ref_country_states` VALUES (3288, 226, 'Tobago');
INSERT INTO `ref_country_states` VALUES (3289, 228, 'Chang-hua');
INSERT INTO `ref_country_states` VALUES (3290, 228, 'Chia-i');
INSERT INTO `ref_country_states` VALUES (3291, 228, 'Hsin-chu');
INSERT INTO `ref_country_states` VALUES (3292, 228, 'Hua-lien');
INSERT INTO `ref_country_states` VALUES (3293, 228, 'I-lan');
INSERT INTO `ref_country_states` VALUES (3294, 228, 'Kao-hsiung');
INSERT INTO `ref_country_states` VALUES (3295, 228, 'Kin-men');
INSERT INTO `ref_country_states` VALUES (3296, 228, 'Lien-chiang');
INSERT INTO `ref_country_states` VALUES (3297, 228, 'Miao-li');
INSERT INTO `ref_country_states` VALUES (3298, 228, 'Nan-t\'ou');
INSERT INTO `ref_country_states` VALUES (3299, 228, 'P\'eng-hu');
INSERT INTO `ref_country_states` VALUES (3300, 228, 'P\'ing-tung');
INSERT INTO `ref_country_states` VALUES (3301, 228, 'T\'ai-chung');
INSERT INTO `ref_country_states` VALUES (3302, 228, 'T\'ai-nan');
INSERT INTO `ref_country_states` VALUES (3303, 228, 'T\'ai-pei');
INSERT INTO `ref_country_states` VALUES (3304, 228, 'T\'ai-tung');
INSERT INTO `ref_country_states` VALUES (3305, 228, 'T\'ao-yuan');
INSERT INTO `ref_country_states` VALUES (3306, 228, 'Yun-lin');
INSERT INTO `ref_country_states` VALUES (3307, 228, 'Chia-i');
INSERT INTO `ref_country_states` VALUES (3308, 228, 'Chi-lung');
INSERT INTO `ref_country_states` VALUES (3309, 228, 'Hsin-chu');
INSERT INTO `ref_country_states` VALUES (3310, 228, 'T\'ai-chung');
INSERT INTO `ref_country_states` VALUES (3311, 228, 'T\'ai-nan');
INSERT INTO `ref_country_states` VALUES (3312, 228, 'Kao-hsiung city');
INSERT INTO `ref_country_states` VALUES (3313, 228, 'T\'ai-pei city');
INSERT INTO `ref_country_states` VALUES (3314, 229, 'Arusha');
INSERT INTO `ref_country_states` VALUES (3315, 229, 'Dar es Salaam');
INSERT INTO `ref_country_states` VALUES (3316, 229, 'Dodoma');
INSERT INTO `ref_country_states` VALUES (3317, 229, 'Iringa');
INSERT INTO `ref_country_states` VALUES (3318, 229, 'Kagera');
INSERT INTO `ref_country_states` VALUES (3319, 229, 'Kigoma');
INSERT INTO `ref_country_states` VALUES (3320, 229, 'Kilimanjaro');
INSERT INTO `ref_country_states` VALUES (3321, 229, 'Lindi');
INSERT INTO `ref_country_states` VALUES (3322, 229, 'Manyara');
INSERT INTO `ref_country_states` VALUES (3323, 229, 'Mara');
INSERT INTO `ref_country_states` VALUES (3324, 229, 'Mbeya');
INSERT INTO `ref_country_states` VALUES (3325, 229, 'Morogoro');
INSERT INTO `ref_country_states` VALUES (3326, 229, 'Mtwara');
INSERT INTO `ref_country_states` VALUES (3327, 229, 'Mwanza');
INSERT INTO `ref_country_states` VALUES (3328, 229, 'Pemba North');
INSERT INTO `ref_country_states` VALUES (3329, 229, 'Pemba South');
INSERT INTO `ref_country_states` VALUES (3330, 229, 'Pwani');
INSERT INTO `ref_country_states` VALUES (3331, 229, 'Rukwa');
INSERT INTO `ref_country_states` VALUES (3332, 229, 'Ruvuma');
INSERT INTO `ref_country_states` VALUES (3333, 229, 'Shinyanga');
INSERT INTO `ref_country_states` VALUES (3334, 229, 'Singida');
INSERT INTO `ref_country_states` VALUES (3335, 229, 'Tabora');
INSERT INTO `ref_country_states` VALUES (3336, 229, 'Tanga');
INSERT INTO `ref_country_states` VALUES (3337, 229, 'Zanzibar Central/South');
INSERT INTO `ref_country_states` VALUES (3338, 229, 'Zanzibar North');
INSERT INTO `ref_country_states` VALUES (3339, 229, 'Zanzibar Urban/West');
INSERT INTO `ref_country_states` VALUES (3340, 230, 'Cherkasy');
INSERT INTO `ref_country_states` VALUES (3341, 230, 'Chernihiv');
INSERT INTO `ref_country_states` VALUES (3342, 230, 'Chernivtsi');
INSERT INTO `ref_country_states` VALUES (3343, 230, 'Crimea');
INSERT INTO `ref_country_states` VALUES (3344, 230, 'Dnipropetrovs\'k');
INSERT INTO `ref_country_states` VALUES (3345, 230, 'Donets\'k');
INSERT INTO `ref_country_states` VALUES (3346, 230, 'Ivano-Frankivs\'k');
INSERT INTO `ref_country_states` VALUES (3347, 230, 'Kharkiv');
INSERT INTO `ref_country_states` VALUES (3348, 230, 'Kherson');
INSERT INTO `ref_country_states` VALUES (3349, 230, 'Khmel\'nyts\'kyy');
INSERT INTO `ref_country_states` VALUES (3350, 230, 'Kirovohrad');
INSERT INTO `ref_country_states` VALUES (3351, 230, 'Kiev');
INSERT INTO `ref_country_states` VALUES (3352, 230, 'Kyyiv');
INSERT INTO `ref_country_states` VALUES (3353, 230, 'Luhans\'k');
INSERT INTO `ref_country_states` VALUES (3354, 230, 'L\'viv');
INSERT INTO `ref_country_states` VALUES (3355, 230, 'Mykolayiv');
INSERT INTO `ref_country_states` VALUES (3356, 230, 'Odesa');
INSERT INTO `ref_country_states` VALUES (3357, 230, 'Poltava');
INSERT INTO `ref_country_states` VALUES (3358, 230, 'Rivne');
INSERT INTO `ref_country_states` VALUES (3359, 230, 'Sevastopol\'');
INSERT INTO `ref_country_states` VALUES (3360, 230, 'Sumy');
INSERT INTO `ref_country_states` VALUES (3361, 230, 'Ternopil\'');
INSERT INTO `ref_country_states` VALUES (3362, 230, 'Vinnytsya');
INSERT INTO `ref_country_states` VALUES (3363, 230, 'Volyn\'');
INSERT INTO `ref_country_states` VALUES (3364, 230, 'Zakarpattya');
INSERT INTO `ref_country_states` VALUES (3365, 230, 'Zaporizhzhya');
INSERT INTO `ref_country_states` VALUES (3366, 230, 'Zhytomyr');
INSERT INTO `ref_country_states` VALUES (3367, 231, 'Adjumani');
INSERT INTO `ref_country_states` VALUES (3368, 231, 'Apac');
INSERT INTO `ref_country_states` VALUES (3369, 231, 'Arua');
INSERT INTO `ref_country_states` VALUES (3370, 231, 'Bugiri');
INSERT INTO `ref_country_states` VALUES (3371, 231, 'Bundibugyo');
INSERT INTO `ref_country_states` VALUES (3372, 231, 'Bushenyi');
INSERT INTO `ref_country_states` VALUES (3373, 231, 'Busia');
INSERT INTO `ref_country_states` VALUES (3374, 231, 'Gulu');
INSERT INTO `ref_country_states` VALUES (3375, 231, 'Hoima');
INSERT INTO `ref_country_states` VALUES (3376, 231, 'Iganga');
INSERT INTO `ref_country_states` VALUES (3377, 231, 'Jinja');
INSERT INTO `ref_country_states` VALUES (3378, 231, 'Kabale');
INSERT INTO `ref_country_states` VALUES (3379, 231, 'Kabarole');
INSERT INTO `ref_country_states` VALUES (3380, 231, 'Kaberamaido');
INSERT INTO `ref_country_states` VALUES (3381, 231, 'Kalangala');
INSERT INTO `ref_country_states` VALUES (3382, 231, 'Kampala');
INSERT INTO `ref_country_states` VALUES (3383, 231, 'Kamuli');
INSERT INTO `ref_country_states` VALUES (3384, 231, 'Kamwenge');
INSERT INTO `ref_country_states` VALUES (3385, 231, 'Kanungu');
INSERT INTO `ref_country_states` VALUES (3386, 231, 'Kapchorwa');
INSERT INTO `ref_country_states` VALUES (3387, 231, 'Kasese');
INSERT INTO `ref_country_states` VALUES (3388, 231, 'Katakwi');
INSERT INTO `ref_country_states` VALUES (3389, 231, 'Kayunga');
INSERT INTO `ref_country_states` VALUES (3390, 231, 'Kibale');
INSERT INTO `ref_country_states` VALUES (3391, 231, 'Kiboga');
INSERT INTO `ref_country_states` VALUES (3392, 231, 'Kisoro');
INSERT INTO `ref_country_states` VALUES (3393, 231, 'Kitgum');
INSERT INTO `ref_country_states` VALUES (3394, 231, 'Kotido');
INSERT INTO `ref_country_states` VALUES (3395, 231, 'Kumi');
INSERT INTO `ref_country_states` VALUES (3396, 231, 'Kyenjojo');
INSERT INTO `ref_country_states` VALUES (3397, 231, 'Lira');
INSERT INTO `ref_country_states` VALUES (3398, 231, 'Luwero');
INSERT INTO `ref_country_states` VALUES (3399, 231, 'Masaka');
INSERT INTO `ref_country_states` VALUES (3400, 231, 'Masindi');
INSERT INTO `ref_country_states` VALUES (3401, 231, 'Mayuge');
INSERT INTO `ref_country_states` VALUES (3402, 231, 'Mbale');
INSERT INTO `ref_country_states` VALUES (3403, 231, 'Mbarara');
INSERT INTO `ref_country_states` VALUES (3404, 231, 'Moroto');
INSERT INTO `ref_country_states` VALUES (3405, 231, 'Moyo');
INSERT INTO `ref_country_states` VALUES (3406, 231, 'Mpigi');
INSERT INTO `ref_country_states` VALUES (3407, 231, 'Mubende');
INSERT INTO `ref_country_states` VALUES (3408, 231, 'Mukono');
INSERT INTO `ref_country_states` VALUES (3409, 231, 'Nakapiripirit');
INSERT INTO `ref_country_states` VALUES (3410, 231, 'Nakasongola');
INSERT INTO `ref_country_states` VALUES (3411, 231, 'Nebbi');
INSERT INTO `ref_country_states` VALUES (3412, 231, 'Ntungamo');
INSERT INTO `ref_country_states` VALUES (3413, 231, 'Pader');
INSERT INTO `ref_country_states` VALUES (3414, 231, 'Pallisa');
INSERT INTO `ref_country_states` VALUES (3415, 231, 'Rakai');
INSERT INTO `ref_country_states` VALUES (3416, 231, 'Rukungiri');
INSERT INTO `ref_country_states` VALUES (3417, 231, 'Sembabule');
INSERT INTO `ref_country_states` VALUES (3418, 231, 'Sironko');
INSERT INTO `ref_country_states` VALUES (3419, 231, 'Soroti');
INSERT INTO `ref_country_states` VALUES (3420, 231, 'Tororo');
INSERT INTO `ref_country_states` VALUES (3421, 231, 'Wakiso');
INSERT INTO `ref_country_states` VALUES (3422, 231, 'Yumbe');
INSERT INTO `ref_country_states` VALUES (3423, 233, 'Alabama');
INSERT INTO `ref_country_states` VALUES (3424, 233, 'Alaska');
INSERT INTO `ref_country_states` VALUES (3425, 233, 'Arizona');
INSERT INTO `ref_country_states` VALUES (3426, 233, 'Arkansas');
INSERT INTO `ref_country_states` VALUES (3427, 233, 'California');
INSERT INTO `ref_country_states` VALUES (3428, 233, 'Colorado');
INSERT INTO `ref_country_states` VALUES (3429, 233, 'Connecticut');
INSERT INTO `ref_country_states` VALUES (3430, 233, 'Delaware');
INSERT INTO `ref_country_states` VALUES (3431, 233, 'District of Columbia');
INSERT INTO `ref_country_states` VALUES (3432, 233, 'Florida');
INSERT INTO `ref_country_states` VALUES (3433, 233, 'Georgia');
INSERT INTO `ref_country_states` VALUES (3434, 233, 'Hawaii');
INSERT INTO `ref_country_states` VALUES (3435, 233, 'Idaho');
INSERT INTO `ref_country_states` VALUES (3436, 233, 'Illinois');
INSERT INTO `ref_country_states` VALUES (3437, 233, 'Indiana');
INSERT INTO `ref_country_states` VALUES (3438, 233, 'Iowa');
INSERT INTO `ref_country_states` VALUES (3439, 233, 'Kansas');
INSERT INTO `ref_country_states` VALUES (3440, 233, 'Kentucky');
INSERT INTO `ref_country_states` VALUES (3441, 233, 'Louisiana');
INSERT INTO `ref_country_states` VALUES (3442, 233, 'Maine');
INSERT INTO `ref_country_states` VALUES (3443, 233, 'Maryland');
INSERT INTO `ref_country_states` VALUES (3444, 233, 'Massachusetts');
INSERT INTO `ref_country_states` VALUES (3445, 233, 'Michigan');
INSERT INTO `ref_country_states` VALUES (3446, 233, 'Minnesota');
INSERT INTO `ref_country_states` VALUES (3447, 233, 'Mississippi');
INSERT INTO `ref_country_states` VALUES (3448, 233, 'Missouri');
INSERT INTO `ref_country_states` VALUES (3449, 233, 'Montana');
INSERT INTO `ref_country_states` VALUES (3450, 233, 'Nebraska');
INSERT INTO `ref_country_states` VALUES (3451, 233, 'Nevada');
INSERT INTO `ref_country_states` VALUES (3452, 233, 'New Hampshire');
INSERT INTO `ref_country_states` VALUES (3453, 233, 'New Jersey');
INSERT INTO `ref_country_states` VALUES (3454, 233, 'New Mexico');
INSERT INTO `ref_country_states` VALUES (3455, 233, 'New York');
INSERT INTO `ref_country_states` VALUES (3456, 233, 'North Carolina');
INSERT INTO `ref_country_states` VALUES (3457, 233, 'North Dakota');
INSERT INTO `ref_country_states` VALUES (3458, 233, 'Ohio');
INSERT INTO `ref_country_states` VALUES (3459, 233, 'Oklahoma');
INSERT INTO `ref_country_states` VALUES (3460, 233, 'Oregon');
INSERT INTO `ref_country_states` VALUES (3461, 233, 'Pennsylvania');
INSERT INTO `ref_country_states` VALUES (3462, 233, 'Rhode Island');
INSERT INTO `ref_country_states` VALUES (3463, 233, 'South Carolina');
INSERT INTO `ref_country_states` VALUES (3464, 233, 'South Dakota');
INSERT INTO `ref_country_states` VALUES (3465, 233, 'Tennessee');
INSERT INTO `ref_country_states` VALUES (3466, 233, 'Texas');
INSERT INTO `ref_country_states` VALUES (3467, 233, 'Utah');
INSERT INTO `ref_country_states` VALUES (3468, 233, 'Vermont');
INSERT INTO `ref_country_states` VALUES (3469, 233, 'Virginia');
INSERT INTO `ref_country_states` VALUES (3470, 233, 'Washington');
INSERT INTO `ref_country_states` VALUES (3471, 233, 'West Virginia');
INSERT INTO `ref_country_states` VALUES (3472, 233, 'Wisconsin');
INSERT INTO `ref_country_states` VALUES (3473, 233, 'Wyoming');
INSERT INTO `ref_country_states` VALUES (3474, 234, 'Artigas');
INSERT INTO `ref_country_states` VALUES (3475, 234, 'Canelones');
INSERT INTO `ref_country_states` VALUES (3476, 234, 'Cerro Largo');
INSERT INTO `ref_country_states` VALUES (3477, 234, 'Colonia');
INSERT INTO `ref_country_states` VALUES (3478, 234, 'Durazno');
INSERT INTO `ref_country_states` VALUES (3479, 234, 'Flores');
INSERT INTO `ref_country_states` VALUES (3480, 234, 'Florida');
INSERT INTO `ref_country_states` VALUES (3481, 234, 'Lavalleja');
INSERT INTO `ref_country_states` VALUES (3482, 234, 'Maldonado');
INSERT INTO `ref_country_states` VALUES (3483, 234, 'Montevideo');
INSERT INTO `ref_country_states` VALUES (3484, 234, 'Paysandu');
INSERT INTO `ref_country_states` VALUES (3485, 234, 'Rio Negro');
INSERT INTO `ref_country_states` VALUES (3486, 234, 'Rivera');
INSERT INTO `ref_country_states` VALUES (3487, 234, 'Rocha');
INSERT INTO `ref_country_states` VALUES (3488, 234, 'Salto');
INSERT INTO `ref_country_states` VALUES (3489, 234, 'San Jose');
INSERT INTO `ref_country_states` VALUES (3490, 234, 'Soriano');
INSERT INTO `ref_country_states` VALUES (3491, 234, 'Tacuarembo');
INSERT INTO `ref_country_states` VALUES (3492, 234, 'Treinta y Tres');
INSERT INTO `ref_country_states` VALUES (3493, 235, 'Andijon Viloyati');
INSERT INTO `ref_country_states` VALUES (3494, 235, 'Buxoro Viloyati');
INSERT INTO `ref_country_states` VALUES (3495, 235, 'Farg\'ona Viloyati');
INSERT INTO `ref_country_states` VALUES (3496, 235, 'Jizzax Viloyati');
INSERT INTO `ref_country_states` VALUES (3497, 235, 'Namangan Viloyati');
INSERT INTO `ref_country_states` VALUES (3498, 235, 'Navoiy Viloyati');
INSERT INTO `ref_country_states` VALUES (3499, 235, 'Qashqadaryo Viloyati');
INSERT INTO `ref_country_states` VALUES (3500, 235, 'Qaraqalpog\'iston Respublikasi');
INSERT INTO `ref_country_states` VALUES (3501, 235, 'Samarqand Viloyati');
INSERT INTO `ref_country_states` VALUES (3502, 235, 'Sirdaryo Viloyati');
INSERT INTO `ref_country_states` VALUES (3503, 235, 'Surxondaryo Viloyati');
INSERT INTO `ref_country_states` VALUES (3504, 235, 'Toshkent Shahri');
INSERT INTO `ref_country_states` VALUES (3505, 235, 'Toshkent Viloyati');
INSERT INTO `ref_country_states` VALUES (3506, 235, 'Xorazm Viloyati');
INSERT INTO `ref_country_states` VALUES (3507, 238, 'Amazonas');
INSERT INTO `ref_country_states` VALUES (3508, 238, 'Anzoategui');
INSERT INTO `ref_country_states` VALUES (3509, 238, 'Apure');
INSERT INTO `ref_country_states` VALUES (3510, 238, 'Aragua');
INSERT INTO `ref_country_states` VALUES (3511, 238, 'Barinas');
INSERT INTO `ref_country_states` VALUES (3512, 238, 'Bolivar');
INSERT INTO `ref_country_states` VALUES (3513, 238, 'Carabobo');
INSERT INTO `ref_country_states` VALUES (3514, 238, 'Cojedes');
INSERT INTO `ref_country_states` VALUES (3515, 238, 'Delta Amacuro');
INSERT INTO `ref_country_states` VALUES (3516, 238, 'Dependencias Federales');
INSERT INTO `ref_country_states` VALUES (3517, 238, 'Distrito Federal');
INSERT INTO `ref_country_states` VALUES (3518, 238, 'Falcon');
INSERT INTO `ref_country_states` VALUES (3519, 238, 'Guarico');
INSERT INTO `ref_country_states` VALUES (3520, 238, 'Lara');
INSERT INTO `ref_country_states` VALUES (3521, 238, 'Merida');
INSERT INTO `ref_country_states` VALUES (3522, 238, 'Miranda');
INSERT INTO `ref_country_states` VALUES (3523, 238, 'Monagas');
INSERT INTO `ref_country_states` VALUES (3524, 238, 'Nueva Esparta');
INSERT INTO `ref_country_states` VALUES (3525, 238, 'Portuguesa');
INSERT INTO `ref_country_states` VALUES (3526, 238, 'Sucre');
INSERT INTO `ref_country_states` VALUES (3527, 238, 'Tachira');
INSERT INTO `ref_country_states` VALUES (3528, 238, 'Trujillo');
INSERT INTO `ref_country_states` VALUES (3529, 238, 'Vargas');
INSERT INTO `ref_country_states` VALUES (3530, 238, 'Yaracuy');
INSERT INTO `ref_country_states` VALUES (3531, 238, 'Zulia');
INSERT INTO `ref_country_states` VALUES (3532, 241, 'An Giang');
INSERT INTO `ref_country_states` VALUES (3533, 241, 'Bac Giang');
INSERT INTO `ref_country_states` VALUES (3534, 241, 'Bac Kan');
INSERT INTO `ref_country_states` VALUES (3535, 241, 'Bac Lieu');
INSERT INTO `ref_country_states` VALUES (3536, 241, 'Bac Ninh');
INSERT INTO `ref_country_states` VALUES (3537, 241, 'Ba Ria-Vung Tau');
INSERT INTO `ref_country_states` VALUES (3538, 241, 'Ben Tre');
INSERT INTO `ref_country_states` VALUES (3539, 241, 'Binh Dinh');
INSERT INTO `ref_country_states` VALUES (3540, 241, 'Binh Duong');
INSERT INTO `ref_country_states` VALUES (3541, 241, 'Binh Phuoc');
INSERT INTO `ref_country_states` VALUES (3542, 241, 'Binh Thuan');
INSERT INTO `ref_country_states` VALUES (3543, 241, 'Ca Mau');
INSERT INTO `ref_country_states` VALUES (3544, 241, 'Cao Bang');
INSERT INTO `ref_country_states` VALUES (3545, 241, 'Dac Lak');
INSERT INTO `ref_country_states` VALUES (3546, 241, 'Dac Nong');
INSERT INTO `ref_country_states` VALUES (3547, 241, 'Dien Bien');
INSERT INTO `ref_country_states` VALUES (3548, 241, 'Dong Nai');
INSERT INTO `ref_country_states` VALUES (3549, 241, 'Dong Thap');
INSERT INTO `ref_country_states` VALUES (3550, 241, 'Gia Lai');
INSERT INTO `ref_country_states` VALUES (3551, 241, 'Ha Giang');
INSERT INTO `ref_country_states` VALUES (3552, 241, 'Hai Duong');
INSERT INTO `ref_country_states` VALUES (3553, 241, 'Ha Nam');
INSERT INTO `ref_country_states` VALUES (3554, 241, 'Ha Tay');
INSERT INTO `ref_country_states` VALUES (3555, 241, 'Ha Tinh');
INSERT INTO `ref_country_states` VALUES (3556, 241, 'Hau Giang');
INSERT INTO `ref_country_states` VALUES (3557, 241, 'Hoa Binh');
INSERT INTO `ref_country_states` VALUES (3558, 241, 'Hung Yen');
INSERT INTO `ref_country_states` VALUES (3559, 241, 'Khanh Hoa');
INSERT INTO `ref_country_states` VALUES (3560, 241, 'Kien Giang');
INSERT INTO `ref_country_states` VALUES (3561, 241, 'Kon Tum');
INSERT INTO `ref_country_states` VALUES (3562, 241, 'Lai Chau');
INSERT INTO `ref_country_states` VALUES (3563, 241, 'Lam Dong');
INSERT INTO `ref_country_states` VALUES (3564, 241, 'Lang Son');
INSERT INTO `ref_country_states` VALUES (3565, 241, 'Lao Cai');
INSERT INTO `ref_country_states` VALUES (3566, 241, 'Long An');
INSERT INTO `ref_country_states` VALUES (3567, 241, 'Nam Dinh');
INSERT INTO `ref_country_states` VALUES (3568, 241, 'Nghe An');
INSERT INTO `ref_country_states` VALUES (3569, 241, 'Ninh Binh');
INSERT INTO `ref_country_states` VALUES (3570, 241, 'Ninh Thuan');
INSERT INTO `ref_country_states` VALUES (3571, 241, 'Phu Tho');
INSERT INTO `ref_country_states` VALUES (3572, 241, 'Phu Yen');
INSERT INTO `ref_country_states` VALUES (3573, 241, 'Quang Binh');
INSERT INTO `ref_country_states` VALUES (3574, 241, 'Quang Nam');
INSERT INTO `ref_country_states` VALUES (3575, 241, 'Quang Ngai');
INSERT INTO `ref_country_states` VALUES (3576, 241, 'Quang Ninh');
INSERT INTO `ref_country_states` VALUES (3577, 241, 'Quang Tri');
INSERT INTO `ref_country_states` VALUES (3578, 241, 'Soc Trang');
INSERT INTO `ref_country_states` VALUES (3579, 241, 'Son La');
INSERT INTO `ref_country_states` VALUES (3580, 241, 'Tay Ninh');
INSERT INTO `ref_country_states` VALUES (3581, 241, 'Thai Binh');
INSERT INTO `ref_country_states` VALUES (3582, 241, 'Thai Nguyen');
INSERT INTO `ref_country_states` VALUES (3583, 241, 'Thanh Hoa');
INSERT INTO `ref_country_states` VALUES (3584, 241, 'Thua Thien-Hue');
INSERT INTO `ref_country_states` VALUES (3585, 241, 'Tien Giang');
INSERT INTO `ref_country_states` VALUES (3586, 241, 'Tra Vinh');
INSERT INTO `ref_country_states` VALUES (3587, 241, 'Tuyen Quang');
INSERT INTO `ref_country_states` VALUES (3588, 241, 'Vinh Long');
INSERT INTO `ref_country_states` VALUES (3589, 241, 'Vinh Phuc');
INSERT INTO `ref_country_states` VALUES (3590, 241, 'Yen Bai');
INSERT INTO `ref_country_states` VALUES (3591, 241, 'Can Tho');
INSERT INTO `ref_country_states` VALUES (3592, 241, 'Da Nang');
INSERT INTO `ref_country_states` VALUES (3593, 241, 'Hai Phong');
INSERT INTO `ref_country_states` VALUES (3594, 241, 'Hanoi');
INSERT INTO `ref_country_states` VALUES (3595, 241, 'Ho Chi Minh');
INSERT INTO `ref_country_states` VALUES (3596, 242, 'Malampa');
INSERT INTO `ref_country_states` VALUES (3597, 242, 'Penama');
INSERT INTO `ref_country_states` VALUES (3598, 242, 'Sanma');
INSERT INTO `ref_country_states` VALUES (3599, 242, 'Shefa');
INSERT INTO `ref_country_states` VALUES (3600, 242, 'Tafea');
INSERT INTO `ref_country_states` VALUES (3601, 242, 'Torba');
INSERT INTO `ref_country_states` VALUES (3602, 244, 'A\'ana');
INSERT INTO `ref_country_states` VALUES (3603, 244, 'Aiga-i-le-Tai');
INSERT INTO `ref_country_states` VALUES (3604, 244, 'Atua');
INSERT INTO `ref_country_states` VALUES (3605, 244, 'Fa\'asaleleaga');
INSERT INTO `ref_country_states` VALUES (3606, 244, 'Gaga\'emauga');
INSERT INTO `ref_country_states` VALUES (3607, 244, 'Gagaifomauga');
INSERT INTO `ref_country_states` VALUES (3608, 244, 'Palauli');
INSERT INTO `ref_country_states` VALUES (3609, 244, 'Satupa\'itea');
INSERT INTO `ref_country_states` VALUES (3610, 244, 'Tuamasaga');
INSERT INTO `ref_country_states` VALUES (3611, 244, 'Va\'a-o-Fonoti');
INSERT INTO `ref_country_states` VALUES (3612, 244, 'Vaisigano');
INSERT INTO `ref_country_states` VALUES (3613, 245, 'Ferizaj');
INSERT INTO `ref_country_states` VALUES (3614, 245, 'Gjakova');
INSERT INTO `ref_country_states` VALUES (3615, 245, 'Gjilan');
INSERT INTO `ref_country_states` VALUES (3616, 245, 'Mitrovica');
INSERT INTO `ref_country_states` VALUES (3617, 245, 'Peja');
INSERT INTO `ref_country_states` VALUES (3618, 245, 'Pristina');
INSERT INTO `ref_country_states` VALUES (3619, 245, 'Prizren');
INSERT INTO `ref_country_states` VALUES (3620, 246, 'Abyan');
INSERT INTO `ref_country_states` VALUES (3621, 246, '\'Adan');
INSERT INTO `ref_country_states` VALUES (3622, 246, 'Ad Dali\'');
INSERT INTO `ref_country_states` VALUES (3623, 246, 'Al Bayda\'');
INSERT INTO `ref_country_states` VALUES (3624, 246, 'Al Hudaydah');
INSERT INTO `ref_country_states` VALUES (3625, 246, 'Al Jawf');
INSERT INTO `ref_country_states` VALUES (3626, 246, 'Al Mahrah');
INSERT INTO `ref_country_states` VALUES (3627, 246, 'Al Mahwit');
INSERT INTO `ref_country_states` VALUES (3628, 246, '\'Amran');
INSERT INTO `ref_country_states` VALUES (3629, 246, 'Dhamar');
INSERT INTO `ref_country_states` VALUES (3630, 246, 'Hadramawt');
INSERT INTO `ref_country_states` VALUES (3631, 246, 'Hajjah');
INSERT INTO `ref_country_states` VALUES (3632, 246, 'Ibb');
INSERT INTO `ref_country_states` VALUES (3633, 246, 'Lahij');
INSERT INTO `ref_country_states` VALUES (3634, 246, 'Ma\'rib');
INSERT INTO `ref_country_states` VALUES (3635, 246, 'Sa\'dah');
INSERT INTO `ref_country_states` VALUES (3636, 246, 'San\'a\'');
INSERT INTO `ref_country_states` VALUES (3637, 246, 'Shabwah');
INSERT INTO `ref_country_states` VALUES (3638, 246, 'Ta\'izz');
INSERT INTO `ref_country_states` VALUES (3639, 248, 'Eastern Cape');
INSERT INTO `ref_country_states` VALUES (3640, 248, 'Free State');
INSERT INTO `ref_country_states` VALUES (3641, 248, 'Gauteng');
INSERT INTO `ref_country_states` VALUES (3642, 248, 'KwaZulu-Natal');
INSERT INTO `ref_country_states` VALUES (3643, 248, 'Limpopo');
INSERT INTO `ref_country_states` VALUES (3644, 248, 'Mpumalanga');
INSERT INTO `ref_country_states` VALUES (3645, 248, 'North-West');
INSERT INTO `ref_country_states` VALUES (3646, 248, 'Northern Cape');
INSERT INTO `ref_country_states` VALUES (3647, 248, 'Western Cape');
INSERT INTO `ref_country_states` VALUES (3648, 249, 'Central');
INSERT INTO `ref_country_states` VALUES (3649, 249, 'Copperbelt');
INSERT INTO `ref_country_states` VALUES (3650, 249, 'Eastern');
INSERT INTO `ref_country_states` VALUES (3651, 249, 'Luapula');
INSERT INTO `ref_country_states` VALUES (3652, 249, 'Lusaka');
INSERT INTO `ref_country_states` VALUES (3653, 249, 'Northern');
INSERT INTO `ref_country_states` VALUES (3654, 249, 'North-Western');
INSERT INTO `ref_country_states` VALUES (3655, 249, 'Southern');
INSERT INTO `ref_country_states` VALUES (3656, 249, 'Western');
INSERT INTO `ref_country_states` VALUES (3657, 250, 'Bulawayo');
INSERT INTO `ref_country_states` VALUES (3658, 250, 'Harare');
INSERT INTO `ref_country_states` VALUES (3659, 250, 'Manicaland');
INSERT INTO `ref_country_states` VALUES (3660, 250, 'Mashonaland Central');
INSERT INTO `ref_country_states` VALUES (3661, 250, 'Mashonaland East');
INSERT INTO `ref_country_states` VALUES (3662, 250, 'Mashonaland West');
INSERT INTO `ref_country_states` VALUES (3663, 250, 'Masvingo');
INSERT INTO `ref_country_states` VALUES (3664, 250, 'Matabeleland North');
INSERT INTO `ref_country_states` VALUES (3665, 250, 'Matabeleland South');
INSERT INTO `ref_country_states` VALUES (3666, 250, 'Midlands');

-- ----------------------------
-- Table structure for ref_disabilities
-- ----------------------------
DROP TABLE IF EXISTS `ref_disabilities`;
CREATE TABLE `ref_disabilities`  (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_disabilities
-- ----------------------------
INSERT INTO `ref_disabilities` VALUES (1, 'TUNA RUNGU');
INSERT INTO `ref_disabilities` VALUES (2, 'TUNA NETRA');
INSERT INTO `ref_disabilities` VALUES (3, 'TUNA DAKSA');
INSERT INTO `ref_disabilities` VALUES (4, 'TUNA GRAHITA');
INSERT INTO `ref_disabilities` VALUES (5, 'TUNA LARAS');
INSERT INTO `ref_disabilities` VALUES (6, 'LAMBAN BELAJAR');
INSERT INTO `ref_disabilities` VALUES (7, 'SULIT BELAJAR');
INSERT INTO `ref_disabilities` VALUES (8, 'GANGGUAN KOMUNIKASI');
INSERT INTO `ref_disabilities` VALUES (9, 'BAKAT LUAR BIASA');

-- ----------------------------
-- Table structure for ref_employments
-- ----------------------------
DROP TABLE IF EXISTS `ref_employments`;
CREATE TABLE `ref_employments`  (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_employments
-- ----------------------------
INSERT INTO `ref_employments` VALUES (1, 'TIDAK BEKERJA');
INSERT INTO `ref_employments` VALUES (2, 'PENSIUNAN/ALMARHUM');
INSERT INTO `ref_employments` VALUES (3, 'PNS');
INSERT INTO `ref_employments` VALUES (4, 'TNI/POLISI');
INSERT INTO `ref_employments` VALUES (5, 'GURU/DOSEN');
INSERT INTO `ref_employments` VALUES (6, 'PEGAWAI SWASTA');
INSERT INTO `ref_employments` VALUES (7, 'PENGUSAHA/WIRASWASTA');
INSERT INTO `ref_employments` VALUES (8, 'PENGACARA/HAKIM/JAKSA/NOTARIS');
INSERT INTO `ref_employments` VALUES (9, 'SENIMAN/PELUKIS/ARTIS/SEJENIS');
INSERT INTO `ref_employments` VALUES (10, 'DOKTER/BIDAN/PERAWAT');
INSERT INTO `ref_employments` VALUES (11, 'PILOT/PRAMUGARI');
INSERT INTO `ref_employments` VALUES (12, 'PEDAGANG');
INSERT INTO `ref_employments` VALUES (13, 'PETANI/PETERNAK');
INSERT INTO `ref_employments` VALUES (14, 'NELAYAN');
INSERT INTO `ref_employments` VALUES (15, 'BURUH (TANI/PABRIK/BANGUNAN)');
INSERT INTO `ref_employments` VALUES (16, 'SOPIR/MASINIS/KONDEKTUR');
INSERT INTO `ref_employments` VALUES (17, 'POLITIKUS');
INSERT INTO `ref_employments` VALUES (18, 'LAINNYA');

-- ----------------------------
-- Table structure for ref_grades
-- ----------------------------
DROP TABLE IF EXISTS `ref_grades`;
CREATE TABLE `ref_grades`  (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_grades
-- ----------------------------
INSERT INTO `ref_grades` VALUES (1, 'PAUD/SEDERAJAT');
INSERT INTO `ref_grades` VALUES (2, 'TK/RA/SEDERAJAT');
INSERT INTO `ref_grades` VALUES (3, 'SD/MI/SEDERAJAT');
INSERT INTO `ref_grades` VALUES (4, 'SLTP/SMP/MTS/SEDERAJAT');
INSERT INTO `ref_grades` VALUES (5, 'SLTA/SMA/MA/SEDERAJAT');
INSERT INTO `ref_grades` VALUES (6, 'Diploma I');
INSERT INTO `ref_grades` VALUES (7, 'Diploma II');
INSERT INTO `ref_grades` VALUES (8, 'Diploma III');
INSERT INTO `ref_grades` VALUES (9, 'Diploma IV');
INSERT INTO `ref_grades` VALUES (10, 'Strata I');
INSERT INTO `ref_grades` VALUES (11, 'Strata II');
INSERT INTO `ref_grades` VALUES (12, 'Strata III');

-- ----------------------------
-- Table structure for ref_languages
-- ----------------------------
DROP TABLE IF EXISTS `ref_languages`;
CREATE TABLE `ref_languages`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `native` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `rtl` tinyint UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 115 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_languages
-- ----------------------------
INSERT INTO `ref_languages` VALUES (1, 'af', 'Afrikaans', 'Afrikaans', 0);
INSERT INTO `ref_languages` VALUES (2, 'am', 'Amharic', 'አማርኛ', 0);
INSERT INTO `ref_languages` VALUES (3, 'ar', 'Arabic', 'العربية', 1);
INSERT INTO `ref_languages` VALUES (4, 'ay', 'Aymara', 'Aymar', 0);
INSERT INTO `ref_languages` VALUES (5, 'az', 'Azerbaijani', 'Azərbaycanca / آذربايجان', 0);
INSERT INTO `ref_languages` VALUES (6, 'be', 'Belarusian', 'Беларуская', 0);
INSERT INTO `ref_languages` VALUES (7, 'bg', 'Bulgarian', 'Български', 0);
INSERT INTO `ref_languages` VALUES (8, 'bi', 'Bislama', 'Bislama', 0);
INSERT INTO `ref_languages` VALUES (9, 'bn', 'Bengali', 'বাংলা', 0);
INSERT INTO `ref_languages` VALUES (10, 'bs', 'Bosnian', 'Bosanski', 0);
INSERT INTO `ref_languages` VALUES (11, 'ca', 'Catalan', 'Català', 0);
INSERT INTO `ref_languages` VALUES (12, 'ch', 'Chamorro', 'Chamoru', 0);
INSERT INTO `ref_languages` VALUES (13, 'cs', 'Czech', 'Čeština', 0);
INSERT INTO `ref_languages` VALUES (14, 'da', 'Danish', 'Dansk', 0);
INSERT INTO `ref_languages` VALUES (15, 'de', 'German', 'Deutsch', 0);
INSERT INTO `ref_languages` VALUES (16, 'dv', 'Divehi', 'ދިވެހިބަސް', 1);
INSERT INTO `ref_languages` VALUES (17, 'dz', 'Dzongkha', 'ཇོང་ཁ', 0);
INSERT INTO `ref_languages` VALUES (18, 'el', 'Greek', 'Ελληνικά', 0);
INSERT INTO `ref_languages` VALUES (19, 'en', 'English', 'English', 0);
INSERT INTO `ref_languages` VALUES (20, 'es', 'Spanish', 'Español', 0);
INSERT INTO `ref_languages` VALUES (21, 'et', 'Estonian', 'Eesti', 0);
INSERT INTO `ref_languages` VALUES (22, 'eu', 'Basque', 'Euskara', 0);
INSERT INTO `ref_languages` VALUES (23, 'fa', 'Persian', 'فارسی', 1);
INSERT INTO `ref_languages` VALUES (24, 'ff', 'Peul', 'Fulfulde', 0);
INSERT INTO `ref_languages` VALUES (25, 'fi', 'Finnish', 'Suomi', 0);
INSERT INTO `ref_languages` VALUES (26, 'fj', 'Fijian', 'Na Vosa Vakaviti', 0);
INSERT INTO `ref_languages` VALUES (27, 'fo', 'Faroese', 'Føroyskt', 0);
INSERT INTO `ref_languages` VALUES (28, 'fr', 'French', 'Français', 0);
INSERT INTO `ref_languages` VALUES (29, 'ga', 'Irish', 'Gaeilge', 0);
INSERT INTO `ref_languages` VALUES (30, 'gl', 'Galician', 'Galego', 0);
INSERT INTO `ref_languages` VALUES (31, 'gn', 'Guarani', 'Avañe\'ẽ', 0);
INSERT INTO `ref_languages` VALUES (32, 'gv', 'Manx', 'Gaelg', 0);
INSERT INTO `ref_languages` VALUES (33, 'he', 'Hebrew', 'עברית', 1);
INSERT INTO `ref_languages` VALUES (34, 'hi', 'Hindi', 'हिन्दी', 0);
INSERT INTO `ref_languages` VALUES (35, 'hr', 'Croatian', 'Hrvatski', 0);
INSERT INTO `ref_languages` VALUES (36, 'ht', 'Haitian', 'Krèyol ayisyen', 0);
INSERT INTO `ref_languages` VALUES (37, 'hu', 'Hungarian', 'Magyar', 0);
INSERT INTO `ref_languages` VALUES (38, 'hy', 'Armenian', 'Հայերեն', 0);
INSERT INTO `ref_languages` VALUES (39, 'id', 'Indonesian', 'Bahasa Indonesia', 0);
INSERT INTO `ref_languages` VALUES (40, 'is', 'Icelandic', 'Íslenska', 0);
INSERT INTO `ref_languages` VALUES (41, 'it', 'Italian', 'Italiano', 0);
INSERT INTO `ref_languages` VALUES (42, 'ja', 'Japanese', '日本語', 0);
INSERT INTO `ref_languages` VALUES (43, 'ka', 'Georgian', 'ქართული', 0);
INSERT INTO `ref_languages` VALUES (44, 'kg', 'Kongo', 'KiKongo', 0);
INSERT INTO `ref_languages` VALUES (45, 'kk', 'Kazakh', 'Қазақша', 0);
INSERT INTO `ref_languages` VALUES (46, 'kl', 'Greenlandic', 'Kalaallisut', 0);
INSERT INTO `ref_languages` VALUES (47, 'km', 'Cambodian', 'ភាសាខ្មែរ', 0);
INSERT INTO `ref_languages` VALUES (48, 'ko', 'Korean', '한국어', 0);
INSERT INTO `ref_languages` VALUES (49, 'ku', 'Kurdish', 'Kurdî / كوردی', 1);
INSERT INTO `ref_languages` VALUES (50, 'ky', 'Kyrgyz', 'Кыргызча', 0);
INSERT INTO `ref_languages` VALUES (51, 'la', 'Latin', 'Latina', 0);
INSERT INTO `ref_languages` VALUES (52, 'lb', 'Luxembourgish', 'Lëtzebuergesch', 0);
INSERT INTO `ref_languages` VALUES (53, 'ln', 'Lingala', 'Lingála', 0);
INSERT INTO `ref_languages` VALUES (54, 'lo', 'Laotian', 'ລາວ / Pha xa lao', 0);
INSERT INTO `ref_languages` VALUES (55, 'lt', 'Lithuanian', 'Lietuvių', 0);
INSERT INTO `ref_languages` VALUES (56, 'lu', 'Luba-Katanga', 'Tshiluba', 0);
INSERT INTO `ref_languages` VALUES (57, 'lv', 'Latvian', 'Latviešu', 0);
INSERT INTO `ref_languages` VALUES (58, 'mg', 'Malagasy', 'Malagasy', 0);
INSERT INTO `ref_languages` VALUES (59, 'mh', 'Marshallese', 'Kajin Majel / Ebon', 0);
INSERT INTO `ref_languages` VALUES (60, 'mi', 'Maori', 'Māori', 0);
INSERT INTO `ref_languages` VALUES (61, 'mk', 'Macedonian', 'Македонски', 0);
INSERT INTO `ref_languages` VALUES (62, 'mn', 'Mongolian', 'Монгол', 0);
INSERT INTO `ref_languages` VALUES (63, 'ms', 'Malay', 'Bahasa Melayu', 0);
INSERT INTO `ref_languages` VALUES (64, 'mt', 'Maltese', 'bil-Malti', 0);
INSERT INTO `ref_languages` VALUES (65, 'my', 'Burmese', 'မြန်မာစာ', 0);
INSERT INTO `ref_languages` VALUES (66, 'na', 'Nauruan', 'Dorerin Naoero', 0);
INSERT INTO `ref_languages` VALUES (67, 'nb', 'Norwegian Bokmål', 'Norsk bokmål', 0);
INSERT INTO `ref_languages` VALUES (68, 'nd', 'North Ndebele', 'Sindebele', 0);
INSERT INTO `ref_languages` VALUES (69, 'ne', 'Nepali', 'नेपाली', 0);
INSERT INTO `ref_languages` VALUES (70, 'nl', 'Dutch', 'Nederlands', 0);
INSERT INTO `ref_languages` VALUES (71, 'nn', 'Norwegian Nynorsk', 'Norsk nynorsk', 0);
INSERT INTO `ref_languages` VALUES (72, 'no', 'Norwegian', 'Norsk', 0);
INSERT INTO `ref_languages` VALUES (73, 'nr', 'South Ndebele', 'isiNdebele', 0);
INSERT INTO `ref_languages` VALUES (74, 'ny', 'Chichewa', 'Chi-Chewa', 0);
INSERT INTO `ref_languages` VALUES (75, 'oc', 'Occitan', 'Occitan', 0);
INSERT INTO `ref_languages` VALUES (76, 'pa', 'Panjabi / Punjabi', 'ਪੰਜਾਬੀ / पंजाबी / پنجابي', 0);
INSERT INTO `ref_languages` VALUES (77, 'pl', 'Polish', 'Polski', 0);
INSERT INTO `ref_languages` VALUES (78, 'ps', 'Pashto', 'پښتو', 1);
INSERT INTO `ref_languages` VALUES (79, 'pt', 'Portuguese', 'Português', 0);
INSERT INTO `ref_languages` VALUES (80, 'qu', 'Quechua', 'Runa Simi', 0);
INSERT INTO `ref_languages` VALUES (81, 'rn', 'Kirundi', 'Kirundi', 0);
INSERT INTO `ref_languages` VALUES (82, 'ro', 'Romanian', 'Română', 0);
INSERT INTO `ref_languages` VALUES (83, 'ru', 'Russian', 'Русский', 0);
INSERT INTO `ref_languages` VALUES (84, 'rw', 'Rwandi', 'Kinyarwandi', 0);
INSERT INTO `ref_languages` VALUES (85, 'sg', 'Sango', 'Sängö', 0);
INSERT INTO `ref_languages` VALUES (86, 'si', 'Sinhalese', 'සිංහල', 0);
INSERT INTO `ref_languages` VALUES (87, 'sk', 'Slovak', 'Slovenčina', 0);
INSERT INTO `ref_languages` VALUES (88, 'sl', 'Slovenian', 'Slovenščina', 0);
INSERT INTO `ref_languages` VALUES (89, 'sm', 'Samoan', 'Gagana Samoa', 0);
INSERT INTO `ref_languages` VALUES (90, 'sn', 'Shona', 'chiShona', 0);
INSERT INTO `ref_languages` VALUES (91, 'so', 'Somalia', 'Soomaaliga', 0);
INSERT INTO `ref_languages` VALUES (92, 'sq', 'Albanian', 'Shqip', 0);
INSERT INTO `ref_languages` VALUES (93, 'sr', 'Serbian', 'Српски', 0);
INSERT INTO `ref_languages` VALUES (94, 'ss', 'Swati', 'SiSwati', 0);
INSERT INTO `ref_languages` VALUES (95, 'st', 'Southern Sotho', 'Sesotho', 0);
INSERT INTO `ref_languages` VALUES (96, 'sv', 'Swedish', 'Svenska', 0);
INSERT INTO `ref_languages` VALUES (97, 'sw', 'Swahili', 'Kiswahili', 0);
INSERT INTO `ref_languages` VALUES (98, 'ta', 'Tamil', 'தமிழ்', 0);
INSERT INTO `ref_languages` VALUES (99, 'tg', 'Tajik', 'Тоҷикӣ', 0);
INSERT INTO `ref_languages` VALUES (100, 'th', 'Thai', 'ไทย / Phasa Thai', 0);
INSERT INTO `ref_languages` VALUES (101, 'ti', 'Tigrinya', 'ትግርኛ', 0);
INSERT INTO `ref_languages` VALUES (102, 'tk', 'Turkmen', 'Туркмен / تركمن', 0);
INSERT INTO `ref_languages` VALUES (103, 'tn', 'Tswana', 'Setswana', 0);
INSERT INTO `ref_languages` VALUES (104, 'to', 'Tonga', 'Lea Faka-Tonga', 0);
INSERT INTO `ref_languages` VALUES (105, 'tr', 'Turkish', 'Türkçe', 0);
INSERT INTO `ref_languages` VALUES (106, 'ts', 'Tsonga', 'Xitsonga', 0);
INSERT INTO `ref_languages` VALUES (107, 'uk', 'Ukrainian', 'Українська', 0);
INSERT INTO `ref_languages` VALUES (108, 'ur', 'Urdu', 'اردو', 1);
INSERT INTO `ref_languages` VALUES (109, 'uz', 'Uzbek', 'Ўзбек', 0);
INSERT INTO `ref_languages` VALUES (110, 've', 'Venda', 'Tshivenḓa', 0);
INSERT INTO `ref_languages` VALUES (111, 'vi', 'Vietnamese', 'Tiếng Việt', 0);
INSERT INTO `ref_languages` VALUES (112, 'xh', 'Xhosa', 'isiXhosa', 0);
INSERT INTO `ref_languages` VALUES (113, 'zh', 'Chinese', '中文', 0);
INSERT INTO `ref_languages` VALUES (114, 'zu', 'Zulu', 'isiZulu', 0);

-- ----------------------------
-- Table structure for ref_salaries
-- ----------------------------
DROP TABLE IF EXISTS `ref_salaries`;
CREATE TABLE `ref_salaries`  (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_salaries
-- ----------------------------
INSERT INTO `ref_salaries` VALUES (1, '<=  RP 500.000');
INSERT INTO `ref_salaries` VALUES (2, 'RP 500.001 - RP 1.000.000');
INSERT INTO `ref_salaries` VALUES (3, 'RP 1.000.001 - RP 2.000.000');
INSERT INTO `ref_salaries` VALUES (4, 'RP 2.000.001 - RP 3.000.000');
INSERT INTO `ref_salaries` VALUES (5, 'RP 3.000.001 - RP 5.000.000');
INSERT INTO `ref_salaries` VALUES (6, '> RP 5.000.000');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'admin', 'web', '2024-03-02 14:48:07', '2024-03-02 14:48:07');
INSERT INTO `roles` VALUES (2, 'user', 'web', '2024-08-26 15:19:09', '2024-08-26 15:19:12');

-- ----------------------------
-- Table structure for schedule_post
-- ----------------------------
DROP TABLE IF EXISTS `schedule_post`;
CREATE TABLE `schedule_post`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint UNSIGNED NOT NULL,
  `schedule_on` date NOT NULL,
  `timepicker` time NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of schedule_post
-- ----------------------------
INSERT INTO `schedule_post` VALUES (1, 1792427062530363, '2024-03-04', '16:55:00', '2024-03-04 15:21:07', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (2, 1792427062530363, '2024-03-05', '12:50:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (3, 1792427062530363, '2024-03-05', '14:17:00', '2024-03-05 14:13:55', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (4, 1792427062530363, '2024-03-05', '14:24:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (5, 1792427062530363, '2024-03-05', '14:24:00', '2024-03-05 14:16:01', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (6, 1792427062530363, '2024-03-05', '14:28:00', '2024-03-05 14:26:44', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (7, 1792427062530363, '2024-03-05', '14:37:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (8, 1792427062530363, '2024-03-05', '14:37:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (9, 1792427062530363, '2024-03-05', '14:37:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (10, 1792427062530363, '2024-03-05', '14:37:00', '2024-03-05 14:27:06', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (11, 1792427062530363, '2024-03-13', '16:55:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (12, 1792427062530363, '2024-03-13', '16:55:00', '2024-03-12 16:54:39', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (13, 1792427062530363, '2024-03-12', '17:04:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (14, 1792427062530363, '2024-03-12', '17:04:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (15, 1792427062530363, '2024-03-12', '17:04:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (16, 1792427062530363, '2024-03-12', '17:04:00', '2024-03-12 16:54:59', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (17, 1792427062530363, '2024-03-12', '17:05:00', '2024-03-12 16:55:23', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (18, 1792427062530363, '2024-03-12', '17:06:00', '2024-03-12 16:56:23', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (19, 1792470458501574, '2024-03-12', '21:10:00', '2024-03-12 21:01:56', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (20, 1792427062530363, '2024-03-16', '21:02:00', '2024-03-12 21:01:48', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (21, 1792470458501574, '2024-03-16', '21:03:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (22, 1792427062530363, '2024-03-14', '21:03:00', '2024-03-12 21:02:38', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (23, 1792470458501574, '2024-03-14', '21:03:00', '2024-03-12 21:02:43', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (24, 1792470458501574, '2024-03-12', '21:26:00', '2024-03-12 21:17:27', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (25, 1792470458501574, '2024-03-12', '21:28:00', NULL, 1, NULL, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (26, 1792470458501574, '2024-03-12', '21:28:00', '2024-03-12 21:23:19', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (27, 1792470458501574, '2024-03-12', '21:34:00', '2024-03-12 21:24:37', 1, 1, 1, NULL, NULL);
INSERT INTO `schedule_post` VALUES (28, 1792427062530363, '2024-05-17', '08:34:00', '2024-05-02 08:33:29', 1, 1, 1, NULL, NULL);

-- ----------------------------
-- Table structure for signupuser
-- ----------------------------
DROP TABLE IF EXISTS `signupuser`;
CREATE TABLE `signupuser`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint NULL DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of signupuser
-- ----------------------------
INSERT INTO `signupuser` VALUES (1, 3, 'test', 'oke', 'testoke@gmail.com', 892323323, '2024-08-26 15:51:16', '2024-08-26 15:51:16', NULL);

-- ----------------------------
-- Table structure for social_account
-- ----------------------------
DROP TABLE IF EXISTS `social_account`;
CREATE TABLE `social_account`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `provider_id` int NULL DEFAULT NULL,
  `provided_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of social_account
-- ----------------------------

-- ----------------------------
-- Table structure for subnews
-- ----------------------------
DROP TABLE IF EXISTS `subnews`;
CREATE TABLE `subnews`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subnews
-- ----------------------------
INSERT INTO `subnews` VALUES (1, 'agustinus2h@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `subnews` VALUES (2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for suplliers
-- ----------------------------
DROP TABLE IF EXISTS `suplliers`;
CREATE TABLE `suplliers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` tinyint NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100009 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of suplliers
-- ----------------------------

-- ----------------------------
-- Table structure for suppliers_meta
-- ----------------------------
DROP TABLE IF EXISTS `suppliers_meta`;
CREATE TABLE `suppliers_meta`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` int UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `suppliers_meta_supplier_id_key_unique`(`supplier_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `suppliers_meta_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suplliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of suppliers_meta
-- ----------------------------

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1792427173198039 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tags
-- ----------------------------
INSERT INTO `tags` VALUES (1792427173198038, 'Berita Tag', 'berita-tag', 'fas fa-memory', NULL, 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for testimonial_form
-- ----------------------------
DROP TABLE IF EXISTS `testimonial_form`;
CREATE TABLE `testimonial_form`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `testimoni` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `feedback` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of testimonial_form
-- ----------------------------
INSERT INTO `testimonial_form` VALUES (1, 'Robet', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php1CC4.tmp', 'oke', 4, '2024-08-12 11:39:28', '2024-08-12 11:39:28', NULL);
INSERT INTO `testimonial_form` VALUES (2, 'Robert', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\phpD7F9.tmp', 'oke', 4, '2024-08-12 11:40:16', '2024-08-12 11:40:16', NULL);
INSERT INTO `testimonial_form` VALUES (3, 'RRR', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php6BC0.tmp', 'oke', 4, '2024-08-12 11:40:54', '2024-08-12 11:40:54', NULL);
INSERT INTO `testimonial_form` VALUES (4, 'Oke', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php1F44.tmp', 'test', 4, '2024-08-12 11:41:40', '2024-08-12 11:41:40', NULL);
INSERT INTO `testimonial_form` VALUES (5, 'Robert', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\phpE087.tmp', 'Buagus sekali', 4, '2024-08-14 10:46:00', '2024-08-14 10:46:00', NULL);
INSERT INTO `testimonial_form` VALUES (6, 'Roberts', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php9403.tmp', 'oke', 4, '2024-08-14 10:52:13', '2024-08-14 10:52:13', NULL);
INSERT INTO `testimonial_form` VALUES (7, 'Robert ', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php5E00.tmp', 'Testing', 4, '2024-08-14 11:49:53', '2024-08-14 11:49:53', NULL);
INSERT INTO `testimonial_form` VALUES (8, 'AFHHHH ROOBEERT', 'C:\\Users\\PEMAD\\AppData\\Local\\Temp\\php4811.tmp', 'oke', 4, '2024-08-14 16:40:20', '2024-08-14 16:40:20', NULL);

-- ----------------------------
-- Table structure for tool
-- ----------------------------
DROP TABLE IF EXISTS `tool`;
CREATE TABLE `tool`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_asset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_sub` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_goods` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year_buy` int NULL DEFAULT NULL,
  `qty` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `origin` int NULL DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `address_primary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `conditional` int NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool
-- ----------------------------
INSERT INTO `tool` VALUES (7, 'cxvxv', '1', '1', '1', '2', 1, 'erwre', 2344, '0', NULL, 1, 'te', 'ww', 1, NULL, '2024-06-07 10:22:40', '2024-07-15 11:27:45');
INSERT INTO `tool` VALUES (9, 'Test asset', '1', '1', '1', '1', 2000, 'test', 2022, '2', NULL, 1, 'test', 'oke', 1, NULL, '2024-06-07 10:25:08', '2024-07-15 11:36:49');
INSERT INTO `tool` VALUES (10, 'tessss ttt', '1', '2', '1', '2', 22233, 'wwwwwwaa', 2025, '3', '66699', 2, 'oke test', 'test tttt', 2, NULL, '2024-06-07 10:26:26', '2024-06-24 13:38:55');
INSERT INTO `tool` VALUES (11, 'Test123', '1', '1', '1', '5', 5, 'oke', 2024, '2', '5000', 1, 'test', 'Oke oce', 1, NULL, '2024-07-12 12:14:42', '2024-07-12 12:14:42');
INSERT INTO `tool` VALUES (12, 'Testers', '1', '1', '1', '1', 4000, 'oke', 2024, '2', '4000', 1, 'tes', 'oke', 1, NULL, '2024-07-12 13:28:12', '2024-07-12 13:28:12');
INSERT INTO `tool` VALUES (13, 'Oke', '1', '1', '2', '9', 6000, 'rt', 2024, '2', '12000', 1, 'tes', 'oke', 1, NULL, '2024-07-12 13:29:34', '2024-07-12 13:35:35');

-- ----------------------------
-- Table structure for tool_item_mutation
-- ----------------------------
DROP TABLE IF EXISTS `tool_item_mutation`;
CREATE TABLE `tool_item_mutation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `unit_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `qty_mutation` int NULL DEFAULT NULL,
  `qty_src` int NULL DEFAULT NULL,
  `tool_id` int NULL DEFAULT NULL,
  `mutation_id` int NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_item_mutation
-- ----------------------------
INSERT INTO `tool_item_mutation` VALUES (19, '9', '3', 1, 1, 9, 6, '2024-06-21 14:45:52', '2024-06-21 14:45:52', NULL);
INSERT INTO `tool_item_mutation` VALUES (20, '7', '2', 1, 1, 7, 11, '2024-06-24 09:27:45', '2024-06-24 09:27:45', NULL);
INSERT INTO `tool_item_mutation` VALUES (21, '10', '1', 1, 2, 10, 11, '2024-06-24 09:27:45', '2024-06-24 09:27:45', NULL);

-- ----------------------------
-- Table structure for tool_lend
-- ----------------------------
DROP TABLE IF EXISTS `tool_lend`;
CREATE TABLE `tool_lend`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sk` date NULL DEFAULT NULL,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_lend
-- ----------------------------
INSERT INTO `tool_lend` VALUES (37, 'INV/PEMINJAMAN/TOOL/283034/2024', '2024-07-11', 'file_tool_lend/668f5c9d9dd8e/x6HX4fblZcynoh5B3uMl2BqbnOvi8G-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-11 11:16:29', '2024-07-11 11:16:29');
INSERT INTO `tool_lend` VALUES (39, 'INV/PEMINJAMAN/TOOL/792794/2024', '2024-07-11', 'file_tool_lend/668fb67e78b29/Arm8yllEVjGF3a3AdkaClUMGKArh6x-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-11 17:39:58', '2024-07-11 17:39:58');
INSERT INTO `tool_lend` VALUES (48, 'INV/PEMINJAMAN/TOOL/172183/2024', '2024-07-11', 'file_tool_lend/668fb7a1a74f0/nyJvE8HhiANJuyGydpfhsWJsNySFCp-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-11 17:44:49', '2024-07-11 17:44:49');
INSERT INTO `tool_lend` VALUES (49, 'INV/PEMINJAMAN/TOOL/905669/2024', '2024-07-12', 'file_tool_lend/668fb7e4f2442/u5VoOpxQjnXtkDihjxJK1exLUtKYGO-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-11 17:45:56', '2024-07-11 17:45:56');

-- ----------------------------
-- Table structure for tool_lend_item
-- ----------------------------
DROP TABLE IF EXISTS `tool_lend_item`;
CREATE TABLE `tool_lend_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `tool_lend_id` int NULL DEFAULT NULL,
  `tool_id` int NULL DEFAULT NULL,
  `forheit_price` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_slice` decimal(20, 2) NULL DEFAULT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_back` int NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_lend_item
-- ----------------------------
INSERT INTO `tool_lend_item` VALUES (20, 37, 7, 2000.00, 5000.00, '2024-07-01', '2024-07-10', 1, 1, '2024-07-11 11:16:29', '2024-07-11 17:35:24', NULL);
INSERT INTO `tool_lend_item` VALUES (21, 37, 9, 3000.00, NULL, '2024-07-08', '2024-07-09', NULL, 1, '2024-07-11 11:16:29', '2024-07-11 11:16:29', NULL);
INSERT INTO `tool_lend_item` VALUES (22, 39, 7, 3400.00, NULL, '2024-07-11', '2024-07-12', NULL, 1, '2024-07-11 17:39:58', '2024-07-11 17:39:58', NULL);
INSERT INTO `tool_lend_item` VALUES (30, 48, 7, 2000.00, 5000.00, '2024-07-11', '2024-07-17', 1, 1, '2024-07-11 17:44:49', '2024-07-11 17:45:22', NULL);
INSERT INTO `tool_lend_item` VALUES (31, 49, 7, 33.00, NULL, '2024-07-11', '2024-07-12', NULL, 2, '2024-07-11 17:45:56', '2024-07-11 17:45:56', NULL);

-- ----------------------------
-- Table structure for tool_mutation
-- ----------------------------
DROP TABLE IF EXISTS `tool_mutation`;
CREATE TABLE `tool_mutation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_sk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sk` datetime NULL DEFAULT NULL,
  `number_sk` int NULL DEFAULT NULL,
  `is_return` int NULL DEFAULT NULL,
  `date_mutation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_mutation
-- ----------------------------
INSERT INTO `tool_mutation` VALUES (6, 'file_mutation_tool/66752fb0941d9/fDUa7Z4auWQhf26bvT0b53KUAc8xfy-metac2FtcGxlLnBkZg==-.pdf', '2024-06-21 00:00:00', 126690, NULL, '2024-06-21', '2024-06-21 14:45:52', '2024-06-21 14:45:52', NULL);
INSERT INTO `tool_mutation` VALUES (11, 'file_mutation_tool/6678d9a104e7a/1dhTETb5Eoe7sUwIneBkeQCfvyATqu-metac2FtcGxlLnBkZg==-.pdf', '2024-06-25 00:00:00', 823560, 1, '2024-06-26', '2024-06-24 09:27:45', '2024-06-24 13:38:55', NULL);

-- ----------------------------
-- Table structure for tool_sell
-- ----------------------------
DROP TABLE IF EXISTS `tool_sell`;
CREATE TABLE `tool_sell`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sell` date NULL DEFAULT NULL,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_sell
-- ----------------------------
INSERT INTO `tool_sell` VALUES (7, 'INV/PENJUALAN/TOOL/404674/2024', NULL, 'file_tool_sell/6669216065dc2/dFq4by9gX1zlvsXqWCwGab1Jq6Mu7x-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-06-12 11:17:36', '2024-06-12 11:50:27');
INSERT INTO `tool_sell` VALUES (8, 'INV/PENJUALAN/TOOL/175608/2024', NULL, 'file_tool_sell/666922267917d/7FMU92GbI8dxkWCyWeJDfK7p2AXqgh-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-06-12 11:20:54', '2024-06-12 11:20:54');
INSERT INTO `tool_sell` VALUES (9, 'INV/PENJUALAN/TOOL/556550/2024', '2024-06-12', 'http://embark.test/uploads/file_tool_sell/6669227a7956d/0fln05i1zUMuJ88Z986BJZthErYqSW-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-06-12 11:22:18', '2024-06-12 11:42:34');
INSERT INTO `tool_sell` VALUES (10, 'INV/PENJUALAN/TOOL/280311/2024', '2024-07-15', 'file_tool_sell/6694a5412ae9c/yzL2NDP47OcVcGh6CeX9EZdWspySJO-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-15 11:27:45', '2024-07-15 11:27:45');
INSERT INTO `tool_sell` VALUES (11, 'INV/PENJUALAN/TOOL/461976/2024', '2024-07-15', 'file_tool_sell/6694a76100af2/3uQvis7selrfyHK3QpWzNbDslyQsEo-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-07-15 11:36:49', '2024-07-15 11:36:49');

-- ----------------------------
-- Table structure for tool_sell_item
-- ----------------------------
DROP TABLE IF EXISTS `tool_sell_item`;
CREATE TABLE `tool_sell_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `tool_sell_id` int NULL DEFAULT NULL,
  `tool_id` int NULL DEFAULT NULL,
  `tool_price` decimal(20, 2) NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tool_sell_item
-- ----------------------------
INSERT INTO `tool_sell_item` VALUES (8, 7, 7, 3000.00, NULL, '2024-06-12 11:17:36', '2024-06-12 11:50:27', NULL);
INSERT INTO `tool_sell_item` VALUES (9, 8, 7, 3400.00, NULL, '2024-06-12 11:20:54', '2024-06-12 11:20:54', NULL);
INSERT INTO `tool_sell_item` VALUES (11, 9, 9, 5000.00, NULL, '2024-06-12 11:42:34', '2024-06-12 11:42:34', NULL);
INSERT INTO `tool_sell_item` VALUES (12, 10, 7, 2000.00, 1, '2024-07-15 11:27:45', '2024-07-15 11:27:45', NULL);
INSERT INTO `tool_sell_item` VALUES (13, 11, 9, 2000.00, 1, '2024-07-15 11:36:49', '2024-07-15 11:36:49', NULL);

-- ----------------------------
-- Table structure for transaction_extras
-- ----------------------------
DROP TABLE IF EXISTS `transaction_extras`;
CREATE TABLE `transaction_extras`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_extras` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_extras
-- ----------------------------
INSERT INTO `transaction_extras` VALUES (15, 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', '1', '2024-08-08 15:43:57', '2024-08-08 15:43:57', NULL);
INSERT INTO `transaction_extras` VALUES (16, 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', '2', '2024-08-08 18:15:12', '2024-08-08 18:15:12', NULL);
INSERT INTO `transaction_extras` VALUES (17, 'H3nJWERYCXY6Kx1r9fulpgyFrlXBMoJouJfyjROi', '1', '2024-08-09 11:29:20', '2024-08-09 11:29:20', NULL);
INSERT INTO `transaction_extras` VALUES (18, 'uozTmn5wlVYaE3e1j1QptoZ7Ni3Ou5Zk1fwbm6SU', '1', '2024-08-13 10:44:32', '2024-08-13 10:44:32', NULL);
INSERT INTO `transaction_extras` VALUES (19, '73', '1', '2024-08-13 11:07:37', '2024-08-13 11:07:37', NULL);
INSERT INTO `transaction_extras` VALUES (20, '73', '3', '2024-08-13 11:07:37', '2024-08-13 11:07:37', NULL);

-- ----------------------------
-- Table structure for transaction_facility_has_person
-- ----------------------------
DROP TABLE IF EXISTS `transaction_facility_has_person`;
CREATE TABLE `transaction_facility_has_person`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `identity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `phone` bigint UNSIGNED NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_in` timestamp NULL DEFAULT NULL,
  `date_out` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_facility_has_person
-- ----------------------------
INSERT INTO `transaction_facility_has_person` VALUES (73, 'Tester projects', '3123131323131313', 882342334, 'test11@gmail.com', '2024-08-13 11:19:59', '2024-08-14 11:20:03', '2024-08-13 11:07:37', '2024-08-13 11:07:37', NULL);

-- ----------------------------
-- Table structure for transaction_form
-- ----------------------------
DROP TABLE IF EXISTS `transaction_form`;
CREATE TABLE `transaction_form`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transaction_form
-- ----------------------------

-- ----------------------------
-- Table structure for transaction_room
-- ----------------------------
DROP TABLE IF EXISTS `transaction_room`;
CREATE TABLE `transaction_room`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `date_in` date NULL DEFAULT NULL,
  `date_out` date NULL DEFAULT NULL,
  `cupon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_room
-- ----------------------------
INSERT INTO `transaction_room` VALUES (39, 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', 2, '2024-08-08', '2024-08-09', NULL, '2024-08-08 07:58:16', '2024-08-08 07:58:16', NULL);
INSERT INTO `transaction_room` VALUES (40, 'H3nJWERYCXY6Kx1r9fulpgyFrlXBMoJouJfyjROi', 2, '2024-08-11', '2024-08-13', NULL, '2024-08-09 08:06:07', '2024-08-09 10:03:44', NULL);
INSERT INTO `transaction_room` VALUES (41, 'FSjyvW4JlgKUg9afe4DJ1y6kX2KQxSLjNE6WjDQn', 2, '2024-08-21', '2024-08-24', NULL, '2024-08-12 08:59:22', '2024-08-12 08:59:22', NULL);
INSERT INTO `transaction_room` VALUES (42, 'uozTmn5wlVYaE3e1j1QptoZ7Ni3Ou5Zk1fwbm6SU', 2, '2024-08-13', '2024-08-14', NULL, '2024-08-13 10:44:21', '2024-08-13 10:44:21', NULL);

-- ----------------------------
-- Table structure for transaction_room_has_facility
-- ----------------------------
DROP TABLE IF EXISTS `transaction_room_has_facility`;
CREATE TABLE `transaction_room_has_facility`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `identity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `phone` bigint UNSIGNED NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 70 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_room_has_facility
-- ----------------------------

-- ----------------------------
-- Table structure for transaction_room_has_person
-- ----------------------------
DROP TABLE IF EXISTS `transaction_room_has_person`;
CREATE TABLE `transaction_room_has_person`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `identity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `phone` bigint UNSIGNED NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 71 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_room_has_person
-- ----------------------------
INSERT INTO `transaction_room_has_person` VALUES (66, 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', 'agustinus', '3123131323131311', 9754545345, 'test10@gmail.com', '2024-08-08 18:16:22', '2024-08-08 18:16:22', NULL);
INSERT INTO `transaction_room_has_person` VALUES (69, 'H3nJWERYCXY6Kx1r9fulpgyFrlXBMoJouJfyjROi', 'Robert Test Lagi', '3123131323131311', 8923232323, 'ags@gmail.com', '2024-08-09 13:26:37', '2024-08-09 13:26:37', NULL);
INSERT INTO `transaction_room_has_person` VALUES (70, 'uozTmn5wlVYaE3e1j1QptoZ7Ni3Ou5Zk1fwbm6SU', 'test bookingss', '3123131323131315', 8932332332, 'bookingsz@gmail.com', '2024-08-13 10:45:35', '2024-08-13 10:45:35', NULL);

-- ----------------------------
-- Table structure for transaction_room_has_rooms
-- ----------------------------
DROP TABLE IF EXISTS `transaction_room_has_rooms`;
CREATE TABLE `transaction_room_has_rooms`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `person_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `people` int NULL DEFAULT NULL,
  `room_id` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transaction_room_has_rooms
-- ----------------------------
INSERT INTO `transaction_room_has_rooms` VALUES (39, '385448352', 'exZ2JgBWQQaeK8Trofqdmj1oKh9YwcvW3RtY1wUA', 4, 1, '2024-08-07 17:33:42', '2024-08-07 17:33:42', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (40, '1515187119', 'exZ2JgBWQQaeK8Trofqdmj1oKh9YwcvW3RtY1wUA', 2, 2, '2024-08-07 17:34:36', '2024-08-07 17:34:36', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (46, '1202159640', 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', 4, 3, '2024-08-08 15:24:31', '2024-08-08 15:24:31', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (47, '1425880802', 'wxkc3dOW3OIiUQKyL4klVGVlJ5fS9kIHiJMp4RIQ', 2, 4, '2024-08-08 18:15:07', '2024-08-08 18:15:07', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (48, '191610568', 'H3nJWERYCXY6Kx1r9fulpgyFrlXBMoJouJfyjROi', 2, 1, '2024-08-09 11:29:14', '2024-08-09 11:29:14', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (49, '915366580', 'FSjyvW4JlgKUg9afe4DJ1y6kX2KQxSLjNE6WjDQn', 2, 1, '2024-08-12 08:59:27', '2024-08-12 08:59:27', NULL);
INSERT INTO `transaction_room_has_rooms` VALUES (50, '750779566', 'uozTmn5wlVYaE3e1j1QptoZ7Ni3Ou5Zk1fwbm6SU', 2, 1, '2024-08-13 10:44:27', '2024-08-13 10:44:27', NULL);

-- ----------------------------
-- Table structure for user_information
-- ----------------------------
DROP TABLE IF EXISTS `user_information`;
CREATE TABLE `user_information`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `invoice_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` int NULL DEFAULT NULL,
  `account_status` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_information
-- ----------------------------
INSERT INTO `user_information` VALUES (1, '2071724308144', 'robert', 'haryanto', 'agustinus2h@gmail.com', '089923233223', 1, 0, '2024-08-23 13:32:35', '2024-08-23 16:07:33', NULL);
INSERT INTO `user_information` VALUES (2, '1491724647004', 'agustinus', 'hr', 'agustinus2h@gmail.com', '123323232', 1, 0, '2024-08-26 11:36:44', '2024-08-26 13:22:12', NULL);

-- ----------------------------
-- Table structure for user_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `modelable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_logs_modelable_type_modelable_id_index`(`modelable_type` ASC, `modelable_id` ASC) USING BTREE,
  INDEX `user_logs_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_logs
-- ----------------------------
INSERT INTO `user_logs` VALUES (1, 1010101, 'membuat peran baru Client <strong>[ID: 5]</strong>', 'App\\Models\\Role', 5, '0.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0', '2024-07-10 08:06:56', '2024-07-10 08:06:56');
INSERT INTO `user_logs` VALUES (2, 1010101, 'memperbarui hak akses peran Client <strong>[ID: 5]</strong>', 'App\\Models\\Role', 5, '0.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0', '2024-07-10 08:15:36', '2024-07-10 08:15:36');
INSERT INTO `user_logs` VALUES (3, 1010101, 'membuat pengguna baru dengan nama Client 1 <strong>[ID: 1010106]</strong>', 'Modules\\Account\\Models\\User', 1010106, '0.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0', '2024-07-10 08:22:13', '2024-07-10 08:22:13');

-- ----------------------------
-- Table structure for user_metas
-- ----------------------------
DROP TABLE IF EXISTS `user_metas`;
CREATE TABLE `user_metas`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_metas_user_id_key_unique`(`user_id` ASC, `key` ASC) USING BTREE,
  CONSTRAINT `user_metas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_metas
-- ----------------------------
INSERT INTO `user_metas` VALUES (1, 1010101, 'profile_religion', '2', 'integer', '2024-05-29 15:34:37', '2024-05-29 15:34:37');

-- ----------------------------
-- Table structure for user_password_resets
-- ----------------------------
DROP TABLE IF EXISTS `user_password_resets`;
CREATE TABLE `user_password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `user_password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `user_id` int UNSIGNED NOT NULL,
  `role_id` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `user_id`) USING BTREE,
  INDEX `user_roles_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `app_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_roles
-- ----------------------------
INSERT INTO `user_roles` VALUES (1010101, 1);

-- ----------------------------
-- Table structure for user_tokens
-- ----------------------------
DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE `user_tokens`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_tokens_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `embark_id` int UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1010107 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1010101, 'Danang Saragih', 'admin', 'opradana@example.com', NULL, '$2y$10$/FE4264YFKcGQE4WgN22MuTzgLx0zeoj3If91z691J/JmQJFwjAYS', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `users` VALUES (1010102, 'Umar Pangestu S.Gz', 'kawaya.sihotang', 'xkurniawan@example.com', NULL, '$2y$10$AIFWoATDhTGSQEM5zOFWM.FaPLdKbsQVpK3usKbU0yLgXEtu.omqm', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `users` VALUES (1010103, 'Agnes Prastuti', 'emaryadi', 'santoso.ana@example.org', NULL, '$2y$10$E63gz32.LTCs34DFeSlB3uAen53Fz1LslztB4As1MPuPC/MzD8fzC', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `users` VALUES (1010104, 'Ani Haryanti M.M.', 'uhabibi', 'ivan.waluyo@example.com', NULL, '$2y$10$9RvGVPUxN7ng/KYRdZAqROTeNxeqpxqZabmsUwLs1JqIJDWHCNaUm', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `users` VALUES (1010105, 'Carla Palastri', 'mardhiyah.ade', 'mutia69@example.net', NULL, '$2y$10$iHzmqYYsESv/ocNqy/YPjOCTWyvFD7CSR0oMLe5opsufImoEuaHJ.', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-29 15:34:37', '2024-05-29 15:34:37');
INSERT INTO `users` VALUES (1010106, 'Client 1', 'maria.anggrea', 'maria.anggrea@example.com', NULL, '$2y$10$B9DKzMIZK7gaod7f5aUQNeelXRK/WGz9AUehvrcyQxG/cM6fMHoHe', NULL, 1010101, 1010101, NULL, NULL, NULL, '2024-07-10 08:22:13', '2024-07-10 08:22:13');

-- ----------------------------
-- Table structure for vehcile
-- ----------------------------
DROP TABLE IF EXISTS `vehcile`;
CREATE TABLE `vehcile`  (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_sub` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_goods` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `acquisition_cost` decimal(20, 2) NULL DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `acquisition_year` int NULL DEFAULT NULL,
  `qty` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `number_chassis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `number_machine` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `number_police` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bpkb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tax_date` date NULL DEFAULT NULL,
  `product_year` year NULL DEFAULT NULL,
  `identity_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `assurance` int NULL DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `address_primary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `right_of_user` int NULL DEFAULT NULL,
  `pricings_lend` decimal(20, 2) NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `moved_status` int NULL DEFAULT NULL,
  `moved_to` int NULL DEFAULT NULL,
  `moved_from` int NULL DEFAULT NULL,
  `is_lend` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vehcile
-- ----------------------------
INSERT INTO `vehcile` VALUES (11, 'Test', '6', '1', '29', '97', 'test', '20', 20000.00, NULL, 2024, '3', '111', '2', 'AD123D', 'test', 'test', '2024-06-08', 2024, '1123', 1, 'test', 'jlan oke', 1, NULL, NULL, '2024-06-07 15:44:43', '2024-06-19 11:52:37', 1, 2, 1, NULL);
INSERT INTO `vehcile` VALUES (12, 'Testda', '6', '1', '29', '98', 'testssss', '200', 100000.00, 'bsa', 2025, '3', '34', '12313', '243234', '435', 'biru', '2024-06-06', 2024, '123555', 2, 'tewss', 'sstttt', 2, NULL, NULL, '2024-06-07 15:46:37', '2024-07-11 17:56:56', 1, 2, 1, 1);
INSERT INTO `vehcile` VALUES (18, 'Test', '6', '2', '29', '97', 'test', '20', 20000.00, NULL, 2024, '3', '111', '2', 'AD123D', 'test', 'test', '2024-06-08', 2024, '1123', 1, 'test', 'jlan oke', 1, NULL, NULL, '2024-06-19 11:52:37', '2024-06-19 13:23:49', 1, 1, 2, NULL);
INSERT INTO `vehcile` VALUES (22, 'Test', '6', '1', '29', '97', 'test', '20', 20000.00, NULL, 2024, '3', '111', '2', 'AD123D', 'test', 'test', '2024-06-08', 2024, '1123', 1, 'test', 'jlan oke', 1, NULL, NULL, '2024-06-19 13:23:49', '2024-06-19 13:23:49', 0, NULL, NULL, NULL);
INSERT INTO `vehcile` VALUES (23, 'Testda', '6', '2', '29', '98', 'testssss', '200', 100000.00, 'bsa', 2025, '3', '34', '12313', '243234', '435', 'biru', '2024-06-06', 2024, '123555', 2, 'tewss', 'sstttt', 2, NULL, NULL, '2024-06-24 10:03:33', '2024-06-24 10:03:33', 0, NULL, NULL, NULL);
INSERT INTO `vehcile` VALUES (24, 'SuzukiRTX21', '6', '1', '29', '97', 'montor', '20', 400000.00, 'Besi', 2024, '1', '44', '22', 'AD 1 A', '57889', 'Hijau', '2024-07-10', 2024, '34434', 1, 'Test', 'jalan raya', 1, 5000.00, NULL, '2024-07-10 09:21:01', '2024-07-11 17:56:23', NULL, NULL, NULL, 1);
INSERT INTO `vehcile` VALUES (25, 'Test Montors', '6', '1', '29', '97', 'test', '2334', 30000.00, 'oke', 2024, NULL, '12345', '4455', 'AD 123 DE', 'DEF', 'hijau', '2024-07-12', 2019, '12345', 1, 'Test plane', 'jalan raya test', 1, 34444.00, NULL, '2024-07-12 14:01:31', '2024-07-12 14:13:28', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for vehcile_lend
-- ----------------------------
DROP TABLE IF EXISTS `vehcile_lend`;
CREATE TABLE `vehcile_lend`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `vehcile_id` int NULL DEFAULT NULL,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `forheit_price` decimal(20, 2) NULL DEFAULT NULL,
  `forheit_slice` decimal(20, 2) NULL DEFAULT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vehcile_lend
-- ----------------------------
INSERT INTO `vehcile_lend` VALUES (2, 24, 'file_lend_vehcile/668e04ec9a270/29noueWCcSKQA10Nvi2xY4L2MzvRN3-metac2FtcGxlLnBkZg==-.pdf', 20000.00, 20000.00, '2024-07-01', '2024-07-09', '2024-07-10 10:50:04', '2024-07-10 11:57:11', NULL);
INSERT INTO `vehcile_lend` VALUES (3, 24, 'file_lend_vehcile/668fba57616d2/2W8ZVk70fb56ny3ALpUBKOiPoDERTJ-metac2FtcGxlLnBkZg==-.pdf', 3000.00, NULL, '2024-07-08', '2024-07-10', '2024-07-11 17:56:23', '2024-07-11 17:56:23', NULL);
INSERT INTO `vehcile_lend` VALUES (4, 12, 'file_lend_vehcile/668fba7842822/1JkHYpOV3K2fNQ6hgq6NEhwkzsRjaa-metac2FtcGxlLnBkZg==-.pdf', 4000.00, NULL, '2024-07-15', '2024-07-19', '2024-07-11 17:56:56', '2024-07-11 17:56:56', NULL);

-- ----------------------------
-- Table structure for vehcile_mutation
-- ----------------------------
DROP TABLE IF EXISTS `vehcile_mutation`;
CREATE TABLE `vehcile_mutation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_sk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sk` datetime NULL DEFAULT NULL,
  `number_sk` int NULL DEFAULT NULL,
  `date_mutation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `vehcile_id` int NULL DEFAULT NULL,
  `unit_from` int NULL DEFAULT NULL,
  `unit_to` int NULL DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_return` int NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `vehcile_new_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vehcile_mutation
-- ----------------------------
INSERT INTO `vehcile_mutation` VALUES (4, 'file_mutation_vehcile/66726415e2ba6/xd8ipl4hkLXgsHY14hU7l1c6UjYRKA-metac2FtcGxlLnBkZg==-.pdf', '2024-06-19 00:00:00', 406938, '2024-06-19', 11, 1, 2, 'testing', 1, '2024-06-19 11:52:37', '2024-06-19 13:23:49', NULL, 18);
INSERT INTO `vehcile_mutation` VALUES (5, 'file_mutation_vehcile/6678e205095bb/P3XbPV2tBxG1IHoJ1TGav1v2Ox2G52-metac2FtcGxlLnBkZg==-.pdf', '2024-06-24 00:00:00', 740741, '2024-06-24', 12, 1, 2, 'test', NULL, '2024-06-24 10:03:33', '2024-06-24 10:03:33', NULL, 23);

-- ----------------------------
-- Table structure for vehcile_sell
-- ----------------------------
DROP TABLE IF EXISTS `vehcile_sell`;
CREATE TABLE `vehcile_sell`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `date_sell` date NULL DEFAULT NULL,
  `file_sk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vehcile_sell
-- ----------------------------
INSERT INTO `vehcile_sell` VALUES (6, 'INV/PENJUALAN/VEH/780601/2024', '2024-06-11', 'http://embark.test/uploads/file_vehcile/6667f396e4e88/k06Ml5NzeiXUiBtpOtRGUZ5tgIw0st-metac2FtcGxlLnBkZg==-.pdf', NULL, '2024-06-11 13:49:58', '2024-06-11 15:09:04');

-- ----------------------------
-- Table structure for vehcile_sell_item
-- ----------------------------
DROP TABLE IF EXISTS `vehcile_sell_item`;
CREATE TABLE `vehcile_sell_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `vehcile_sell_id` int NULL DEFAULT NULL,
  `vehcile_id` int NULL DEFAULT NULL,
  `vehcile_price` double NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vehcile_sell_item
-- ----------------------------
INSERT INTO `vehcile_sell_item` VALUES (1, 4, 1, NULL, '2024-06-11 13:38:44', '2024-06-11 13:38:44', NULL);
INSERT INTO `vehcile_sell_item` VALUES (2, 4, 2, NULL, '2024-06-11 13:38:44', '2024-06-11 13:38:44', NULL);
INSERT INTO `vehcile_sell_item` VALUES (3, 5, 1, 3000, '2024-06-11 13:45:44', '2024-06-11 13:45:44', NULL);
INSERT INTO `vehcile_sell_item` VALUES (4, 5, 2, 400, '2024-06-11 13:45:44', '2024-06-11 13:45:44', NULL);
INSERT INTO `vehcile_sell_item` VALUES (6, 6, 1, 3000, '2024-06-11 14:40:58', '2024-06-11 15:09:04', NULL);
INSERT INTO `vehcile_sell_item` VALUES (7, 6, 2, 5000, '2024-06-11 14:40:58', '2024-06-11 15:09:04', NULL);

SET FOREIGN_KEY_CHECKS = 1;
