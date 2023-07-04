<button
    class="btn btn-danger btn-sm"
    onclick="return btnDelete(<?= $pertanyaan_id ?>)"
    id="btnDelete-<?= $pertanyaan_id ?>"
    data-url="<?= base_url('pertanyaan/delete/'.$pertanyaan_id) ?>"
    data-method="post">
    <i class="fas fa-trash"></i>
</button>
<button
    class="btn btn-outline-primary btn-sm"
    onclick="return changeNumber(<?= $pertanyaan_id ?> , 'up')"
    id="btn-change-number-up-<?= $pertanyaan_id ?>"
    data-url="<?= base_url('pertanyaan/'.$pertanyaan_id.'/change-number') ?>"
    data-method="post">
    <i class="fas fa-angle-double-up"></i>
</button>
<button
    class="btn btn-outline-primary btn-sm"
    onclick="return changeNumber(<?= $pertanyaan_id ?> , 'down')"
    id="btn-change-number-down-<?= $pertanyaan_id ?>"
    data-url="<?= base_url('pertanyaan/'.$pertanyaan_id.'/change-number') ?>"
    data-method="post">
    <i class="fas fa-angle-double-down"></i>
</button>