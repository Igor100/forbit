## Engine "ForBIT"

- 	Одна точка входа;
- 	Работа с БД используя PDO;
- 	Поддержка MVC
- 	Composer

Сущноcть:

```
CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL,
  auth_key varchar(32) NOT NULL,
  password_hash varchar(255) NOT NULL,
  balance decimal(20, 8) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX email (email),
  UNIQUE INDEX password_reset_token (password_reset_token),
  UNIQUE INDEX username (username)
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_unicode_ci
ROW_FORMAT = DYNAMIC;
```

Поле password_hash используется в явном виде.
После реализации регистрации будет сохранятся в виде хеша.

Соединение с базой прописываем в _ROOT/config/db.php_