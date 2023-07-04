<p>Jawaban :</p>
<?php $row = 0 ?>
<?php foreach($pilihan_jawaban as $pilih) : ?>
<div class="custom-control custom-radio custom-control-inline">
    <input
        type="radio"
        id="optional-<?= $pertanyaan_id.'-'.strtolower(preg_replace('/\s+/', '', $pilih)).$row  ?>"
        name="<?= $pertanyaan_id ?>"
        class="custom-control-input"
        value="<?= $pilih ?>"
        <?= $jawaban == $pilih ? 'checked' : '' ?>
        onclick="return pilihJawaban('optional' , <?= $pertanyaan_id ?>, '<?= strtolower(preg_replace('/\s+/', '', $pilih)).$row ?>')">
    <label class="custom-control-label" for="optional-<?= $pertanyaan_id.'-'.strtolower(preg_replace('/\s+/', '', $pilih)).$row++ ?>"><?= $pilih ?></label>
</div>
<?php endforeach ?>