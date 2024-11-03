/*
 Navicat Premium Data Transfer

 Source Server         : mydb
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3035005 (3.35.5)
 File Encoding         : 65001

 Date: 02/11/2024 19:59:52
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for itemfavi
-- ----------------------------
DROP TABLE IF EXISTS "itemfavi";
CREATE TABLE "itemfavi" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "uId" INTEGER DEFAULT 0,
  "itemId" INTEGER DEFAULT 0,
  "datetime" text DEFAULT (datetime(CURRENT_TIMESTAMP,'localtime')),
  "expired" integer DEFAULT 0,
  CONSTRAINT "itemId" FOREIGN KEY ("itemId") REFERENCES "items" ("id") ON DELETE SET DEFAULT ON UPDATE SET DEFAULT
);

-- ----------------------------
-- Auto increment value for itemfavi
-- ----------------------------
UPDATE "sqlite_sequence" SET seq = 0 WHERE name = 'itemfavi';

-- ----------------------------
-- Indexes structure for table itemfavi
-- ----------------------------
CREATE INDEX "datetime"
ON "itemfavi" (
  "datetime" DESC
);
CREATE UNIQUE INDEX "itemId"
ON "itemfavi" (
  "itemId" ASC,
  "uId" ASC
);

PRAGMA foreign_keys = true;
