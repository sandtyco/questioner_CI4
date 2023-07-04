<!-- Modal -->
<div
    class="modal fade"
    id="<?= $modalId ?>"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form
                action="<?= base_url('pertanyaan/save') ?>"
                method="post"
                enctype="multipart/form-data"
                id="form-save">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pertanyaan">Pertanyaan</label>
                        <textarea
                            name="pertanyaan"
                            id="pertanyaan"
                            cols="30"
                            rows="5"
                            class="form-control"></textarea>
                        <div class="invalid-feedback errorPertanyaan"></div>
                    </div>
                    <div class="form-group">
                        <label for="type_jawaban">Tipe Jawaban</label>
                        <select name="type_jawaban" id="type_jawaban" class="form-control">
                            <option disabled="disabled" selected="selected">-- Pilih Tipe Jawaban --</option>
                            <option value="short">Short Answer</option>
                            <option value="long">Long Answer</option>
                            <option value="optional">Optional</option>
                            <option value="checklist">Checklist</option>
                            <option value="range">Range</option>
                        </select>
                        <div class="invalid-feedback errorTypeJawaban"></div>
                    </div>
                    <div
                        class="view-pilihan-jawaban"
                        id="view-pilihan-jawaban"
                        style="display: none;"
                        data-url="<?= base_url('pertanyaan/get-answer-choice') ?>"
                        data-method="post"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.form-control').on('input change', function(){
        $(this).removeClass('is-invalid');
    })

    $('#file').on('change', function () {
        const filechange = document.querySelector('#file');
        const fileLabel = document.querySelector('.custom-file-label');
        fileLabel.textContent = filechange
            .files[0]
            .name;

        $('#btn-save').removeAttr('disabled');
    })

    $('#type_jawaban').on('change', function () {
        let val = $(this).val();
        let view = $('#view-pilihan-jawaban');
        if (val != 'short' && val != 'long') {
            $.ajax({
                type: view.data('method'),
                url: view.data('url'),
                data: {
                    type: val
                },
                success: function (response) {
                    console.log(response)
                    let resp = JSON.parse(response)
                    view.html(resp['success']['view']);
                    view.show();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })


    $('#form-save').on('submit', function (e) {
        let btn = $('#btn-save');
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            chace: false,
            beforeSend: function () {
                Swal.fire({
                    html: 'Please wait...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            },
            success: function (response) {
                console.log(response)
                let resp = JSON.parse(response)
                if (resp['error']) {
                    if (resp['error']['pertanyaan']) {
                        $('#pertanyaan').addClass('is-invalid');
                        $('.errorPertanyaan').html(resp['error']['pertanyaan']);
                    } else {
                        $('#pertanyaan').removeClass('is-invalid');
                    }
                    if (resp['error']['type_jawaban']) {
                        $('#type_jawaban').addClass('is-invalid');
                        $('.errorTypeJawaban').html(resp['error']['type_jawaban']);
                    } else {
                        $('#type_jawaban').removeClass('is-invalid');
                    }

                    Swal.close();
                    if(resp['error']['msg'])
                    {
                        Swal.fire({icon: 'error', title: 'Mohon maaf', text: resp['error']['msg']})
                    }
                }
                if (resp['success']) {
                    Swal.fire({icon: 'success', title: 'Berhasil', text: resp['success']['msg']
                    })
                    reloadTable();
                    $("<?= '#'.$modalId ?>").modal('hide');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
</script>