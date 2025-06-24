@extends('layout.template')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-white text-center mb-4">Data Tabel</h2>
            </div>
        </div>

        {{-- Nav Tabs --}}
        <ul class="nav nav-tabs justify-content-center mb-4" id="geoTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="points-tab" data-bs-toggle="tab" data-bs-target="#points" type="button"
                    role="tab" aria-controls="points" aria-selected="true">Points</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="polylines-tab" data-bs-toggle="tab" data-bs-target="#polylines" type="button"
                    role="tab" aria-controls="polylines" aria-selected="false">Polylines</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="polygons-tab" data-bs-toggle="tab" data-bs-target="#polygons" type="button"
                    role="tab" aria-controls="polygons" aria-selected="false">Polygons</button>
            </li>
        </ul>

        <div class="tab-content" id="geoTabContent">
            {{-- Points Table --}}
            <div class="tab-pane fade show active" id="points" role="tabpanel" aria-labelledby="points-tab">
                <div class="card bg-dark text-white mb-4 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped mb-0" id="pointstable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Dibuat</th>
                                        <th>Diperbarui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($points as $p)
                                        <tr>
                                            <td>{{ $p->id }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->description }}</td>
                                            <td><img src="{{ asset('storage/images/' . $p->image) }}"
                                                    alt="{{ $p->name }}" class="img-fluid rounded"
                                                    style="max-width:80px;"></td>
                                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $p->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada data points</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Polylines Table --}}
            <div class="tab-pane fade" id="polylines" role="tabpanel" aria-labelledby="polylines-tab">
                <div class="card bg-dark text-white mb-4 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped mb-0" id="polylinestable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Dibuat</th>
                                        <th>Diperbarui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($polylines as $pl)
                                        <tr>
                                            <td>{{ $pl->id }}</td>
                                            <td>{{ $pl->name }}</td>
                                            <td>{{ $pl->description }}</td>
                                            <td><img src="{{ asset('storage/images/' . $pl->image) }}"
                                                    alt="{{ $pl->name }}" class="img-fluid rounded"
                                                    style="max-width:80px;"></td>
                                            <td>{{ $pl->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $pl->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada data polylines</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Polygons Table --}}
            <div class="tab-pane fade" id="polygons" role="tabpanel" aria-labelledby="polygons-tab">
                <div class="card bg-dark text-white mb-4 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped mb-0" id="polygonstable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Dibuat</th>
                                        <th>Diperbarui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($polygons as $pg)
                                        <tr>
                                            <td>{{ $pg->id }}</td>
                                            <td>{{ $pg->name }}</td>
                                            <td>{{ $pg->description }}</td>
                                            <td><img src="{{ asset('storage/images/' . $pg->image) }}"
                                                    alt="{{ $pg->name }}" class="img-fluid rounded"
                                                    style="max-width:80px;"></td>
                                            <td>{{ $pg->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $pg->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada data polygons</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <style>
        body {
        padding-top: 70px; /* memberi ruang di bawah navbar fixed */
        background-color: #0d1117;
        color: #c9d1d9;
        }

        .nav-tabs .nav-link {
            color: #c9d1d9;
        }

        .nav-tabs .nav-link.active {
            background-color: #161b22;
            color: #58a6ff;
            border-color: #58a6ff #58a6ff #161b22;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        $(function() {
            $('#pointstable, #polylinestable, #polygonstable').DataTable({
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ entri'
                }
            });
            // Activate Bootstrap tab on load
            var urlHash = window.location.hash;
            if (urlHash) {
                var triggerEl = document.querySelector(`button[data-bs-target="${urlHash}"]`);
                if (triggerEl) new bootstrap.Tab(triggerEl).show();
            }
        });
    </script>
@endsection
