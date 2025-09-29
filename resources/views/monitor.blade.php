<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Monitor Antrian - {{ config('app.name') }}</title>
    
    <!-- Bootstrap dan Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- PENTING: Import Laravel Echo dan Pusher -->
    @vite(['resources/js/app.js']) 

    <style>
        body {
            background-color: #212529;
            color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        .monitor-card {
            background-color: #343a40;
            border: none;
            border-radius: 1.5rem;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .display-1-loket {
            font-size: 5rem; 
            font-weight: 700;
        }
        .display-2-antrian {
            font-size: 10rem;
            font-weight: 900;
            color: #1abc9c;
            transition: color 0.5s, transform 0.5s; 
        }
        .list-group-item {
            background-color: #495057;
            border-color: #6c757d;
            color: #f8f9fa;
            border-radius: 0;
        }
        .alert-call {
            background-image: linear-gradient(to right, #1abc9c 0%, #16a085 100%);
            color: white;
            box-shadow: 0 0 20px rgba(26, 188, 156, 0.5);
        }
    </style>
</head>
<body class="bg-dark text-white">

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-light">Papan Monitor Antrian</h1>
                    <p class="lead">Nomor antrian yang sedang dilayani saat ini.</p>
                </div>

                <!-- BLOK UTAMA MONITOR PUBLIK -->
                <div id="called-antrian-section" class="monitor-card text-center p-5 shadow-lg mb-5">
                    <p class="fs-4 text-secondary mb-2">Nomor Antrian</p>
                    <h2 id="nomor-antrian" class="display-2-antrian mb-4">--</h2>
                    <p class="fs-4 text-secondary mb-2">Silakan Menuju</p>
                    <h3 id="loket-tujuan" class="display-1-loket text-info fw-bold">--</h3>
                </div>

                <!-- Blok Daftar Antrian Menunggu -->
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card monitor-card shadow-lg p-0" style="min-height: auto;">
                            <div class="card-header bg-dark text-white fw-bold fs-5 text-center py-3 rounded-top-4">
                                <i class="fas fa-list-ol me-2"></i>Daftar Antrian Menunggu (10 Teratas)
                            </div>
                            <div class="card-body p-0">
                                <ul id="antrian-list" class="list-group list-group-flush">
                                    <li class="list-group-item text-center py-5" id="initial-loading">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Memuat data real-time...
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script PUSHER/ECHO -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const $antrianNum = $('#nomor-antrian');
        const $loketTujuan = $('#loket-tujuan');
        const $antrianList = $('#antrian-list');
        const $calledSection = $('#called-antrian-section');

        document.addEventListener('DOMContentLoaded', function () {
            
            fetchAntrianData(); 

            window.Echo.channel('antrian-channel')
                .listen('.antrian.dipanggil', (e) => {
                    updateMonitorDisplay(e.antrian);
                    fetchAntrianData(); 
                });

            function updateMonitorDisplay(antrian) {
                if (antrian && antrian.nomor_antrian) {
                    $antrianNum.text(antrian.nomor_antrian);
                    $loketTujuan.text(antrian.loket.nama_loket);
                    
                    $calledSection.addClass('alert-call');
                    setTimeout(() => {
                        $calledSection.removeClass('alert-call');
                    }, 2000); 

                } else {
                    $antrianNum.text('--');
                    $loketTujuan.text('SEMUA LOKET SIAP');
                }
            }
            
            function updateWaitingList(antrians_menunggu) {
                $antrianList.empty();
                if (antrians_menunggu && antrians_menunggu.length > 0) {
                    $.each(antrians_menunggu, function(index, a) {
                        $antrianList.append(
                            '<li class="list-group-item d-flex justify-content-between align-items-center py-3">' +
                                '<div>' +
                                    '<span class="fw-bold fs-5 me-2 text-info">' + a.nomor_antrian + '</span>' +
                                    '<small class="text-secondary">(' + a.loket.nama_loket + ')</small>' +
                                '</div>' +
                                '<span class="badge bg-warning text-dark px-3 py-2">Menunggu</span>' +
                            '</li>'
                        );
                    });
                } else {
                    $antrianList.append('<li class="list-group-item text-center text-muted py-5">Tidak ada antrian menunggu saat ini.</li>');
                }
            }

            function fetchAntrianData() {
                $.ajax({
                    url: "{{ route('api.antrian.monitor_data') }}",
                    method: 'GET',
                    success: function(response) {
                        updateWaitingList(response.antrians_menunggu);
                        updateMonitorDisplay(response.antrian_dipanggil); 
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error fetching initial data:", error);
                        $antrianList.html('<li class="list-group-item text-center text-danger py-5">Koneksi API Gagal. Coba Refresh.</li>');
                    }
                });
            }
        });
    </script>
</body>
</html>