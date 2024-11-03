/*
 Navicat Premium Data Transfer

 Source Server         : mydb
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3035005 (3.35.5)
 File Encoding         : 65001

 Date: 02/11/2024 17:59:33
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS "role";
CREATE TABLE "role" (
  "id" integer NOT NULL,
  "date" datetime,
  "title" TEXT,
  "name" TEXT,
  "images" TEXT,
  "remark" TEXT,
  PRIMARY KEY ("id")
);

PRAGMA foreign_keys = true;
