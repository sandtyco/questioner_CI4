<?php if($type != 'range') : ?>


<div class="view-input" data-type="<?= $type ?>"></div>

    <button
        class="btn btn-primary btn-sm"
        type="button"
        onclick="return appendChoice()">
        <i class="fas fa-plus"></i> Tambah Jawaban
    </button>

<script>
    function appendChoice() {
        let count = $('.view-input input').length;
        let id = count + 1;
        $('.view-input').append(
            `
        <div class="form-group" id="form-group-${id}">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text opsi-jawaban" id="basic-addon1">jawaban</span>
                </div>
                <input
                    type="text"
                    class="form-control" name="pilihan_jawaban[]" id="pilihan-jawaban-${id}"/>
                <div class="input-group-append">
                    <button class="btn btn-danger rounded-circle ml-2" type="button" onclick='return deleteOpsi(${id})'><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
        `
        );

        reloadNumbering();
    }
    function reloadNumbering() {
        let type = $('.view-input').data('type');

        let opsiName = '';

        switch (type) {
            case 'checklist':
                opsiName = '<i class="far fa-square mr-2"></i> Jawaban';
                break;
            case 'optional':
                opsiName = '<i class="far fa-circle mr-2"></i> Jawaban';
                break;
        }

        var numberingTarget = $('.opsi-jawaban');
        var numberingCount = numberingTarget.length;
        $(numberingTarget).each(function (i) {

            $(this).html(`${opsiName} ${++ i} : `);
        });
    }

    function deleteOpsi(id) {
        $('#form-group-' + id).remove();
        reloadNumbering();
    }
</script>
<?php else : ?>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="range_mulai">
                Range Mulai
            </label>
            <input
                type="number"
                value="1"
                min="0"
                class="form-control"
                id="range_mulai"
                name="range_mulai">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="range_akhir">
                Range Akhir
            </label>
            <input
                type="number"
                min="0"
                class="form-control"
                id="range_akhir"
                name="range_akhir">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="kelipatan">
                Kelipatan
            </label>
            <input
                type="number"
                min="0"
                value="1"
                class="form-control"
                id="kelipatan"
                name="kelipatan">
        </div>
    </div>
</div>
<?php endif ?>