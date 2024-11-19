document.addEventListener("DOMContentLoaded", () => {
    // Objeto para mantener track de la canción actual
    let currentAudio = null;
    let currentPlayBtn = null;

    // Función para detener la canción actual
    function stopCurrentAudio() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            if (currentPlayBtn) {
                currentPlayBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            }
        }
    }

    // Manejar todos los botones de play
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const audioContainer = this.closest('td');
            const audio = audioContainer.querySelector('audio');
            const progressBar = audioContainer.querySelector('.progress-bar');

            if (currentAudio && currentAudio !== audio) {
                stopCurrentAudio();
            }

            if (audio.paused) {
                audio.play();
                this.innerHTML = '<i class="bi bi-pause-fill"></i>';
                currentAudio = audio;
                currentPlayBtn = this;
            } else {
                audio.pause();
                this.innerHTML = '<i class="bi bi-play-fill"></i>';
            }

            // Actualizar barra de progreso
            audio.addEventListener('timeupdate', () => {
                const progress = (audio.currentTime / audio.duration) * 100;
                progressBar.style.width = `${progress}%`;
            });

            // Cuando termina la canción
            audio.addEventListener('ended', () => {
                this.innerHTML = '<i class="bi bi-play-fill"></i>';
                progressBar.style.width = '0%';
            });
        });
    });

    // Manejar botones de pausa
    document.querySelectorAll('.stop-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const audio = this.closest('td').querySelector('audio');
            const playBtn = this.closest('td').querySelector('.play-btn');
            
            if (audio) {
                audio.pause();
                playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            }
        });
    });

    // Manejar botones de mute
    document.querySelectorAll('.mute-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const audio = this.closest('td').querySelector('audio');
            if (audio) {
                audio.muted = !audio.muted;
                this.innerHTML = audio.muted ? 
                    '<i class="bi bi-volume-mute-fill"></i>' : 
                    '<i class="bi bi-volume-up-fill"></i>';
            }
        });
    });

    // Manejar la edición de canciones con AJAX
    document.querySelectorAll('.edit-song-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const songId = formData.get('song_id');

            $.ajax({
                url: 'index.php?r=updatesong',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        // Actualizar la tabla
                        const row = document.querySelector(`tr[data-song-id="${songId}"]`);
                        if (row) {
                            row.querySelector('td:nth-child(2)').innerHTML = 
                                `<div class="d-flex align-items-center">
                                    <i class="bi bi-music-note me-2"></i>
                                    <span class="text-truncate">${formData.get('song_name')}</span>
                                </div>`;
                            row.querySelector('td:nth-child(3)').innerHTML = 
                                `<div class="d-flex align-items-center">
                                    <i class="bi bi-person me-2"></i>
                                    <span class="text-truncate">${formData.get('artist')}</span>
                                </div>`;

                            // Si se actualizó el archivo de audio
                            const audioFile = formData.get('song');
                            if (audioFile && audioFile.size > 0 && response.newSongPath) {
                                const audio = row.querySelector('audio source');
                                if (audio) {
                                    audio.src = response.newSongPath;
                                    row.querySelector('audio').load();
                                }
                            }

                            // Cerrar el modal
                            const modalElement = document.querySelector(`#editSongModal-${songId}`);
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }

                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Canción actualizada correctamente',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } catch (error) {
                        console.error('Error al procesar la respuesta:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar la canción'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar la canción: ' + error
                    });
                }
            });
        });
    });

    // Manejador para los botones de eliminar
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const songId = this.dataset.songId;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'index.php?r=deletesong',
                        type: 'POST',
                        data: { song_id: songId },
                        success: function(response) {
                            if (response === "success") {
                                // Eliminar la fila de la tabla
                                const row = document.querySelector(`tr[data-song-id="${songId}"]`);
                                if (row) {
                                    row.remove();
                                }
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: 'La canción ha sido eliminada.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo eliminar la canción'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la petición AJAX:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar la canción'
                            });
                        }
                    });
                }
            });
        });
    });
}); 