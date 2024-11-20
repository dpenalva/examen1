<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reproductor de Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-white bg-warning">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <alt="Logo" height="30" class="d-inline-block align-text-top">
                WEBAMP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?r=form">Añadir Canción</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?r=credits">Credits</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Player</h5>
            </div>
            <div class="card-body">
                <div class="cancionRepro">
                    <p class="mb-2"><i class="bi bi-music-note"></i>
                    <span id="cancionAhora">Canción (Artista) (el que esta sonant)</span></p>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" id="playBtn">
                            <i class="bi bi-play-fill"></i>
                        </button>
                        <button class="btn btn-secondary btn-sm" id="pauseBtn">
                            <i class="bi bi-pause-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Opcions</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="randomCheck">
                    <label class="form-check-label" for="random">Aleatori</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="muteCheck">
                    <label class="form-check-label" for="mute">Silencia</label>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Cançó anterior</h5>
            </div>
            <div class="card-body">
                <p><i class="bi bi-music-note"></i> Canción (Artista) (l'anterior)</p>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mt-4" role="alert">
                <i class="bi bi-check-circle"></i> Canción añadida correctamente
            </div>
        <?php endif; ?>
        
        <div class="table-responsive-sm">
            <table class="table align-middle">
                <thead class="table-warning">
                    <tr>
                        <th class="d-none d-sm-table-cell" style="width: 5%"></th>
                        <th style="width: 25%">
                            <i class="bi bi-music-note"></i> 
                            <span class="d-none d-sm-inline">Nombre</span>
                        </th>
                        <th style="width: 20%">
                            <i class="bi bi-person"></i> 
                            <span class="d-none d-sm-inline">Artista</span>
                        </th>
                        <th style="width: 30%">
                            <i class="bi bi-controller"></i> 
                            <span class="d-none d-sm-inline">Controles</span>
                        </th>
                        <th style="width: 10%">
                            <span class="d-none d-sm-inline">Acciones</span>
                            <i class="bi bi-gear-fill d-sm-none"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $contador = 1; ?>
                    <?php foreach ($songs as $song): ?>
                    <tr data-song-id="<?php echo htmlspecialchars($song['id']); ?>">
                        <td class="d-none d-sm-table-cell"><?php echo $contador++; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-music-note me-2"></i>
                                <span class="text-truncate"><?php echo htmlspecialchars($song['nombre']); ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person me-2"></i>
                                <span class="text-truncate"><?php echo htmlspecialchars($song['artista']); ?></span>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <?php echo htmlspecialchars($song['duracion']); ?>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm play-btn">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm stop-btn">
                                        <i class="bi bi-stop-fill"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm mute-btn">
                                        <i class="bi bi-volume-up-fill"></i>
                                    </button>
                                </div>
                            </div>
                            <audio class="d-none">
                                <source src="uploads/songs/<?php echo htmlspecialchars($song['archivo']); ?>" type="audio/mpeg">
                            </audio>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editSongModal-<?php echo $song['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-btn" 
                                        data-song-id="<?php echo $song['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap modal -->
    <?php foreach ($songs as $song): ?>
    <div class="modal fade" id="editSongModal-<?php echo $song['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Canción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="edit-song-form needs-validation" novalidate>
                        <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la canción</label>
                            <input type="text" class="form-control" name="song_name" 
                                   value="<?php echo htmlspecialchars($song['nombre']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Artista</label>
                            <input type="text" class="form-control" name="artist" 
                                   value="<?php echo htmlspecialchars($song['artista']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Archivo de audio (opcional)</label>
                            <input type="file" class="form-control" name="song" accept="audio/*">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>