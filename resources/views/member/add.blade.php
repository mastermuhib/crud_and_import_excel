@extends('layout.app')
@section('asset')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets')}}/vendors/css/forms/select/select2.min.css">
@endsection
@section('title', 'Member')
@section('content')
<div class="row" >
    <div class="col-lg-12 col-xxl-4">
        <section class="multiple-validation">
            <div class="card mb-3">
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" class="form-horizontal p-3" id="form_add" enctype="multipart/form-data">@csrf
                            <div class="row mb-5">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">NKK</label>
                                        <input type="text" name="nkk" class="form-control form-control-lg" placeholder="16 angka" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">NIK</label>
                                        <input type="text" name="nik" class="form-control form-control-lg" placeholder="16 angka">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Nama" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Tempat Lahir</label>
                                        <input type="text" name="birthplace" class="form-control form-control-lg" placeholder="Kota / Kabupaten">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Tanggal Lahir</label>
                                        <input type="text" name="birthday" class="form-control form-control-lg" placeholder="tanggal || bulan || tahun ex 26|08|1999">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <select class="select2 form-control" id="select_status" name="status" required>
                                            <option value="S">Sudah Menikah</option>
                                            <option value="B" selected>Sudah Menikah</option>
                                            <option value="P">Pernah Menikah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Jenis Kelamin</label>
                                        <select class="select2 form-control" id="select_gender" name="gender" required>
                                            <option value="L">Laki - Laki</option>
                                            <option value="P" selected>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">RT</label>
                                        <input type="text" name="rt" class="form-control form-control-lg" >
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">RW</label>
                                        <input type="text" name="rw" class="form-control form-control-lg" >
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Address</label>
                                        <input type="text" name="address" class="form-control form-control-lg" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header text-center">
                                            Foto Profile
                                        </div>
                                        <div class="card-body text-center">
                                            
                                            <img id="profile_admin" src="https://souq-cms.trendcas.com/assets/imgs/theme/upload.svg" alt="your image" style="max-width: 200px;max-height: 200px;" />
                                            <label class="btn btn-white btn-sm mb-0 w-100 align-self-center">
                                                Upload File <input type="file" name="photo" style="display: none;" onchange="gantiProfile_admin(this);">
                                            </label>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-primary float-right" id="save_btn">Submit</button>
                                    <button type="button" class="btn btn-primary float-right" style="display: none;" id="loading_btn" disabled>Submit</button>
                                    <button type="button" class="btn btn-danger mr-2 float-right" id="batalkan">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
@include('components/componen_crud')
<script type="text/javascript">

function ResetAll(){
    location.reload();
}

function gantiProfile_admin(input) {
    //alert("okey");
    if (input.files && input.files[0]) {
        $("#profile_admin").css('display', '');
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#profile_admin')
                .attr('src', e.target.result)
                .css('width', '150px')
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#form_add").validate({
    submitHandler: function(form) {
        $("#loading_btn").css('display','');
        $("#save_btn").css('display','none');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({ //line 28
            type: 'POST',
            url: '/post_member',
            dataType: 'json',
            data: new FormData($("#form_add")[0]),
            processData: false,
            contentType: false,
            success: function(data) {
                $("#loading_btn").css('display','none');
                $("#save_btn").css('display','');
                show_toast(data.message, 1);
                //location.assign('/member/list');
            }, error : function(data) {
                $("#loading_btn").css('display','none');
                $("#save_btn").css('display','');
            }
        });
    }
});
</script>
@endsection