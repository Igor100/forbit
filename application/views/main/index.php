<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/main/logout">Выход</a>
            </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0" method="post">
            <input class="form-control mr-sm-2" name="payout" type="text" placeholder="Введите сумму" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Выплата</button>
        </form>
    </div>
</nav>
<main role="main" class="container">
Вы зашли под логином "<?= $data['data']['username'];?>"<br>
Ваш баланс: <?= $data['data']['balance'];?> рублей
</main>
