<button
    class="btn btn-primary btn-sm"
    data-url="<?= base_url('jawaban/'.$user_id) ?>"
    id="btn-detail-<?= $user_id ?>"
    onclick="return detail(<?= $user_id ?>)">
    <i class="fas fa-eye"></i>
    Lihat Jawaban
</button>
<!-- <button
    class="btn btn-danger btn-sm"
    data-url="<?= base_url('jawaban/'.$user_id.'/delete') ?>"
    data-method="delete"
    id="btn-delete-<?= $user_id ?>"
    onclick="return btnDelete(<?= $user_id ?>)">
    <i class="fas fa-trash"></i>
</button> -->