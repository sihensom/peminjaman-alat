-- Update password hashes untuk semua user dengan hash yang valid
-- Password: admin123, petugas123, peminjam123, siswa123, guru123

UPDATE users SET password = '$2y$10$fdhoxA4f4Ircz3NYhjpMfenZ7tCQe83jFAINWGwztZ5fxVIaiOP0O' WHERE role = 'admin';
UPDATE users SET password = '$2y$10$if12Kwu5cvtUMJLEUxK6AeQbD028/3JBh722ICFIdnu6sm0Xr1hKC' WHERE role = 'petugas';
UPDATE users SET password = '$2y$10$Uuqo6y2IOGMzlqtjUCoN1OsjagZN8DOwZSk9rFrv7QasiO2Bkutg2' WHERE role = 'peminjam';
