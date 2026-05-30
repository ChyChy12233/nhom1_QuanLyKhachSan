-- Migration: add Status and StaffId columns to invoice table
-- Chạy: mysql -u root --default-character-set=utf8mb4 hotel < sql/migration_invoice_v2.sql

ALTER TABLE invoice
  ADD COLUMN IF NOT EXISTS `Status`  varchar(50)  DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `StaffId` varchar(10)  DEFAULT NULL;
