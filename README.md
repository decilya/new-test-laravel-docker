# Тестовое задание

После скачивания проекта запустите: 

```bash
$ make install
```

Миграции:
```bash
$ docker compose exec app php artisan migrage
```

гиды:

http://localhost/guides

http://localhost/guides/create

бронирование:

http://localhost/bookings

http://localhost/bookings/create


Для запуска тестов используйте команды:

```bash
docker compose exec app php artisan test
# или
docker compose exec app php artisan test --filter=GuideFilterTest
```

Описание задачи: 
```bash
Создать минимальный Laravel-модуль, который реализует:

Миграции и модели:
Guide (поля: name, experience_years, is_active)
HuntingBooking (поля: tour_name, hunter_name, guide_id, date, participants_count)
API-эндпоинты:
GET /api/guides — список активных гидов
POST /api/bookings — создание нового бронирования
Логика бронирования:
Проверить, что у выбранного гида нет других бронирований на ту же дату
Проверить, что participants_count <= 10
Вернуть осмысленные ответы (200, 400, 422 и т.д.)
Что оценивается

Корректность и чистота кода
Использование Laravel best practices (модели, валидация, контроллеры, ресурсы)
Структура проекта и понятность решений
Минимум «магии» — максимум логики

Бонус (по желанию):
Добавить простейший Unit/Feature-тест
Сделать фильтр GET /api/guides?min_experience=3
```
Если мы хотим перенести это в новый проект, то мы можем пойти 2мя путями:
1) просто перенести файлы проекта в другой проект 
2) выделить мой код в пакет, но для того нужно будет еще описать ServiceProvider и добавить потом этот провайдер в config/app.php основного приложения (в массив 'providers' => [])


![Страница бронирования](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/bookings/bookings.png)
![Страница создания бронирования](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/bookings/create_1.png)
![Страница создания бронирования №2](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/bookings/create_2.png)

![Страница списка гидов](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/guides/gids.png)
![Страница редактирование гида](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/guides/update_gid.png)
![Страница просмотра гида](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/guides/gid_view.png)
![Страница удаления гида](https://github.com/decilya/new-test-laravel-docker/blob/main/src/resources/imgs/guides/del.png)



