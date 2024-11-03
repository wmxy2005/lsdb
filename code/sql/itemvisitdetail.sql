/*
 Navicat Premium Data Transfer

 Source Server         : mydb
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3035005 (3.35.5)
 File Encoding         : 65001

 Date: 02/11/2024 20:49:53
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for itemvisitdetail
-- ----------------------------
DROP TABLE IF EXISTS "itemvisitdetail";
CREATE TABLE "itemvisitdetail" (
  "itemvisitId" INTEGER NOT NULL,
  "datetime" text DEFAULT (datetime(CURRENT_TIMESTAMP,'localtime'))
);

-- ----------------------------
-- Indexes structure for table itemvisitdetail
-- ----------------------------
CREATE INDEX "itemvisitDatetime"
ON "itemvisitdetail" (
  "datetime" DESC
);
CREATE INDEX "itemvisitId"
ON "itemvisitdetail" (
  "itemvisitId" ASC
);

PRAGMA foreign_keys = true;
