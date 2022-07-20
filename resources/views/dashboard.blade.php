@extends('layouts.app')

@section('content')
    @include('layouts.headers.guest')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col col-md-4">
                <div class="card mb-5">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">{{$model ? "Edit Data" : "Tambah Data"}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('save')}}" method="POST">
                            @csrf
                            <input name="id" type="hidden" value="{{$model->id ?? null}}">
                            <div class="form-group">
                                <label for="labelName">Nama</label>
                                <input name="nama" type="nama" class="form-control" id="labelName" required value="{{$model->nama ?? null}}" placeholder="Nama">
                            </div>
                            <div class="form-group">
                                <label for="labelPsikologi">Nilai Psikologi</label>
                                <input name="psikologi" type="number" step="0.01" class="form-control" id="labelPsikologi" required value="{{$model->psikologi ?? null}}" placeholder="Nilai Psikologi">
                            </div>
                            {{-- <div class="form-group">
                                <label for="labelTkk">Nilai Tkk</label>
                                <input name="tkk" type="number" step="0.01" class="form-control" id="labelTkk" required value="{{$model->tkk ?? null}}" placeholder="Nilai Tkk">
                            </div> --}}
                            <div class="form-group">
                                <label for="labelTkk">Nilai Jasmani</label>
                                <input name="jasmani" type="number" step="0.01" class="form-control" id="labelJasmani" required value="{{$model->jasmani ?? null}}" placeholder="Nilai Jasmani">
                            </div>
                            <div class="form-group">
                                <label for="labelTkk">Nilai Akademik</label>
                                <input name="akademik" type="number" step="0.01" class="form-control" id="labelAkademik" required value="{{$model->akademik ?? null}}" placeholder="Nilai Akademik">
                            </div>
                            {{-- <div class="form-group">
                                <label for="labelTkk">Nilai Kuota</label>
                                <input name="kuota" type="number" step="0.01" class="form-control" id="labelKuota" required value="{{$model->kuota ?? null}}" placeholder="Nilai Kuota">
                            </div> --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-icon btn-success btn-sm" type="button">
                                    <span class="btn-inner--text">{{$model ? "Edit" : "Save"}}</span>
                                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        {{-- </div>
        <div class="row"> --}}
            <div class="col col-md-8">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Tabel Keputusan</h6>
                                <h2 class="mb-0">Daftar Peserta</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div>
                                <table class="table align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">No</th>
                                            <th scope="col" class="sort" data-sort="name">Nama</th>
                                            <th scope="col" class="sort" data-sort="name">Psikologi</th>
                                            {{-- <th scope="col" class="sort" data-sort="budget">Tkk</th> --}}
                                            <th scope="col" class="sort" data-sort="status">Jasmani</th>
                                            <th scope="col" class="sort" data-sort="status">Akademik</th>
                                            {{-- <th scope="col" class="sort" data-sort="status">Kuota</th> --}}
                                            <th scope="col" class="sort" data-sort="status">Hasil</th>
                                            <th scope="col" class="sort" data-sort="status">Label</th>
                                            <th scope="col" class="sort" data-sort="status">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($dataKeputusan as $key => $keputusan)
                                        <tr>
                                            <th>{{$key+1}}</th>
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->nama ?? null}}</span>
                                                    </div>
                                                </div>
                                            </th>
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->psikologi ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->tkk ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td> --}}
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->jasmani ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->akademik ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->kuota ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td> --}}
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->hasil ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$keputusan->hasil_label ?? null}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <th scope="row">
                                                <div class="media align-items-center mb-1">
                                                    <div class="media-body">
                                                        <form action="{{route('home')}}" method="get">
                                                            @csrf
                                                            <input type="hidden" name="id" value={{$keputusan->id}}>
                                                            <button type="submit" class="btn btn-icon btn-primary btn-sm" type="button">
                                                                <span class="btn-inner--text">Edit</span>
                                                                <span class="btn-inner--icon"><i class="ni ni-fat-delete"></i></span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <form action="{{route('delete')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value={{$keputusan->id}}>
                                                            <button type="submit" class="btn btn-icon btn-danger btn-sm" type="button">
                                                                <span class="btn-inner--text">Delete</span>
                                                                <span class="btn-inner--icon"><i class="ni ni-fat-remove"></i></span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
    <script>
        let parameterChart = document.getElementById('chartSales');
        var salesChart = new Chart(parameterChart, {
			type: 'line',
			options: {
				scales: {
					yAxes: [{
						gridLines: {
							color: "#212529",
							zeroLineColor: "#212529"
						}
					}]
				},
				tooltips: {
					callbacks: {
						label: function(item, data) {
							var label = data.datasets[item.datasetIndex].label || '';
							var yLabel = item.yLabel;
							var content = '';

							if (data.datasets.length > 1) {
								content += '<span class="popover-body-label mr-auto">' + label + '</span>';
							}

							content += '<span class="popover-body-value">$' + yLabel + 'k</span>';
							return content;
						}
					}
				}
			},
			data: {
				labels: [0,1,2],
				datasets: [{
					label: 'Performance',
					data: [0,1,3]
				}]
			}
		});
    </script>
@endpush
