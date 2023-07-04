<p>Jawaban :</p>
<?php $row = 0 ?>
<?php foreach($pilihan_jawaban as $pilih) : ?>
<div class="custom-control custom-checkbox  custom-control-inline">
    <input
        type="checkbox"
        class="custom-control-input checkbox-kuesioner-<?= $pertanyaan_id ?>"
        id="check-<?= $pertanyaan_id.'-'.strtolower(preg_replace('/\s+/', '', $pilih)).$row  ?>"
        value="<?= $pilih ?>"
        <?= in_array($pilih, $jawaban) ? 'checked' : '' ?>
        onclick="return pilihJawaban('checklist' , <?= $pertanyaan_id ?>, '<?= strtolower(preg_replace('/\s+/', '', $pilih)).$row ?>')"
        name="check_<?= $pertanyaan_id ?>">
    <label
        class="custom-control-label"
        for="check-<?= $pertanyaan_id.'-'.strtolower(preg_replace('/\s+/', '', $pilih)).$row++ ?>"><?= $pilih ?></label>
</div>
<?php endforeach ?>

