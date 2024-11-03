/*
 Navicat Premium Data Transfer

 Source Server         : mydb
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3035005 (3.35.5)
 File Encoding         : 65001

 Date: 02/11/2024 17:59:20
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS "items";
CREATE TABLE "items" (
  "id" INTEGER,
  "base" TEXT,
  "category" TEXT,
  "subcategory" TEXT,
  "name" TEXT,
  "title" TEXT,
  "date" TEXT,
  "thumbnail" TEXT,
  "tag" TEXT,
  "content" TEXT,
  "images" TEXT,
  "type" integer,
  PRIMARY KEY ("id")
);

-- ----------------------------
-- Indexes structure for table items
-- ----------------------------
CREATE INDEX "cate"
ON "items" (
  "category" ASC,
  "subcategory" ASC
);
CREATE INDEX "date"
ON "items" (
  "date" DESC
);

PRAGMA foreign_keys = true;
