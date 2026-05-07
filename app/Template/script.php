<!-- SynAlert Component -->
<script src="<?= BASE_URL ?>/assets/js/components/alert-dialog.js"></script>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script>
  $(document).ready(function() {
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
      feather.replace();
    }

    // Auto-hide alerts
    setTimeout(function() {
      $('.alert').fadeOut('slow');
    }, 5000);
  });
</script>
