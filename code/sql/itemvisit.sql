/*
 Navicat Premium Data Transfer

 Source Server         : mydb
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3035005 (3.35.5)
 File Encoding         : 65001

 Date: 02/11/2024 20:49:29
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for itemvisit
-- ----------------------------
DROP TABLE IF EXISTS "itemvisit";
CREATE TABLE "itemvisit" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "uId" INTEGER DEFAULT 0,
  "itemId" INTEGER DEFAULT 0,
  "datetime" text DEFAULT (datetime(CURRENT_TIMESTAMP,'localtime')),
  "count" INTEGER DEFAULT 0
);

-- ----------------------------
-- Auto increment value for itemvisit
-- ----------------------------
UPDATE "sqlite_sequence" SET seq = 0 WHERE name = 'itemvisit';

-- ----------------------------
-- Indexes structure for table itemvisit
-- ----------------------------
CREATE INDEX "visitDatetime"
ON "itemvisit" (
  "datetime" DESC
);
CREATE UNIQUE INDEX "visitItemId"
ON "itemvisit" (
  "itemId" ASC,
  "uId" ASC
);

PRAGMA foreign_keys = true;
