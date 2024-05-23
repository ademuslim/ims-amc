<!-- <footer>
  <p>&copy; 2024 Invoice Management System | AMC</p>
</footer> -->
</div>

<!-- DataTables Responsive Bootstrap5 JS -->
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dataTables.js') ?>"></script>
<script src="<?= base_url('assets/js/dataTables.bootstrap5.js') ?>"></script>
<script src="<?= base_url('assets/js/dataTables.responsive.js') ?>"></script>
<script src="<?= base_url('assets/js/responsive.bootstrap5.js') ?>"></script>
<!-- DataTables Button JS -->
<script src="<?= base_url('assets/js/dataTables.buttons.js') ?>"></script>
<script src="<?= base_url('assets/js/buttons.dataTables.js') ?>"></script>
<script src="<?= base_url('assets/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/js/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/js/buttons.colVis.min.js') ?>"></script>

<script src="<?= base_url('assets/js/script.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Retrieve the last opened accordion status from localStorage
  var openAccordion = localStorage.getItem("openAccordion");
  if (openAccordion) {
    var collapseElement = document.getElementById(openAccordion);
    if (collapseElement) {
      collapseElement.classList.add('show-immediate');
      collapseElement.closest('.accordion-item').classList.add('no-transition');
      var bsCollapse = new bootstrap.Collapse(collapseElement, {
        toggle: false
      });
      bsCollapse.show();
      // Remove the show-immediate class after initial opening
      setTimeout(function() {
        collapseElement.classList.remove('show-immediate');
      }, 0);
    }
  }

  // Add event listener to each accordion button
  var accordionButtons = document.querySelectorAll('.accordion-button');
  accordionButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      var target = button.getAttribute('data-bs-target').substring(1);
      var collapseElement = document.getElementById(target);
      if (button.classList.contains('collapsed')) {
        localStorage.removeItem('openAccordion');
        collapseElement.closest('.accordion-item').classList.remove('no-transition');
      } else {
        localStorage.setItem('openAccordion', target);
        collapseElement.closest('.accordion-item').classList.add('no-transition');
      }
    });
  });
});

function confirmDelete(url, message) {
  if (confirm(message)) {
    window.location.href = url;
  }
}
</script>
</body>

</html>