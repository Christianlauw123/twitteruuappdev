@extends('layouts.app')
@section('title')
Jenis Mobil
@endsection

@section('main-content-section')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Jenis Mobil
                </h3>
                <div class="float-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Jenis Mobil</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Insentif</th>
                            <th>Km/L</th>
                            <th>BBM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenis_mobils as $jenis)
                        <tr>
                            <td>
                                {{$jenis->id}}
                            </td>
                            <td>
                                {{$jenis->nama}}
                            </td>
                            <td>
                                {{number_format($jenis->insentif)}}
                            </td>
                            <td>
                                {{$jenis->km_l}}
                            </td>
                            <td>
                                {{$jenis->bbm->nama}}
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit" master="jenis_mobil" data-id="{{$jenis->id}}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm btn-delete" master="jenis_mobil" data-id="{{$jenis->id}}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Jenis Mobil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-tambah">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" placeholder="Nama" name="nama" id="nama">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Insentif</label>
                        <input type="number" class="form-control" placeholder="Insentif" name="insentif" id="insentif">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Km/L</label>
                        <input type="number" class="form-control" placeholder="Km/L" name="km_l" id="km_l">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>BBM</label>
                        <select name="bbm_id" id="bbm_id" class="form-control select2">
                            <option value="" selected disabled>-- Pilih BBM --</option>
                            @foreach($bbms as $bbm)
                                <option value="{{$bbm->id}}">{{$bbm->nama}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-tambah" master="jenis_mobil">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Mobil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-tambah">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" placeholder="Nama" name="nama" id="nama-edit">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Insentif</label>
                        <input type="number" class="form-control" placeholder="Insentif" name="insentif" id="insentif-edit">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Km/L</label>
                        <input type="number" class="form-control" placeholder="Km/L" name="km_l" id="km_l-edit">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>BBM</label>
                        <select name="bbm_id" id="bbm_id-edit" class="form-control select2">
                            <option value="" selected disabled>-- Pilih BBM --</option>
                            @foreach($bbms as $bbm)
                                <option value="{{$bbm->id}}">{{$bbm->nama}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-edit" master="jenis_mobil">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
