<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
    <title>Mini MVC</title>
</head>
<body>
    <h1>Управление пользователями</h1>
     <div>
        <div>
            <h2>Добавить пользователя</h2>
            
            <form method="POST" action="">
                <div >
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div >
                    <label for="email">Email:</label>
                    <input type="email"  name="email" required>
                </div>
                
                <button type="submit" name="create">Добавить пользователя</button>
            </form>
        </div>
        <div>
            <h2>Список пользователей</h2>
            <?php if (empty($users)): ?>
                <p>Пользователей нет</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Удалить пользователя?')">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>