<div class="form-group">
    <input
        type="text"
        class="form-control"
        value="<?= $jawaban ?>"
        id="jawaban-<?= $pertanyaan_id?>"
        placeholder="Tulis jawaban..."
        onchange="return pilihJawaban('short' , <?= $pertanyaan_id ?>)">
</div>