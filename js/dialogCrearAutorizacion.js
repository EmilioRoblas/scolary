document.getElementById('selectorAsignacion').addEventListener('change', function () {
    const grupoSelector = document.getElementById('grupoSelector');
    const alumnoSelector = document.getElementById('alumnoSelector');

    grupoSelector.classList.add('d-none');
    alumnoSelector.classList.add('d-none');

    if (this.value === 'grupo') {
      grupoSelector.classList.remove('d-none');
    } else if (this.value === 'alumno') {
      alumnoSelector.classList.remove('d-none');
    }
  });