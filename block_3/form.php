<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Блок 0</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <h1>Форма данные пользователя</h1>
</header>
<main>
    <div>
        <form action="chek_post.php" method="post">
            <input type="text" name="username" placeholder="Введите имя"><br>
            <input type="number" name="age" placeholder="Введите возраст" min="0" max="110"><br>
            <div>
                <label class="radio-label">
                    <input type="radio" name="gender" value="male" required>
                    <span class="radio-custom"></span>
                    Мужской
                </label>

                <label class="radio-label">
                    <input type="radio" name="gender" value="female">
                    <span class="radio-custom"></span>
                    Женский
                </label>
            </div><br>
            <input type="email" name="email" placeholder="Введите email"><br>
            <input type="password" name="password" placeholder="Введите пароль"><br>
            <input type="submit" value="Отправить">
        </form>
    </div>
</main>

</html>