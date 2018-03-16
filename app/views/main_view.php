<section class="tickets-type-section">
    <section class="tickets-type-list">
        <label for="air" class="checked">Авіаквитки</label>
        <input type="radio" name="tickets-type" value="airplane" id="air" checked>
        <label for="bus">Квитки на автобус</label>
        <input type="radio" name="tickets-type" value="bus" id="bus">
        <label for="all">Будь-який</label>
        <input type="radio" name="tickets-type" value="all" id="all">
    </section>
    <section class="show-type-section">
        <span>Тип квитка</span>
        <span class="icon"></span>
    </section>
</section>
<section class="main">
    <section class="search-section airplane">
        <form action="/passage/search" method="get" name="search">
            <input type="text" name="origin" placeholder="Київ, Україна" required>
            <input type="text" name="destination" placeholder="Львів, Україна" required>
            <input type="text" name="date" required>
            <input type="hidden" name="tickets-type-selected" value="airplane">
            <input type="submit" name="search" value="Розклад і ціни">
        </form>
    </section>
</section>