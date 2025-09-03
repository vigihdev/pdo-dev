```sql
CREATE USER 'okkarentorg'@'localhost' IDENTIFIED WITH mysql_native_password BY '***';GRANT ALL PRIVILEGES ON *.* TO 'okkarentorg'@'localhost' WITH GRANT OPTION;ALTER USER 'okkarentorg'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;GRANT ALL PRIVILEGES ON `okkarentorg`.* TO 'okkarentorg'@'localhost';
```
