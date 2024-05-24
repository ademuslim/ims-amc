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
      let sidebar = document.querySelector(".sidebar");
      if (parseInt(sidebar.style.width) === 50) {
        // If sidebar is 50px, set it back to the original width first
        sidebar.style.width = originalWidth;
        localStorage.setItem("sidebarWidth", originalWidth);
      }

      // Delay the accordion toggle to ensure the sidebar width change takes effect
      setTimeout(function() {
        var target = button.getAttribute('data-bs-target').substring(1);
        var collapseElement = document.getElementById(target);
        if (button.classList.contains('collapsed')) {
          localStorage.removeItem('openAccordion');
          collapseElement.closest('.accordion-item').classList.remove('no-transition');
        } else {
          localStorage.setItem('openAccordion', target);
          collapseElement.closest('.accordion-item').classList.add('no-transition');
        }
      }, 0);
    });
  });

  // Check sidebar width on page load
  checkSidebarWidth();
});

// Resizer Sidebar
let originalWidth = "230px";

document.addEventListener("DOMContentLoaded", function() {
  let storedWidth = localStorage.getItem("sidebarWidth");
  if (storedWidth) {
    let sidebar = document.querySelector(".sidebar");
    sidebar.style.width = storedWidth;
    checkSidebarWidth(); // Check sidebar width on page load
  }
});

document.addEventListener("keydown", function(event) {
  // Periksa jika tombol "Ctrl" dan "B" ditekan secara bersamaan
  if (event.ctrlKey && event.key === "b") {
    toggleSidebarWidth();
  }
});

var resizer = document.querySelector(".resizer"),
  sidebar = document.querySelector(".sidebar");

function initResizerFn(resizer, sidebar) {
  var x, w;

  function rs_mousedownHandler(e) {
    x = e.clientX;
    var sbWidth = window.getComputedStyle(sidebar).width;
    w = parseInt(sbWidth, 10);
    document.addEventListener("mousemove", rs_mousemoveHandler);
    document.addEventListener("mouseup", rs_mouseupHandler);
  }

  function rs_mousemoveHandler(e) {
    var dx = e.clientX - x;
    var cw = w + dx;
    if (cw < 700) {
      sidebar.style.width = `${cw}px`;
      checkSidebarWidth(); // Check sidebar width during resize
    }
  }

  function rs_mouseupHandler() {
    document.removeEventListener("mouseup", rs_mouseupHandler);
    document.removeEventListener("mousemove", rs_mousemoveHandler);
    localStorage.setItem("sidebarWidth", sidebar.style.width);
  }

  resizer.addEventListener("mousedown", rs_mousedownHandler);
}

initResizerFn(resizer, sidebar);

function toggleSidebarWidth() {
  let sidebar = document.querySelector(".sidebar");
  if (sidebar.style.width === "50px") {
    sidebar.style.width = originalWidth;
  } else {
    sidebar.style.width = "50px";
  }
  checkSidebarWidth();
  localStorage.setItem("sidebarWidth", sidebar.style.width);
}

function checkSidebarWidth() {
  let sidebar = document.querySelector(".sidebar");
  if (parseInt(sidebar.style.width) <= 50) {
    let activeAccordion = document.querySelector(".accordion-collapse.show");
    if (activeAccordion) {
      let bsCollapse = bootstrap.Collapse.getInstance(activeAccordion);
      if (bsCollapse) {
        bsCollapse.hide(); // Hide the active accordion
      }
    }
    localStorage.removeItem('openAccordion'); // Hapus status accordion dari localStorage
  }
}

function confirmDelete(url, message) {
  if (confirm(message)) {
    window.location.href = url;
  }
}
</script>
</body>

</html>