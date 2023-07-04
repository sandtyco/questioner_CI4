<?php foreach($pertanyaan as $p) : ?>
<div
    class="card card-pertanyaan mb-3 overflow-hidden <?= $p['jawaban'] != null ? 'border-success' : '' ?>"
    style="border-radius: 10px;"
    id="card-pertanyaan-<?= $p['pertanyaan_id'] ?>"
    data-url="<?= base_url('jawaban/'.$user_id.'/save-jawaban/'.$p['pertanyaan_id']) ?>"
    data-method="post">
    <div class="card-body shadow">
        <p>
            <?= ucfirst($p['pertanyaan'])  ?>
        </p>
        <div>
            <?= $p['viewResponse'] ?>
        </div>
    </div>
</div>

<?php endforeach ?>