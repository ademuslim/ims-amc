// DataTables JS
$(document).ready(function () {
  var table = $("#example").DataTable({
    columnDefs: [
      {
        className: "dtr-control arrow-right",
        orderable: false,
        targets: -1,
      },
    ],
    responsive: {
      details: {
        type: "column",
        target: -1,
      },
    },
    dom:
      "<'d-flex justify-content-between align-items-center mb-4'<B><f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-12 d-flex justify-content-end align-items-center gap-3'ipl>>",
    // lengthChange: false,
    buttons: [
      // {
      //   extend: "pageLength",
      // },
      {
        extend: "colvis", // Menambahkan tombol Column Visibility
        postfixButtons: ["colvisRestore"], // Menambahkan tombol untuk mengembalikan visibilitas kolom
        text: "Tampil Kolom",
      },
      {
        extend: "copy",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Salin",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Excel",
      },
      {
        extend: "pdf",
        orientation: "landscape",
        pageSize: "LEGAL",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "PDF",
      },
      {
        extend: "print",
        // autoPrint: false,
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Cetak",
      },
    ],
    language: {
      sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
      sInfo: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
      sInfoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
      sInfoFiltered: "(disaring dari total entri _MAX_)",
      sInfoPostFix: "",
      sInfoThousands: ",",
      sLengthMenu: "_MENU_",
      sLoadingRecords: "Memuat...",
      sProcessing: "Sedang memproses...",
      sSearch: "",
      searchPlaceholder: "Cari data...",
      sZeroRecords: "Tidak ditemukan data yang cocok",
      oAria: {
        sSortAscending: ": aktifkan untuk mengurutkan kolom secara ascending",
        sSortDescending: ": aktifkan untuk mengurutkan kolom secara descending",
      },
    },
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, 100],
    ], // Menetapkan opsi panjang halaman
  });

  table.buttons().container().appendTo("#example_wrapper .col-md-8:eq(0)");
  // Menambahkan margin bawah ke elemen yang menampung tombol dan fitur pencarian
  $("#example_wrapper .row").css("margin-bottom", "20px");
});

// Resizer Sidebar
var resizer = document.querySelector(".resizer"),
  sidebar = document.querySelector(".sidebar");

function initResizerFn(resizer, sidebar) {
  // track current mouse position in x var
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

    var cw = w + dx; // complete width

    if (cw < 700) {
      sidebar.style.width = `${cw}px`;
    }
  }

  function rs_mouseupHandler() {
    // remove event mousemove && mouseup
    document.removeEventListener("mouseup", rs_mouseupHandler);
    document.removeEventListener("mousemove", rs_mousemoveHandler);
  }

  resizer.addEventListener("mousedown", rs_mousedownHandler);
}

initResizerFn(resizer, sidebar);

// Modal Auto Focus
document.addEventListener("DOMContentLoaded", function () {
  var modals = document.querySelectorAll(".modal");
  modals.forEach(function (modal) {
    modal.addEventListener("shown.bs.modal", function () {
      var autoFocus = modal.querySelector(".auto-focus");
      if (autoFocus) {
        autoFocus.focus();
      }
    });
  });
});
