<div class="form-group">
    <textarea
        class="form-control"
        id="jawaban-<?= $pertanyaan_id?>"
        cols="30"
        rows="5"
        placeholder="Tulis Jawaban..."
        onchange="return pilihJawaban('short' , <?= $pertanyaan_id ?>)"><?= $jawaban ?></textarea>
</div>